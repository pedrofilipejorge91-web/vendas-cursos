@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="mb-4">Editar Curso</h3>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('formador.cursos.update', $curso->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="titulo" class="form-label">Título *</label>
            <input type="text" name="titulo" id="titulo" class="form-control @error('titulo') is-invalid @enderror" value="{{ old('titulo', $curso->titulo) }}" required>
            @error('titulo')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="duracao_horas" class="form-label">Duração (horas) *</label>
                <input type="number" name="duracao_horas" id="duracao_horas" class="form-control @error('duracao_horas') is-invalid @enderror" value="{{ old('duracao_horas', $curso->duracao_horas) }}" min="1" required>
                @error('duracao_horas')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-6 mb-3">
                <label for="idioma" class="form-label">Idioma *</label>
                <select name="idioma" id="idioma" class="form-select">
                    <option value="pt-AO" {{ old('idioma', $curso->idioma) === 'pt-AO' ? 'selected' : '' }}>Português (Angola)</option>
                    <option value="pt-PT" {{ old('idioma', $curso->idioma) === 'pt-PT' ? 'selected' : '' }}>Português</option>
                    <option value="en" {{ old('idioma', $curso->idioma) === 'en' ? 'selected' : '' }}>Inglês</option>
                    <option value="fr" {{ old('idioma', $curso->idioma) === 'fr' ? 'selected' : '' }}>Francês</option>
                </select>
            </div>
        </div>

        <div class="mb-3">
            <label for="descricao" class="form-label">Descrição *</label>
            <textarea name="descricao" id="descricao" class="form-control @error('descricao') is-invalid @enderror" rows="6" required>{{ old('descricao', $curso->descricao) }}</textarea>
            @error('descricao')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="row">
            <div class="col-md-4 mb-3">
                <label for="preco" class="form-label">Preço (Kz) *</label>
                <input type="number" name="preco" id="preco" class="form-control @error('preco') is-invalid @enderror" value="{{ old('preco', $curso->preco) }}" step="0.01" min="0" required>
                @error('preco')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-4 mb-3">
                <label for="status" class="form-label">Status *</label>
                <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                    <option value="rascunho" {{ old('status', $curso->status) == 'rascunho' ? 'selected' : '' }}>Rascunho</option>
                    <option value="publicado" {{ old('status', $curso->status) == 'publicado' ? 'selected' : '' }}>Publicado</option>
                    <option value="inativo" {{ old('status', $curso->status) == 'inativo' ? 'selected' : '' }}>Inativo</option>
                </select>
            </div>

            <div class="col-md-4 mb-3">
                <label for="categoria_id" class="form-label">Categoria *</label>
                <select name="categoria_id" id="categoria_id" class="form-select" required>
                    <option value="" disabled>Selecione...</option>
                    @foreach($categorias as $categoria)
                        <option value="{{ $categoria->id }}" {{ old('categoria_id', $curso->categoria_id) == $categoria->id ? 'selected' : '' }}>{{ $categoria->nome }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="mb-3">
            <label for="foto" class="form-label">Capa do Curso (Opcional)</label>
            <input type="file" name="foto" id="foto" class="form-control @error('foto') is-invalid @enderror" accept="image/*">
            @error('foto')<div class="invalid-feedback">{{ $message }}</div>@enderror
            @if($curso->foto)
                <div class="mt-2">
                    <img src="{{ asset('storage/' . $curso->foto) }}" alt="Capa" style="max-width:200px;" />
                </div>
            @endif
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">Salvar</button>
            <a href="{{ route('formador.cursos') }}" class="btn btn-secondary">Cancelar</a>
        </div>

    </form>
</div>
@endsection
