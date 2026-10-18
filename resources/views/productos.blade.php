<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <x-navbar/>
    <h1 class="text-center">Productos</h1>

    @if (session('mensaje'))
        <div class="alert alert-success col-md-8 offset-md-2">{{ session('mensaje') }}</div>
    @endif

    <div class="row">
        <div class="col-lg-8 offset-lg-2 mb-3">
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createProducto">
                Nuevo producto
            </button>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 offset-lg-2">
            <table class="table table-bordered">
                <thead class="table-info">
                    <tr>
                        <th>id</th>
                        <th>producto</th>
                        <th>precio</th>
                        <th>stock</th>
                        <th>categoria</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($productos as $p)
                        <tr>
                            <td>{{ $p->id }}</td>
                            <td>{{ $p->producto }}</td>
                            <td>${{ $p->precio }}</td>
                            <td>{{ $p->stock }}</td>
                            <td>{{ $p->categoria }}</td>
                            <td>
                                <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#updateProducto{{ $p->id }}">Actualizar</button>
                                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteProducto{{ $p->id }}">Eliminar</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Modal crear --}}
    <div class="modal fade" id="createProducto" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{ route('productos.create') }}" method="post">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Registrar producto</h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <input class="form-control mb-2" name="inputProducto" placeholder="Producto" value="{{ old('inputProducto') }}">
                        <input class="form-control mb-2" name="inputPrecio" type="number" step="0.01" placeholder="Precio" value="{{ old('inputPrecio') }}">
                        <input class="form-control mb-2" name="inputStock" type="number" placeholder="Stock" value="{{ old('inputStock') }}">
                        <select class="form-control mb-2" name="inputCategoria_id">
                            <option value="">Selecciona categoría</option>
                            @foreach ($categorias as $c)
                                <option value="{{ $c->id }}">{{ $c->categoria }}</option>
                            @endforeach
                        </select>
                        @if ($errors->any())
                            <small class="text-danger">Todos los campos son obligatorios.</small>
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

    {{-- Modales actualizar y eliminar (uno por producto) --}}
    @foreach ($productos as $p)
        <div class="modal fade" id="updateProducto{{ $p->id }}" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form action="{{ route('productos.update', $p->id) }}" method="post">
                        @csrf
                        @method('PUT')
                        <div class="modal-header">
                            <h5 class="modal-title">Actualizar producto</h5>
                            <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                        </div>
                        <div class="modal-body">
                            <input class="form-control mb-2" name="inputProducto" value="{{ $p->producto }}">
                            <input class="form-control mb-2" name="inputPrecio" type="number" step="0.01" value="{{ $p->precio }}">
                            <input class="form-control mb-2" name="inputStock" type="number" value="{{ $p->stock }}">
                            <select class="form-control mb-2" name="inputCategoria_id">
                                @foreach ($categorias as $c)
                                    <option value="{{ $c->id }}" {{ $c->id == $p->categoria_id ? 'selected' : '' }}>{{ $c->categoria }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-success">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="deleteProducto{{ $p->id }}" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form action="{{ route('productos.delete', $p->id) }}" method="post">
                        @csrf
                        @method('DELETE')
                        <div class="modal-header">
                            <h5 class="modal-title">Eliminar producto</h5>
                            <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                        </div>
                        <div class="modal-body">
                            ¿Seguro que quieres eliminar <strong>{{ $p->producto }}</strong>?
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
                $('#createProducto').modal('show');
            });
        </script>
    @endif
</body>
</html>