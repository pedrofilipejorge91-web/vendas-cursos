@component('mail::message')
# Recuperacao de senha

Recebemos um pedido para redefinir a senha da sua conta na plataforma {{ config('app.name') }}.

@component('mail::button', ['url' => $url])
Redefinir senha
@endcomponent

Este link expira em 60 minutos. Se nao foi voce, ignore esta mensagem.

Atenciosamente,<br>
{{ config('app.name') }}
@endcomponent
