<!-- Basic Modal -->
<div class="modal fade" id="basicModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Novo Estudante</h5>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close">
                </button>
            </div>

            <div class="modal-body">

                <form method="POST" action="{{ route('estudante.store') }}">
                    @csrf

                    <!-- LOGIN -->
                    <h6 class="text-primary mb-3">Acesso</h6>

                    <div class="row g-3">

                        <div class="col-md-4">
                            <input type="text"
                                   class="form-control"
                                   name="name"
                                   placeholder="Nome de utilizador"
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

                    <!-- PESSOAL -->
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

                    <!-- ACADÉMICO -->
                    <h6 class="text-primary mb-3">Académico</h6>

                    <div class="row g-3">

                        <div class="col-md-6">
                            <input type="text"
                                   class="form-control"
                                   name="escola_actual"
                                   placeholder="Escola actual">
                        </div>

                        <div class="col-md-3">
                            <select class="form-select" name="status">

                                <option value="ativo">Activo</option>
                                <option value="inativo">Inactivo</option>

                            </select>
                        </div>

                        <div class="col-md-3">
                            <input type="date"
                                   class="form-control"
                                   name="data_inscricao">
                        </div>

                    </div>

                    <!-- BOTÃO -->
                    <div class="mt-4">
                        <button type="submit"
                                class="btn btn-primary w-100 py-2 fw-bold">

                            Cadastrar Estudante

                        </button>
                    </div>

                </form>

            </div>

        </div>
    </div>
</div>