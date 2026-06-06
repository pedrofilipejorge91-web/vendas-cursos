<?php

namespace Tests\Unit;

use App\Services\FasmaPayService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class FasmaPayServiceTest extends TestCase
{
    public function test_validated_payment_response_is_accepted(): void
    {
        config([
            'services.fasmapay.key' => 'test-key',
            'services.fasmapay.endpoint' => 'https://comprovativos.sudomakes.com/validate/',
        ]);

        Http::fake([
            'comprovativos.sudomakes.com/*' => Http::response([
                'status' => 'VALIDATED_PAYMENT',
                'message' => 'Comprovativo valido.',
            ]),
        ]);

        $result = app(FasmaPayService::class)
            ->validarComprovativo(UploadedFile::fake()->create('recibo.pdf', 10, 'application/pdf'));

        $this->assertTrue($result['valid']);
        $this->assertSame('Comprovativo valido.', $result['message']);
    }

    public function test_missing_key_rejects_validation(): void
    {
        config(['services.fasmapay.key' => null]);

        $result = app(FasmaPayService::class)
            ->validarComprovativo(UploadedFile::fake()->create('recibo.pdf', 10, 'application/pdf'));

        $this->assertFalse($result['valid']);
        $this->assertSame('A chave de acesso da FasmaPay ainda nao foi configurada.', $result['message']);
    }

    public function test_missing_endpoint_rejects_validation(): void
    {
        config([
            'services.fasmapay.key' => 'test-key',
            'services.fasmapay.endpoint' => null,
        ]);

        $result = app(FasmaPayService::class)
            ->validarComprovativo(UploadedFile::fake()->create('recibo.pdf', 10, 'application/pdf'));

        $this->assertFalse($result['valid']);
        $this->assertSame('O endpoint da FasmaPay ainda nao foi configurado.', $result['message']);
    }

    public function test_non_json_response_is_reported_as_invalid_pdf(): void
    {
        config([
            'services.fasmapay.key' => 'test-key',
            'services.fasmapay.endpoint' => 'https://comprovativos.sudomakes.com/validate/',
        ]);

        Log::spy();

        Http::fake([
            'comprovativos.sudomakes.com/*' => Http::response('<html>Invalid document</html>', 422, [
                'content-type' => 'text/html',
            ]),
        ]);

        $result = app(FasmaPayService::class)
            ->validarComprovativo(UploadedFile::fake()->create('monografia.pdf', 10, 'application/pdf'));

        $this->assertFalse($result['valid']);
        $this->assertSame('O PDF enviado nao foi reconhecido como comprovativo valido pela FasmaPay.', $result['message']);
        Log::shouldHaveReceived('warning')->once();
    }
}
