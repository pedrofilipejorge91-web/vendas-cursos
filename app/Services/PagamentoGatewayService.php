<?php

namespace App\Services;

use App\Models\Pagamento;
use App\Models\Pedido;
use Illuminate\Support\Str;

class PagamentoGatewayService
{
    public function metodosDisponiveis(): array
    {
        return [
            'multicaixa_express' => 'Multicaixa Express',
            'transferencia_bancaria' => 'Transferencia bancaria',
            'mbway_angola' => 'MBWay Angola',
            'pagamento_presencial' => 'Pagamento presencial',
        ];
    }

    public function gerarUrlPagamento(Pedido $pedido, ?Pagamento $pagamento = null): ?string
    {
        $metodo = $pagamento?->metodo ?? null;

        if ($metodo === 'multicaixa_express') {
            $endpoint = env('MULTICAIXA_ENDPOINT', 'https://sandbox.multicaixaexpress.com/pay');

            return $endpoint.'?'.http_build_query([
                'reference' => $pedido->referencia,
                'amount' => number_format($pedido->total, 2, '.', ''),
                'currency' => 'AOA',
                'customer_email' => $pedido->user->email,
            ]);
        }

        if ($metodo === 'mbway_angola') {
            $endpoint = env('MBWAY_ENDPOINT', 'https://sandbox.mbway.ao/pay');
            $telefone = $this->normalizarTelefone($pagamento?->telefone ?? $pedido->user->pessoa->contacto ?? '');

            if (! $telefone) {
                return null;
            }

            return $endpoint.'?'.http_build_query([
                'phone' => $telefone,
                'reference' => $pedido->referencia,
                'amount' => number_format($pedido->total, 2, '.', ''),
                'currency' => 'AOA',
            ]);
        }

        return null;
    }

    public function obterDescricaoPagamento(Pedido $pedido, ?Pagamento $pagamento = null): string
    {
        $metodo = $pagamento?->metodo ?? null;

        return match ($metodo) {
            'multicaixa_express' => 'Use o link de pagamento Multicaixa Express para concluir a compra.',
            'mbway_angola' => 'Use o MBWay para completar o valor. Selecione o telefone indicado no formulário.',
            'transferencia_bancaria' => 'Faça transferência para a conta bancária cadastrada e envie o comprovativo em seguida.',
            'pagamento_presencial' => 'Compareça presencialmente para efetuar o pagamento e apresentar o comprovativo.',
            default => 'O pagamento será processado em breve.',
        };
    }

    private function normalizarTelefone(string $telefone): string
    {
        $numero = preg_replace('/[^0-9+]/', '', $telefone);

        if (! $numero) {
            return '';
        }

        if (! Str::startsWith($numero, '+')) {
            $numero = '+244'.ltrim($numero, '0');
        }

        return $numero;
    }
}
