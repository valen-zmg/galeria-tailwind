<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ventas</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <x-navbar/>
    <h1 class="text-center">Ventas</h1>

    @if (session('mensaje'))
        <div class="alert alert-success col-md-10 offset-md-1">{{ session('mensaje') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger col-md-10 offset-md-1">{{ session('error') }}</div>
    @endif

    <div class="row">
        <div class="col-lg-10 offset-lg-1 mb-3">
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createVenta">
                Nueva venta
            </button>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-10 offset-lg-1">
            <table class="table table-bordered">
                <thead class="table-info">
                    <tr>
                        <th>id</th>
                        <th>cliente</th>
                        <th>fecha</th>
                        <th>productos</th>
                        <th>estado</th>
                        <th>total</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($ventas as $v)
                        <tr>
                            <td>{{ $v->id }}</td>
                            <td>{{ $v->cliente->nombre }}</td>
                            <td>{{ $v->fecha_venta }}</td>
                            <td>
                                @foreach ($v->detalles as $d)
                                    <div>{{ $d->producto->producto }} x{{ $d->cantidad }} (${{ $d->precio_unitario }})</div>
                                @endforeach
                            </td>
                            <td>
                                @if ($v->estado == 'pendiente')
                                    <span class="badge badge-warning">pendiente</span>
                                @elseif ($v->estado == 'confirmada')
                                    <span class="badge badge-success">confirmada</span>
                                @else
                                    <span class="badge badge-danger">cancelada</span>
                                @endif
                            </td>
                            <td>${{ $v->total }}</td>
                            <td>
                                <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#updateVenta{{ $v->id }}">Actualizar</button>
                                <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteVenta{{ $v->id }}">Eliminar</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" id="createVenta" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{ route('ventas.create') }}" method="post">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Registrar venta</h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <label>Cliente</label>
                        <select class="form-control mb-2" name="inputCliente_id">
                            <option value="">Selecciona cliente</option>
                            @foreach ($clientes as $c)
                                <option value="{{ $c->id }}" {{ old('inputCliente_id') == $c->id ? 'selected' : '' }}>{{ $c->nombre }}</option>
                            @endforeach
                        </select>

                        <label>Fecha</label>
                        <input type="date" class="form-control mb-2" name="inputFecha" value="{{ old('inputFecha', date('Y-m-d')) }}">

                        <label>Estado</label>
                        <select class="form-control mb-2" name="inputEstado">
                            <option value="pendiente" {{ old('inputEstado') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                            <option value="confirmada" {{ old('inputEstado') == 'confirmada' ? 'selected' : '' }}>Confirmada</option>
                            <option value="cancelada" {{ old('inputEstado') == 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                        </select>
                        <label>Productos</label>
                            @for ($i = 0; $i < 10; $i++)
                                <div @if ($i > 0) class="collapse" id="fila{{ $i }}" @endif>
                                    <div class="form-row mb-2">
                                        <div class="col-8">
                                            <select class="form-control" name="productos[{{ $i }}][id]">
                                                <option value="">Selecciona producto</option>
                                                @foreach ($productos as $p)
                                                    <option value="{{ $p->id }}">{{ $p->producto }} (${{ $p->precio }} - stock: {{ $p->stock }})</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    <div class="col-4">
                                <input type="number" min="1" class="form-control" name="productos[{{ $i }}][cantidad]" placeholder="Cantidad">
                            </div>
                        </div>

                    @if ($i < 9)
                        <button type="button" class="btn btn-sm btn-outline-primary mb-2" data-toggle="collapse" data-target="#fila{{ $i + 1 }}"> + Agregar otro producto</button>
                    @endif
                                </div>
                            @endfor

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

    @foreach ($ventas as $v)
        <div class="modal fade" id="updateVenta{{ $v->id }}" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form action="{{ route('ventas.update', $v->id) }}" method="post">
                        @csrf
                        @method('PUT')
                        <div class="modal-header">
                            <h5 class="modal-title">Actualizar venta #{{ $v->id }}</h5>
                            <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                        </div>
                        <div class="modal-body">
                            <label>Cliente</label>
                            <select class="form-control mb-2" name="inputCliente_id">
                                @foreach ($clientes as $c)
                                    <option value="{{ $c->id }}" {{ $c->id == $v->cliente_id ? 'selected' : '' }}>{{ $c->nombre }}</option>
                                @endforeach
                            </select>

                            <label>Fecha</label>
                            <input type="date" class="form-control mb-2" name="inputFecha" value="{{ $v->fecha_venta }}">

                            <label>Estado</label>
                            <select class="form-control mb-2" name="inputEstado">
                                @foreach (['pendiente', 'confirmada', 'cancelada'] as $e)
                                    <option value="{{ $e }}" {{ $v->estado == $e ? 'selected' : '' }}>{{ ucfirst($e) }}</option>
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

        <div class="modal fade" id="deleteVenta{{ $v->id }}" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form action="{{ route('ventas.delete', $v->id) }}" method="post">
                        @csrf
                        @method('DELETE')
                        <div class="modal-header">
                            <h5 class="modal-title">Eliminar venta #{{ $v->id }}</h5>
                            <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                        </div>
                        <div class="modal-body">
                            ¿Seguro que quieres eliminar la venta #{{ $v->id }}?
                            @if ($v->estado == 'confirmada')
                                <p class="text-danger mt-2 mb-0">Esta venta está confirmada y no se puede eliminar.</p>
                            @endif
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
                $('#createVenta').modal('show');
            });
        </script>
    @endif
</body>
</html>