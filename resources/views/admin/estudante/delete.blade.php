<!-- Delete Modal -->
<div class="modal fade" id="delete-{{ $estudante->id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Eliminar Estudante</h5>

                <button type="button"
                        class="btn-close btn-close-white"
                        data-bs-dismiss="modal">
                </button>
            </div>

            <div class="modal-body">

                <p>Tem certeza que deseja eliminar este estudante?</p>

                <div class="alert alert-warning">
                    <strong>
                        {{ $estudante->pessoa->primeironome }}
                        {{ $estudante->pessoa->segundonome }}
                    </strong>
                </div>

                <p class="text-muted">
                    Esta ação irá apagar também o utilizador e os dados pessoais.
                </p>

            </div>

            <div class="modal-footer">

                <form method="POST"
                      action="{{ route('estudante.destroy', $estudante->id) }}">

                    @csrf
                    @method('DELETE')

                    <button type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal">

                        Cancelar
                    </button>

                    <button type="submit"
                            class="btn btn-danger">

                        Sim, Eliminar
                    </button>

                </form>

            </div>

        </div>
    </div>
</div>