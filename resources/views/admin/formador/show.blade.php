<!-- Details Modal -->
<div class="modal fade" id="details-{{ $formador->id }}" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Detalhes do Formador</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <div class="row">

                    <!-- PERFIL -->
                    <div class="col-xl-4">

                        <div class="card">
                            <div class="card-body text-center pt-4">

                                @if($formador->foto_perfil)
                                    <img src="{{ Storage::url($formador->foto_perfil) }}"
                                         class="rounded-circle mb-3"
                                         style="width: 90px; height: 90px; object-fit: cover;">
                                @endif

                                <h5 class="mb-0">
                                    {{ $formador->pessoa->primeironome }}
                                    {{ $formador->pessoa->segundonome }}
                                </h5>

                                <small class="text-muted">
                                    {{ $formador->especialidade }}
                                </small>

                            </div>
                        </div>

                    </div>

                    <!-- DADOS -->
                    <div class="col-xl-8">

                        <div class="card">
                            <div class="card-body pt-3">

                                <ul class="nav nav-tabs nav-tabs-bordered">
                                    <li class="nav-item">
                                        <button class="nav-link active"
                                                data-bs-toggle="tab"
                                                data-bs-target="#overview">
                                            Detalhes
                                        </button>
                                    </li>

                                    <li class="nav-item">
                                        <button class="nav-link"
                                                data-bs-toggle="tab"
                                                data-bs-target="#cursos">
                                            Cursos
                                        </button>
                                    </li>
                                </ul>

                                <div class="tab-content pt-3">

                                    <!-- DETALHES -->
                                    <div class="tab-pane fade show active" id="overview">

                                        <p class="fst-italic">
                                            {{ $formador->biografia ?? 'Sem biografia' }}
                                        </p>

                                        <hr>

                                        <p><strong>Nome:</strong>
                                            {{ $formador->pessoa->primeironome }}
                                            {{ $formador->pessoa->segundonome }}
                                        </p>

                                        <p><strong>B.I:</strong> {{ $formador->pessoa->BI }}</p>
                                        <p><strong>Gênero:</strong> {{ $formador->pessoa->genero }}</p>
                                        <p><strong>Nacionalidade:</strong> {{ $formador->pessoa->nacionalidade }}</p>
                                        <p><strong>Contacto:</strong> {{ $formador->pessoa->contacto }}</p>
                                        <p><strong>Experiência:</strong> {{ $formador->anos_experiencia }} anos</p>
                                        <p><strong>Email:</strong> {{ $formador->pessoa->user->email }}</p>
                                        <p><strong>Data cadastro:</strong> {{ $formador->created_at }}</p>

                                    </div>

                                    <!-- CURSOS -->
                                    <div class="tab-pane fade" id="cursos">

                                        <h6 class="text-primary">Cursos vinculados</h6>

                                        @if($formador->cursos->count())

                                            <ul class="list-group">

                                                @foreach($formador->cursos as $curso)
                                                    <li class="list-group-item d-flex justify-content-between">

                                                        <span>
                                                            {{ $curso->titulo ?? 'Curso' }}
                                                        </span>

                                                        <span class="badge bg-success">
                                                            {{ $curso->status ?? 'ativo' }}
                                                        </span>

                                                    </li>
                                                @endforeach

                                            </ul>

                                        @else
                                            <p class="text-muted">Nenhum curso vinculado.</p>
                                        @endif

                                    </div>

                                </div>

                            </div>
                        </div>

                    </div>

                </div>

            </div>

        </div>
    </div>
</div>