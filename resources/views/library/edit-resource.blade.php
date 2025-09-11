@extends('layouts.app')

@section('title', 'Editar Recurso - Biblioteca')

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
                    <h1 class="text-3xl font-bold text-gray-900">Editar Recurso</h1>
                    <p class="mt-2 text-gray-600">Edita la información del recurso: {{ $resource->title }}</p>
                </div>
            </div>
        </div>

        <!-- Form -->
        <div class="bg-white rounded-lg shadow">
            <form action="{{ route('library.admin.update-resource', $resource) }}" method="POST" class="p-6 space-y-6">
                @csrf
                @method('PATCH')

                <!-- Basic Information -->
                <div class="border-b border-gray-200 pb-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Información Básica</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                                Título <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="title" name="title" value="{{ old('title', $resource->title) }}" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('title') border-red-500 @enderror">
                            @error('title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="author" class="block text-sm font-medium text-gray-700 mb-2">
                                Autor <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="author" name="author" value="{{ old('author', $resource->author) }}" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('author') border-red-500 @enderror">
                            @error('author')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="isbn" class="block text-sm font-medium text-gray-700 mb-2">
                                ISBN
                            </label>
                            <input type="text" id="isbn" name="isbn" value="{{ old('isbn', $resource->isbn) }}"
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
                                <option value="book" {{ old('resource_type', $resource->resource_type) == 'book' ? 'selected' : '' }}>Libro</option>
                                <option value="magazine" {{ old('resource_type', $resource->resource_type) == 'magazine' ? 'selected' : '' }}>Revista</option>
                                <option value="digital" {{ old('resource_type', $resource->resource_type) == 'digital' ? 'selected' : '' }}>Recurso Digital</option>
                                <option value="multimedia" {{ old('resource_type', $resource->resource_type) == 'multimedia' ? 'selected' : '' }}>Multimedia</option>
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
                                  placeholder="Descripción detallada del recurso...">{{ old('description', $resource->description) }}</textarea>
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
                            <input type="text" id="location" name="location" value="{{ old('location', $resource->location) }}" required
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
                            <input type="number" id="total_copies" name="total_copies" value="{{ old('total_copies', $resource->total_copies) }}" min="1" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('total_copies') border-red-500 @enderror">
                            @error('total_copies')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="available_copies" class="block text-sm font-medium text-gray-700 mb-2">
                                Copias Disponibles <span class="text-blue-500">(Calculado Automáticamente)</span>
                            </label>
                            <input type="number" id="available_copies" name="available_copies" 
                                   value="{{ old('available_copies', $resource->available_copies) }}" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 text-gray-600 cursor-not-allowed" 
                                   readonly>
                            <p class="mt-1 text-xs text-gray-500">
                                Se calcula automáticamente: Total de copias - Copias prestadas activas
                            </p>
                            
                            @php
                                $activeLoans = $resource->loans()->whereIn('status', ['active', 'approved'])->count();
                                $calculatedAvailable = $resource->total_copies - $activeLoans;
                            @endphp
                            
                            <p class="mt-1 text-xs text-blue-600">
                                Total: {{ $resource->total_copies }} - Prestadas: {{ $activeLoans }} = Disponibles: {{ $calculatedAvailable }}
                            </p>
                        </div>
                    </div>

                    <!-- Loan Status Info -->
                    @if($resource->loans()->whereIn('status', ['active', 'approved'])->exists())
                        <div class="mt-4 p-4 bg-yellow-50 border border-yellow-200 rounded-md">
                            <div class="flex">
                                <svg class="w-5 h-5 text-yellow-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                <div class="text-sm">
                                    <p class="font-medium text-yellow-800">Este recurso tiene préstamos activos</p>
                                    <p class="text-yellow-600">Ten cuidado al modificar el número de copias disponibles.</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Status -->
                <div class="pb-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Estado</h3>
                    
                    <div class="flex items-center">
                        <input type="checkbox" id="status" name="status" value="1" 
                               {{ old('status', $resource->status) ? 'checked' : '' }}
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
                        Actualizar Recurso
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Auto-calculate available copies when total copies changes
document.getElementById('total_copies').addEventListener('input', function() {
    const totalCopies = parseInt(this.value) || 0;
    const activeLoans = {{ $activeLoans }}; // From PHP
    const calculatedAvailable = Math.max(0, totalCopies - activeLoans);
    
    // Update the available copies field
    const availableCopiesInput = document.getElementById('available_copies');
    availableCopiesInput.value = calculatedAvailable;
    
    // Update the calculation display
    const calculationInfo = document.querySelector('.text-blue-600');
    if (calculationInfo) {
        calculationInfo.textContent = `Total: ${totalCopies} - Prestadas: ${activeLoans} = Disponibles: ${calculatedAvailable}`;
    }
    
    // Validate that total copies can accommodate current loans
    if (totalCopies < activeLoans) {
        this.setCustomValidity(`El total de copias no puede ser menor que ${activeLoans} (copias actualmente prestadas)`);
    } else {
        this.setCustomValidity('');
    }
});

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    const totalCopiesInput = document.getElementById('total_copies');
    const event = new Event('input');
    totalCopiesInput.dispatchEvent(event);
});
</script>
@endsection
