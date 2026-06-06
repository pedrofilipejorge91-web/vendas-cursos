<?php

namespace App\Services;

use App\Models\Pagamento;
use App\Models\Pedido;

class PagamentoGatewayService
{
    public function metodosDisponiveis(): array
    {
        return [
            'multicaixa_express' => 'Multicaixa Express',
            'transferencia_bancaria' => 'Transferência Bancária',
        ];
    }

    public function gerarUrlPagamento(Pedido $pedido, ?Pagamento $pagamento = null): ?string
    {
        $metodo = $pagamento?->metodo ?? null;

        if ($metodo === 'multicaixa_express') {
            $endpoint = env('MULTICAIXA_ENDPOINT', 'https://sandbox.multicaixaexpress.com/pay');

            return $endpoint . '?' . http_build_query([
                'reference' => $pedido->referencia,
                'amount' => number_format($pedido->total, 2, '.', ''),
                'currency' => 'AOA',
                'customer_email' => $pedido->user->email,
            ]);
        }

        return null;
    }

    public function obterDescricaoPagamento(Pedido $pedido, ?Pagamento $pagamento = null): string
    {
        $metodo = $pagamento?->metodo ?? null;

        return match ($metodo) {
            'multicaixa_express' =>
                'Efetue o pagamento através do Multicaixa Express e envie o comprovativo em PDF.',

            'transferencia_bancaria' =>
                'Faça a transferência bancária para a conta indicada e envie o comprovativo para validação.',

            default =>
                'O pagamento será processado em breve.',
        };
    }
}