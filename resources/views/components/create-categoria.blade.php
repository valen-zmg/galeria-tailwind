<!-- Button trigger modal -->
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
  Nueva categoria
</button>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Registrar</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="{{ route('categoria.create') }}" method="post">
        @csrf
        <div class="modal-body">
          <input class="form-control form-control-sm" type="text" name="categoria"
                 value="{{ old('categoria') }}" placeholder="Ingresa el nombre de la categoria">
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

@if ($errors->has('categoria'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            $('#exampleModal').modal('show');
        });
    </script>
@endif