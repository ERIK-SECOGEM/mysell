<x-app-layout>
    <div class="max-w-3xl mx-auto p-6">
        <h1 class="text-2xl font-bold mb-6 text-gray-800 dark:text-gray-100">Nuevo Vehículo</h1>

        <form action="{{ route('vehicles.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
    @csrf

    <div>
        <label class="block">Título</label>
        <input type="text" name="title" class="w-full">
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div>
            <label>Marca</label>
            <input type="text" name="make" class="w-full">
        </div>
        <div>
            <label>Modelo</label>
            <input type="text" name="model" class="w-full">
        </div>
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div>
            <label>Año</label>
            <input type="number" name="year" class="w-full">
        </div>
        <div>
            <label>Kilometraje</label>
            <input type="number" name="mileage" class="w-full">
        </div>
    </div>

    <div>
        <label>Precio</label>
        <input type="number" name="price" class="w-full">
    </div>

    <div>
        <label>Moneda</label>
        <select name="currency" class="w-full">
            <option value="MXN">MXN</option>
            <option value="USD">USD</option>
        </select>
    </div>

    <div>
        <label>VIN</label>
        <input type="text" name="vin" class="w-full">
    </div>

    <div>
        <label>Ubicación</label>
        <input type="text" name="location" class="w-full">
    </div>

    <div>
        <label>Descripción</label>
        <textarea name="description" rows="4" class="w-full"></textarea>
    </div>

    <div>
        <label>Imagen de portada</label>
        <input type="file" name="cover" class="w-full">
    </div>

    <button class="px-4 py-2 bg-blue-600 text-white rounded">Guardar</button>
</form>

    </div>
</x-app-layout>