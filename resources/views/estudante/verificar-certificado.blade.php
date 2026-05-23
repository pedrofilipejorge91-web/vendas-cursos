@extends('welcome.apps')

@section('content')
<section class="py-28 px-6 bg-surface-container-low">
    <div class="max-w-3xl mx-auto bg-white rounded-xl p-10 text-center academic-monolith-shadow">
        <span class="material-symbols-outlined text-green-600 text-6xl">verified</span>
        <h1 class="text-3xl font-bold text-primary mt-4">Certificado válido</h1>
        <p class="text-on-surface-variant mt-3">Este certificado foi emitido pelo Centro de Formação Paruana Comercial.</p>
        <div class="mt-8 text-left bg-surface-container-low rounded-lg p-6 space-y-3">
            <p><strong>Aluno:</strong> {{ $certificado->matricula->user->name }}</p>
            <p><strong>Curso:</strong> {{ $certificado->matricula->curso->titulo }}</p>
            <p><strong>Código:</strong> {{ $certificado->codigo }}</p>
            <p><strong>Emitido em:</strong> {{ $certificado->emitido_em?->format('d/m/Y') }}</p>
        </div>
    </div>
</section>
@endsection
