<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\Matricula;
use App\Models\Pedido;
use Illuminate\Http\Request;

class CarrinhoController extends Controller
{
    /**
     * Mostrar carrinho.
     */
    public function index()
    {
        $carrinho = session()->get('carrinho', []);
        $carrinho = $this->removerCursosJaComprados($carrinho);
        $total = $this->calcularTotal($carrinho);

        return view('checkout.carrinho', compact('carrinho', 'total'));
    }

    /**
     * Adicionar curso ao carrinho.
     */
    public function add(Request $request)
    {
        $request->validate([
            'curso_id' => 'required|exists:cursos,id',
        ]);

        $curso = Curso::findOrFail($request->curso_id);

        if ($this->cursoJaCompradoOuPendente($request->user()->id, $curso->id)) {
            return redirect()
                ->route('home.carrinho')
                ->withErrors(['curso' => 'Este curso ja foi comprado ou ja tem um pedido pendente.']);
        }

        $adicionado = $this->adicionarCurso($curso);

        return redirect()
            ->route('home.carrinho')
            ->with('success', $adicionado ? 'Curso adicionado ao carrinho!' : 'Este curso ja esta no carrinho.');
    }

    /**
     * Adicionar ao carrinho e seguir direto para pagamento.
     */
    public function buyNow(Request $request)
    {
        $request->validate([
            'curso_id' => 'required|exists:cursos,id',
        ]);

        $curso = Curso::findOrFail($request->curso_id);

        if ($this->cursoJaCompradoOuPendente($request->user()->id, $curso->id)) {
            return back()->withErrors(['curso' => 'Este curso ja foi comprado ou ja tem um pedido pendente.']);
        }

        $this->adicionarCurso($curso);

        return redirect()->route('pagamento');
    }

    /**
     * Remover item do carrinho.
     */
    public function remove(Request $request)
    {
        $request->validate([
            'curso_id' => 'required|exists:cursos,id',
        ]);

        $carrinho = session()->get('carrinho', []);

        if (isset($carrinho[$request->curso_id])) {
            unset($carrinho[$request->curso_id]);
            session()->put('carrinho', $carrinho);
        }

        return redirect()
            ->route('home.carrinho')
            ->with('success', 'Curso removido do carrinho!');
    }

    /**
     * Limpar carrinho inteiro.
     */
    public function clear()
    {
        session()->forget('carrinho');

        return redirect()
            ->route('home.carrinho')
            ->with('success', 'Carrinho limpo!');
    }

    private function adicionarCurso(Curso $curso): bool
    {
        $carrinho = session()->get('carrinho', []);

        if (isset($carrinho[$curso->id])) {
            return false;
        }

        $carrinho[$curso->id] = [
            'titulo' => $curso->titulo,
            'preco' => (float) $curso->preco,
            'foto' => $curso->foto,
            'duracao_horas' => $curso->duracao_horas,
            'categoria' => $curso->categoria->nome ?? 'Curso',
            'quantidade' => 1,
        ];

        session()->put('carrinho', $carrinho);

        return true;
    }

    private function removerCursosJaComprados(array $carrinho): array
    {
        $userId = auth()->id();

        if (! $userId || empty($carrinho)) {
            return $carrinho;
        }

        foreach (array_keys($carrinho) as $cursoId) {
            if ($this->cursoJaCompradoOuPendente($userId, (int) $cursoId)) {
                unset($carrinho[$cursoId]);
            }
        }

        session()->put('carrinho', $carrinho);

        return $carrinho;
    }

    private function cursoJaCompradoOuPendente(int $userId, int $cursoId): bool
    {
        if (Matricula::where('user_id', $userId)->where('curso_id', $cursoId)->exists()) {
            return true;
        }

        return Pedido::where('user_id', $userId)
            ->whereIn('status', ['pendente', 'pago'])
            ->whereHas('itens', function ($query) use ($cursoId) {
                $query->where('curso_id', $cursoId);
            })
            ->exists();
    }

    private function calcularTotal(array $carrinho): float
    {
        return collect($carrinho)->sum(function ($item) {
            return ((float) $item['preco']) * ((int) ($item['quantidade'] ?? 1));
        });
    }
}
