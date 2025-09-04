<x-app-layout>
    <div class="max-w-4xl mx-auto p-6">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">{{ $vehicle->title }}</h1>
        <p class="text-gray-600 dark:text-gray-400">{{ $vehicle->make }} {{ $vehicle->model }} • {{ $vehicle->year }}</p>
        <p class="mt-2 text-blue-600 dark:text-blue-400 text-xl font-bold">${{ number_format($vehicle->price,0) }} {{ $vehicle->currency }}</p>

        <img src="{{ $vehicle->cover_image_path ? asset('storage/'.$vehicle->cover_image_path) : 'https://via.placeholder.com/800x400' }}"
             class="rounded-lg my-6">

        <p class="text-gray-700 dark:text-gray-300">{{ $vehicle->description }}</p>

        <div class="mt-6 flex gap-4">
            <a href="{{ route('vehicles.qr', $vehicle) }}" target="_blank" 
               class="px-3 py-2 bg-green-500 text-white rounded hover:bg-green-600">Ver QR</a>
            <a href="{{ route('vehicles.qr.download', $vehicle) }}" 
               class="px-3 py-2 bg-indigo-500 text-white rounded hover:bg-indigo-600">Descargar QR</a>
        </div>
    </div>
</x-app-layout>