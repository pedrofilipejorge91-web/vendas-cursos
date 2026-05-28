@extends('admin-layouts.apps')

@section('content')

<div class="pagetitle d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1>Relatórios Gerais</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Relatórios Analíticos</li>
            </ol>
        </nav>
    </div>
    <!-- Botão Funcional -->
    <button type="button" class="btn btn-outline-primary shadow-sm fw-bold px-3" onclick="window.print()">
        <i class="bi bi-printer me-2"></i> Exportar PDF
    </button>
</div>

<section class="section dashboard">
    <!-- LINHA DE MÉTRICAS (KPIs) -->
    <div class="row g-3">
        <!-- Receita -->
        <div class="col-xxl-3 col-md-6">
            <div class="card info-card revenue-card border-0 shadow-sm">
                <div class="card-body pt-3">
                    <h5 class="card-title text-muted small text-uppercase">Receita Total</h5>
                    <div class="d-flex align-items-center">
                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-success bg-opacity-10 text-success">
                            <i class="bi bi-currency-dollar"></i>
                        </div>
                        <div class="ps-3">
                            <h6 class="fw-bold mb-0 text-success">{{ number_format($metricas['receita'], 2, ',', '.') }} <small>Kz</small></h6>
                            <span class="text-muted small pt-2 ps-1">Valor acumulado</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alunos Ativos -->
        <div class="col-xxl-3 col-md-6">
            <div class="card info-card customers-card border-0 shadow-sm">
                <div class="card-body pt-3">
                    <h5 class="card-title text-muted small text-uppercase">Alunos Activos</h5>
                    <div class="d-flex align-items-center">
                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary">
                            <i class="bi bi-people-fill"></i>
                        </div>
                        <div class="ps-3">
                            <h6 class="fw-bold mb-0">{{ $metricas['alunos_ativos'] }}</h6>
                            <span class="text-muted small pt-2 ps-1">Na plataforma</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cursos Publicados -->
        <div class="col-xxl-3 col-md-6">
            <div class="card info-card sales-card border-0 shadow-sm">
                <div class="card-body pt-3">
                    <h5 class="card-title text-muted small text-uppercase">Cursos On-line</h5>
                    <div class="d-flex align-items-center">
                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-info bg-opacity-10 text-info">
                            <i class="bi bi-mortarboard-fill"></i>
                        </div>
                        <div class="ps-3">
                            <h6 class="fw-bold mb-0">{{ $metricas['cursos_publicados'] }}</h6>
                            <span class="text-muted small pt-2 ps-1">Publicados</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pagamentos Pendentes -->
        <div class="col-xxl-3 col-md-6">
            <div class="card info-card border-0 shadow-sm">
                <div class="card-body pt-3">
                    <h5 class="card-title text-muted small text-uppercase">Pendentes</h5>
                    <div class="d-flex align-items-center">
                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-warning bg-opacity-10 text-warning">
                            <i class="bi bi-hourglass-split"></i>
                        </div>
                        <div class="ps-3">
                            <h6 class="fw-bold mb-0 text-warning">{{ $metricas['pagamentos_pendentes'] }}</h6>
                            <span class="text-muted small pt-2 ps-1">Aguardando aprovação</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- LINHA DE TABELAS E DETALHES -->
    <div class="row">
        <!-- Pagamentos Pendentes -->
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-header bg-white border-bottom-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                    <h5 class="card-title p-0 m-0 fw-bold">Validação de Pagamentos</h5>
                    <span class="badge bg-warning bg-opacity-10 text-warning px-3 py-2 rounded-pill">Ação Requerida</span>
                </div>
                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle border-light">
                            <thead class="bg-light">
                                <tr>
                                    <th class="border-0 small text-uppercase">Ref.</th>
                                    <th class="border-0 small text-uppercase">Aluno</th>
                                    <th class="border-0 small text-uppercase">Montante</th>
                                    <th class="border-0 text-center small text-uppercase">Ação</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pagamentosPendentes as $pedido)
                                    <tr>
                                        <td><span class="fw-bold text-dark">#{{ $pedido->referencia }}</span></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm-text bg-secondary bg-opacity-10 text-secondary rounded-circle me-2">
                                                    {{ strtoupper(substr($pedido->user->name, 0, 1)) }}
                                                </div>
                                                <span class="small">{{ $pedido->user->name }}</span>
                                            </div>
                                        </td>
                                        <td><span class="text-dark fw-semibold">{{ number_format($pedido->total, 2, ',', '.') }} Kz</span></td>
                                        <td class="text-center">
                                            <form action="{{ route('pagamento.confirmar', $pedido) }}" method="POST">
                                                @csrf
                                                <button class="btn btn-success btn-sm px-3 shadow-sm fw-bold">
                                                    <i class="bi bi-check2-circle me-1"></i> Aprovar
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-5">
                                            <i class="bi bi-check-all fs-1 text-success d-block mb-2"></i>
                                            <span class="text-muted">Tudo em dia! Sem pendências.</span>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cursos mais vendidos -->
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-header bg-white border-bottom-0 pt-4 px-4">
                    <h5 class="card-title p-0 m-0 fw-bold">Top Performance</h5>
                </div>
                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead class="bg-light">
                                <tr>
                                    <th class="border-0 small text-uppercase">Curso</th>
                                    <th class="border-0 small text-uppercase text-end">Alunos</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($cursosMaisVendidos as $curso)
                                    <tr>
                                        <td>
                                            <div class="fw-bold text-dark small">{{ Str::limit($curso->titulo, 30) }}</div>
                                            <!-- Barra de progresso visual -->
                                            <div class="progress mt-1" style="height: 4px;">
                                                @php
                                                    $maxMatriculas = $cursosMaisVendidos->max('matriculas_count') ?? 0;
                                                    $percent = ($maxMatriculas > 0)
                                                        ? (($curso->matriculas_count / $maxMatriculas) * 100)
                                                        : 0;
                                                @endphp
                                                <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $percent }}%">
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-end">
                                            <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill">
                                                {{ $curso->matriculas_count }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="text-center py-5 text-muted">
                                            Nenhum dado disponível.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    /* Estilos Adicionais para o Dashboard Analítico */
    .avatar-sm-text {
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 0.8rem;
    }

    .info-card h6 {
        font-size: 1.5rem;
    }

    .card-icon {
        width: 50px;
        height: 50px;
        font-size: 1.5rem;
    }

    /* Tabela Hover Effect */
    .table-hover tbody tr:hover {
        background-color: rgba(0,0,0,0.01);
    }

    /* Custom Scrollbar para tabelas mobile */
    .table-responsive::-webkit-scrollbar {
        height: 5px;
    }
    .table-responsive::-webkit-scrollbar-thumb {
        background: #dee2e6;
        border-radius: 10px;
    }
</style>

@endsection