@extends('formador-layouts.apps')

@section('content')
<div class="formador-dashboard">
    <div class="edit-page-header">
        <div>
            <span class="eyebrow">Centro de alertas</span>
            <h1>Notificações</h1>
            <p>Acompanhe certificados, respostas, aulas e avisos da plataforma.</p>
        </div>

        <form method="POST" action="{{ route('formador.notificacoes.lidas') }}">
            @csrf
            <button class="btn btn-outline-primary" type="submit">
                <i class="bi bi-check2-all me-2"></i>Marcar como lidas
            </button>
        </form>
    </div>

    <section class="panel-card">
        @forelse($notificacoes as $notificacao)
            <article class="action-item">
                <i class="bi {{ $notificacao->lida_em ? 'bi-check-circle' : 'bi-bell' }}"></i>
                <div>
                    <strong>{{ $notificacao->titulo }}</strong>
                    <span>{{ $notificacao->mensagem }}</span>
                    <span>{{ $notificacao->created_at?->diffForHumans() }} · {{ $notificacao->lida_em ? 'Lida' : 'Por ler' }}</span>
                </div>
                <form action="{{ route('notificacoes.destroy', $notificacao) }}" method="POST" class="ms-auto" onsubmit="return confirm('Apagar esta notificação?')">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-outline-danger" type="submit" title="Apagar notificação">
                        <i class="bi bi-trash"></i>
                    </button>
                </form>
            </article>
        @empty
            <div class="empty-state">
                <i class="bi bi-bell"></i>
                <h3>Sem notificações</h3>
                <p>Quando houver novidades importantes, elas ficam guardadas aqui.</p>
            </div>
        @endforelse

        {{ $notificacoes->links() }}
    </section>
</div>
@endsection
