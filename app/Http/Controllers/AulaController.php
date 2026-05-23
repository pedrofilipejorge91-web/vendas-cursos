<?php

namespace App\Http\Controllers;

use App\Models\Aula;
use App\Models\Curso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AulaController extends Controller
{
    public function index()
    {
        $formadorId = $this->formadorIdAutenticado();

        $aulas = Aula::with('curso')
            ->when(auth()->user()?->tipo === 'formador', function ($query) use ($formadorId) {
                $query->whereHas('curso', fn ($curso) => $curso->where('formador_id', $formadorId));
            })
            ->latest()
            ->paginate(10);

        $cursos = Curso::when(auth()->user()?->tipo === 'formador', function ($query) use ($formadorId) {
            $query->where('formador_id', $formadorId);
        })->get();

        return view('admin.aulas.index', compact('aulas', 'cursos'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'tipo' => 'required|in:video,pdf,quiz,texto',
            'numero_aula' => 'required|integer|min:0',
            'duracao_minutos' => 'required|integer|min:0',
            'curso_id' => 'required|exists:cursos,id',
            'url_conteudo' => 'nullable|url',
            'arquivo_video' => 'nullable|file|mimetypes:video/mp4,video/mpeg,video/quicktime,application/pdf|max:204800',
            'permite_download' => 'nullable|boolean',
        ]);

        $validated['permite_download'] = $request->boolean('permite_download');

        $curso = Curso::findOrFail($validated['curso_id']);
        $this->autorizarGestao($curso);

        if ($request->hasFile('arquivo_video')) {
            $path = $request->file('arquivo_video')->store($this->pastaArquivo($validated['tipo']), 'public');
            $validated['url_conteudo'] = asset('storage/' . $path);
            $validated['arquivo_video'] = $path;
        }

        Aula::create($validated);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Aula criada com sucesso!',
            ]);
        }

        return redirect()->back()->with('success', 'Aula criada com sucesso!');
    }

    public function update(Request $request, $id)
    {
        // Segurança extra: evita requests mal roteados.
        // Aceita PUT/PATCH (override do form) e também POST quando o Laravel injeta o _method.
        // Ex.: se vier como DELETE por engano, aborta.
        $method = strtoupper($request->method());
        abort_unless(in_array($method, ['PUT', 'PATCH', 'POST'], true), 405);


        $aula = Aula::with('curso')->findOrFail($id);
        $this->autorizarGestao($aula->curso);


        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'tipo' => 'required|in:video,pdf,quiz,texto',
            'numero_aula' => 'required|integer|min:0',
            'duracao_minutos' => 'required|integer|min:0',
            'url_conteudo' => 'nullable|url',
            'curso_id' => 'required|exists:cursos,id',
            'arquivo_video' => 'nullable|file|mimetypes:video/mp4,video/mpeg,video/quicktime,application/pdf|max:204800',
            'permite_download' => 'nullable|boolean',
        ]);

        $validated['permite_download'] = $request->boolean('permite_download');

        $novoCurso = Curso::findOrFail($validated['curso_id']);
        $this->autorizarGestao($novoCurso);

        if ($request->hasFile('arquivo_video')) {
            if ($aula->arquivo_video) {
                Storage::disk('public')->delete($aula->arquivo_video);
            }

            $path = $request->file('arquivo_video')->store($this->pastaArquivo($validated['tipo']), 'public');
            $validated['url_conteudo'] = asset('storage/' . $path);
            $validated['arquivo_video'] = $path;
        }

        $aula->update($validated);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Aula actualizada com sucesso!',
            ]);
        }

        return redirect()->back()->with('success', 'Aula Atualizada com sucesso!');
    }

    public function destroy($id)
    {
        $aula = Aula::with('curso')->findOrFail($id);
        $this->autorizarGestao($aula->curso);

        if ($aula->arquivo_video) {
            Storage::disk('public')->delete($aula->arquivo_video);
        }

        $aula->delete();

        if (request()->expectsJson()) {
            return response()->json([
                'message' => 'Aula eliminada com sucesso!',
            ]);
        }

        return redirect()->back()->with('success', 'Aula deletada com sucesso');
    }

    public function show(Aula $aula)
    {
        $aula->load('curso');
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

    private function pastaArquivo(string $tipo): string
    {
        return $tipo === 'pdf' ? 'pdfs/aulas' : 'videos/aulas';
    }
}
