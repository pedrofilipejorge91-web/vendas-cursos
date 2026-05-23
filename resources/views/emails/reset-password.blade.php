@component('mail::message')
# Recuperação de Senha

Você recebeu este email porque solicitou uma recuperação de senha.

@component('mail::button', ['url' => $url])
Redefinir Senha
@endcomponent

Este link expirará em 60 minutos.

Se você não solicitou uma recuperação de senha, ignore este email.

Obrigado,<br>
{{ config('app.name') }}
@endcomponent
