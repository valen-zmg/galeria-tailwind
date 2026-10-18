<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Document</title>
</head>
<body>
    <x-navbar/>
    <h1 class="text-center">Productos</h1>

    @if (session('mensaje'))
        <div class="alert alert-success col-md-6 offset-md-3">{{ session('mensaje') }}</div>
    @endif

    <div class="row">
        <div class="col-lg-8 offset-lg-2">
            <form action="{{ route('productos.create') }}" method="post" class="mb-3">
                @csrf
                <input class="form-control mb-2" name="inputProducto" placeholder="Producto" value="{{ old('inputProducto') }}">
                <input class="form-control mb-2" name="inputPrecio" placeholder="Precio" value="{{ old('inputPrecio') }}">
                <input class="form-control mb-2" name="inputStock" placeholder="Stock" value="{{ old('inputStock') }}">
                <select class="form-control mb-2" name="inputCategoria_id">
                    <option value="">Selecciona categoría</option>
                    @foreach ($categorias as $c)
                        <option value="{{ $c->id }}">{{ $c->categoria }}</option>
                    @endforeach
                </select>
                @if ($errors->any())
                    <small class="text-danger">Todos los campos son obligatorios.</small>
                @endif
                <button class="btn btn-success btn-block mt-2">Guardar</button>
            </form>

            <table class="table table-bordered">
                <thead class="table-info">
                    <tr><th>id</th><th>producto</th><th>precio</th><th>stock</th><th>categoria</th><th>Acciones</th></tr>
                </thead>
                <tbody>
                    @foreach ($productos as $p)
                        <tr>
                            <td>{{ $p->id }}</td>
                            <td>{{ $p->producto }}</td>
                            <td>{{ $p->precio }}</td>
                            <td>{{ $p->stock }}</td>
                            <td>{{ $p->categoria }}</td>
                            <td>
                                <form action="{{ route('productos.delete', $p->id) }}" method="post" style="display:inline">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-danger">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</body></html>