@extends('formador-layouts.apps')

@section('content')
<div class="formador-edit-page">
    <div class="edit-page-header">
        <div>
            <span class="eyebrow">Conta</span>
            <h1>Meu perfil</h1>
            <p>Mantenha os seus dados actualizados para cursos, certificados, comunicações e suporte aos alunos.</p>
        </div>

        <a href="{{ route('formador.dashboard') }}" class="btn btn-outline-primary">
            <i class="bi bi-arrow-left me-2"></i>Voltar ao painel
        </a>
    </div>

    <form action="{{ route('formador.perfil.update') }}" method="POST" enctype="multipart/form-data" class="edit-course-layout" id="definicoes">
        @csrf
        @method('PUT')

        <section class="panel-card edit-course-form">
            <div class="panel-heading">
                <div>
                    <span class="eyebrow">Identificação</span>
                    <h2>Dados pessoais</h2>
                </div>
            </div>

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label" for="name">Nome de utilizador *</label>
                    <input class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="email">Email *</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="primeironome">Primeiro nome *</label>
                    <input class="form-control @error('primeironome') is-invalid @enderror" id="primeironome" name="primeironome" value="{{ old('primeironome', $pessoa?->primeironome) }}" required>
                    @error('primeironome')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="segundonome">Segundo nome *</label>
                    <input class="form-control @error('segundonome') is-invalid @enderror" id="segundonome" name="segundonome" value="{{ old('segundonome', $pessoa?->segundonome) }}" required>
                    @error('segundonome')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="BI">BI</label>
                    <input class="form-control @error('BI') is-invalid @enderror" id="BI" name="BI" value="{{ old('BI', $pessoa?->BI) }}">
                    @error('BI')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="genero">Género</label>
                    <select class="form-select @error('genero') is-invalid @enderror" id="genero" name="genero">
                        <option value="">Selecionar</option>
                        <option value="M" {{ old('genero', $pessoa?->genero) === 'M' ? 'selected' : '' }}>Masculino</option>
                        <option value="F" {{ old('genero', $pessoa?->genero) === 'F' ? 'selected' : '' }}>Feminino</option>
                    </select>
                    @error('genero')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="data_nascimento">Data de nascimento</label>
                    <input type="date" class="form-control @error('data_nascimento') is-invalid @enderror" id="data_nascimento" name="data_nascimento" value="{{ old('data_nascimento', $pessoa?->data_nascimento ? \Illuminate\Support\Carbon::parse($pessoa->data_nascimento)->format('Y-m-d') : '') }}">
                    @error('data_nascimento')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="nacionalidade">Nacionalidade</label>
                    <input class="form-control @error('nacionalidade') is-invalid @enderror" id="nacionalidade" name="nacionalidade" value="{{ old('nacionalidade', $pessoa?->nacionalidade) }}">
                    @error('nacionalidade')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="contacto">Contacto</label>
                    <input class="form-control @error('contacto') is-invalid @enderror" id="contacto" name="contacto" value="{{ old('contacto', $pessoa?->contacto) }}">
                    @error('contacto')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="bairro">Bairro</label>
                    <input class="form-control @error('bairro') is-invalid @enderror" id="bairro" name="bairro" value="{{ old('bairro', $pessoa?->bairro) }}">
                    @error('bairro')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-12">
                    <label class="form-label" for="rua">Rua / Morada</label>
                    <input class="form-control @error('rua') is-invalid @enderror" id="rua" name="rua" value="{{ old('rua', $pessoa?->rua) }}">
                    @error('rua')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
        </section>

        <aside class="edit-course-sidebar">
            <section class="panel-card">
                <div class="panel-heading">
                    <div>
                        <span class="eyebrow">Formador</span>
                        <h2>Dados profissionais</h2>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label" for="especialidade">Especialidade</label>
                        <input class="form-control @error('especialidade') is-invalid @enderror" id="especialidade" name="especialidade" value="{{ old('especialidade', $formador?->especialidade) }}">
                        @error('especialidade')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-12">
                        <label class="form-label" for="anos_experiencia">Anos de experiência</label>
                        <input type="number" min="0" class="form-control @error('anos_experiencia') is-invalid @enderror" id="anos_experiencia" name="anos_experiencia" value="{{ old('anos_experiencia', $formador?->anos_experiencia ?? 0) }}">
                        @error('anos_experiencia')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-12">
                        <label class="form-label" for="biografia">Biografia</label>
                        <textarea class="form-control @error('biografia') is-invalid @enderror" id="biografia" name="biografia" rows="5">{{ old('biografia', $formador?->biografia) }}</textarea>
                        @error('biografia')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-12">
                        <label class="form-label" for="foto_perfil">Foto de perfil</label>
                        <input type="file" class="form-control @error('foto_perfil') is-invalid @enderror" id="foto_perfil" name="foto_perfil" accept="image/*">
                        @error('foto_perfil')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </section>

            <div class="edit-actions">
                <button class="btn btn-primary btn-lg" type="submit">
                    <i class="bi bi-check2-circle me-2"></i>Guardar perfil
                </button>
            </div>
        </aside>
    </form>
</div>
@endsection
