<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Certificado - {{ $matricula->user->name }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Georgia, serif; background: #fff; padding: 20px; }
        .certificate { width: 100%; max-width: 800px; margin: 0 auto; padding: 60px; border: 3px solid #1a472a; background: #f5f5f0; position: relative; }
        .certificate::before { content: ''; position: absolute; inset: 20px; border: 1px solid #1a472a; }
        .content { position: relative; z-index: 1; text-align: center; }
        .institution-name { font-size: 24px; font-weight: bold; color: #1a472a; margin-bottom: 5px; }
        .subtitle { font-size: 12px; color: #666; margin-bottom: 10px; }
        .badge { display: inline-block; background: #1a472a; color: white; padding: 8px 16px; border-radius: 20px; font-size: 11px; font-weight: bold; letter-spacing: 1px; margin-top: 10px; }
        .divider { height: 2px; background: #1a472a; margin: 30px 0; }
        .text-centered p { font-size: 14px; color: #333; margin: 8px 0; }
        .recipient-name { font-size: 28px; font-weight: bold; color: #1a472a; margin: 15px 0; text-transform: uppercase; }
        .course-title { font-size: 20px; font-weight: bold; color: #1a472a; margin: 15px 0; font-style: italic; }
        .footer-info { margin-top: 40px; display: grid; grid-template-columns: 1fr 1fr; gap: 40px; font-size: 12px; }
        .code-section { border: 1px solid #1a472a; padding: 12px; background: white; }
        .code-label { font-size: 10px; color: #666; text-transform: uppercase; margin-bottom: 4px; }
        .code { font-family: "Courier New", monospace; font-weight: bold; font-size: 14px; color: #1a472a; }
        .qr-section { text-align: center; margin-top: 20px; }
        .qr-code { display: inline-block; padding: 10px; background: white; border: 1px solid #ddd; }
        .verification-url { font-size: 10px; color: #666; margin-top: 10px; word-break: break-all; }
        .signature-section { display: grid; grid-template-columns: 1fr 1fr; gap: 60px; margin-top: 50px; font-size: 12px; }
        .signature-line { border-top: 1px solid #333; padding-top: 10px; text-align: center; }
        .issue-date { font-size: 12px; color: #666; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="certificate">
        <div class="content">
            <div class="header">
                <div class="institution-name">{{ $centro['nome'] ?? 'Centro de Formação Paruana Comercial' }}</div>
                <div class="subtitle">Plataforma de Educacao e Certificacao</div>
                <div class="subtitle">
                    {{ $centro['endereco'] ?? 'Angola' }}
                    @if(!empty($centro['nif'])) | NIF: {{ $centro['nif'] }} @endif
                    @if(!empty($centro['contacto'])) | Contacto: {{ $centro['contacto'] }} @endif
                </div>
                <div class="badge">CERTIFICADO DIGITAL</div>
            </div>

            <div class="divider"></div>

            <div class="text-centered">
                <p>Certificamos que</p>
                <div class="recipient-name">{{ $matricula->user->name }}</div>
                <p>concluiu com sucesso o curso</p>
                <div class="course-title">{{ $matricula->curso->titulo }}</div>
                <p>completando {{ $matricula->curso->duracao_horas }} horas de aprendizado e atividades praticas.</p>
            </div>

            <div class="divider"></div>

            <div class="footer-info">
                <div class="code-section">
                    <div class="code-label">Codigo do Certificado</div>
                    <div class="code">{{ $certificado->codigo }}</div>
                </div>
                <div class="code-section">
                    <div class="code-label">Data de Emissao</div>
                    <div class="code">{{ $certificado->emitido_em?->format('d/m/Y') ?? now()->format('d/m/Y') }}</div>
                </div>
            </div>

            <div class="qr-section">
                <p style="font-size: 12px; margin-bottom: 10px; color: #666;">Escaneie o codigo QR para verificar a autenticidade:</p>
                <div class="qr-code">{!! $qrCode !!}</div>
                <div class="verification-url">Verificar em: {{ $verificationUrl }}</div>
            </div>

            <div class="signature-section">
                <div class="signature-line">
                    <p style="font-weight: bold;">Diretor Geral</p>
                    <p style="font-size: 10px;">{{ $centro['nome'] ?? 'Centro de Formação Paruana Comercial' }}</p>
                </div>
                <div class="signature-line">
                    <p style="font-weight: bold;">{{ $matricula->curso->formador->pessoa->primeironome ?? 'Formador' }}</p>
                    <p style="font-size: 10px;">Instrutor do Curso</p>
                </div>
            </div>

            <div class="issue-date">
                Emitido em: {{ $certificado->emitido_em?->format('d/m/Y') ?? now()->format('d/m/Y') }}
            </div>
        </div>
    </div>
</body>
</html>
