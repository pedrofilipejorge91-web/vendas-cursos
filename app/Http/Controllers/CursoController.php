<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Models\Categoria;
use App\Models\Formador;
use App\Models\Curso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Services\NotificacaoService;

class CursoController extends Controller
{
    public function index()
    {
        $cursos = Curso::when(auth()->user()?->tipo === 'formador', function ($query) {
            $query->where('formador_id', $this->formadorIdAutenticado());
        })->get();
        $categorias = Categoria::all();
        $formadores = Formador::with('pessoa')->get();

        return view('admin.cursos.index', compact('cursos', 'categorias', 'formadores'));
    }


    public function store(Request $request)
    {
       $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'required|string',
            'preco' => 'required|numeric|min:0',
            'duracao_horas' => 'required|integer|min:1',
            'idioma' => 'required|string|max:10',
            'categoria_id' => 'required|exists:categorias,id',
            'formador_id' => auth()->user()?->tipo === 'formador' ? 'nullable' : 'required|exists:formadors,id',
            'status' => 'nullable|in:rascunho,publicado,inativo',
            'foto' => 'nullable|image|max:2048',
       ]);

       $curso = $request->all();
       if (auth()->user()?->tipo === 'formador') {
        $curso['formador_id'] = $this->formadorIdAutenticado();
        $curso['status'] = 'rascunho';
       }
       if($request->foto){
        $curso['foto']= $request->foto->store('cursos','public');
       }
        $curso = Curso::create($curso);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Curso criado com sucesso!',
                'curso' => $curso,
            ]);
        }

        return redirect()->back()->with('success', 'Curso criado com sucesso!');
    }

    public function show(Curso $curso)
    {
        // Carrega aulas e avaliações junto com o curso
        $curso->load(['aulas', 'avaliacoes', 'formador']);
    }

    public function edit($id)
    {
        $curso = Curso::findOrFail($id);
        $this->autorizarGestao($curso);

        $categorias = Categoria::all();
        $formadores = Formador::with('pessoa')->get();

        return view('formador.cursos.edit', compact('curso', 'categorias', 'formadores'));
    }
      public function update(Request $request, $id)
    {
        $curso = Curso::findOrFail($id);
        $this->autorizarGestao($curso);

        $dados = $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'required|string',
            'preco' => 'required|numeric|min:0',
            'duracao_horas' => 'required|integer|min:1',
            'idioma' => 'required|string|max:10',
            'categoria_id' => 'required|exists:categorias,id',
            'formador_id' => auth()->user()?->tipo === 'formador' ? 'nullable' : 'required|exists:formadors,id',
            'status' => 'nullable|in:rascunho,publicado,inativo',
            'foto' => 'nullable|image|max:2048',
        ]);

        if (auth()->user()?->tipo === 'formador') {
            $dados['formador_id'] = $this->formadorIdAutenticado();
            $dados['status'] = 'rascunho';
        }

        if ($request->hasFile('foto')) {
            if ($curso->foto) {
                Storage::disk('public')->delete($curso->foto);
            }
            $dados['foto'] = $request->file('foto')->store('cursos', 'public');
        }

        $curso->update($dados);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Curso actualizado com sucesso!',
                'curso' => $curso,
            ]);
        }

        return redirect()->back()->with('success', 'Curso Actualizado com sucesso!');
    }


public function destroy($id)
    {
        $curso = Curso::findOrfail($id);
        $this->autorizarGestao($curso);
        if ($curso->foto){
            Storage::disk('public')->delete($curso->foto);


        }
        $curso->delete();

        if (request()->expectsJson()) {
            return response()->json([
                'message' => 'Curso eliminado com sucesso!',
            ]);
        }

        return redirect()->back()->with('success', 'Curso excluído com sucesso!');
    }

    public function publicar($id)
    {
        $curso = Curso::findOrFail($id);
        $curso->update(['status' => 'publicado']);

        $notificador = app(NotificacaoService::class);
        User::with('pessoa')->where('tipo', 'estudante')->chunk(100, function ($usuarios) use ($notificador, $curso) {
            foreach ($usuarios as $usuario) {
                $notificador->enviar(
                    $usuario,
                    'Novo curso publicado',
                    'O curso '.$curso->titulo.' ja esta disponivel no catalogo. Veja o programa, valor e detalhes para decidir se este e o seu proximo passo.',
                    ['email', 'whatsapp'],
                    [
                        'linhas' => [
                            'Curso' => $curso->titulo,
                            'Estado' => 'Publicado',
                        ],
                        'acao_url' => route('home.detalhe', $curso->id),
                        'acao_texto' => 'Ver curso',
                        'preheader' => 'Novo curso disponivel no catalogo.',
                    ]
                );
            }
        });

        return redirect()->back()->with('success', 'Curso aprovado e publicado com sucesso!');
    }

    public function rejeitar($id)
    {
        Curso::findOrFail($id)->update(['status' => 'rascunho']);

        return redirect()->back()->with('success', 'Curso rejeitado e devolvido para rascunho.');
    }

    private function autorizarGestao(Curso $curso): void
    {
        if (auth()->user()?->tipo === 'formador') {
            abort_unless($curso->formador_id === $this->formadorIdAutenticado(), 403);
        }
    }

    private function formadorIdAutenticado(): ?int
    {
        return auth()->user()?->pessoa?->formador?->id;
    }



public function inscrever($id)
{
    $curso = Curso::findOrFail($id);

    $user = Auth::user();

    // VERIFICA SE É ESTUDANTE
    if ($user->tipo != 'estudante') {

        return back()->with('error',
            'Apenas estudantes podem inscrever-se.');
    }

    $estudante = $user->pessoa->estudante;

    // EVITAR DUPLICAÇÃO
    if ($estudante->cursos()
        ->where('curso_id', $curso->id)
        ->exists()) {

        return back()->with('error',
            'Já está inscrito neste curso.');
    }

    // INSCRIÇÃO
    $estudante->cursos()->attach($curso->id, [

        'status' => 'activo',
        'data_inscricao' => now(),

    ]);

    return back()->with('success',
        'Inscrição realizada com sucesso!');
}


}
