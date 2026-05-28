@extends('admin-layouts.apps')

@section('content')
<section class="section">
    <div class="card border-0 shadow-sm">
        <div class="card-body p-5">
            <h3 class="mb-4">Solicitacoes de Certificado</h3>

            @if($solicitacoes->isEmpty())
                <div class="alert alert-info">Nenhuma solicitacao aguardando decisao.</div>
            @else
                <div class="table-responsive">
                    <table class="table table-striped align-middle">
                        <thead>
                            <tr>
                                <th>Aluno</th>
                                <th>Curso</th>
                                <th>Formador</th>
                                <th>Status</th>
                                <th>Nota</th>
                                <th>Accoes</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($solicitacoes as $solicitacao)
                                <tr>
                                    <td>{{ $solicitacao->matricula?->user?->name ?? '-' }}</td>
                                    <td>{{ $solicitacao->matricula?->curso?->titulo ?? '-' }}</td>
                                    <td>{{ $solicitacao->instrutor?->pessoa?->user?->name ?? '-' }}</td>
                                    <td>
                                        <span class="badge bg-{{ $solicitacao->statusBadge() }}">
                                            {{ $solicitacao->statusLabel() }}
                                        </span>
                                    </td>
                                    <td>{{ $solicitacao->nota_curso ?? '-' }}</td>
                                    <td>
                                        @if(in_array($solicitacao->status, [\App\Models\CertificadoSolicitacao::STATUS_AGUARDANDO_ADMIN, 'pendente'], true))
                                            <div class="d-flex flex-column gap-2">
                                                <form method="POST" action="{{ route('admin.certificados.decidir', $solicitacao) }}">
                                                    @csrf
                                                    <input type="hidden" name="acao" value="aprovar">
                                                    <button class="btn btn-sm btn-success" type="submit">Aprovar download</button>
                                                </form>
                                                <form method="POST" action="{{ route('admin.certificados.decidir', $solicitacao) }}">
                                                    @csrf
                                                    <input type="hidden" name="acao" value="rejeitar">
                                                    <button class="btn btn-sm btn-danger" type="submit">Rejeitar</button>
                                                </form>
                                            </div>
                                        @else
                                            <span class="text-muted">Decidido em {{ $solicitacao->decidido_em?->format('d/m/Y H:i') ?? '-' }}</span>
                                        @endif
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
