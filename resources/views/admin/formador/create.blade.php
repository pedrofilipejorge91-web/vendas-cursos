<!-- Basic Modal -->
<div class="modal fade" id="basicModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Novo Formador</h5>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close">
                </button>
            </div>

            <div class="modal-body">

                <form method="POST"
                      action="{{ route('formador.store') }}"
                      enctype="multipart/form-data">

                    @csrf

                    <!-- ACESSO -->
                    <h6 class="text-primary mb-3">Acesso</h6>

                    <div class="row g-3">

                        <div class="col-md-4">
                            <input type="text"
                                   class="form-control"
                                   name="name"
                                   placeholder="Nome de login"
                                   required>
                        </div>

                        <div class="col-md-4">
                            <input type="email"
                                   class="form-control"
                                   name="email"
                                   placeholder="E-mail"
                                   required>
                        </div>

                        <div class="col-md-4">
                            <input type="password"
                                   class="form-control"
                                   name="password"
                                   placeholder="Senha"
                                   required>
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
                                   placeholder="Nome Completo"
                                   required>
                        </div>

                        <div class="col-md-6">
                            <input type="text"
                                   class="form-control"
                                   name="segundonome"
                                   placeholder="Apelido"
                                   required>
                        </div>

                        <div class="col-md-4">
                            <input type="text"
                                   class="form-control"
                                   name="BI"
                                   placeholder="BI"
                                   required>
                        </div>

                        <div class="col-md-4">
                            <select class="form-select"
                                    name="genero"
                                    required>

                                <option value="">Gênero</option>
                                <option value="M">Masculino</option>
                                <option value="F">Feminino</option>

                            </select>
                        </div>

                        <div class="col-md-4">
                            <input type="text"
                                   class="form-control"
                                   name="nacionalidade"
                                   placeholder="Nacionalidade"
                                   required>
                        </div>

                        <div class="col-md-6">
                            <input type="date"
                                   class="form-control"
                                   name="data_nascimento"
                                   required>
                        </div>

                        <div class="col-md-3">
                            <input type="text"
                                   class="form-control"
                                   name="rua"
                                   placeholder="Rua"
                                   required>
                        </div>

                        <div class="col-md-3">
                            <input type="text"
                                   class="form-control"
                                   name="bairro"
                                   placeholder="Bairro"
                                   required>
                        </div>

                        <div class="col-12">
                            <input type="text"
                                   class="form-control"
                                   name="contacto"
                                   placeholder="Contacto"
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
                                   placeholder="Especialidade"
                                   required>
                        </div>

                        <div class="col-md-6">
                            <input type="number"
                                   class="form-control"
                                   name="anos_experiencia"
                                   placeholder="Anos de experiência"
                                   required>
                        </div>

                        <div class="col-12">
                            <textarea class="form-control"
                                      name="biografia"
                                      placeholder="Biografia"
                                      style="height: 100px;"></textarea>
                        </div>

                        <div class="col-12">
                            <input class="form-control"
                                   type="file"
                                   name="foto_perfil"
                                   accept="image/*">
                        </div>

                    </div>

                    <!-- BOTÃO -->
                    <div class="mt-4">
                        <button type="submit"
                                class="btn btn-primary w-100 py-2 fw-bold">

                            Adicionar Formador
                        </button>
                    </div>

                </form>

            </div>

        </div>
    </div>
</div>