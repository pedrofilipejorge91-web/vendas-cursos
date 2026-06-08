<?php

namespace App\Http\Controllers;

use App\Models\Matricula;
use App\Models\Pagamento;
use App\Models\Pedido;
use App\Services\NotificacaoService;
use App\Services\PagamentoGatewayService;
use App\Services\SudoPayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PagamentoController extends Controller
{
    public function index()
    {
        $carrinho = session()->get('carrinho', []);

        if (empty($carrinho)) {
            return redirect()
                ->route('home.carrinho')
                ->withErrors(['carrinho' => 'Adicione pelo menos um curso antes de finalizar a compra.']);
        }

        $cursosIndisponiveis = $this->cursosJaCompradosOuPendentes(array_keys($carrinho));

        if (! empty($cursosIndisponiveis)) {
            foreach ($cursosIndisponiveis as $cursoId) {
                unset($carrinho[$cursoId]);
            }

            session()->put('carrinho', $carrinho);

            if (empty($carrinho)) {
                return redirect()
                    ->route('home.carrinho')
                    ->withErrors(['carrinho' => 'Os cursos no carrinho ja foram comprados ou ja tem pedido pendente.']);
            }
        }

        [$subtotal, $desconto, $total] = $this->calcularTotais($carrinho);
        $resumoCompra = $this->prepararResumoCompra($carrinho);
        $metodos = app(PagamentoGatewayService::class)->metodosDisponiveis();

        return view('checkout.pagamento', compact('carrinho', 'resumoCompra', 'subtotal', 'desconto', 'total', 'metodos'));
    }

    public function processar(Request $request)
    {
        $metodos = app(PagamentoGatewayService::class)->metodosDisponiveis();

        $request->validate([
            'metodo_pagamento' => 'required|in:'.implode(',', array_keys($metodos)),
            'telefone' => 'nullable|string|max:30',
        ]);

        $carrinho = session()->get('carrinho', []);

        if (empty($carrinho)) {
            return redirect()
                ->route('home.carrinho')
                ->withErrors(['carrinho' => 'O carrinho esta vazio.']);
        }

        $cursosIndisponiveis = $this->cursosJaCompradosOuPendentes(array_keys($carrinho));

        if (! empty($cursosIndisponiveis)) {
            foreach ($cursosIndisponiveis as $cursoId) {
                unset($carrinho[$cursoId]);
            }

            session()->put('carrinho', $carrinho);

            return redirect()
                ->route('home.carrinho')
                ->withErrors(['carrinho' => 'Removemos do carrinho cursos que ja foram comprados ou que ja tem pedido pendente.']);
        }

        [$subtotal, $desconto, $total] = $this->calcularTotais($carrinho);

        $gatewayPayload = null;
        $pagamentoConfirmado = false;
        $comprovativoPath = null;

        $pedido = DB::transaction(function () use ($request, $carrinho, $subtotal, $desconto, $total, $comprovativoPath, $gatewayPayload, $pagamentoConfirmado) {
            $pedido = Pedido::create([
                'user_id' => Auth::id(),
                'referencia' => $this->gerarReferencia('PED'),
                'subtotal' => $subtotal,
                'desconto' => $desconto,
                'total' => $total,
                'status' => $pagamentoConfirmado ? 'pago' : 'pendente',
                'expira_em' => $pagamentoConfirmado ? null : now()->addHours(48),
            ]);

            foreach ($carrinho as $cursoId => $item) {
                $pedido->itens()->create([
                    'curso_id' => $cursoId,
                    'titulo' => $item['titulo'],
                    'preco' => (float) $item['preco'],
                    'quantidade' => (int) ($item['quantidade'] ?? 1),
                ]);
            }

            $pedido->pagamento()->create([
                'referencia' => $this->gerarReferencia('PAY'),
                'metodo' => $request->metodo_pagamento,
                'valor' => $total,
                'status' => $pagamentoConfirmado ? 'confirmado' : 'pendente',
                'telefone' => $request->telefone,
                'comprovativo' => $comprovativoPath,
                'gateway_payload' => $gatewayPayload,
                'confirmado_em' => $pagamentoConfirmado ? now() : null,
            ]);

            return $pedido->load('itens', 'pagamento');
        });

        session()->forget('carrinho');

        if ($pagamentoConfirmado) {
            $this->liberarMatriculas($pedido);

            return redirect()
                ->route('pagamento.comprovante', $pedido)
                ->with('success', 'Comprovativo validado pela SudoPay. Pagamento confirmado e acesso liberado.');
        }

        app(NotificacaoService::class)->enviar(
            Auth::user(),
            'Pedido criado',
            'O seu pedido '.$pedido->referencia.' foi criado e esta aguardando pagamento. Complete o pagamento dentro do prazo para garantir o acesso aos cursos.',
            ['email', 'sms', 'whatsapp'],
            [
                'linhas' => [
                    'Pedido' => $pedido->referencia,
                    'Total' => number_format((float) $pedido->total, 2, ',', '.').' Kz',
                    'Estado' => 'Aguardando pagamento',
                    'Valido ate' => $pedido->expira_em?->format('d/m/Y H:i') ?? '-',
                ],
                'acao_url' => route('pagamento.comprovante', $pedido),
                'acao_texto' => 'Ver instrucoes de pagamento',
                'preheader' => 'Pedido criado e aguardando pagamento.',
            ]
        );

        return redirect()
            ->route('pagamento.comprovante', $pedido)
            ->with('success', 'Pedido criado com sucesso. Complete o pagamento conforme instrucoes.');
    }

    public function comprovante(Pedido $pedido, PagamentoGatewayService $gatewayService)
    {
        abort_unless($pedido->user_id === Auth::id() || Auth::user()?->tipo === 'admin', 403);

        $pedido->load('itens.curso', 'pagamento', 'user');
        $gatewayDescription = $gatewayService->obterDescricaoPagamento($pedido, $pedido->pagamento);

        return view('checkout.comprovante', compact('pedido', 'gatewayDescription'));
    }

 public function enviarComprovativo(Request $request, Pedido $pedido)
{
    abort_unless($pedido->user_id === Auth::id(), 403);
    abort_unless($pedido->status === 'pendente', 403);

    $request->validate([
        'comprovativo' => 'required|file|mimes:pdf|max:4096',
    ]);

    $pedido->load('itens', 'pagamento', 'user');

    $sudoPayService = app(SudoPayService::class);

    // 1. Validar o comprovativo na SudoPay
    $validacao = $sudoPayService->validarComprovativo($request->file('comprovativo'));

    if (! $validacao['valid']) {
        return redirect()
            ->back()
            ->withErrors(['comprovativo' => $validacao['message']]);
    }

    $dadosSudoPay = $validacao['response'];
    $numeroTransacao = $dadosSudoPay['TRANSACAO'] ?? null;

    // 2. VERIFICAR SE JÁ EXISTE PAGAMENTO COM ESTE NÚMERO DE TRANSAÇÃO
    if ($numeroTransacao) {
        $pagamentoDuplicado = Pagamento::where('transacao_id', $numeroTransacao)
            ->where('status', 'confirmado')
            ->first();

        if ($pagamentoDuplicado && $pagamentoDuplicado->pedido_id !== $pedido->id) {
            Log::warning('Tentativa de reutilização de comprovativo', [
                'transacao' => $numeroTransacao,
                'pedido_atual' => $pedido->id,
                'pedido_original' => $pagamentoDuplicado->pedido_id,
                'user_id' => $pedido->user_id,
            ]);

            return redirect()
                ->back()
                ->withErrors(['comprovativo' => 'Este comprovativo (transação '.$numeroTransacao.') já foi utilizado no pedido #'.$pagamentoDuplicado->pedido->referencia.'.']);
        }
    }

    // 3. Verificar se o dinheiro foi para a NOSSA conta (IBAN)
    if (! $sudoPayService->verificarIBAN($dadosSudoPay)) {
        return redirect()
            ->back()
            ->withErrors(['comprovativo' => 'Este comprovativo nao foi transferido para a nossa conta bancaria. Verifique os dados de pagamento.']);
    }

    // 4. Verificar o nome do beneficiário
    if (! $sudoPayService->verificarNomeBeneficiario($dadosSudoPay)) {
        return redirect()
            ->back()
            ->withErrors(['comprovativo' => 'O nome do beneficiario no comprovativo nao corresponde aos nossos dados.']);
    }

    // 5. Verificar se o valor pago cobre o total do pedido
    $valorPago = $this->valorPagoNoComprovativo($dadosSudoPay);

    if ($valorPago === null || $valorPago < (float) $pedido->total) {
        return redirect()
            ->back()
            ->withErrors([
                'comprovativo' => 'O comprovativo foi reconhecido, mas o valor pago ('.number_format($valorPago ?? 0, 2, ',', '.').' Kz) nao cobre o total da compra ('.number_format($pedido->total, 2, ',', '.').' Kz).',
            ]);
    }

    // 6. Tudo OK! Guardar o PDF e confirmar o pagamento
    $comprovativoPath = $request->file('comprovativo')->store('comprovativos/'.$pedido->id, 'public');

    DB::transaction(function () use ($pedido, $comprovativoPath, $dadosSudoPay, $numeroTransacao) {
        $pedido->update(['status' => 'pago', 'expira_em' => null]);

        $pedido->pagamento?->update([
            'status' => 'confirmado',
            'comprovativo' => $comprovativoPath,
            'gateway_payload' => $dadosSudoPay,
            'transacao_id' => $numeroTransacao, // ✅ Salvar o número da transação
            'confirmado_em' => now(),
        ]);

        $this->liberarMatriculas($pedido);
    });

    Log::info('Pagamento confirmado automaticamente via SudoPay', [
        'pedido_id' => $pedido->id,
        'user_id' => $pedido->user_id,
        'valor' => $valorPago,
        'transacao_id' => $numeroTransacao,
    ]);

    return redirect()
        ->route('pagamento.comprovante', $pedido)
        ->with('success', 'Comprovativo validado pela SudoPay. Pagamento confirmado e acesso liberado.');
}

    public function confirmar(Pedido $pedido)
    {
        abort_unless(Auth::user()?->tipo === 'admin', 403);

        DB::transaction(function () use ($pedido) {
            $pedido->update(['status' => 'pago', 'expira_em' => null]);
            $pedido->pagamento?->update(['status' => 'confirmado', 'confirmado_em' => now()]);
            $this->liberarMatriculas($pedido->load('itens'));
        });

        return back()->with('success', 'Pagamento confirmado e acesso liberado.');
    }

    private function liberarMatriculas(Pedido $pedido): void
    {
        foreach ($pedido->itens as $item) {
            Matricula::firstOrCreate([
                'user_id' => $pedido->user_id,
                'curso_id' => $item->curso_id,
            ], [
                'pedido_id' => $pedido->id,
                'progresso' => 0,
            ]);
        }

        app(NotificacaoService::class)->enviar(
            $pedido->user,
            'Matricula confirmada',
            'Confirmamos o pagamento do pedido '.$pedido->referencia.'. O acesso aos cursos ja esta liberado na sua area de aluno.',
            ['email', 'sms', 'whatsapp'],
            [
                'linhas' => [
                    'Pedido' => $pedido->referencia,
                    'Estado' => 'Pago',
                    'Cursos liberados' => $pedido->itens->count(),
                ],
                'acao_url' => route('estudante.cursos'),
                'acao_texto' => 'Acessar meus cursos',
                'preheader' => 'Pagamento confirmado e matricula liberada.',
            ]
        );
    }

    private function calcularTotal(array $carrinho): float
    {
        return collect($carrinho)->sum(function ($item) {
            return ((float) $item['preco']) * ((int) ($item['quantidade'] ?? 1));
        });
    }

    private function calcularTotais(array $carrinho): array
    {
        $subtotal = $this->calcularTotal($carrinho);
        $desconto = 0;
        $total = $subtotal;

        return [$subtotal, $desconto, $total];
    }

    private function prepararResumoCompra(array $carrinho): array
    {
        return collect($carrinho)
            ->map(function ($item) {
                $quantidade = (int) ($item['quantidade'] ?? 1);
                $preco = (float) $item['preco'];

                return [
                    'titulo' => $item['titulo'],
                    'preco' => $preco,
                    'quantidade' => $quantidade,
                    'total' => $preco * $quantidade,
                ];
            })
            ->values()
            ->all();
    }

    private function valorPagoNoComprovativo(?array $gatewayPayload): ?float
    {
        if (! is_array($gatewayPayload) || ! array_key_exists('DINHEIRO', $gatewayPayload)) {
            return null;
        }

        return is_numeric($gatewayPayload['DINHEIRO'])
            ? (float) $gatewayPayload['DINHEIRO']
            : null;
    }

    private function cursosJaCompradosOuPendentes(array $cursoIds): array
    {
        $cursoIds = collect($cursoIds)->map(fn ($cursoId) => (int) $cursoId)->filter()->values();

        if ($cursoIds->isEmpty()) {
            return [];
        }

        $matriculados = Matricula::where('user_id', Auth::id())
            ->whereIn('curso_id', $cursoIds)
            ->pluck('curso_id');

        $emPedidosAtivos = Pedido::where('user_id', Auth::id())
            ->whereIn('status', ['pendente', 'pago'])
            ->whereHas('itens', function ($query) use ($cursoIds) {
                $query->whereIn('curso_id', $cursoIds);
            })
            ->with(['itens' => function ($query) use ($cursoIds) {
                $query->whereIn('curso_id', $cursoIds);
            }])
            ->get()
            ->pluck('itens')
            ->flatten()
            ->pluck('curso_id');

        return $matriculados
            ->merge($emPedidosAtivos)
            ->unique()
            ->map(fn ($cursoId) => (int) $cursoId)
            ->values()
            ->all();
    }

    private function gerarReferencia(string $prefixo): string
    {
        do {
            $referencia = $prefixo.'-'.now()->format('Ymd').'-'.Str::upper(Str::random(6));
        } while (Pedido::where('referencia', $referencia)->exists() || Pagamento::where('referencia', $referencia)->exists());

        return $referencia;
    }
}