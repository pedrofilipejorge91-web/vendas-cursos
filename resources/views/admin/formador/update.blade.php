<!-- Edit Modal -->
<div class="modal fade" id="edit-{{ $formador->id }}" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Editar Formador</h5>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                </button>
            </div>

            <div class="modal-body">

                <form method="POST"
                      action="{{ route('formador.update', $formador->id) }}"
                      enctype="multipart/form-data">

                    @csrf
                    @method('PUT')

                    <!-- FOTO -->
                    <h6 class="text-primary mb-3">Imagem de Perfil</h6>

                    <div class="row g-3">

                        <div class="col-md-12 text-center">

                            @if($formador->foto_perfil)
                                <img src="{{ asset('storage/' . $formador->foto_perfil) }}"
                                     class="img-thumbnail mb-2"
                                     style="max-height: 150px;">
                            @endif

                            <input type="file"
                                   class="form-control"
                                   name="foto_perfil"
                                   accept="image/*">

                        </div>

                    </div>

                    <hr class="my-4">

                    <!-- DADOS PESSOAIS -->
                    <h6 class="text-primary mb-3">Dados Pessoais</h6>

                    <div class="row g-3">

                        <div class="col-md-6">
                            <input type="text"
                                   class="form-control"
                                   name="primeironome"
                                   value="{{ $formador->pessoa->primeironome }}"
                                   required>
                        </div>

                        <div class="col-md-6">
                            <input type="text"
                                   class="form-control"
                                   name="segundonome"
                                   value="{{ $formador->pessoa->segundonome }}"
                                   required>
                        </div>

                        <div class="col-md-4">
                            <input type="text"
                                   class="form-control"
                                   name="BI"
                                   value="{{ $formador->pessoa->BI }}"
                                   required>
                        </div>

                        <div class="col-md-4">

                            <select class="form-select" name="genero" required>

                                <option value="M"
                                    {{ $formador->pessoa->genero == 'M' ? 'selected' : '' }}>
                                    Masculino
                                </option>

                                <option value="F"
                                    {{ $formador->pessoa->genero == 'F' ? 'selected' : '' }}>
                                    Feminino
                                </option>

                            </select>

                        </div>

                        <div class="col-md-4">
                            <input type="text"
                                   class="form-control"
                                   name="nacionalidade"
                                   value="{{ $formador->pessoa->nacionalidade }}"
                                   required>
                        </div>

                        <div class="col-md-6">
                            <input type="date"
                                   class="form-control"
                                   name="data_nascimento"
                                   value="{{ $formador->pessoa->data_nascimento }}"
                                   required>
                        </div>

                        <div class="col-md-3">
                            <input type="text"
                                   class="form-control"
                                   name="rua"
                                   value="{{ $formador->pessoa->rua }}"
                                   required>
                        </div>

                        <div class="col-md-3">
                            <input type="text"
                                   class="form-control"
                                   name="bairro"
                                   value="{{ $formador->pessoa->bairro }}"
                                   required>
                        </div>

                        <div class="col-12">
                            <input type="text"
                                   class="form-control"
                                   name="contacto"
                                   value="{{ $formador->pessoa->contacto }}"
                                   required>
                        </div>

                    </div>

                    <hr class="my-4">

                    <!-- PROFISSIONAL -->
                    <h6 class="text-primary mb-3">Profissional</h6>

                    <div class="row g-3">

                        <div class="col-md-6">
                            <input type="text"
                                   class="form-control"
                                   name="especialidade"
                                   value="{{ $formador->especialidade }}"
                                   required>
                        </div>

                        <div class="col-md-6">
                            <input type="number"
                                   class="form-control"
                                   name="anos_experiencia"
                                   value="{{ $formador->anos_experiencia }}"
                                   required>
                        </div>

                        <div class="col-12">
                            <textarea class="form-control"
                                      name="biografia"
                                      style="height: 120px;">{{ $formador->biografia }}</textarea>
                        </div>

                    </div>

                    <!-- BOTÃO -->
                    <div class="mt-4">
                        <button type="submit"
                                class="btn btn-success w-100 py-2 fw-bold">

                            Salvar Alterações
                        </button>
                    </div>

                </form>

            </div>

        </div>
    </div>
</div>