@extends('admin-layouts.apps')

@section('content')

<div class="pagetitle">
  <h1>Notificacoes</h1>
</div>

<section class="section">
  <div class="card">
    <div class="card-body">
      <div class="d-flex justify-content-between align-items-center">
        <h5 class="card-title">Mensagens do sistema</h5>
        <form action="{{ route('admin.notificacoes.lidas') }}" method="POST">
          @csrf
          <button class="btn btn-sm btn-outline-primary" type="submit">Marcar todas como lidas</button>
        </form>
      </div>

      <div class="list-group">
        @forelse($notificacoes as $notificacao)
          <div class="list-group-item {{ $notificacao->lida_em ? '' : 'list-group-item-primary' }}">
            <div class="d-flex justify-content-between gap-3">
              <div>
                <strong>{{ $notificacao->titulo }}</strong>
                <small class="d-block text-muted">{{ strtoupper($notificacao->canal) }} · {{ $notificacao->created_at->format('d/m/Y H:i') }}</small>
              </div>
              <form action="{{ route('notificacoes.destroy', $notificacao) }}" method="POST" onsubmit="return confirm('Apagar esta notificação?')">
                @csrf
                @method('DELETE')
                <button class="btn btn-sm btn-outline-danger" type="submit" title="Apagar notificação">
                  <i class="bi bi-trash"></i>
                </button>
              </form>
            </div>
            <p class="mb-0 mt-2">{{ $notificacao->mensagem }}</p>
          </div>
        @empty
          <p class="text-muted">Sem notificacoes.</p>
        @endforelse
      </div>

      <div class="mt-3">{{ $notificacoes->links() }}</div>
    </div>
  </div>
</section>

@endsection
