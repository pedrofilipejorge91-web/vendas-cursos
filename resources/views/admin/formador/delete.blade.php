<!-- Basic Modal -->
<div class="modal fade" id="delete-{{$formador->id}}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Deletar o formador</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Floating Labels Form -->
                                          <form method="POST" action="{{ route('formador.destroy', $formador->id) }}">
                                @csrf
                                @method('DELETE')

                                <h4 class="text-danger text-center">
                                    Tens certeza que quer eliminar o formador {{ $formador->pessoa->primeironome }}
                                     {{ $formador->pessoa->segundonome }}?
                                </h4>

                                <div class="text-center">
                                    <button class="btn btn-danger w-50">Eliminar</button>
                                </div>
                            </form><!-- End floating Labels Form -->
            </div>
            
        </div>
    </div>
</div><!-- End Basic Modal-->