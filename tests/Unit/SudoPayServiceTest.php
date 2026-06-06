<?php

namespace Tests\Unit;

use App\Services\SudoPayService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class SudoPayServiceTest extends TestCase
{
    public function test_validated_payment_response_is_accepted(): void
    {
        config([
            'services.sudopay.key' => 'test-key',
            'services.sudopay.endpoint' => 'https://comprovativos.sudomakes.com/validar/',
        ]);

        Http::fake([
            'comprovativos.sudomakes.com/*' => Http::response([
                'APLICATIVO' => 'MULTICAIXA EXPRESS',
                'STATUS' => 200,
                'LOG' => 'MULTICAIXA EXPRESS',
                'DINHEIRO' => 52000,
                'TRANSACAO' => '8000246',
            ]),
        ]);

        $result = app(SudoPayService::class)
            ->validarComprovativo(UploadedFile::fake()->create('recibo.pdf', 10, 'application/pdf'));

        $this->assertTrue($result['valid']);
        $this->assertSame('Comprovativo validado com sucesso.', $result['message']);
        $this->assertSame(52000, $result['response']['DINHEIRO']);
    }

    public function test_missing_key_rejects_validation(): void
    {
        config(['services.sudopay.key' => null]);

        $result = app(SudoPayService::class)
            ->validarComprovativo(UploadedFile::fake()->create('recibo.pdf', 10, 'application/pdf'));

        $this->assertFalse($result['valid']);
        $this->assertSame('A chave de acesso da SudoPay ainda nao foi configurada.', $result['message']);
    }

    public function test_missing_endpoint_rejects_validation(): void
    {
        config([
            'services.sudopay.key' => 'test-key',
            'services.sudopay.endpoint' => null,
        ]);

        $result = app(SudoPayService::class)
            ->validarComprovativo(UploadedFile::fake()->create('recibo.pdf', 10, 'application/pdf'));

        $this->assertFalse($result['valid']);
        $this->assertSame('O endpoint da SudoPay ainda nao foi configurado.', $result['message']);
    }

    public function test_non_json_response_is_reported_as_invalid_pdf(): void
    {
        config([
            'services.sudopay.key' => 'test-key',
            'services.sudopay.endpoint' => 'https://comprovativos.sudomakes.com/validar/',
        ]);

        Log::spy();

        Http::fake([
            'comprovativos.sudomakes.com/*' => Http::response('<html>Invalid document</html>', 422, [
                'content-type' => 'text/html',
            ]),
        ]);

        $result = app(SudoPayService::class)
            ->validarComprovativo(UploadedFile::fake()->create('monografia.pdf', 10, 'application/pdf'));

        $this->assertFalse($result['valid']);
        $this->assertSame('O PDF enviado nao foi reconhecido como comprovativo valido pela SudoPay.', $result['message']);
        Log::shouldHaveReceived('warning')->once();
    }
}
