<button type="button" class="btn btn-warning" data-toggle="modal" data-target="#updateModal{{ $i->id }}">
    Actualizar
</button>

<div class="modal fade" id="updateModal{{ $i->id }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Actualizar categoria</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('categoria.update', $i->id) }}" method="post">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <input class="form-control" type="text" name="categoria" value="{{ $i->categoria }}">
                    @error('categoria')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-success">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>