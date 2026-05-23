<?php

namespace App\Services;

use App\Models\Notificacao;
use App\Models\User;
use Twilio\Rest\Client;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;

class NotificacaoService
{
    public function enviar(?User $user, string $titulo, string $mensagem, array $canais = ['email']): void
    {
        foreach ($canais as $canal) {
            Notificacao::create([
                'user_id' => $user?->id,
                'canal' => $canal,
                'titulo' => $titulo,
                'mensagem' => $mensagem,
            ]);

            if ($canal === 'email') {
                $this->enviarEmail($user, $titulo, $mensagem);
            }

            if ($canal === 'sms') {
                $this->enviarSms($user, $mensagem);
            }

            if ($canal === 'whatsapp') {
                $this->enviarWhatsapp($user, $mensagem);
            }
        }
    }

    private function enviarEmail(?User $user, string $titulo, string $mensagem): void
    {
        if (! $user?->email) {
            return;
        }

        try {
            Mail::raw($mensagem, function ($message) use ($user, $titulo) {
                $message->to($user->email)->subject($titulo);
            });
        } catch (\Throwable $e) {
            // fallback: registro local apenas
        }
    }

    private function enviarSms(?User $user, string $mensagem): void
    {
        $numero = $this->obterTelefone($user);
        $remetente = env('TWILIO_FROM_SMS');

        if (! $numero || ! $remetente || ! $this->twilioClient()) {
            return;
        }

        try {
            $this->twilioClient()->messages->create($numero, [
                'from' => $remetente,
                'body' => $mensagem,
            ]);
        } catch (\Throwable $e) {
            // fallback: registro local apenas
        }
    }

    private function enviarWhatsapp(?User $user, string $mensagem): void
    {
        $numero = $this->obterTelefone($user);
        $remetente = env('TWILIO_FROM_WHATSAPP', env('TWILIO_FROM_SMS'));

        if (! $numero || ! $remetente || ! $this->twilioClient()) {
            return;
        }

        try {
            $this->twilioClient()->messages->create('whatsapp:'.$numero, [
                'from' => 'whatsapp:'.$remetente,
                'body' => $mensagem,
            ]);
        } catch (\Throwable $e) {
            // fallback: registro local apenas
        }
    }

    private function obterTelefone(?User $user): ?string
    {
        if (! $user) {
            return null;
        }

        $contato = data_get($user, 'pessoa.contacto') ?? data_get($user, 'telefone');

        if (! $contato) {
            return null;
        }

        $numero = preg_replace('/[^0-9+]/', '', $contato);

        if (! Str::startsWith($numero, '+')) {
            $numero = '+244'.ltrim($numero, '0');
        }

        return $numero;
    }

    private function twilioClient(): ?Client
    {
        $sid = env('TWILIO_SID');
        $token = env('TWILIO_AUTH_TOKEN');

        if (! $sid || ! $token) {
            return null;
        }

        return new Client($sid, $token);
    }
}
