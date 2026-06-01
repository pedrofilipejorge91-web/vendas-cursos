<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Formador;
use App\Models\Pessoa;
use App\Models\Matricula;
use App\Models\Curso;
use Illuminate\Support\Facades\DB;


class FormadorDashboardController extends Controller
{
    //
    public function index()
{
    $formadorId = auth()->user()?->pessoa?->formador?->id;

    $cursos = Curso::where('formador_id', $formadorId)->get();

    $totalCursos = $cursos->count();
    $cursosAtivos = $cursos->where('status', 'publicado')->count();

    $alunosMatriculados = Matricula::whereHas('curso', function ($q) use ($formadorId) {
        $q->where('formador_id', $formadorId);
    })->pluck('user_id');

    $alunosInscritos = DB::table('curso_estudante')
        ->join('cursos', 'cursos.id', '=', 'curso_estudante.curso_id')
        ->join('estudantes', 'estudantes.id', '=', 'curso_estudante.estudante_id')
        ->join('pessoas', 'pessoas.id', '=', 'estudantes.pessoa_id')
        ->where('cursos.formador_id', $formadorId)
        ->pluck('pessoas.user_id');

    $totalAlunos = $alunosMatriculados
        ->merge($alunosInscritos)
        ->filter()
        ->unique()
        ->count();

    return view('formador.dashboard', compact(
        'cursos',
        'totalCursos',
        'cursosAtivos',
        'totalAlunos'
    ));
}
}
