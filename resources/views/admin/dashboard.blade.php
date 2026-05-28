@extends('admin-layouts.apps')

@section('content')
@php
    $periodos = [
        'hoje' => 'Hoje',
        'mes' => 'Este mês',
        'ano' => 'Este ano',
    ];

    $statusBadge = [
        'pago' => 'success',
        'pendente' => 'warning',
        'cancelado' => 'danger',
    ];
@endphp

<div class="pagetitle">
    <h1>Dashboard Administrativo</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Admin</a></li>
            <li class="breadcrumb-item active">Resumo</li>
        </ol>
    </nav>
</div>

<section class="section dashboard">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h5 class="mb-1 fw-bold text-dark">Visão geral da operação</h5>
            <p class="text-muted mb-0">Indicadores actualizados com pedidos, matrículas, cursos e certificados.</p>
        </div>
        <div class="btn-group" role="group" aria-label="Filtrar período">
            @foreach($periodos as $key => $label)
                <a href="{{ route('admin.dashboard', ['periodo' => $key]) }}"
                   class="btn btn-sm {{ $periodo === $key ? 'btn-primary' : 'btn-outline-primary' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>
    </div>

    <div class="row g-4">
        <div class="col-xxl-3 col-md-6">
            <a href="{{ route('admin.relatorios') }}" class="text-decoration-none">
                <div class="card info-card sales-card h-100">
                    <div class="card-body">
                        <h5 class="card-title">Vendas <span>| {{ $periodoLabel }}</span></h5>
                        <div class="d-flex align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                <i class="bi bi-cart-check"></i>
                            </div>
                            <div class="ps-3">
                                <h6>{{ $vendasPeriodo }}</h6>
                                <span class="text-muted small">Hoje: {{ $vendasHoje }} · Mês: {{ $vendasMes }} · Ano: {{ $vendasAno }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-xxl-3 col-md-6">
            <a href="{{ route('admin.relatorios') }}" class="text-decoration-none">
                <div class="card info-card revenue-card h-100">
                    <div class="card-body">
                        <h5 class="card-title">Receita <span>| {{ $periodoLabel }}</span></h5>
                        <div class="d-flex align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                <i class="bi bi-cash-coin"></i>
                            </div>
                            <div class="ps-3">
                                <h6>{{ number_format($receitaPeriodo, 2, ',', '.') }} Kz</h6>
                                <span class="text-muted small">Receita mensal: {{ number_format($receitaMes, 2, ',', '.') }} Kz</span>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-xxl-3 col-md-6">
            <a href="{{ route('estudante.index') }}" class="text-decoration-none">
                <div class="card info-card customers-card h-100">
                    <div class="card-body">
                        <h5 class="card-title">Alunos <span>| Este ano</span></h5>
                        <div class="d-flex align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                <i class="bi bi-people"></i>
                            </div>
                            <div class="ps-3">
                                <h6>{{ $alunosAno }}</h6>
                                <span class="text-muted small">Hoje: {{ $alunosHoje }} · Mês: {{ $alunosMes }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-xxl-3 col-md-6">
            <a href="{{ route('admin.certificados.solicitacoes') }}" class="text-decoration-none">
                <div class="card info-card h-100">
                    <div class="card-body">
                        <h5 class="card-title">Pendências</h5>
                        <div class="d-flex align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-warning bg-opacity-10 text-warning">
                                <i class="bi bi-exclamation-diamond"></i>
                            </div>
                            <div class="ps-3">
                                <h6>{{ $pedidosPendentes + $certificadosPendentes }}</h6>
                                <span class="text-muted small">{{ $pedidosPendentes }} pedidos · {{ $certificadosPendentes }} certificados</span>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start gap-3">
                        <h5 class="card-title">Evolução <span>| últimos 7 dias</span></h5>
                        <a href="{{ route('admin.relatorios') }}" class="btn btn-sm btn-outline-primary">Relatórios</a>
                    </div>
                    <div id="reportsChart"></div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start gap-3">
                        <h5 class="card-title">Operação</h5>
                        <a href="{{ route('curso.index') }}" class="btn btn-sm btn-outline-primary">Cursos</a>
                    </div>
                    <div class="d-grid gap-3">
                        <div class="d-flex justify-content-between border-bottom pb-2">
                            <span class="text-muted">Cursos publicados</span>
                            <strong>{{ $cursosPublicados }}</strong>
                        </div>
                        <div class="d-flex justify-content-between border-bottom pb-2">
                            <span class="text-muted">Pedidos pendentes</span>
                            <strong>{{ $pedidosPendentes }}</strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Certificados pendentes</span>
                            <strong>{{ $certificadosPendentes }}</strong>
                        </div>
                    </div>
                    <div id="trafficChart" style="min-height: 270px;" class="echart mt-3"></div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card recent-sales overflow-auto">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start gap-3">
                        <h5 class="card-title">Pedidos recentes</h5>
                        <a href="{{ route('admin.relatorios') }}" class="btn btn-sm btn-outline-primary">Ver relatório</a>
                    </div>
                    <table class="table table-borderless align-middle">
                        <thead>
                            <tr>
                                <th>Referência</th>
                                <th>Cliente</th>
                                <th>Cursos</th>
                                <th>Total</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentPedidos as $pedido)
                                <tr>
                                    <th>{{ $pedido->referencia }}</th>
                                    <td>{{ $pedido->user->name ?? 'Cliente' }}</td>
                                    <td>{{ $pedido->itens->count() }} item(ns)</td>
                                    <td>{{ number_format($pedido->total, 2, ',', '.') }} Kz</td>
                                    <td>
                                        <span class="badge bg-{{ $statusBadge[$pedido->status] ?? 'secondary' }}">
                                            {{ ucfirst($pedido->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">Ainda não existem pedidos registados.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Actividade recente</h5>
                    <div class="activity">
                        @forelse($atividades as $atividade)
                            <div class="activity-item d-flex">
                                <div class="activite-label">{{ $atividade['data']->diffForHumans(null, true) }}</div>
                                <i class="bi {{ $atividade['icone'] }} activity-badge text-{{ $atividade['cor'] }} align-self-start"></i>
                                <div class="activity-content">
                                    <a href="{{ $atividade['rota'] }}" class="fw-bold text-dark">{{ $atividade['texto'] }}</a>
                                </div>
                            </div>
                        @empty
                            <p class="text-muted">Sem actividade recente.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card top-selling overflow-auto">
                <div class="card-body pb-0">
                    <div class="d-flex justify-content-between align-items-start gap-3">
                        <h5 class="card-title">Cursos mais vendidos</h5>
                        <a href="{{ route('curso.index') }}" class="btn btn-sm btn-outline-primary">Gerir cursos</a>
                    </div>
                    <table class="table table-borderless align-middle">
                        <thead>
                            <tr>
                                <th>Curso</th>
                                <th>Preço</th>
                                <th>Vendidos</th>
                                <th>Receita</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topVendasCursos as $curso)
                                <tr>
                                    <td><strong>{{ $curso->titulo }}</strong></td>
                                    <td>{{ number_format($curso->preco, 2, ',', '.') }} Kz</td>
                                    <td class="fw-bold">{{ $curso->vendidos }}</td>
                                    <td>{{ number_format($curso->receita, 2, ',', '.') }} Kz</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">Ainda não há vendas confirmadas.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-body pb-0">
                    <h5 class="card-title">Receita por curso <span>| mês</span></h5>
                    <div id="budgetChart" style="min-height: 320px;" class="echart"></div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener("DOMContentLoaded", () => {
    new ApexCharts(document.querySelector("#reportsChart"), {
        series: [{
            name: 'Vendas',
            data: @json($seriesSales),
        }, {
            name: 'Receita',
            data: @json($seriesRevenue),
        }, {
            name: 'Alunos',
            data: @json($seriesCustomers),
        }],
        chart: {
            height: 350,
            type: 'area',
            toolbar: { show: false },
        },
        markers: { size: 4 },
        colors: ['#4154f1', '#2eca6a', '#ff771d'],
        fill: {
            type: "gradient",
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.3,
                opacityTo: 0.4,
                stops: [0, 90, 100]
            }
        },
        dataLabels: { enabled: false },
        stroke: { curve: 'smooth', width: 2 },
        xaxis: {
            type: 'datetime',
            categories: @json($seriesDias->map(fn($d) => $d.'T00:00:00.000Z')->values()),
        },
        tooltip: {
            x: { format: 'dd/MM/yy' },
        }
    }).render();

    echarts.init(document.querySelector("#trafficChart")).setOption({
        tooltip: { trigger: 'item' },
        legend: { bottom: '0', left: 'center' },
        series: [{
            name: 'Cursos publicados',
            type: 'pie',
            radius: ['45%', '70%'],
            avoidLabelOverlap: false,
            label: { show: false },
            data: @json($categoriasCursos->map(fn($categoria) => [
                'value' => $categoria->cursos_count,
                'name' => $categoria->nome,
            ])->values()),
        }]
    });

    echarts.init(document.querySelector("#budgetChart")).setOption({
        tooltip: { trigger: 'axis' },
        xAxis: {
            type: 'category',
            data: @json($nomesCursos),
            axisLabel: { rotate: 25 },
        },
        yAxis: { type: 'value' },
        series: [{
            name: 'Receita',
            type: 'bar',
            data: @json($receitaPorCurso),
            itemStyle: { color: '#2eca6a' },
        }, {
            name: 'Matrículas',
            type: 'line',
            data: @json($matriculasPorCurso),
            itemStyle: { color: '#4154f1' },
        }]
    });
});
</script>
@endsection
