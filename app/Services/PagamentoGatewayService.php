<?php

namespace App\Services;

use App\Models\Pagamento;
use App\Models\Pedido;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PagamentoGatewayService
{
    /**
     * Lista de métodos de pagamento disponíveis
     */
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

    /**
     * Gera URL de pagamento (para gateways externos, se necessário)
     */
    public function gerarUrlPagamento(Pedido $pedido, ?Pagamento $pagamento = null): ?string
    {
        return null;
    }

    /**
     * Retorna a descrição do método de pagamento
     */
    public function obterDescricaoPagamento(Pedido $pedido, ?Pagamento $pagamento = null): string
    {
        $metodo = $pagamento?->metodo ?? null;
        $metodos = $this->metodosDisponiveis();

        return $metodos[$metodo]['descricao'] ?? 'O pagamento sera processado em breve.';
    }

    /**
     * Valida o comprovativo PDF usando a API da SudoPay
     *
     * @param UploadedFile $pdf Ficheiro PDF do comprovativo
     * @return array Dados extraídos do comprovativo ou array com erro
     */
    public function validarComprovativoSudoPay(UploadedFile $pdf): array
    {
        $url = config('services.sudopay.endpoint');
        $apiKey = config('services.sudopay.key');

        try {
            $response = Http::timeout(30)
                ->attach('recibo', file_get_contents($pdf->getRealPath()), $pdf->getClientOriginalName())
                ->post($url, [
                    'sudopay_key' => $apiKey,
                ]);

            $resultado = $response->json();

            // Verificar se a API retornou sucesso
            if ($response->successful() && isset($resultado['STATUS']) && $resultado['STATUS'] == 200) {
                return [
                    'sucesso' => true,
                    'dados' => $resultado,
                ];
            }

            // Erro retornado pela API
            return [
                'sucesso' => false,
                'mensagem' => $resultado['LOG'] ?? 'Comprovativo inválido.',
                'codigo_erro' => $response->status(),
            ];

        } catch (\Exception $e) {
            Log::error('Erro ao validar comprovativo na SudoPay: ' . $e->getMessage());

            return [
                'sucesso' => false,
                'mensagem' => 'Erro ao comunicar com o serviço de validação. Tente novamente.',
            ];
        }
    }

    /**
     * Verifica se o IBAN do beneficiário corresponde à conta da empresa
     */
    public function verificarContaDestino(array $dadosSudoPay): bool
    {
        $ibanRecebido = str_replace(['.', ' '], '', $dadosSudoPay['B_IBAN'] ?? '');
        $meuIban = str_replace(['.', ' '], '', config('services.sudopay.meu_iban'));

        return $ibanRecebido === $meuIban;
    }

    /**
     * Verifica se o valor transferido é suficiente para o pedido
     */
    public function verificarValor(Pedido $pedido, array $dadosSudoPay): bool
    {
        $valorRecebido = $dadosSudoPay['DINHEIRO'] ?? 0;
        return $valorRecebido >= $pedido->total;
    }
}
