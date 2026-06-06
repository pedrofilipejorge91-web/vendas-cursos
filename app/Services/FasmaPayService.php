<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FasmaPayService
{
    public function validarComprovativo(UploadedFile $recibo): array
    {
        $apiKey = config('services.fasmapay.key');
        $endpoint = config('services.fasmapay.endpoint');

        try {

            $response = Http::timeout(30)
                ->attach(
                    'recibo',
                    fopen($recibo->getRealPath(), 'r'),
                    $recibo->getClientOriginalName()
                )
                ->post($endpoint, [
                    'sudopay_key' => $apiKey,
                ]);

            $payload = $response->json();

            Log::info('Resposta FasmaPay', [
                'http_status' => $response->status(),
                'payload' => $payload,
            ]);

            if (!is_array($payload)) {
                return [
                    'valid' => false,
                    'message' => 'Resposta inválida da FasmaPay.',
                    'response' => null,
                ];
            }

            $valid = ((int)($payload['STATUS'] ?? 0) === 200);

            return [
                'valid' => $valid,
                'message' => $valid
                    ? 'Comprovativo validado com sucesso.'
                    : ($payload['LOG'] ?? 'Comprovativo inválido.'),
                'response' => $payload,
            ];

        } catch (\Throwable $e) {

            Log::error('Erro FasmaPay', [
                'message' => $e->getMessage(),
            ]);

            return [
                'valid' => false,
                'message' => 'Nao foi possivel contactar a FasmaPay.',
                'response' => [
                    'error' => $e->getMessage(),
                ],
            ];
        }
    }
}