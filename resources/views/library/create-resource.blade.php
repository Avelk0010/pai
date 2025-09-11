@extends('layouts.app')

@section('title', 'Agregar Recurso - Biblioteca')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center space-x-3 mb-4">
                <a href="{{ route('library.admin.resources') }}" 
                   class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </a>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Agregar Nuevo Recurso</h1>
                    <p class="mt-2 text-gray-600">Agrega un nuevo recurso al catálogo de la biblioteca</p>
                </div>
            </div>
        </div>

        <!-- Form -->
        <div class="bg-white rounded-lg shadow">
            <form action="{{ route('library.admin.store-resource') }}" method="POST" class="p-6 space-y-6">
                @csrf

                <!-- Basic Information -->
                <div class="border-b border-gray-200 pb-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Información Básica</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                                Título <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="title" name="title" value="{{ old('title') }}" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('title') border-red-500 @enderror">
                            @error('title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="author" class="block text-sm font-medium text-gray-700 mb-2">
                                Autor <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="author" name="author" value="{{ old('author') }}" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('author') border-red-500 @enderror">
                            @error('author')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="isbn" class="block text-sm font-medium text-gray-700 mb-2">
                                ISBN
                            </label>
                            <input type="text" id="isbn" name="isbn" value="{{ old('isbn') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('isbn') border-red-500 @enderror"
                                   placeholder="978-0-123456-78-9">
                            @error('isbn')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="resource_type" class="block text-sm font-medium text-gray-700 mb-2">
                                Tipo de Recurso <span class="text-red-500">*</span>
                            </label>
                            <select id="resource_type" name="resource_type" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('resource_type') border-red-500 @enderror">
                                <option value="">Selecciona un tipo</option>
                                <option value="book" {{ old('resource_type') == 'book' ? 'selected' : '' }}>Libro</option>
                                <option value="magazine" {{ old('resource_type') == 'magazine' ? 'selected' : '' }}>Revista</option>
                                <option value="digital" {{ old('resource_type') == 'digital' ? 'selected' : '' }}>Recurso Digital</option>
                                <option value="multimedia" {{ old('resource_type') == 'multimedia' ? 'selected' : '' }}>Multimedia</option>
                            </select>
                            @error('resource_type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Description -->
                <div class="border-b border-gray-200 pb-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Descripción</h3>
                    
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                            Descripción del Recurso
                        </label>
                        <textarea id="description" name="description" rows="4"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('description') border-red-500 @enderror"
                                  placeholder="Descripción detallada del recurso...">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Location and Inventory -->
                <div class="border-b border-gray-200 pb-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Ubicación e Inventario</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label for="location" class="block text-sm font-medium text-gray-700 mb-2">
                                Ubicación <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="location" name="location" value="{{ old('location') }}" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('location') border-red-500 @enderror"
                                   placeholder="Ej: Sección Literatura, Estante A-3">
                            @error('location')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="total_copies" class="block text-sm font-medium text-gray-700 mb-2">
                                Copias Totales <span class="text-red-500">*</span>
                            </label>
                            <input type="number" id="total_copies" name="total_copies" value="{{ old('total_copies', 1) }}" min="1" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('total_copies') border-red-500 @enderror">
                            @error('total_copies')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="available_copies" class="block text-sm font-medium text-gray-700 mb-2">
                                Copias Disponibles <span class="text-red-500">*</span>
                            </label>
                            <input type="number" id="available_copies" name="available_copies" value="{{ old('available_copies', 1) }}" min="0" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('available_copies') border-red-500 @enderror">
                            @error('available_copies')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">No puede ser mayor que las copias totales</p>
                        </div>
                    </div>
                </div>

                <!-- Status -->
                <div class="pb-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Estado</h3>
                    
                    <div class="flex items-center">
                        <input type="checkbox" id="status" name="status" value="1" 
                               {{ old('status', true) ? 'checked' : '' }}
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="status" class="ml-2 block text-sm text-gray-700">
                            Recurso activo (disponible para préstamo)
                        </label>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                    <a href="{{ route('library.admin.resources') }}" 
                       class="px-6 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition-colors">
                        Cancelar
                    </a>
                    <button type="submit" 
                            class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                        Agregar Recurso
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Sync available copies with total copies
document.getElementById('total_copies').addEventListener('input', function() {
    const totalCopies = parseInt(this.value) || 0;
    const availableCopiesInput = document.getElementById('available_copies');
    const currentAvailable = parseInt(availableCopiesInput.value) || 0;
    
    if (currentAvailable > totalCopies) {
        availableCopiesInput.value = totalCopies;
    }
    
    availableCopiesInput.setAttribute('max', totalCopies);
});

// Validate available copies doesn't exceed total copies
document.getElementById('available_copies').addEventListener('input', function() {
    const availableCopies = parseInt(this.value) || 0;
    const totalCopies = parseInt(document.getElementById('total_copies').value) || 0;
    
    if (availableCopies > totalCopies) {
        this.value = totalCopies;
    }
});
</script>
@endsection
