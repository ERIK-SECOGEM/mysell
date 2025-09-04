<x-app-layout>
    <div class="max-w-7xl mx-auto p-6">
        <h1 class="text-2xl font-bold mb-6 text-gray-800 dark:text-gray-100">Mis Vehículos</h1>

        <a href="{{ route('vehicles.create') }}" 
           class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600">
           + Nuevo Vehículo
        </a>

        <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-6">
            @forelse($vehicles as $vehicle)
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4 flex flex-col">
                    <img src="{{ $vehicle->cover_image_path ? asset('storage/'.$vehicle->cover_image_path) : 'https://via.placeholder.com/400x200' }}"
                         alt="{{ $vehicle->title }}" class="rounded-lg mb-3 h-40 object-cover">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $vehicle->title }}</h2>
                    <p class="text-gray-600 dark:text-gray-400 text-sm">
                        {{ $vehicle->make }} {{ $vehicle->model }} • {{ $vehicle->year }}
                    </p>
                    <p class="mt-1 text-blue-600 dark:text-blue-400 font-bold">
                        ${{ number_format($vehicle->price, 0) }} {{ $vehicle->currency }}
                    </p>

                    <div class="mt-4 flex gap-2">
                        <a href="{{ route('vehicles.show', $vehicle) }}" 
                           class="text-sm px-3 py-1 bg-gray-200 dark:bg-gray-700 rounded hover:bg-gray-300 dark:hover:bg-gray-600">Ver</a>
                        <a href="{{ route('vehicles.edit', $vehicle) }}" 
                           class="text-sm px-3 py-1 bg-yellow-400 dark:bg-yellow-500 rounded hover:bg-yellow-500 dark:hover:bg-yellow-600">Editar</a>
                        <form action="{{ route('vehicles.destroy', $vehicle) }}" method="POST" class="inline">
                            @csrf @method('DELETE')
                            <button class="text-sm px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600">Eliminar</button>
                        </form>
                    </div>
                </div>
            @empty
                <p class="text-gray-500 dark:text-gray-400">No tienes vehículos publicados.</p>
            @endforelse
        </div>
    </div>
</x-app-layout>