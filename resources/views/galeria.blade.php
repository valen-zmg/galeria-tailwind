<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuestra Galería</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 text-gray-800">

    <nav class="bg-gray-900 text-white">
        <div class="max-w-7xl mx-auto px-4 py-3 flex items-center justify-between">
            <span class="font-bold text-lg">Galería</span>
            <ul class="hidden md:flex gap-6 text-sm">
                <li><a href="#" class="hover:text-gray-300">Inicio</a></li>
                <li><a href="#" class="hover:text-gray-300">Galería</a></li>
                <li><a href="#" class="hover:text-gray-300">Nosotros</a></li>
                <li><a href="#" class="hover:text-gray-300">Contacto</a></li>
            </ul>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 py-8">
        <h1 class="text-2xl md:text-3xl font-bold text-center mb-8">Nuestra Galería</h1>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach ($fotos as $foto)
                <article class="bg-white rounded-lg shadow overflow-hidden hover:shadow-lg transition">
                    <img src="{{ asset('images/' . $foto['img']) }}"
                         alt="{{ $foto['titulo'] }}"
                         class="w-full h-48 object-cover">
                    <div class="p-4">
                        <h2 class="font-semibold">{{ $foto['titulo'] }}</h2>
                        <p class="text-sm text-gray-500">{{ $foto['desc'] }}</p>
                    </div>
                </article>
            @endforeach
        </div>
    </main>

</body>
</html>