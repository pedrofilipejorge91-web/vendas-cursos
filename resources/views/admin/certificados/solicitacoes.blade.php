@extends('admin-layouts.apps')

@section('content')
<section class="section">
    <div class="card border-0 shadow-sm">
        <div class="card-body p-5">
            <h3 class="mb-4">Solicitações de Certificado</h3>

            @if($solicitacoes->isEmpty())
                <div class="alert alert-info">Nenhuma solicitação encontrada.</div>
            @else
                <div class="table-responsive">
                    <table class="table table-striped align-middle">
                        <thead>
                            <tr>
                                <th>Aluno</th>
                                <th>Curso</th>
                                <th>Status</th>
                                <th>Nota</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($solicitacoes as $solicitacao)
                                <tr>
                                    <td>{{ $solicitacao->matricula?->user?->name ?? '-' }}</td>
                                    <td>{{ $solicitacao->matricula?->curso?->titulo ?? '-' }}</td>
                                    <td>
                                        <span class="badge bg-{{ $solicitacao->status === 'aprovado' ? 'success' : ($solicitacao->status === 'rejeitado' ? 'danger' : 'warning') }}">
                                            {{ $solicitacao->status }}
                                        </span>
                                    </td>
                                    <td>{{ $solicitacao->nota_curso ?? '-' }}</td>
                                    <td>
                                        @if($solicitacao->status === 'pendente')
                                            <form method="POST" action="{{ route('admin.certificados.decidir', $solicitacao) }}" class="d-flex gap-2">
                                                @csrf
                                                <input type="hidden" name="acao" value="aprovar">
                                                <button class="btn btn-sm btn-success" type="submit">Aprovar</button>
                                            </form>
                                            <form method="POST" action="{{ route('admin.certificados.decidir', $solicitacao) }}" class="d-flex gap-2 mt-2">
                                                @csrf
                                                <input type="hidden" name="acao" value="rejeitar">
                                                <button class="btn btn-sm btn-danger" type="submit">Rejeitar</button>
                                            </form>
                                        @else
                                            <span class="text-muted">Decidido</span>
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

