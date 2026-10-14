<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categorias</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <x-navbar/>

    <h1 class="text-center">Categorias</h1>

    @if (session('mensaje'))
        <div class="alert alert-success col-md-6 offset-md-3">
            {{ session('mensaje') }}
        </div>
    @endif

    <div class="row">
        <div class="col-md-6 offset-md-3">
            <x-create-categoria/>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-lg-8 offset-lg-2">
            <table class="table table-bordered">
                <thead class="table-info">
                    <tr>
                        <th>id</th>
                        <th>categoria</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($categorias as $i)
                        <tr>
                            <td>{{ $i->id }}</td>
                            <td>{{ $i->categoria }}</td>
                            <td>
                                <x-update-categoria :i="$i" />
                                <x-delete-categoria :i="$i" />
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>