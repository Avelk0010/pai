@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Page Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Editar Nivel de Grado</h1>
        <nav class="flex mt-2" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-4">
                <li><a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-gray-700">Dashboard</a></li>
                <li><span class="text-gray-500">/</span></li>
                <li><a href="{{ route('grade-levels.index') }}" class="text-gray-500 hover:text-gray-700">Niveles de Grado</a></li>
                <li><span class="text-gray-500">/</span></li>
                <li><a href="{{ route('grade-levels.show', $gradeLevel) }}" class="text-gray-500 hover:text-gray-700">{{ $gradeLevel->grade_name }}</a></li>
                <li><span class="text-gray-500">/</span></li>
                <li><span class="text-gray-900">Editar</span></li>
            </ol>
        </nav>
    </div>

    <!-- Current Info Card -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
        <div class="flex items-center">
            <div class="w-12 h-12 rounded-full bg-blue-100 text-blue-800 font-bold text-xl flex items-center justify-center">
                {{ $gradeLevel->grade_number }}
            </div>
            <div class="ml-4">
                <h2 class="text-lg font-medium text-blue-900">{{ $gradeLevel->grade_name }}</h2>
                <div class="flex items-center mt-1 text-sm text-blue-700">
                    <span class="mr-4">👥 {{ $gradeLevel->groups_count }} grupos</span>
                    <span class="mr-4">📚 {{ $gradeLevel->academic_plans_count }} planes académicos</span>
                    <span class="{{ $gradeLevel->status ? 'text-green-600' : 'text-red-600' }}">
                        {{ $gradeLevel->status ? '🟢 Activo' : '🔴 Inactivo' }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white card-shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-medium text-gray-900">🎓 Actualizar Información</h2>
        </div>
        
        <form action="{{ route('grade-levels.update', $gradeLevel) }}" method="POST" class="p-6">
            @csrf
            @method('PUT')
            
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
                            <option value="{{ $i }}" {{ old('grade_number', $gradeLevel->grade_number) == $i ? 'selected' : '' }}>
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
                           value="{{ old('grade_name', $gradeLevel->grade_name) }}"
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
                               {{ old('status', $gradeLevel->status) ? 'checked' : '' }}
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

            <!-- Warning Section for Dependencies -->
            @if($gradeLevel->groups_count > 0 || $gradeLevel->academic_plans_count > 0)
            <div class="mt-6 bg-amber-50 border border-amber-200 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-amber-800">⚠️ Nivel con dependencias</h3>
                        <div class="mt-2 text-sm text-amber-700">
                            <p>Este nivel de grado tiene asociados:</p>
                            <ul class="list-disc pl-5 mt-1">
                                @if($gradeLevel->groups_count > 0)
                                    <li>{{ $gradeLevel->groups_count }} grupos</li>
                                @endif
                                @if($gradeLevel->academic_plans_count > 0)
                                    <li>{{ $gradeLevel->academic_plans_count }} planes académicos</li>
                                @endif
                            </ul>
                            <p class="mt-2">Cambiar el número de grado puede afectar estas relaciones.</p>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Action Buttons -->
            <div class="flex justify-end space-x-3 mt-6 pt-6 border-t border-gray-200">
                <a href="{{ route('grade-levels.show', $gradeLevel) }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">
                    ❌ Cancelar
                </a>
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md shadow-sm">
                    💾 Actualizar Nivel
                </button>
            </div>
        </form>
    </div>

    <!-- Change History -->
    <div class="bg-gray-50 rounded-lg p-6 mt-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">📝 Historial de Cambios</h3>
        <div class="space-y-3">
            <div class="bg-white rounded-lg p-4 border border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-8 h-8 rounded-full bg-green-100 text-green-800 text-sm font-medium flex items-center justify-center">
                            C
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900">Nivel creado</p>
                            <p class="text-xs text-gray-500">{{ $gradeLevel->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                    <span class="text-xs text-gray-400">hace {{ $gradeLevel->created_at->diffForHumans() }}</span>
                </div>
            </div>
            
            @if($gradeLevel->updated_at != $gradeLevel->created_at)
            <div class="bg-white rounded-lg p-4 border border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-800 text-sm font-medium flex items-center justify-center">
                            E
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900">Última actualización</p>
                            <p class="text-xs text-gray-500">{{ $gradeLevel->updated_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                    <span class="text-xs text-gray-400">hace {{ $gradeLevel->updated_at->diffForHumans() }}</span>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="mt-6 flex justify-center">
        <div class="flex space-x-4">
            <a href="{{ route('grade-levels.show', $gradeLevel) }}" 
               class="inline-flex items-center px-3 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                👁️ Ver Detalles
            </a>
            <a href="{{ route('grade-levels.index') }}" 
               class="inline-flex items-center px-3 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                📋 Listar Todos
            </a>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Auto-generate grade name based on number if current name matches pattern
    const gradeNumberSelect = document.getElementById('grade_number');
    const gradeNameInput = document.getElementById('grade_name');
    const originalName = '{{ $gradeLevel->grade_name }}';
    
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
            
            // Only auto-update if the current name matches a standard pattern
            const currentName = gradeNameInput.value;
            const standardNames = Object.values(names);
            
            if (standardNames.includes(currentName) || currentName === originalName) {
                gradeNameInput.value = names[number] || `Grado ${number}°`;
            }
        }
    });

    // Confirmation for major changes
    document.querySelector('form').addEventListener('submit', function(e) {
        const originalNumber = {{ $gradeLevel->grade_number }};
        const newNumber = parseInt(gradeNumberSelect.value);
        const hasGroups = {{ $gradeLevel->groups_count }};
        const hasPlans = {{ $gradeLevel->academic_plans_count }};
        
        if (originalNumber !== newNumber && (hasGroups > 0 || hasPlans > 0)) {
            if (!confirm('¿Estás seguro de cambiar el número de grado? Esto puede afectar ' + (hasGroups + hasPlans) + ' registros relacionados.')) {
                e.preventDefault();
            }
        }
    });
</script>
@endpush
@endsection
