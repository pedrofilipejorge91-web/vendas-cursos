<?php

namespace App\Http\Controllers;

use App\Models\Matricula;
use App\Models\Pagamento;
use App\Models\Pedido;
use App\Services\NotificacaoService;
use App\Services\PagamentoGatewayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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

        [$subtotal, $desconto, $total] = $this->calcularTotais($carrinho);
        $metodos = app(PagamentoGatewayService::class)->metodosDisponiveis();

        return view('home.pagamento', compact('carrinho', 'subtotal', 'desconto', 'total', 'metodos'));
    }

    public function processar(Request $request)
    {
        $request->validate([
            'metodo_pagamento' => 'required|in:multicaixa_express,transferencia_bancaria,mbway_angola,pagamento_presencial',
            'telefone' => 'nullable|string|max:30',
            'comprovativo' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:4096',
        ]);

        $carrinho = session()->get('carrinho', []);

        if (empty($carrinho)) {
            return redirect()
                ->route('home.carrinho')
                ->withErrors(['carrinho' => 'O carrinho esta vazio.']);
        }

        $comprovativoPath = $request->hasFile('comprovativo')
            ? $request->file('comprovativo')->store('comprovativos', 'public')
            : null;

        $pedido = DB::transaction(function () use ($request, $carrinho, $comprovativoPath) {
            [$subtotal, $desconto, $total] = $this->calcularTotais($carrinho);

            $pedido = Pedido::create([
                'user_id' => Auth::id(),
                'referencia' => $this->gerarReferencia('PED'),
                'subtotal' => $subtotal,
                'desconto' => $desconto,
                'total' => $total,
                'status' => 'pendente',
                'expira_em' => now()->addHours(48),
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
                'status' => 'pendente',
                'telefone' => $request->telefone,
                'comprovativo' => $comprovativoPath,
                'confirmado_em' => null,
            ]);

            return $pedido->load('itens', 'pagamento');
        });

        session()->forget('carrinho');

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
        $gatewayUrl = $gatewayService->gerarUrlPagamento($pedido, $pedido->pagamento);
        $gatewayDescription = $gatewayService->obterDescricaoPagamento($pedido, $pedido->pagamento);

        return view('home.comprovante', compact('pedido', 'gatewayUrl', 'gatewayDescription'));
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

    private function gerarReferencia(string $prefixo): string
    {
        do {
            $referencia = $prefixo.'-'.now()->format('Ymd').'-'.Str::upper(Str::random(6));
        } while (Pedido::where('referencia', $referencia)->exists() || Pagamento::where('referencia', $referencia)->exists());

        return $referencia;
    }
}
