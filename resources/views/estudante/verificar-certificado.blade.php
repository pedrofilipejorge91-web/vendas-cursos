@extends('welcome.apps')

@section('content')
<section class="receipt-page">
    <div class="site-container">
        <div class="receipt-card text-center">
            <i class="bi bi-patch-check-fill green" style="font-size: 3rem;"></i>
            <h1 class="mt-3">Certificado válido</h1>
            <p class="muted">Este certificado foi emitido pelo Centro de Formação Paruana Comercial.</p>

            <div class="receipt-grid text-start">
                <div>
                    <span>Aluno</span>
                    <h2>{{ $certificado->matricula->user->name }}</h2>
                </div>
                <div>
                    <span>Curso</span>
                    <h2>{{ $certificado->matricula->curso->titulo }}</h2>
                </div>
                <div>
                    <span>Código</span>
                    <p class="reference">{{ $certificado->codigo }}</p>
                </div>
                <div>
                    <span>Emitido em</span>
                    <h2>{{ $certificado->emitido_em?->format('d/m/Y') }}</h2>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
