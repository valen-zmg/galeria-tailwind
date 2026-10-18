<button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteModal{{ $i->id }}">
    Eliminar
</button>

<div class="modal fade" id="deleteModal{{ $i->id }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Eliminar categoria</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('categoria.delete', $i->id) }}" method="post">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    ¿Seguro que quieres eliminar <strong>{{ $i->categoria }}</strong>?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Sí, eliminar</button>
                </div>
            </form>
        </div>
    </div>
</div>