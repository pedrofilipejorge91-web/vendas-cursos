<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use Illuminate\Http\Request;

class CarrinhoController extends Controller
{
    /**
     * Mostrar carrinho.
     */
    public function index()
    {
        $carrinho = session()->get('carrinho', []);
        $total = $this->calcularTotal($carrinho);

        return view('home.carrinho', compact('carrinho', 'total'));
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

    private function calcularTotal(array $carrinho): float
    {
        return collect($carrinho)->sum(function ($item) {
            return ((float) $item['preco']) * ((int) ($item['quantidade'] ?? 1));
        });
    }
}
