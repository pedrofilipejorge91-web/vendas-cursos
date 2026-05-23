<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\Matricula;
use App\Models\Pagamento;
use App\Models\Pedido;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class RelatorioController extends Controller
{
    public function admin()
    {
        $metricas = [
            'receita' => Pedido::where('status', 'pago')->sum('total'),
            'alunos_ativos' => Matricula::distinct('user_id')->count('user_id'),
            'cursos_publicados' => Curso::where('status', 'publicado')->count(),
            'pagamentos_pendentes' => Pagamento::where('status', 'pendente')->count(),
            'usuarios' => User::count(),
        ];

        $pagamentosPendentes = Pedido::with('user', 'pagamento', 'itens')
            ->where('status', 'pendente')
            ->latest()
            ->take(10)
            ->get();

        $cursosMaisVendidos = Curso::query()
            ->withCount('matriculas')
            ->orderByDesc('matriculas_count')
            ->take(10)
            ->get();

        return view('admin.relatorios.index', compact('metricas', 'pagamentosPendentes', 'cursosMaisVendidos'));
    }

    public function formador()
    {
        $formadorId = Auth::user()?->pessoa?->formador?->id;

        $cursos = Curso::withCount('matriculas')
            ->where('formador_id', $formadorId)
            ->get();

        $receita = Pedido::where('status', 'pago')
            ->whereHas('itens.curso', function ($query) use ($formadorId) {
                $query->where('formador_id', $formadorId);
            })
            ->sum('total');

        return view('formador.relatorios', compact('cursos', 'receita'));
    }
}
