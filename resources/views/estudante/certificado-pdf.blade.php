<!DOCTYPE html>
<html lang="pt-AO">
<head>
    <meta charset="UTF-8">
    <title>Certificado - {{ $matricula->user->name }}</title>
    <style>
        @page { margin: 18mm; }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            color: #141414;
            font-family: "Times New Roman", Times, serif;
            background: #fff;
        }
        .page {
            position: relative;
            width: 100%;
            min-height: 100%;
            padding: 22px 34px 28px;
            border: 7px double #111;
            background: #fffdf6;
            page-break-after: always;
        }
        .page:last-child { page-break-after: auto; }
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 380px;
            opacity: 0.06;
            transform: translate(-50%, -50%);
        }
        .inner-border {
            position: absolute;
            inset: 14px;
            border: 1px solid #b59b42;
        }
        .content {
            position: relative;
            z-index: 2;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
        }
        .header-table td {
            vertical-align: middle;
        }
        .logo {
            width: 118px;
        }
        .brand {
            text-align: center;
        }
        .brand h1 {
            margin: 0;
            color: #0033b7;
            font-family: Georgia, "Times New Roman", serif;
            font-size: 34px;
            line-height: 1;
            text-transform: uppercase;
        }
        .brand .commercial {
            color: #d11616;
            font-size: 18px;
            font-weight: bold;
            letter-spacing: 1px;
            text-transform: uppercase;
        }
        .brand p {
            margin: 4px 0 0;
            font-size: 12px;
            letter-spacing: 0.4px;
            text-transform: uppercase;
        }
        .certificate-title {
            margin: 18px 0 22px;
            color: #111;
            font-size: 40px;
            font-weight: bold;
            letter-spacing: 4px;
            text-align: center;
            text-decoration: underline;
            text-transform: uppercase;
        }
        .certificate-text {
            width: 88%;
            margin: 0 auto;
            font-size: 19px;
            line-height: 1.75;
            text-align: justify;
        }
        .certificate-text .student,
        .certificate-text .course {
            font-weight: bold;
            text-transform: uppercase;
        }
        .date-line {
            width: 88%;
            margin: 18px auto 0;
            font-size: 18px;
            text-align: left;
        }
        .signature-area {
            width: 100%;
            margin-top: 22px;
            border-collapse: collapse;
            font-size: 14px;
        }
        .signature-area td {
            width: 33.333%;
            vertical-align: bottom;
            text-align: center;
        }
        .signature-line {
            display: inline-block;
            min-width: 210px;
            padding-top: 8px;
            border-top: 1px solid #111;
            font-weight: bold;
            text-transform: uppercase;
        }
        .signature-name {
            display: block;
            margin-top: 4px;
            font-size: 13px;
            font-weight: normal;
            text-transform: none;
        }
        .qr-box {
            display: inline-block;
            padding: 6px;
            border: 1px solid #111;
            background: #fff;
        }
        .qr-box svg {
            width: 82px;
            height: 82px;
        }
        .meta-row {
            width: 88%;
            margin: 18px auto 0;
            border-collapse: collapse;
            font-size: 12px;
        }
        .meta-row td {
            width: 50%;
            padding: 7px 10px;
            border: 1px solid #111;
        }
        .meta-label {
            display: block;
            color: #555;
            font-size: 10px;
            text-transform: uppercase;
        }
        .meta-value {
            font-family: "Courier New", monospace;
            font-weight: bold;
        }
        .program-page {
            background: #fff;
        }
        .program-title {
            margin: 14px 0 22px;
            color: #111;
            font-size: 34px;
            letter-spacing: 3px;
            text-align: center;
            text-decoration: underline;
            text-transform: uppercase;
        }
        .program-subtitle {
            margin: 0 0 14px;
            font-size: 16px;
            text-align: center;
            text-transform: uppercase;
        }
        .program-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }
        .program-table th,
        .program-table td {
            padding: 9px 10px;
            border: 1px solid #111;
        }
        .program-table th {
            background: #f0f0f0;
            font-weight: bold;
            text-align: center;
            text-transform: uppercase;
        }
        .program-table td:not(:first-child) {
            text-align: center;
            white-space: nowrap;
        }
        .program-total {
            margin-top: 14px;
            font-size: 16px;
            font-weight: bold;
            text-align: right;
            text-transform: uppercase;
        }
        .verification {
            margin-top: 8px;
            color: #555;
            font-size: 9px;
            text-align: center;
            word-break: break-all;
        }
    </style>
</head>
<body>
@php
    $meses = [
        1 => 'Janeiro', 2 => 'Fevereiro', 3 => 'Março', 4 => 'Abril',
        5 => 'Maio', 6 => 'Junho', 7 => 'Julho', 8 => 'Agosto',
        9 => 'Setembro', 10 => 'Outubro', 11 => 'Novembro', 12 => 'Dezembro',
    ];
    $emitidoEm = $certificado->emitido_em ?? now();
    $dataExtenso = 'Icolo e Bengo, aos '.$emitidoEm->format('d').' de '.$meses[(int) $emitidoEm->format('n')].' de '.$emitidoEm->format('Y').'.';
    $inicioCurso = $matricula->created_at?->format('d/m/Y') ?? $emitidoEm->format('d/m/Y');
    $fimCurso = $matricula->concluido_em?->format('d/m/Y') ?? $emitidoEm->format('d/m/Y');
    $nota = $solicitacao?->nota_curso !== null ? number_format((float) $solicitacao->nota_curso, 0, ',', '.') : '__';
    $duracao = (int) ($matricula->curso->duracao_horas ?? 0);
    $aulas = $matricula->curso->aulas->sortBy('numero_aula');
    $logoPath = public_path('assets/img/logo.png');
    $logoData = file_exists($logoPath) ? 'data:image/png;base64,'.base64_encode(file_get_contents($logoPath)) : null;
@endphp

<section class="page">
    <div class="inner-border"></div>
    @if($logoData)
        <img class="watermark" src="{{ $logoData }}" alt="">
    @endif

    <div class="content">
        <table class="header-table">
            <tr>
                <td style="width: 145px;">
                    @if($logoData)
                        <img class="logo" src="{{ $logoData }}" alt="Paruana Comercial">
                    @endif
                </td>
                <td class="brand">
                    <h1>Paruana</h1>
                    <div class="commercial">Comercial</div>
                    <p>{{ $centro['nome'] ?? 'Centro de Formação Profissional Paruana' }}</p>
                    <p>{{ $centro['endereco'] ?? 'Angola' }}@if(!empty($centro['nif'])) | NIF: {{ $centro['nif'] }} @endif @if(!empty($centro['contacto'])) | Contacto: {{ $centro['contacto'] }} @endif</p>
                </td>
                <td style="width: 145px;"></td>
            </tr>
        </table>

        <div class="certificate-title">Certificado</div>

        <div class="certificate-text">
            O centro de formação profissional <strong>PARUANA</strong> certifica que
            <span class="student">{{ $matricula->user->name }}</span>, concluiu com aproveitamento o curso de
            <span class="course">{{ $matricula->curso->titulo }}</span>, monitorizado por este centro de formação profissional
            que decorreu no período de {{ $inicioCurso }} à {{ $fimCurso }} com a duração de {{ $duracao }} horas,
            tendo obtido uma classificação final de {{ $nota }} valores, numa escala de 0 à 20.
            <br>
            Por ser verdade e assim constar, passou-se o presente Certificado, que vai assinado e autenticado por esta instituição.
        </div>

        <div class="date-line">{{ $dataExtenso }}</div>

        <table class="meta-row">
            <tr>
                <td>
                    <span class="meta-label">Código do certificado</span>
                    <span class="meta-value">{{ $certificado->codigo }}</span>
                </td>
                <td>
                    <span class="meta-label">Data de emissão</span>
                    <span class="meta-value">{{ $emitidoEm->format('d/m/Y') }}</span>
                </td>
            </tr>
        </table>

        <table class="signature-area">
            <tr>
                <td>
                    <span class="signature-line">O Director do Centro</span>
                    <span class="signature-name">Paulo António João</span>
                </td>
                <td>
                    <div class="qr-box">{!! $qrCode !!}</div>
                    <div class="verification">Verificação: {{ $verificationUrl }}</div>
                </td>
                <td>
                    <span class="signature-line">O Formador</span>
                    <span class="signature-name">{{ trim(($matricula->curso->formador->pessoa->primeironome ?? '').' '.($matricula->curso->formador->pessoa->segundonome ?? '')) ?: 'Formador' }}</span>
                </td>
            </tr>
        </table>
    </div>
</section>

<section class="page program-page">
    <div class="inner-border"></div>
    <div class="content">
        <table class="header-table">
            <tr>
                <td style="width: 120px;">
                    @if($logoData)
                        <img class="logo" src="{{ $logoData }}" alt="Paruana Comercial">
                    @endif
                </td>
                <td class="brand">
                    <h1>Paruana</h1>
                    <div class="commercial">Comercial</div>
                    <p>{{ $centro['nome'] ?? 'Centro de Formação Profissional Paruana' }}</p>
                </td>
                <td style="width: 120px;"></td>
            </tr>
        </table>

        <div class="program-title">Carga Horária</div>
        <p class="program-subtitle">Curso de {{ $matricula->curso->titulo }}</p>

        <table class="program-table">
            <thead>
                <tr>
                    <th style="width: 55%;">Conteúdo programático</th>
                    <th style="width: 15%;">Teoria</th>
                    <th style="width: 15%;">Prática</th>
                    <th style="width: 15%;">Total</th>
                </tr>
            </thead>
            <tbody>
                @forelse($aulas as $aula)
                    @php
                        $horasAula = max(1, (int) ceil(((int) $aula->duracao_minutos) / 60));
                    @endphp
                    <tr>
                        <td>{{ $aula->titulo }}</td>
                        <td>-</td>
                        <td>{{ $horasAula }} hora{{ $horasAula === 1 ? '' : 's' }}</td>
                        <td>{{ $horasAula }} hora{{ $horasAula === 1 ? '' : 's' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td>Módulo: {{ $matricula->curso->titulo }}</td>
                        <td>-</td>
                        <td>{{ $duracao }} horas</td>
                        <td>{{ $duracao }} horas</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="program-total">Total: {{ $duracao }} horas</div>
    </div>
</section>
</body>
</html>
