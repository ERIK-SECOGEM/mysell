<!DOCTYPE html>
<html lang="es" class="bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100">
<head>
    <meta charset="UTF-8">
    <title>{{ $vehicle->title }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Open Graph para compartir --}}
    <meta property="og:title" content="{{ $vehicle->title }}">
    <meta property="og:description" content="{{ Str::limit($vehicle->description, 150) }}">
    <meta property="og:image" content="{{ $vehicle->cover_image_path ? asset('storage/'.$vehicle->cover_image_path) : 'https://via.placeholder.com/800x400' }}">
</head>
<body class="p-6 max-w-3xl mx-auto">
    <h1 class="text-3xl font-bold">{{ $vehicle->title }}</h1>
    <p class="text-gray-600 dark:text-gray-400">{{ $vehicle->make }} {{ $vehicle->model }} • {{ $vehicle->year }}</p>
    <p class="mt-2 text-blue-600 dark:text-blue-400 text-xl font-bold">${{ number_format($vehicle->price,0) }} {{ $vehicle->currency }}</p>

    <img src="{{ $vehicle->cover_image_path ? asset('storage/'.$vehicle->cover_image_path) : 'https://via.placeholder.com/800x400' }}" 
         class="rounded-lg my-6">

    <p>{{ $vehicle->description }}</p>

    <div class="mt-6 flex gap-4">
        <button onclick="navigator.share?.({ title: '{{ $vehicle->title }}', url: '{{ url()->current() }}' })"
                class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
            Compartir
        </button>
        <button onclick="navigator.clipboard.writeText('{{ url()->current() }}')"
                class="px-4 py-2 bg-gray-300 dark:bg-gray-700 rounded hover:bg-gray-400 dark:hover:bg-gray-600">
            Copiar enlace
        </button>
    </div>
</body>
</html>