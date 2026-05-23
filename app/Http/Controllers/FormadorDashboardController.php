<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Formador;
use App\Models\Pessoa;
use App\Models\Inscricao;
use App\Models\Curso;


class FormadorDashboardController extends Controller
{
    //
    public function index()
{
    $formadorId = auth()->user()?->pessoa?->formador?->id;

    $cursos = Curso::where('formador_id', $formadorId)->get();

    $totalCursos = $cursos->count();
    $cursosAtivos = $cursos->where('status', 'publicado')->count();

    $totalAlunos = Inscricao::whereHas('curso', function ($q) use ($formadorId) {
        $q->where('formador_id', $formadorId);
    })->count();

    return view('formador.dashboard', compact(
        'cursos',
        'totalCursos',
        'cursosAtivos',
        'totalAlunos'
    ));
}
}
