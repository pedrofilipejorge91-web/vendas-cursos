<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminUserController extends Controller
{
    public function index()
    {
        $administradores = User::where('tipo', 'admin')->latest()->get();

        return view('admin.administradores.index', compact('administradores'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'tipo' => 'admin',
        ]);

        return back()->with('success', 'Administrador cadastrado com sucesso.');
    }

    public function update(Request $request, User $administrador)
    {
        abort_unless($administrador->tipo === 'admin', 404);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($administrador->id)],
            'password' => 'nullable|string|min:6',
        ]);

        $dados = [
            'name' => $validated['name'],
            'email' => $validated['email'],
        ];

        if (! empty($validated['password'])) {
            $dados['password'] = Hash::make($validated['password']);
        }

        $administrador->update($dados);

        return back()->with('success', 'Administrador actualizado com sucesso.');
    }

    public function destroy(User $administrador)
    {
        abort_unless($administrador->tipo === 'admin', 404);
        abort_if($administrador->id === auth()->id(), 403, 'Nao pode eliminar a sua propria conta.');

        $administrador->delete();

        return back()->with('success', 'Administrador eliminado com sucesso.');
    }
}
