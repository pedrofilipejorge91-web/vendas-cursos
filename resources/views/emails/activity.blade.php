@php
    $nome = $user?->pessoa?->primeironome ?? $user?->name ?? 'utilizador';
    $appName = config('app.name', 'Paruana Comercial');
@endphp
<!doctype html>
<html lang="pt">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $titulo }}</title>
</head>
<body style="margin:0;background:#f3f6fb;font-family:Arial,Helvetica,sans-serif;color:#1f2937;">
    <span style="display:none!important;visibility:hidden;opacity:0;height:0;width:0;overflow:hidden;">
        {{ $preheader }}
    </span>

    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#f3f6fb;padding:28px 12px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:640px;background:#ffffff;border-radius:12px;overflow:hidden;border:1px solid #e5e7eb;">
                    <tr>
                        <td style="background:#0f3f7a;padding:24px 28px;color:#ffffff;">
                            <div style="font-size:13px;font-weight:700;letter-spacing:.04em;text-transform:uppercase;">{{ $appName }}</div>
                            <h1 style="margin:10px 0 0;font-size:24px;line-height:1.25;">{{ $titulo }}</h1>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:28px;">
                            <p style="margin:0 0 16px;font-size:16px;line-height:1.6;">Olá, {{ $nome }}.</p>

                            @if($intro)
                                <p style="margin:0 0 16px;font-size:16px;line-height:1.6;">{{ $intro }}</p>
                            @endif

                            <p style="margin:0 0 18px;font-size:16px;line-height:1.6;">{{ $mensagem }}</p>

                            @if(!empty($linhas))
                                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:20px 0;border:1px solid #e5e7eb;border-radius:10px;overflow:hidden;">
                                    @foreach($linhas as $label => $valor)
                                        <tr>
                                            <td style="padding:12px 14px;background:#f9fafb;border-bottom:1px solid #e5e7eb;font-size:13px;color:#6b7280;width:38%;">{{ $label }}</td>
                                            <td style="padding:12px 14px;border-bottom:1px solid #e5e7eb;font-size:14px;font-weight:700;color:#111827;">{{ $valor }}</td>
                                        </tr>
                                    @endforeach
                                </table>
                            @endif

                            @if($acaoUrl)
                                <p style="margin:24px 0;">
                                    <a href="{{ $acaoUrl }}" style="display:inline-block;background:#0f3f7a;color:#ffffff;text-decoration:none;font-weight:700;padding:13px 20px;border-radius:8px;">
                                        {{ $acaoTexto }}
                                    </a>
                                </p>
                            @endif

                            <p style="margin:18px 0 0;font-size:14px;line-height:1.6;color:#6b7280;">
                                {{ $rodape ?? 'Se não reconhece esta actividade, entre em contacto com a equipa de suporte da Paruana Comercial.' }}
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:18px 28px;background:#f9fafb;color:#6b7280;font-size:12px;line-height:1.5;">
                            Mensagem automática enviada pela plataforma {{ $appName }}.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
