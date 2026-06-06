<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SudoPayService
{
    public function validarComprovativo(UploadedFile $recibo): array
    {
        $apiKey = config('services.sudopay.key');
        $endpoint = config('services.sudopay.endpoint');
        $timeout = (int) config('services.sudopay.timeout', 30);

        if (blank($apiKey)) {
            return [
                'valid' => false,
                'message' => 'A chave de acesso da SudoPay ainda nao foi configurada.',
                'http_status' => null,
                'response' => null,
            ];
        }

        if (blank($endpoint)) {
            return [
                'valid' => false,
                'message' => 'O endpoint da SudoPay ainda nao foi configurado.',
                'http_status' => null,
                'response' => null,
            ];
        }

        try {
            $response = Http::timeout($timeout)
                ->attach(
                    'recibo',
                    fopen($recibo->getRealPath(), 'r'),
                    $recibo->getClientOriginalName()
                )
                ->post($endpoint, [
                    'sudopay_key' => $apiKey,
                ]);

            $payload = $response->json();

            Log::info('Resposta SudoPay', [
                'http_status' => $response->status(),
                'payload' => $payload,
            ]);

            if (! is_array($payload)) {
                Log::warning('Resposta invalida da SudoPay', [
                    'http_status' => $response->status(),
                    'body' => $response->body(),
                ]);

                return [
                    'valid' => false,
                    'message' => 'O PDF enviado nao foi reconhecido como comprovativo valido pela SudoPay.',
                    'http_status' => $response->status(),
                    'response' => null,
                ];
            }

            $valid = $response->successful() && ((int) ($payload['STATUS'] ?? 0) === 200);
            $message = $valid
                ? 'Comprovativo validado com sucesso.'
                : ($payload['LOG'] ?? 'Comprovativo invalido.');

            return [
                'valid' => $valid,
                'message' => $message,
                'http_status' => $response->status(),
                'response' => $payload,
            ];
        } catch (\Throwable $e) {
            Log::error('Erro SudoPay', [
                'message' => $e->getMessage(),
            ]);

            return [
                'valid' => false,
                'message' => 'Nao foi possivel contactar a SudoPay.',
                'http_status' => null,
                'response' => [
                    'error' => $e->getMessage(),
                ],
            ];
        }
    }
}
