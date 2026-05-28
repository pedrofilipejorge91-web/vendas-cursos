@extends('formador-layouts.apps')

@section('content')
<section class="section">
    <div class="card border-0 shadow-sm">
        <div class="card-body p-5">
            <h3 class="mb-4">Solicitacoes de Certificado</h3>

            @if($solicitacoes->isEmpty())
                <div class="alert alert-info">Nenhuma solicitacao encontrada.</div>
            @else
                <div class="table-responsive">
                    <table class="table table-striped align-middle">
                        <thead>
                            <tr>
                                <th>Aluno</th>
                                <th>Curso</th>
                                <th>Status</th>
                                <th>Nota</th>
                                <th>Accoes</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($solicitacoes as $solicitacao)
                                <tr>
                                    <td>{{ $solicitacao->matricula?->user?->name ?? '-' }}</td>
                                    <td>{{ $solicitacao->curso?->titulo ?? '-' }}</td>
                                    <td>
                                        <span class="badge bg-{{ $solicitacao->statusBadge() }}">
                                            {{ $solicitacao->statusLabel() }}
                                        </span>
                                    </td>
                                    <td>{{ $solicitacao->nota_curso ?? '-' }}</td>
                                    <td>
                                        <a href="{{ route('formador.certificados.questionario', $solicitacao) }}" class="btn btn-sm btn-primary">
                                            Abrir
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</section>
@endsection
