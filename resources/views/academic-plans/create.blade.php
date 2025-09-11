@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Page Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Crear Nuevo Plan Académico</h1>
        <nav class="flex mt-2" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-4">
                <li><a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-gray-700">Dashboard</a></li>
                <li><span class="text-gray-500">/</span></li>
                <li><a href="{{ route('academic-plans.index') }}" class="text-gray-500 hover:text-gray-700">Planes Académicos</a></li>
                <li><span class="text-gray-500">/</span></li>
                <li><span class="text-gray-900">Crear Nuevo</span></li>
            </ol>
        </nav>
    </div>

    <!-- Form -->
    <div class="bg-white card-shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-medium text-gray-900">📚 Información del Plan Académico</h2>
        </div>
        
        <form action="{{ route('academic-plans.store') }}" method="POST" class="p-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Name -->
                <div class="md:col-span-2">
                    <label for="name" class="block text-sm font-medium text-gray-700">Nombre del Plan *</label>
                    <input type="text" 
                           name="name" 
                           id="name" 
                           value="{{ old('name') }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('name') border-red-300 @enderror"
                           placeholder="Ej: Plan Académico Grado 9°"
                           required>
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Grade Level -->
                <div>
                    <label for="grade_level_id" class="block text-sm font-medium text-gray-700">Nivel de Grado *</label>
                    <select name="grade_level_id" 
                            id="grade_level_id" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('grade_level_id') border-red-300 @enderror"
                            required>
                        <option value="">Seleccionar grado...</option>
                        @foreach($gradeLevels as $gradeLevel)
                            <option value="{{ $gradeLevel->id }}" {{ old('grade_level_id') == $gradeLevel->id ? 'selected' : '' }}>
                                {{ $gradeLevel->grade_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('grade_level_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Academic Year -->
                <div>
                    <label for="academic_year" class="block text-sm font-medium text-gray-700">Año Académico *</label>
                    <select name="academic_year" 
                            id="academic_year" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('academic_year') border-red-300 @enderror"
                            required>
                        <option value="">Seleccionar año...</option>
                        @for($year = 2020; $year <= 2030; $year++)
                            <option value="{{ $year }}" {{ old('academic_year', date('Y')) == $year ? 'selected' : '' }}>
                                {{ $year }}
                            </option>
                        @endfor
                    </select>
                    @error('academic_year')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Periods Count -->
                <div class="md:col-span-2">
                    <label for="periods_count" class="block text-sm font-medium text-gray-700">Cantidad de Períodos *</label>
                    <select name="periods_count" 
                            id="periods_count" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('periods_count') border-red-300 @enderror"
                            required>
                        <option value="">Seleccionar cantidad...</option>
                        @for($i = 1; $i <= 6; $i++)
                            <option value="{{ $i }}" {{ old('periods_count', 4) == $i ? 'selected' : '' }}>
                                {{ $i }} {{ $i == 1 ? 'período' : 'períodos' }}
                            </option>
                        @endfor
                    </select>
                    @error('periods_count')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">
                        Se crearán automáticamente los períodos para este plan académico
                    </p>
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
                            Plan académico activo
                        </label>
                    </div>
                    <p class="mt-1 text-sm text-gray-500">
                        Los planes activos estarán disponibles para asignar materias y estudiantes
                    </p>
                </div>
            </div>

            <!-- Information Card -->
            <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-blue-800">Información importante</h3>
                        <div class="mt-2 text-sm text-blue-700">
                            <ul class="list-disc pl-5 space-y-1">
                                <li>Una vez creado el plan, podrás agregar materias específicas para este nivel</li>
                                <li>El plan debe estar activo para poder asignar estudiantes y profesores</li>
                                <li>Puedes crear múltiples planes para el mismo grado en diferentes años</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end space-x-3 mt-6 pt-6 border-t border-gray-200">
                <a href="{{ route('academic-plans.index') }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">
                    ❌ Cancelar
                </a>
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md shadow-sm">
                    💾 Crear Plan Académico
                </button>
            </div>
        </form>
    </div>

    <!-- Preview Section -->
    <div class="bg-gray-50 rounded-lg p-6 mt-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">🔍 Vista Previa</h3>
        <div class="bg-white rounded-lg border-2 border-dashed border-gray-300 p-4">
            <div class="text-center">
                <div class="text-4xl mb-2">📚</div>
                <p class="text-sm text-gray-500">Aquí aparecerá una vista previa del plan cuando completes el formulario</p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Simple form preview
    const nameInput = document.getElementById('name');
    const gradeSelect = document.getElementById('grade_level_id');
    const yearSelect = document.getElementById('academic_year');

    function updatePreview() {
        // You can add preview functionality here if needed
        console.log('Form updated');
    }

    nameInput.addEventListener('input', updatePreview);
    gradeSelect.addEventListener('change', updatePreview);
    yearSelect.addEventListener('change', updatePreview);
</script>
@endpush
@endsection
