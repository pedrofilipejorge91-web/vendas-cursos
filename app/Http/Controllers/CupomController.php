<?php

namespace App\Http\Controllers;

use App\Models\Cupom;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CupomController extends Controller
{
    public function index()
    {
        $cupons = Cupom::latest()->get();

        return view('admin.cupons.index', compact('cupons'));
    }

    public function store(Request $request)
    {
        $validated = $this->validarCupom($request);
        $validated['codigo'] = $this->normalizarCodigo($validated['codigo']);
        $validated['ativo'] = $request->boolean('ativo', true);

        Cupom::create($validated);

        return back()->with('success', 'Cupom cadastrado com sucesso.');
    }

    public function update(Request $request, Cupom $cupom)
    {
        $validated = $this->validarCupom($request, $cupom);
        $validated['codigo'] = $this->normalizarCodigo($validated['codigo']);
        $validated['ativo'] = $request->boolean('ativo');

        $cupom->update($validated);

        return back()->with('success', 'Cupom actualizado com sucesso.');
    }

    public function destroy(Cupom $cupom)
    {
        $cupom->delete();

        return back()->with('success', 'Cupom eliminado com sucesso.');
    }

    private function validarCupom(Request $request, ?Cupom $cupom = null): array
    {
        return $request->validate([
            'codigo' => [
                'required',
                'string',
                'max:50',
                Rule::unique('cupons', 'codigo')->ignore($cupom?->id),
            ],
            'descricao' => 'nullable|string|max:255',
            'tipo' => 'required|in:percentual,valor',
            'valor' => 'required|numeric|min:0.01',
            'valido_ate' => 'nullable|date',
            'limite_uso' => 'nullable|integer|min:1',
        ]);
    }

    private function normalizarCodigo(string $codigo): string
    {
        return strtoupper(trim($codigo));
    }
}
