<!-- Details Modal -->
<div class="modal fade" id="details-{{ $estudante->id }}" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Detalhes do Estudante</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <div class="row">

                    <!-- PERFIL -->
                    <div class="col-xl-4">

                        <div class="card">
                            <div class="card-body text-center pt-4">

                                <div class="avatar mb-3">
                                    <img src="{{ asset('assets/img/logo.png') }}"
                                         class="rounded-circle"
                                         style="width: 90px; height: 90px; object-fit: cover;">
                                </div>

                                <h5 class="mb-0">
                                    {{ $estudante->pessoa->primeironome }}
                                    {{ $estudante->pessoa->segundonome }}
                                </h5>

                                <small class="text-muted">
                                    Estudante - {{ $estudante->status }}
                                </small>

                            </div>
                        </div>

                    </div>

                    <!-- CONTEÚDO -->
                    <div class="col-xl-8">

                        <div class="card">
                            <div class="card-body pt-3">

                                <!-- TABS -->
                                <ul class="nav nav-tabs nav-tabs-bordered">

                                    <li class="nav-item">
                                        <button class="nav-link active"
                                                data-bs-toggle="tab"
                                                data-bs-target="#est-overview">
                                            Detalhes
                                        </button>
                                    </li>

                                    <li class="nav-item">
                                        <button class="nav-link"
                                                data-bs-toggle="tab"
                                                data-bs-target="#est-cursos">
                                            Cursos
                                        </button>
                                    </li>

                                </ul>

                                <div class="tab-content pt-3">

                                    <!-- DADOS -->
                                    <div class="tab-pane fade show active" id="est-overview">

                                        <h6 class="text-primary">Dados Pessoais</h6>

                                        <p><strong>Nome:</strong>
                                            {{ $estudante->pessoa->primeironome }}
                                            {{ $estudante->pessoa->segundonome }}
                                        </p>

                                        <p><strong>B.I:</strong> {{ $estudante->pessoa->BI }}</p>
                                        <p><strong>Gênero:</strong> {{ $estudante->pessoa->genero }}</p>
                                        <p><strong>Contacto:</strong> {{ $estudante->pessoa->contacto }}</p>
                                        <p><strong>Endereço:</strong>
                                            {{ $estudante->pessoa->rua }},
                                            {{ $estudante->pessoa->bairro }}
                                        </p>

                                        <hr>

                                        <h6 class="text-primary">Dados Académicos</h6>

                                        <p><strong>Escola actual:</strong>
                                            {{ $estudante->escola_actual ?? 'Não informado' }}
                                        </p>

                                        <p><strong>Status:</strong>
                                            <span class="badge bg-success">
                                                {{ $estudante->status }}
                                            </span>
                                        </p>

                                        <p><strong>Data inscrição:</strong>
                                            {{ $estudante->data_inscricao ?? $estudante->created_at }}
                                        </p>

                                        <p><strong>Email:</strong>
                                            {{ $estudante->pessoa->user->email }}
                                        </p>

                                    </div>

                                    <!-- CURSOS -->
                                    <div class="tab-pane fade" id="est-cursos">

                                        <h6 class="text-primary">Cursos inscritos</h6>
<hr>



@if($estudante->cursos->count())

<table class="table table-bordered">

    <thead>
        <tr>
            <th>Curso</th>
            <th>Status</th>
            <th>Data</th>
        </tr>
    </thead>

    <tbody>

        @foreach($estudante->cursos as $curso)

        <tr>

            <td>
                {{ $curso->titulo }}
            </td>

            <td>
                <span class="badge bg-success">
                    {{ $curso->pivot->status }}
                </span>
            </td>

            <td>
                {{ $curso->pivot->data_inscricao }}
            </td>

        </tr>

        @endforeach

    </tbody>

</table>

@else

<div class="alert alert-info">
    Nenhum curso inscrito.
</div>

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