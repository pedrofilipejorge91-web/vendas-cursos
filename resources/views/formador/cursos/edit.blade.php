@extends('formador-layouts.apps')

@section('content')

<div class="formador-edit-page">
    <div class="edit-page-header">
        <div>
            <span class="eyebrow">Meus Cursos</span>
            <h1>Editar curso</h1>
            <p>Atualize as informações principais do curso. Depois de salvar, ele volta como rascunho para revisão.</p>
        </div>

        <a href="{{ route('formador.dashboard') }}" class="btn btn-outline-primary">
            <i class="bi bi-arrow-left me-2"></i>Voltar ao painel
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger border-0 shadow-sm">
            <div class="d-flex gap-2">
                <i class="bi bi-exclamation-octagon-fill"></i>
                <div>
                    <strong>Corrija os campos abaixo.</strong>
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <form action="{{ route('formador.cursos.update', $curso->id) }}" method="POST" enctype="multipart/form-data" class="edit-course-layout">
        @csrf
        @method('PUT')

        <section class="panel-card edit-course-form">
            <div class="panel-heading">
                <div>
                    <span class="eyebrow">Conteúdo</span>
                    <h2>Informações do curso</h2>
                </div>
            </div>

            <div class="row g-3">
                <div class="col-12">
                    <label for="titulo" class="form-label">Título *</label>
                    <input type="text" name="titulo" id="titulo" class="form-control @error('titulo') is-invalid @enderror" value="{{ old('titulo', $curso->titulo) }}" required>
                    @error('titulo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6">
                    <label for="categoria_id" class="form-label">Categoria *</label>
                    <select name="categoria_id" id="categoria_id" class="form-select @error('categoria_id') is-invalid @enderror" required>
                        <option value="" disabled>Selecione...</option>
                        @foreach($categorias as $categoria)
                            <option value="{{ $categoria->id }}" {{ old('categoria_id', $curso->categoria_id) == $categoria->id ? 'selected' : '' }}>{{ $categoria->nome }}</option>
                        @endforeach
                    </select>
                    @error('categoria_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6">
                    <label for="idioma" class="form-label">Idioma *</label>
                    <select name="idioma" id="idioma" class="form-select @error('idioma') is-invalid @enderror" required>
                        <option value="pt-AO" {{ old('idioma', $curso->idioma) === 'pt-AO' ? 'selected' : '' }}>Português (Angola)</option>
                        <option value="pt-PT" {{ old('idioma', $curso->idioma) === 'pt-PT' ? 'selected' : '' }}>Português (Portugal)</option>
                        <option value="en" {{ old('idioma', $curso->idioma) === 'en' ? 'selected' : '' }}>Inglês</option>
                        <option value="fr" {{ old('idioma', $curso->idioma) === 'fr' ? 'selected' : '' }}>Francês</option>
                    </select>
                    @error('idioma')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-12">
                    <label for="descricao" class="form-label">Descrição *</label>
                    <textarea name="descricao" id="descricao" class="form-control @error('descricao') is-invalid @enderror" rows="7" required>{{ old('descricao', $curso->descricao) }}</textarea>
                    @error('descricao')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
        </section>

        <aside class="edit-course-sidebar">
            <section class="panel-card">
                <div class="panel-heading">
                    <div>
                        <span class="eyebrow">Publicação</span>
                        <h2>Configurações</h2>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-12">
                        <label for="preco" class="form-label">Preço (Kz) *</label>
                        <div class="input-group">
                            <span class="input-group-text">Kz</span>
                            <input type="number" name="preco" id="preco" class="form-control @error('preco') is-invalid @enderror" value="{{ old('preco', $curso->preco) }}" step="0.01" min="0" required>
                            @error('preco')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="col-12">
                        <label for="duracao_horas" class="form-label">Duração (horas) *</label>
                        <div class="input-group">
                            <input type="number" name="duracao_horas" id="duracao_horas" class="form-control @error('duracao_horas') is-invalid @enderror" value="{{ old('duracao_horas', $curso->duracao_horas) }}" min="1" required>
                            <span class="input-group-text">h</span>
                            @error('duracao_horas')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Status atual</label>
                        <div class="status-readonly">
                            <i class="bi bi-pencil-square"></i>
                            <div>
                                <strong>{{ ucfirst($curso->status) }}</strong>
                                <span>Ao salvar, o curso ficará como rascunho.</span>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="panel-card">
                <div class="panel-heading">
                    <div>
                        <span class="eyebrow">Imagem</span>
                        <h2>Capa do curso</h2>
                    </div>
                </div>

                <div class="course-cover-preview">
                    <img src="{{ $curso->foto ? asset('storage/' . $curso->foto) : asset('assets/img/paruana/formando-instrutor.jpg') }}" alt="Capa atual do curso">
                </div>

                <label for="foto" class="form-label mt-3">Alterar capa</label>
                <input type="file" name="foto" id="foto" class="form-control @error('foto') is-invalid @enderror" accept="image/*">
                @error('foto')<div class="invalid-feedback">{{ $message }}</div>@enderror
                <div class="form-text">Use JPG ou PNG, preferencialmente em formato horizontal.</div>
            </section>

            <div class="edit-actions">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="bi bi-check2-circle me-2"></i>Salvar alterações
                </button>
                <a href="{{ route('formador.dashboard') }}" class="btn btn-light btn-lg">Cancelar</a>
            </div>
        </aside>
    </form>
</div>

@endsection
