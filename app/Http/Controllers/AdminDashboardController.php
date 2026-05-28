<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\CertificadoSolicitacao;
use App\Models\Curso;
use App\Models\Matricula;
use App\Models\Pedido;
use App\Models\PedidoItem;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function admin(Request $request)
    {
        $hoje = now();
        $periodo = in_array($request->get('periodo'), ['hoje', 'mes', 'ano'], true)
            ? $request->get('periodo')
            : 'mes';

        $datasHoje = [$hoje->copy()->startOfDay(), $hoje->copy()->endOfDay()];
        $inicioMes = $hoje->copy()->startOfMonth()->startOfDay();
        $fimMes = $hoje->copy()->endOfMonth()->endOfDay();
        $inicioAno = $hoje->copy()->startOfYear()->startOfDay();
        $fimAno = $hoje->copy()->endOfYear()->endOfDay();

        [$inicioPeriodo, $fimPeriodo, $periodoLabel] = match ($periodo) {
            'hoje' => [$datasHoje[0], $datasHoje[1], 'Hoje'],
            'ano' => [$inicioAno, $fimAno, 'Este ano'],
            default => [$inicioMes, $fimMes, 'Este mês'],
        };

        $vendasHoje = Pedido::where('status', 'pago')->whereBetween('created_at', $datasHoje)->count();
        $vendasMes = Pedido::where('status', 'pago')->whereBetween('created_at', [$inicioMes, $fimMes])->count();
        $vendasAno = Pedido::where('status', 'pago')->whereBetween('created_at', [$inicioAno, $fimAno])->count();
        $vendasPeriodo = Pedido::where('status', 'pago')->whereBetween('created_at', [$inicioPeriodo, $fimPeriodo])->count();

        $receitaHoje = Pedido::where('status', 'pago')->whereBetween('created_at', $datasHoje)->sum('total');
        $receitaMes = Pedido::where('status', 'pago')->whereBetween('created_at', [$inicioMes, $fimMes])->sum('total');
        $receitaPeriodo = Pedido::where('status', 'pago')->whereBetween('created_at', [$inicioPeriodo, $fimPeriodo])->sum('total');

        $alunosHoje = Matricula::whereBetween('created_at', $datasHoje)->distinct('user_id')->count('user_id');
        $alunosMes = Matricula::whereBetween('created_at', [$inicioMes, $fimMes])->distinct('user_id')->count('user_id');
        $alunosAno = Matricula::whereBetween('created_at', [$inicioAno, $fimAno])->distinct('user_id')->count('user_id');

        $pedidosPendentes = Pedido::where('status', 'pendente')->count();
        $cursosPublicados = Curso::where('status', 'publicado')->count();
        $certificadosPendentes = CertificadoSolicitacao::whereIn('status', [
            CertificadoSolicitacao::STATUS_AGUARDANDO_ADMIN,
            'pendente',
        ])->count();

        $topCursos = Curso::query()
            ->selectRaw('cursos.id, cursos.titulo')
            ->withCount('matriculas')
            ->orderByDesc('matriculas_count')
            ->take(5)
            ->get();

        $receitaPorCurso = collect($topCursos->pluck('id')->all())->map(function ($cursoId) use ($inicioMes, $fimMes) {
            return (float) (PedidoItem::query()
                ->selectRaw('SUM(pedido_items.preco * pedido_items.quantidade) as total')
                ->join('pedidos', 'pedidos.id', '=', 'pedido_items.pedido_id')
                ->where('pedidos.status', 'pago')
                ->whereBetween('pedidos.created_at', [$inicioMes, $fimMes])
                ->where('pedido_items.curso_id', $cursoId)
                ->value('total') ?? 0);
        })->values();

        $nomesCursos = $topCursos->pluck('titulo')->values();
        $matriculasPorCurso = $topCursos->pluck('matriculas_count')->values();

        $recentPedidos = Pedido::with(['user', 'itens'])
            ->latest()
            ->take(8)
            ->get();

        $topVendasCursos = PedidoItem::query()
            ->selectRaw('pedido_items.curso_id, pedido_items.titulo, pedido_items.preco, SUM(pedido_items.quantidade) as vendidos, SUM(pedido_items.preco * pedido_items.quantidade) as receita')
            ->join('pedidos', 'pedidos.id', '=', 'pedido_items.pedido_id')
            ->where('pedidos.status', 'pago')
            ->groupBy('pedido_items.curso_id', 'pedido_items.titulo', 'pedido_items.preco')
            ->orderByDesc('vendidos')
            ->take(5)
            ->get();

        $categoriasCursos = Categoria::withCount(['cursos' => function ($query) {
                $query->where('status', 'publicado');
            }])
            ->orderByDesc('cursos_count')
            ->take(6)
            ->get();

        $atividades = collect()
            ->merge($recentPedidos->map(fn ($pedido) => [
                'data' => $pedido->created_at,
                'icone' => $pedido->status === 'pago' ? 'bi-check-circle' : 'bi-clock',
                'cor' => $pedido->status === 'pago' ? 'success' : 'warning',
                'texto' => 'Pedido '.$pedido->referencia.' '.$this->statusPedidoLabel($pedido->status),
                'rota' => route('admin.relatorios'),
            ]))
            ->merge(Matricula::with(['curso', 'user'])->latest()->take(5)->get()->map(fn ($matricula) => [
                'data' => $matricula->created_at,
                'icone' => 'bi-mortarboard',
                'cor' => 'primary',
                'texto' => ($matricula->user->name ?? 'Aluno').' iniciou '.($matricula->curso->titulo ?? 'um curso'),
                'rota' => route('estudante.index'),
            ]))
            ->sortByDesc('data')
            ->take(8)
            ->values();

        $seriesDias = collect(range(0, 6))
            ->map(fn ($i) => $hoje->copy()->subDays(6 - $i)->format('Y-m-d'))
            ->values();

        $seriesSales = [];
        $seriesRevenue = [];
        $seriesCustomers = [];

        foreach ($seriesDias as $dia) {
            $inicioDia = now()->parse($dia)->startOfDay();
            $fimDia = now()->parse($dia)->endOfDay();

            $seriesSales[] = (int) Pedido::where('status', 'pago')->whereBetween('created_at', [$inicioDia, $fimDia])->count();
            $seriesRevenue[] = (float) Pedido::where('status', 'pago')->whereBetween('created_at', [$inicioDia, $fimDia])->sum('total');
            $seriesCustomers[] = (int) Matricula::whereBetween('created_at', [$inicioDia, $fimDia])->distinct('user_id')->count('user_id');
        }

        return view('admin.dashboard', compact(
            'periodo',
            'periodoLabel',
            'vendasHoje',
            'vendasMes',
            'vendasAno',
            'vendasPeriodo',
            'receitaHoje',
            'receitaMes',
            'receitaPeriodo',
            'alunosHoje',
            'alunosMes',
            'alunosAno',
            'pedidosPendentes',
            'cursosPublicados',
            'certificadosPendentes',
            'seriesDias',
            'seriesSales',
            'seriesRevenue',
            'seriesCustomers',
            'nomesCursos',
            'receitaPorCurso',
            'matriculasPorCurso',
            'recentPedidos',
            'topVendasCursos',
            'categoriasCursos',
            'atividades'
        ));
    }

    private function statusPedidoLabel(string $status): string
    {
        return match ($status) {
            'pago' => 'foi confirmado',
            'pendente' => 'aguarda pagamento',
            'cancelado' => 'foi cancelado',
            default => 'esta com status '.ucfirst($status),
        };
    }
}
