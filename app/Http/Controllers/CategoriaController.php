<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoriaController extends Controller
{
    public function index()
    {
        $categorias = Categoria::all();

        return view('admin.Categoria.dashboard', compact('categorias'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required',
            'descricao' => 'nullable',
            'imagem' => 'nullable|image|max:2048',
        ]);

        $data = [
            'nome' => $request->nome,
            'descricao' => $request->descricao,
        ];

        if ($request->hasFile('imagem')) {
            $path = $request->file('imagem')->store('categorias', 'public');
            $data['imagem'] = basename($path);
        }

        $categoria = Categoria::create($data);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Categoria cadastrada com sucesso!',
                'categoria' => $categoria,
            ]);
        }

        return redirect()->back()->with('success', 'Categoria cadastrada com sucesso!');
    }

    public function update(Request $request, $id)
    {
        $categoria = Categoria::findOrFail($id);
        $request->validate([
            'nome' => 'nullable',
            'descricao' => 'nullable',
            'imagem' => 'nullable|image|max:2048',
        ]);

        $data = [
            'nome' => $request->nome,
            'descricao' => $request->descricao,
        ];

        if ($request->hasFile('imagem')) {
            // delete old image
            if ($categoria->imagem) {
                Storage::disk('public')->delete('categorias/' . $categoria->imagem);
            }
            $path = $request->file('imagem')->store('categorias', 'public');
            $data['imagem'] = basename($path);
        }

        $categoria->update($data);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Categoria actualizada com sucesso!',
                'categoria' => $categoria,
            ]);
        }

        return redirect()->back()->with('success', 'Categoria actualizada com sucesso!');
    }

    public function destroy($id)
    {
        Categoria::destroy($id);

        if (request()->expectsJson()) {
            return response()->json([
                'message' => 'Categoria eliminada com sucesso!',
            ]);
        }

        return redirect()->back()->with('success', 'Categoria eliminada com sucesso!');
    }
}
