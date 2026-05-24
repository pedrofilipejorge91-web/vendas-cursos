@extends('admin-layouts.apps')

@section('content')
<div class="pagetitle">
    <h1>Gestao de Cupons</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Cupons</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Novo cupom</h5>

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.cupons.store') }}" class="row g-3">
                        @csrf
                        <div class="col-12">
                            <label class="form-label">Codigo</label>
                            <input type="text" name="codigo" class="form-control text-uppercase" value="{{ old('codigo') }}" placeholder="EXALUNO10" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Descricao</label>
                            <input type="text" name="descricao" class="form-control" value="{{ old('descricao') }}" placeholder="Desconto para parceiros">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tipo</label>
                            <select name="tipo" class="form-select" required>
                                <option value="percentual" @selected(old('tipo') === 'percentual')>Percentual</option>
                                <option value="valor" @selected(old('tipo') === 'valor')>Valor fixo</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Valor</label>
                            <input type="number" name="valor" class="form-control" value="{{ old('valor') }}" min="0.01" step="0.01" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Valido ate</label>
                            <input type="date" name="valido_ate" class="form-control" value="{{ old('valido_ate') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Limite de uso</label>
                            <input type="number" name="limite_uso" class="form-control" value="{{ old('limite_uso') }}" min="1">
                        </div>
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="ativo" value="1" id="ativo-create" checked>
                                <label class="form-check-label" for="ativo-create">Activo</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <button class="btn btn-primary w-100">
                                <i class="bi bi-plus-circle me-1"></i> Cadastrar cupom
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Cupons cadastrados</h5>

                    @forelse($cupons as $cupom)
                        @php
                            $disponivel = $cupom->estaDisponivel();
                            $usoLimite = $cupom->limite_uso ? $cupom->usos . ' / ' . $cupom->limite_uso : $cupom->usos . ' uso(s)';
                        @endphp
                        <div class="border rounded p-3 mb-3">
                            <div class="d-flex justify-content-between align-items-start gap-3 mb-3">
                                <div>
                                    <h6 class="fw-bold mb-1">{{ $cupom->codigo }}</h6>
                                    <p class="text-muted small mb-0">{{ $cupom->descricao ?: 'Sem descricao' }}</p>
                                </div>
                                <span class="badge bg-{{ $disponivel ? 'success' : 'secondary' }}">
                                    {{ $disponivel ? 'Disponivel' : 'Indisponivel' }}
                                </span>
                            </div>

                            <form method="POST" action="{{ route('admin.cupons.update', $cupom) }}" class="row g-2 align-items-end">
                                @csrf
                                @method('PUT')
                                <div class="col-md-3">
                                    <label class="form-label">Codigo</label>
                                    <input type="text" name="codigo" class="form-control text-uppercase" value="{{ old('codigo', $cupom->codigo) }}" required>
                                </div>
                                <div class="col-md-5">
                                    <label class="form-label">Descricao</label>
                                    <input type="text" name="descricao" class="form-control" value="{{ old('descricao', $cupom->descricao) }}">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Tipo</label>
                                    <select name="tipo" class="form-select" required>
                                        <option value="percentual" @selected(old('tipo', $cupom->tipo) === 'percentual')>%</option>
                                        <option value="valor" @selected(old('tipo', $cupom->tipo) === 'valor')>Kz</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Valor</label>
                                    <input type="number" name="valor" class="form-control" value="{{ old('valor', $cupom->valor) }}" min="0.01" step="0.01" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Valido ate</label>
                                    <input type="date" name="valido_ate" class="form-control" value="{{ old('valido_ate', $cupom->valido_ate?->format('Y-m-d')) }}">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Limite</label>
                                    <input type="number" name="limite_uso" class="form-control" value="{{ old('limite_uso', $cupom->limite_uso) }}" min="1">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Usos</label>
                                    <input type="text" class="form-control" value="{{ $usoLimite }}" disabled>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" name="ativo" value="1" id="ativo-{{ $cupom->id }}" @checked(old('ativo', $cupom->ativo))>
                                        <label class="form-check-label" for="ativo-{{ $cupom->id }}">Activo</label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <button class="btn btn-outline-primary w-100">
                                        <i class="bi bi-check2"></i>
                                    </button>
                                </div>
                            </form>

                            <form method="POST" action="{{ route('admin.cupons.destroy', $cupom) }}" class="mt-2 text-end">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash me-1"></i> Eliminar
                                </button>
                            </form>
                        </div>
                    @empty
                        <p class="text-muted">Nenhum cupom cadastrado.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
