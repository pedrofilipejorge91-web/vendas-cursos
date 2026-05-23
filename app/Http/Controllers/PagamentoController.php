<?php

namespace App\Http\Controllers;


use App\Models\Matricula;
use App\Models\Pagamento;
use App\Models\Pedido;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Services\NotificacaoService;
use App\Services\PagamentoGatewayService;

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

        $subtotal = $this->calcularTotal($carrinho);
        $cupom = null;
        $desconto = 0;
        $total = $subtotal;

        $metodos = $this->metodosPagamento();

        return view('home.pagamento', compact('carrinho', 'subtotal', 'desconto', 'total', 'cupom', 'metodos'));
    }

    public function aplicarCupom(Request $request)
    {
        // Removido: sistema de cupons desabilitado.
        return back()->withErrors(['cupom' => 'Cupons de desconto estão desabilitados.']);
    }


    public function removerCupom()
    {
        // Removido: sistema de cupons desabilitado.
        session()->forget('cupom_id');
        return back()->with('success', 'Cupom não utilizado (cupons desabilitados).');
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
            $subtotal = $this->calcularTotal($carrinho);
            $cupom = null;
            $desconto = 0;
            $total = $subtotal;


            $pedido = Pedido::create([
                'user_id' => Auth::id(),
                'cupom_id' => $cupom?->id,
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

        session()->forget(['carrinho', 'cupom_id']);

        app(NotificacaoService::class)->enviar(
            Auth::user(),
            'Pedido criado',
            'Seu pedido '.$pedido->referencia.' foi criado e está aguardando pagamento.',
            ['email', 'sms', 'whatsapp']
        );

        return redirect()
            ->route('pagamento.comprovante', $pedido)
            ->with('success', 'Pedido criado com sucesso. Complete o pagamento conforme instruções.');
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
            'O acesso aos cursos do pedido '.$pedido->referencia.' foi liberado.',
            ['email', 'sms', 'whatsapp']
        );
    }


    private function calcularTotal(array $carrinho): float
    {
        return collect($carrinho)->sum(function ($item) {
            return ((float) $item['preco']) * ((int) ($item['quantidade'] ?? 1));
        });
    }

    private function gerarReferencia(string $prefixo): string
    {
        do {
            $referencia = $prefixo.'-'.now()->format('Ymd').'-'.Str::upper(Str::random(6));
        } while (Pedido::where('referencia', $referencia)->exists() || Pagamento::where('referencia', $referencia)->exists());

        return $referencia;
    }

    private function metodosPagamento(): array
    {
        return [
            'multicaixa_express' => 'Multicaixa Express',
            'transferencia_bancaria' => 'Transferencia bancaria',
            'mbway_angola' => 'MBWay Angola',
            'pagamento_presencial' => 'Pagamento presencial',
        ];
    }
}
