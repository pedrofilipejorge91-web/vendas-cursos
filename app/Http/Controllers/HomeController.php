<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categoria;
use App\Models\Curso;
use App\Models\Aula;
use App\Models\Avaliacao;
use App\Models\Formador;
use App\Models\Matricula;
use Illuminate\Support\Facades\File;


class HomeController extends Controller
{
    //
    public function index(){
        $cursosDestaque = Curso::with('categoria', 'formador.pessoa')
            ->withCount('matriculas')
            ->where('status', 'publicado')
            ->orderByDesc('matriculas_count')
            ->latest()
            ->take(3)
            ->get();

        $categorias = Categoria::query()
            ->withCount(['cursos' => function ($query) {
                $query->where('status', 'publicado');
            }])
            ->orderByDesc('cursos_count')
            ->take(6)
            ->get();

        $formadores = Formador::with('pessoa')
            ->withCount('cursos')
            ->orderByDesc('cursos_count')
            ->take(3)
            ->get();

        $metricas = [
            'alunos' => Matricula::distinct('user_id')->count('user_id'),
            'cursos' => Curso::where('status', 'publicado')->count(),
            'formadores' => Formador::count(),
            'avaliacao' => round((float) Avaliacao::avg('nota'), 1),
        ];

        $galeriaImagens = $this->galeriaImagens();

        return view('home.welcome', compact('cursosDestaque', 'categorias', 'formadores', 'metricas', 'galeriaImagens'));
    }

    public function catalogo(Request $request)
    {
        $query = Curso::with('categoria', 'formador.pessoa')
            ->where('status', 'publicado');

        if ($request->filled('q')) {
            $termo = $request->q;
            $query->where(function ($subquery) use ($termo) {
                $subquery->where('titulo', 'like', "%{$termo}%")
                    ->orWhere('descricao', 'like', "%{$termo}%");
            });
        }

        if ($request->filled('categoria_id')) {
            $query->where('categoria_id', $request->categoria_id);
        }

        if ($request->filled('preco_min')) {
            $query->where('preco', '>=', $request->preco_min);
        }

        if ($request->filled('preco_max')) {
            $query->where('preco', '<=', $request->preco_max);
        }

        if ($request->filled('duracao_max')) {
            $query->where('duracao_horas', '<=', $request->duracao_max);
        }

        if ($request->filled('idioma')) {
            $query->where('idioma', $request->idioma);
        }

        $cursos = $query->latest()->paginate(9)->withQueryString();
        $categorias = Categoria::orderBy('nome')->get();
        $idiomas = Curso::whereNotNull('idioma')->distinct()->pluck('idioma')->filter()->values();

        return view('home.catalogo', compact('cursos', 'categorias', 'idiomas'));
    }


    public function categoria($id){
        $categoria = Categoria::find($id);
     $cursos = Curso::where('categoria_id',$id)->where('status', 'publicado')->paginate(3);
     return view('home.categoria',compact('cursos','categoria'));
    }

    public function show($id){
        $cursos = Curso::with('avaliacoes.estudante.pessoa', 'categoria', 'formador.pessoa')->findOrFail($id);
        $aulas = Aula::where('curso_id',$id)->orderBy('numero_aula')->get();
        $minhaMatricula = auth()->check()
            ? \App\Models\Matricula::where('user_id', auth()->id())->where('curso_id', $id)->first()
            : null;
        $minhaAvaliacao = auth()->check()
            ? Avaliacao::where('curso_id', $id)
                ->where('estudante_id', auth()->user()?->pessoa?->estudante?->id)
                ->first()
            : null;

        return view('home.detalhe', compact('cursos','aulas', 'minhaMatricula', 'minhaAvaliacao'));


    }

    private function galeriaImagens()
    {
        $publicPath = public_path();
        $extensoes = ['jpg', 'jpeg', 'png', 'webp', 'gif', 'svg'];

        if (! File::isDirectory($publicPath)) {
            return collect();
        }

        return collect(File::allFiles($publicPath))
            ->filter(fn ($file) => in_array(strtolower($file->getExtension()), $extensoes, true))
            ->map(function ($file) use ($publicPath) {
                $relativePath = str_replace('\\', '/', $file->getPathname());
                $relativePath = ltrim(str_replace(str_replace('\\', '/', $publicPath), '', $relativePath), '/');
                $titulo = str($file->getFilenameWithoutExtension())->replace(['-', '_'], ' ')->title();

                return [
                    'src' => asset($relativePath),
                    'title' => $titulo,
                    'copy' => 'Imagem da galeria Paruana Comercial',
                ];
            })
            ->values();
    }
}
