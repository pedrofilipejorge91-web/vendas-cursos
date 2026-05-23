@extends('admin-layouts.apps')

@section('content')

<div class="pagetitle">
  <h1>Relatorios Gerais</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
      <li class="breadcrumb-item active">Relatorios</li>
    </ol>
  </nav>
</div>

<section class="section dashboard">
  <div class="row">
    <div class="col-xxl-3 col-md-6">
      <div class="card info-card sales-card">
        <div class="card-body">
          <h5 class="card-title">Receita</h5>
          <div class="d-flex align-items-center">
            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center"><i class="bi bi-cash"></i></div>
            <div class="ps-3"><h6>{{ number_format($metricas['receita'], 2, ',', '.') }} Kz</h6></div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-xxl-3 col-md-6">
      <div class="card info-card customers-card">
        <div class="card-body">
          <h5 class="card-title">Alunos activos</h5>
          <div class="d-flex align-items-center">
            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center"><i class="bi bi-people"></i></div>
            <div class="ps-3"><h6>{{ $metricas['alunos_ativos'] }}</h6></div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-xxl-3 col-md-6">
      <div class="card info-card revenue-card">
        <div class="card-body">
          <h5 class="card-title">Cursos publicados</h5>
          <div class="d-flex align-items-center">
            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center"><i class="bi bi-mortarboard"></i></div>
            <div class="ps-3"><h6>{{ $metricas['cursos_publicados'] }}</h6></div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-xxl-3 col-md-6">
      <div class="card info-card">
        <div class="card-body">
          <h5 class="card-title">Pagamentos pendentes</h5>
          <div class="d-flex align-items-center">
            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center"><i class="bi bi-clock"></i></div>
            <div class="ps-3"><h6>{{ $metricas['pagamentos_pendentes'] }}</h6></div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-lg-7">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title">Pagamentos pendentes</h5>
          <table class="table">
            <thead>
              <tr>
                <th>Referencia</th>
                <th>Aluno</th>
                <th>Total</th>
                <th>Accao</th>
              </tr>
            </thead>
            <tbody>
              @forelse($pagamentosPendentes as $pedido)
                <tr>
                  <td>{{ $pedido->referencia }}</td>
                  <td>{{ $pedido->user->name }}</td>
                  <td>{{ number_format($pedido->total, 2, ',', '.') }} Kz</td>
                  <td>
                    <form action="{{ route('pagamento.confirmar', $pedido) }}" method="POST">
                      @csrf
                      <button class="btn btn-sm btn-success">Confirmar</button>
                    </form>
                  </td>
                </tr>
              @empty
                <tr><td colspan="4" class="text-muted">Sem pagamentos pendentes.</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <div class="col-lg-5">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title">Cursos mais vendidos</h5>
          <table class="table">
            <thead>
              <tr>
                <th>Curso</th>
                <th>Matriculas</th>
              </tr>
            </thead>
            <tbody>
              @forelse($cursosMaisVendidos as $curso)
                <tr>
                  <td>{{ $curso->titulo }}</td>
                  <td>{{ $curso->matriculas_count }}</td>
                </tr>
              @empty
                <tr><td colspan="2" class="text-muted">Sem vendas ainda.</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</section>

@endsection
