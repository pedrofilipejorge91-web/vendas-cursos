@extends('estudant-layouts.apps')

@section('content')
<div class="pagetitle d-flex justify-content-between align-items-start gap-3">
    <div>
        <h1>Notificações</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Painel</a></li>
                <li class="breadcrumb-item active">Notificações</li>
            </ol>
        </nav>
    </div>

    <form method="POST" action="{{ route('estudante.notificacoes.lidas') }}">
        @csrf
        <button class="btn btn-outline-primary" type="submit">
            <i class="bi bi-check2-all me-2"></i>Marcar como lidas
        </button>
    </form>
</div>

<section class="section">
    <div class="card">
        <div class="card-body">
            @forelse($notificacoes as $notificacao)
                <div class="student-list-item list-group-item d-flex gap-3 align-items-start {{ $notificacao->lida_em ? '' : 'bg-primary bg-opacity-10' }}">
                    <i class="bi {{ $notificacao->lida_em ? 'bi-check-circle text-success' : 'bi-bell text-primary' }} fs-4"></i>
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between gap-3">
                            <strong>{{ $notificacao->titulo }}</strong>
                            <small class="text-muted">{{ $notificacao->created_at?->format('d/m/Y H:i') }}</small>
                        </div>
                        <p class="mb-1 text-muted">{{ $notificacao->mensagem }}</p>
                        <span class="badge {{ $notificacao->lida_em ? 'bg-light text-dark' : 'bg-primary' }}">
                            {{ $notificacao->lida_em ? 'Lida' : 'Por ler' }}
                        </span>
                    </div>
                    <form action="{{ route('notificacoes.destroy', $notificacao) }}" method="POST" onsubmit="return confirm('Apagar esta notificação?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger" type="submit" title="Apagar notificação">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </div>
            @empty
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-bell fs-1 d-block mb-2"></i>
                    Ainda não há notificações.
                </div>
            @endforelse

            <div class="mt-3">{{ $notificacoes->links() }}</div>
        </div>
    </div>
</section>
@endsection
