<?php

namespace App\Http\Controllers;

use App\Models\Aula;
use App\Models\Avaliacao;
use App\Models\CertificadoSolicitacao;
use App\Models\Curso;
use App\Models\Notificacao;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class FormadorAreaController extends Controller
{
    public function pesquisar(Request $request)
    {
        $termo = trim((string) $request->get('q', $request->get('query', '')));
        $formadorId = $this->formadorId();

        $cursos = collect();
        $aulas = collect();
        $certificados = collect();

        if ($termo !== '') {
            $cursos = Curso::with('categoria')
                ->where('formador_id', $formadorId)
                ->where(function ($query) use ($termo) {
                    $query->where('titulo', 'like', "%{$termo}%")
                        ->orWhere('descricao', 'like', "%{$termo}%")
                        ->orWhere('status', 'like', "%{$termo}%");
                })
                ->latest()
                ->take(12)
                ->get();

            $aulas = Aula::with('curso')
                ->whereHas('curso', fn ($query) => $query->where('formador_id', $formadorId))
                ->where(function ($query) use ($termo) {
                    $query->where('titulo', 'like', "%{$termo}%")
                        ->orWhere('descricao', 'like', "%{$termo}%")
                        ->orWhere('tipo', 'like', "%{$termo}%");
                })
                ->latest()
                ->take(12)
                ->get();

            $certificados = CertificadoSolicitacao::with('matricula.user', 'curso')
                ->where('instrutor_id', $formadorId)
                ->where(function ($query) use ($termo) {
                    $query->where('status', 'like', "%{$termo}%")
                        ->orWhereHas('curso', fn ($curso) => $curso->where('titulo', 'like', "%{$termo}%"))
                        ->orWhereHas('matricula.user', fn ($user) => $user->where('name', 'like', "%{$termo}%"));
                })
                ->latest()
                ->take(12)
                ->get();
        }

        return view('formador.pesquisa', compact('termo', 'cursos', 'aulas', 'certificados'));
    }

    public function perfil()
    {
        $user = Auth::user()->load('pessoa.formador');

        return view('formador.perfil', [
            'user' => $user,
            'pessoa' => $user->pessoa,
            'formador' => $user->pessoa?->formador,
        ]);
    }

    public function actualizarPerfil(Request $request)
    {
        $user = Auth::user()->load('pessoa.formador');

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'primeironome' => ['required', 'string', 'max:255'],
            'segundonome' => ['required', 'string', 'max:255'],
            'BI' => ['nullable', 'string', 'max:255', Rule::unique('pessoas', 'BI')->ignore($user->pessoa?->id)],
            'genero' => ['nullable', Rule::in(['M', 'F'])],
            'nacionalidade' => ['nullable', 'string', 'max:255'],
            'data_nascimento' => ['nullable', 'date'],
            'rua' => ['nullable', 'string', 'max:255'],
            'bairro' => ['nullable', 'string', 'max:255'],
            'contacto' => ['nullable', 'string', 'max:30'],
            'especialidade' => ['nullable', 'string', 'max:255'],
            'anos_experiencia' => ['nullable', 'integer', 'min:0', 'max:80'],
            'biografia' => ['nullable', 'string', 'max:2000'],
            'foto_perfil' => ['nullable', 'image', 'max:2048'],
        ]);

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        $pessoa = $user->pessoa()->updateOrCreate([], [
            'primeironome' => $validated['primeironome'],
            'segundonome' => $validated['segundonome'],
            'BI' => $validated['BI'] ?? null,
            'genero' => $validated['genero'] ?? null,
            'nacionalidade' => $validated['nacionalidade'] ?? null,
            'data_nascimento' => $validated['data_nascimento'] ?? null,
            'rua' => $validated['rua'] ?? null,
            'bairro' => $validated['bairro'] ?? null,
            'contacto' => $validated['contacto'] ?? null,
        ]);

        $formador = $pessoa->formador()->firstOrCreate([]);

        if ($request->hasFile('foto_perfil')) {
            if ($formador->foto_perfil) {
                Storage::disk('public')->delete($formador->foto_perfil);
            }

            $formador->foto_perfil = $request->file('foto_perfil')->store('formadores', 'public');
        }

        $formador->fill([
            'especialidade' => $validated['especialidade'] ?? null,
            'anos_experiencia' => $validated['anos_experiencia'] ?? 0,
            'biografia' => $validated['biografia'] ?? null,
        ])->save();

        return back()->with('success', 'Perfil actualizado com sucesso.');
    }

    public function comentarios()
    {
        $avaliacoes = Avaliacao::with('curso', 'estudante.pessoa')
            ->whereHas('curso', fn ($query) => $query->where('formador_id', $this->formadorId()))
            ->latest()
            ->paginate(12);

        return view('formador.comentarios', compact('avaliacoes'));
    }

    public function notificacoes()
    {
        $notificacoes = Notificacao::where('user_id', Auth::id())
            ->latest()
            ->paginate(15);

        return view('formador.notificacoes', compact('notificacoes'));
    }

    public function marcarNotificacoesLidas()
    {
        Notificacao::where('user_id', Auth::id())
            ->whereNull('lida_em')
            ->update(['lida_em' => now()]);

        return back()->with('success', 'Notificações marcadas como lidas.');
    }

    public function suporte()
    {
        return view('formador.suporte');
    }

    public function enviarSuporte(Request $request)
    {
        $validated = $request->validate([
            'assunto' => ['required', 'string', 'max:160'],
            'mensagem' => ['required', 'string', 'max:2000'],
        ]);

        $formador = Auth::user();
        $admins = User::where('tipo', 'admin')->get();

        foreach ($admins as $admin) {
            Notificacao::create([
                'user_id' => $admin->id,
                'canal' => 'sistema',
                'titulo' => 'Pedido de suporte do formador',
                'mensagem' => $formador->name.' enviou: '.$validated['assunto'].' - '.$validated['mensagem'],
            ]);
        }

        return back()->with('success', 'Pedido enviado para a administração.');
    }

    private function formadorId(): ?int
    {
        return Auth::user()?->pessoa?->formador?->id;
    }
}
