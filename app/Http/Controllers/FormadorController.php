<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Pessoa;
use App\Models\Formador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class FormadorController extends Controller
{
    // LISTAR
    public function index()
    {
        $formadors = Formador::with('pessoa')->get();

        return view('admin.formador.dashboard', compact('formadors'));
    }

    // FORM CREATE
    public function create()
    {
        return view('admin.formador.create');
    }

    // STORE (CADASTRO ÚNICO)
    public function store(Request $request)
    {
        $request->validate([
            // USER
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',

            // PESSOA
            'primeironome' => 'required',
            'segundonome' => 'required',
            'BI' => 'required',
            'genero' => 'required',
            'nacionalidade' => 'required',
            'data_nascimento' => 'required',
            'rua' => 'required',
            'bairro' => 'required',
            'contacto' => 'required',

            // FORMADOR
            'especialidade' => 'required',
            'foto_perfil' => 'nullable|image|max:2048',
            'biografia' => 'nullable',
            'anos_experiencia' => 'required',
        ]);

        DB::beginTransaction();

        try {

            // 1. USER
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'tipo' => 'formador',
            ]);

            // 2. PESSOA
            $pessoa = Pessoa::create([
                'user_id' => $user->id,
                'primeironome' => $request->primeironome,
                'segundonome' => $request->segundonome,
                'BI' => $request->BI,
                'genero' => $request->genero,
                'nacionalidade' => $request->nacionalidade,
                'data_nascimento' => $request->data_nascimento,
                'rua' => $request->rua,
                'bairro' => $request->bairro,
                'contacto' => $request->contacto,
            ]);

            // FOTO
            $fotoPath = null;

            if ($request->hasFile('foto_perfil')) {
                $fotoPath = $request->file('foto_perfil')->store('formadores', 'public');
            }

            // 3. FORMADOR
            Formador::create([
                'pessoa_id' => $pessoa->id,
                'especialidade' => $request->especialidade,
                'foto_perfil' => $fotoPath,
                'biografia' => $request->biografia,
                'anos_experiencia' => $request->anos_experiencia,
            ]);

            DB::commit();

                  return redirect()->back()->with('success', 'Formador cadastrado com sucesso!');


        } catch (\Exception $e) {

            DB::rollback();

            return back()->with('error', 'Erro: ' . $e->getMessage());
        }
    }

    // SHOW
    public function show(Formador $formador)
    {
        $formador->load('pessoa');

        return view('admin.formador.show', compact('formador'));
    }

    // EDIT
    public function edit(Formador $formador)
    {
        $formador->load('pessoa');

        return view('admin.formador.update', compact('formador'));
    }

    // UPDATE
 public function update(Request $request, $id)
{
    $formador = Formador::findOrFail($id);
    $request->validate([
            'primeironome' => 'required',
            'segundonome' => 'required',
            'BI' => 'required',
            'genero' => 'required',
            'nacionalidade' => 'required',
            'data_nascimento' => 'required',
            'rua' => 'required',
            'bairro' => 'required',
            'contacto' => 'required',
            'especialidade' => 'required',
            'foto_perfil' => 'nullable|image|max:2048',
            'biografia' => 'nullable',
            'anos_experiencia' => 'required',
        ]);

        // PESSOA
        $formador->pessoa->update([
            'primeironome' => $request->primeironome,
            'segundonome' => $request->segundonome,
            'BI' => $request->BI,
            'genero' => $request->genero,
            'nacionalidade' => $request->nacionalidade,
            'data_nascimento' => $request->data_nascimento,
            'rua' => $request->rua,
            'bairro' => $request->bairro,
            'contacto' => $request->contacto,
        ]);

        // FOTO
        if ($request->hasFile('foto_perfil')) {

            if ($formador->foto_perfil) {
                Storage::disk('public')->delete($formador->foto_perfil);
            }

            $formador->foto_perfil = $request->file('foto_perfil')->store('formadores', 'public');
        }

        // FORMADOR
        $formador->update([
            'especialidade' => $request->especialidade,
            'biografia' => $request->biografia,
            'anos_experiencia' => $request->anos_experiencia,
        ]);

               return redirect()->back()->with('success', 'Formador Actualizado com sucesso!');

    }

    // DELETE
public function destroy($id)
{
    $formador = Formador::findOrFail($id);

    // apagar foto
    if ($formador->foto_perfil) {
        Storage::disk('public')->delete($formador->foto_perfil);
    }

    // apagar user (cascade apaga pessoa e formador)
    if ($formador->pessoa && $formador->pessoa->user) {
        $formador->pessoa->user->delete();
    }

    return redirect()->back()
        ->with('success', 'Formador eliminado com sucesso!');
}
}