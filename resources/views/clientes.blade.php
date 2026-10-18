<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clientes</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <x-navbar/>
    <h1 class="text-center">Clientes</h1>

    @if (session('mensaje'))
        <div class="alert alert-success col-md-8 offset-md-2">{{ session('mensaje') }}</div>
    @endif

    <div class="row">
        <div class="col-lg-8 offset-lg-2 mb-3">
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createCliente">
                Nuevo cliente
            </button>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 offset-lg-2">
            <table class="table table-bordered">
                <thead class="table-info">
                    <tr>
                        <th>id</th>
                        <th>nombre</th>
                        <th>email</th>
                        <th>dirección</th>
                        <th>teléfono</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($clientes as $c)
                        <tr>
                            <td>{{ $c->id }}</td>
                            <td>{{ $c->nombre }}</td>
                            <td>{{ $c->email }}</td>
                            <td>{{ $c->direccion }}</td>
                            <td>{{ $c->telefono }}</td>
                            <td>
                                <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#updateCliente{{ $c->id }}">Actualizar</button>
                                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteCliente{{ $c->id }}">Eliminar</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Modal crear --}}
    <div class="modal fade" id="createCliente" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{ route('clientes.create') }}" method="post">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Registrar cliente</h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <input class="form-control mb-2" name="inputNombre" placeholder="Nombre" value="{{ old('inputNombre') }}">
                        <input class="form-control mb-2" name="inputEmail" placeholder="Email" value="{{ old('inputEmail') }}">
                        <input class="form-control mb-2" name="inputDireccion" placeholder="Dirección" value="{{ old('inputDireccion') }}">
                        <input class="form-control mb-2" name="inputTelefono" placeholder="Teléfono (10 dígitos)" value="{{ old('inputTelefono') }}">
                        @if ($errors->any())
                            <ul class="text-danger small mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-success">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modales actualizar y eliminar --}}
    @foreach ($clientes as $c)
        <div class="modal fade" id="updateCliente{{ $c->id }}" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form action="{{ route('clientes.update', $c->id) }}" method="post">
                        @csrf
                        @method('PUT')
                        <div class="modal-header">
                            <h5 class="modal-title">Actualizar cliente</h5>
                            <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                        </div>
                        <div class="modal-body">
                            <input class="form-control mb-2" name="inputNombre" value="{{ $c->nombre }}">
                            <input class="form-control mb-2" name="inputEmail" value="{{ $c->email }}">
                            <input class="form-control mb-2" name="inputDireccion" value="{{ $c->direccion }}">
                            <input class="form-control mb-2" name="inputTelefono" value="{{ $c->telefono }}">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-success">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="deleteCliente{{ $c->id }}" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form action="{{ route('clientes.delete', $c->id) }}" method="post">
                        @csrf
                        @method('DELETE')
                        <div class="modal-header">
                            <h5 class="modal-title">Eliminar cliente</h5>
                            <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                        </div>
                        <div class="modal-body">
                            ¿Seguro que quieres eliminar a <strong>{{ $c->nombre }}</strong>?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-danger">Sí, eliminar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

    @if ($errors->any())
        <script>
            window.addEventListener('load', function () {
                $('#createCliente').modal('show');
            });
        </script>
    @endif
</body>
</html>