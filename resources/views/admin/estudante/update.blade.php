<!-- Edit Modal -->
<div class="modal fade" id="edit-{{ $estudante->id }}" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Actualizar Estudante</h5>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close">
                </button>
            </div>

            <div class="modal-body">

                <form method="POST"
                      action="{{ route('estudante.update', $estudante->id) }}">

                    @csrf
                    @method('PUT')

                    <!-- PESSOAL -->
                    <h6 class="text-primary mb-3">Dados Pessoais</h6>

                    <div class="row g-3">

                        <div class="col-md-6">
                            <input type="text"
                                   class="form-control"
                                   name="primeironome"
                                   value="{{ $estudante->pessoa->primeironome }}"
                                   required>
                        </div>

                        <div class="col-md-6">
                            <input type="text"
                                   class="form-control"
                                   name="segundonome"
                                   value="{{ $estudante->pessoa->segundonome }}"
                                   required>
                        </div>

                        <div class="col-md-4">
                            <input type="text"
                                   class="form-control"
                                   name="BI"
                                   value="{{ $estudante->pessoa->BI }}"
                                   required>
                        </div>

                        <div class="col-md-4">

                            <select class="form-select"
                                    name="genero"
                                    required>

                                <option value="M"
                                    {{ $estudante->pessoa->genero == 'M' ? 'selected' : '' }}>
                                    Masculino
                                </option>

                                <option value="F"
                                    {{ $estudante->pessoa->genero == 'F' ? 'selected' : '' }}>
                                    Feminino
                                </option>

                            </select>

                        </div>

                        <div class="col-md-4">
                            <input type="text"
                                   class="form-control"
                                   name="nacionalidade"
                                   value="{{ $estudante->pessoa->nacionalidade }}"
                                   required>
                        </div>

                        <div class="col-md-6">
                            <input type="date"
                                   class="form-control"
                                   name="data_nascimento"
                                   value="{{ $estudante->pessoa->data_nascimento }}"
                                   required>
                        </div>

                        <div class="col-md-3">
                            <input type="text"
                                   class="form-control"
                                   name="rua"
                                   value="{{ $estudante->pessoa->rua }}"
                                   required>
                        </div>

                        <div class="col-md-3">
                            <input type="text"
                                   class="form-control"
                                   name="bairro"
                                   value="{{ $estudante->pessoa->bairro }}"
                                   required>
                        </div>

                        <div class="col-12">
                            <input type="text"
                                   class="form-control"
                                   name="contacto"
                                   value="{{ $estudante->pessoa->contacto }}"
                                   required>
                        </div>

                    </div>

                    <hr class="my-4">

                    <!-- ACADÉMICO -->
                    <h6 class="text-primary mb-3">Académico</h6>

                    <div class="row g-3">

                        <div class="col-md-6">
                            <input type="text"
                                   class="form-control"
                                   name="escola_actual"
                                   value="{{ $estudante->escola_actual }}">
                        </div>

                        <div class="col-md-3">

                            <select class="form-select" name="status">

                                <option value="ativo"
                                    {{ $estudante->status == 'ativo' ? 'selected' : '' }}>
                                    Activo
                                </option>

                                <option value="inativo"
                                    {{ $estudante->status == 'inativo' ? 'selected' : '' }}>
                                    Inactivo
                                </option>

                            </select>

                        </div>

                        <div class="col-md-3">
                            <input type="date"
                                   class="form-control"
                                   name="data_inscricao"
                                   value="{{ $estudante->data_inscricao }}">
                        </div>

                    </div>

                    <!-- BOTÃO -->
                    <div class="mt-4">
                        <button type="submit"
                                class="btn btn-success w-100 py-2 fw-bold">

                            Actualizar Estudante

                        </button>
                    </div>

                </form>

            </div>

        </div>
    </div>
</div>
