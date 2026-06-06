<?php

namespace App\Services;

use App\Models\Pagamento;
use App\Models\Pedido;

class PagamentoGatewayService
{
    public function metodosDisponiveis(): array
    {
        return [
            'multicaixa_express' => [
                'label' => 'Multicaixa Express',
                'descricao' => 'Pagamento atraves do Multicaixa Express.',
                'icone' => 'bi-phone',
            ],
            'transferencia_bancaria' => [
                'label' => 'Transferencia Bancaria',
                'descricao' => 'Transferencia bancaria para a conta indicada.',
                'icone' => 'bi-bank',
            ],
        ];
    }

    public function gerarUrlPagamento(Pedido $pedido, ?Pagamento $pagamento = null): ?string
    {
        return null;
    }

    public function obterDescricaoPagamento(Pedido $pedido, ?Pagamento $pagamento = null): string
    {
        $metodo = $pagamento?->metodo ?? null;
        $metodos = $this->metodosDisponiveis();

        return $metodos[$metodo]['descricao'] ?? 'O pagamento sera processado em breve.';
    }
}
