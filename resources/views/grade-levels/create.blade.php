@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Page Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Crear Nuevo Nivel de Grado</h1>
        <nav class="flex mt-2" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-4">
                <li><a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-gray-700">Dashboard</a></li>
                <li><span class="text-gray-500">/</span></li>
                <li><a href="{{ route('grade-levels.index') }}" class="text-gray-500 hover:text-gray-700">Niveles de Grado</a></li>
                <li><span class="text-gray-500">/</span></li>
                <li><span class="text-gray-900">Crear Nuevo</span></li>
            </ol>
        </nav>
    </div>

    <!-- Form -->
    <div class="bg-white card-shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-medium text-gray-900">🎓 Información del Nivel de Grado</h2>
        </div>
        
        <form action="{{ route('grade-levels.store') }}" method="POST" class="p-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Grade Number -->
                <div>
                    <label for="grade_number" class="block text-sm font-medium text-gray-700">Número de Grado *</label>
                    <select name="grade_number" 
                            id="grade_number" 
                            class="mt-1 block w-full rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('grade_number') border-red-300 @else border-gray-300 @enderror"
                            required>
                        <option value="">Seleccionar número...</option>
                        @for($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}" {{ old('grade_number') == $i ? 'selected' : '' }}>
                                Grado {{ $i }}°
                            </option>
                        @endfor
                    </select>
                    @error('grade_number')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Grade Name -->
                <div>
                    <label for="grade_name" class="block text-sm font-medium text-gray-700">Nombre del Grado *</label>
                    <input type="text" 
                           name="grade_name" 
                           id="grade_name" 
                           value="{{ old('grade_name') }}"
                           class="mt-1 block w-full rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('grade_name') border-red-300 @else border-gray-300 @enderror"
                           placeholder="Ej: Sexto Grado, Noveno Grado"
                           required>
                    @error('grade_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div class="md:col-span-2">
                    <div class="flex items-center">
                        <input id="status" 
                               name="status" 
                               type="checkbox" 
                               value="1"
                               {{ old('status', true) ? 'checked' : '' }}
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="status" class="ml-2 block text-sm text-gray-900">
                            Nivel de grado activo
                        </label>
                    </div>
                    <p class="mt-1 text-sm text-gray-500">
                        Los grados activos estarán disponibles para crear grupos y planes académicos
                    </p>
                </div>
            </div>

            <!-- Examples Section -->
            <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-blue-800">Ejemplos de nomenclatura</h3>
                        <div class="mt-2 text-sm text-blue-700">
                            <ul class="list-disc pl-5 space-y-1">
                                <li><strong>Primaria:</strong> Grado 1° → "Primer Grado", Grado 6° → "Sexto Grado"</li>
                                <li><strong>Secundaria:</strong> Grado 7° → "Séptimo Grado", Grado 9° → "Noveno Grado"</li>
                                <li><strong>Media:</strong> Grado 10° → "Décimo Grado", Grado 11° → "Undécimo Grado"</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end space-x-3 mt-6 pt-6 border-t border-gray-200">
                <a href="{{ route('grade-levels.index') }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">
                    ❌ Cancelar
                </a>
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md shadow-sm">
                    💾 Crear Nivel de Grado
                </button>
            </div>
        </form>
    </div>

    <!-- Preview Section -->
    <div class="bg-gray-50 rounded-lg p-6 mt-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">🔍 Vista Previa</h3>
        <div class="bg-white rounded-lg border-2 border-dashed border-gray-300 p-6">
            <div id="preview-content" class="text-center">
                <div class="text-4xl mb-2">🎓</div>
                <p class="text-sm text-gray-500">Selecciona un número y nombre para ver la vista previa</p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Form preview functionality
    const gradeNumberSelect = document.getElementById('grade_number');
    const gradeNameInput = document.getElementById('grade_name');
    const previewContent = document.getElementById('preview-content');

    // Auto-generate grade name based on number
    gradeNumberSelect.addEventListener('change', function() {
        const number = parseInt(this.value);
        if (number) {
            const names = {
                1: 'Primer Grado',
                2: 'Segundo Grado', 
                3: 'Tercer Grado',
                4: 'Cuarto Grado',
                5: 'Quinto Grado',
                6: 'Sexto Grado',
                7: 'Séptimo Grado',
                8: 'Octavo Grado',
                9: 'Noveno Grado',
                10: 'Décimo Grado',
                11: 'Undécimo Grado',
                12: 'Duodécimo Grado'
            };
            
            if (!gradeNameInput.value) {
                gradeNameInput.value = names[number] || `Grado ${number}°`;
            }
        }
        updatePreview();
    });

    gradeNameInput.addEventListener('input', updatePreview);

    function updatePreview() {
        const number = gradeNumberSelect.value;
        const name = gradeNameInput.value;
        
        if (number && name) {
            previewContent.innerHTML = `
                <div class="flex items-center justify-center space-x-4">
                    <div class="w-16 h-16 rounded-full bg-blue-100 text-blue-800 font-bold text-2xl flex items-center justify-center">
                        ${number}
                    </div>
                    <div class="text-left">
                        <div class="text-lg font-medium text-gray-900">${name}</div>
                        <div class="text-sm text-gray-500">Grado ${number}°</div>
                    </div>
                </div>
            `;
        } else {
            previewContent.innerHTML = `
                <div class="text-4xl mb-2">🎓</div>
                <p class="text-sm text-gray-500">Selecciona un número y nombre para ver la vista previa</p>
            `;
        }
    }
</script>
@endpush
@endsection
