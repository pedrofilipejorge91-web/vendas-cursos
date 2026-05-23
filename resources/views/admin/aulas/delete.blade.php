<!-- Basic Modal -->
<div class="modal fade" id="delete-{{$aula->id}}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Deletar a Aula</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Floating Labels Form -->
<form class="row g-3" method="POST" action="{{ Auth::user()?->tipo === 'formador' ? route('formador.aulas.destroy', $aula->id) : route('aulas.destroy', $aula->id) }}" data-ajax-confirm="Tem certeza que deseja eliminar esta aula?">
                @csrf
                @method('DELETE')
              
                <h4 class="text-center text-danger">Tem certeza que deseja deletar a aula {{$aula->titulo}}?</h4>
                <div class="text-center">
                  <button type="submit" class="btn btn-danger w-50 ">Deletar</button>
                  
                </div>
              </form><!-- End floating Labels Form -->
            </div>
            
        </div>
    </div>
</div><!-- End Basic Modal-->
