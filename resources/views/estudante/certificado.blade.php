@extends('estudant-layouts.apps')

@section('content')
<section class="section">
    <div class="card border-0 shadow-sm">
        <div class="card-body p-5 text-center">
            <p class="text-uppercase text-muted fw-bold">Certificado digital</p>
            <h1 class="fw-bold mb-3">Centro de Formação Paruana Comercial</h1>
            <p class="lead">Certificamos que</p>
            <h2 class="fw-bold text-primary">{{ $matricula->user->name }}</h2>
            <p class="lead mt-3">concluiu com aproveitamento o curso</p>
            <h3 class="fw-bold">{{ $matricula->curso->titulo }}</h3>
            <p class="text-muted mt-4">Emitido em {{ $certificado->emitido_em?->format('d/m/Y') ?? now()->format('d/m/Y') }}</p>
            <div class="border rounded p-3 d-inline-block mt-3">
                <strong>{{ $certificado->codigo }}</strong>
            </div>
            <p class="mt-3 small text-muted">Verificação: {{ route('certificado.verificar', $certificado->codigo) }}</p>
            <div class="mt-4 d-flex gap-2 justify-content-center">
                <button onclick="window.print()" class="btn btn-primary">
                    <i class="bi bi-printer me-1"></i> Imprimir
                </button>
                @php
                    $solicitacao = \App\Models\CertificadoSolicitacao::where('matricula_id', $matricula->id)->first();
                @endphp

                @if($solicitacao && $solicitacao->status === 'aprovado')
                    <a href="{{ route('estudante.certificado.download', $matricula->id) }}" class="btn btn-success">
                        <i class="bi bi-download me-1"></i> Download PDF
                    </a>
                @else
                    <a class="btn btn-secondary disabled" href="#" aria-disabled="true" onclick="return false;">
                        <i class="bi bi-lock me-1"></i> Download disponível após aprovação do admin
                    </a>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection
