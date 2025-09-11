@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Page Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Editar Grupo</h1>
        <nav class="flex mt-2" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-4">
                <li><a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-gray-700">Dashboard</a></li>
                <li><span class="text-gray-500">/</span></li>
                <li><a href="{{ route('groups.index') }}" class="text-gray-500 hover:text-gray-700">Grupos</a></li>
                <li><span class="text-gray-500">/</span></li>
                <li><a href="{{ route('groups.show', $group) }}" class="text-gray-500 hover:text-gray-700">{{ $group->full_name }}</a></li>
                <li><span class="text-gray-500">/</span></li>
                <li><span class="text-gray-900">Editar</span></li>
            </ol>
        </nav>
    </div>

    <!-- Current Info Card -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
        <div class="flex items-center">
            <div class="w-16 h-16 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 text-white font-bold text-xl flex items-center justify-center">
                {{ $group->group_letter }}
            </div>
            <div class="ml-4">
                <h2 class="text-lg font-medium text-blue-900">{{ $group->full_name }}</h2>
                <div class="flex items-center mt-1 text-sm text-blue-700">
                    <span class="mr-4">📅 {{ $group->academic_year }}</span>
                    <span class="mr-4">👥 {{ $currentEnrollment }}/{{ $group->max_students }} estudiantes</span>
                    <span class="{{ $group->status ? 'text-green-600' : 'text-red-600' }}">
                        {{ $group->status ? '🟢 Activo' : '🔴 Inactivo' }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white card-shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-medium text-gray-900">👥 Actualizar Información del Grupo</h2>
        </div>
        
        <form action="{{ route('groups.update', $group) }}" method="POST" class="p-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Grade Level -->
                <div>
                    <label for="grade_level_id" class="block text-sm font-medium text-gray-700">Nivel de Grado *</label>
                    <select name="grade_level_id" 
                            id="grade_level_id" 
                            class="mt-1 block w-full rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('grade_level_id') border-red-300 @else border-gray-300 @enderror"
                            required>
                        <option value="">Seleccionar nivel...</option>
                        @foreach($gradeLevels as $level)
                            <option value="{{ $level->id }}" {{ old('grade_level_id', $group->grade_level_id) == $level->id ? 'selected' : '' }}>
                                {{ $level->grade_name }} ({{ $level->grade_number }}°)
                            </option>
                        @endforeach
                    </select>
                    @error('grade_level_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Group Letter -->
                <div>
                    <label for="group_letter" class="block text-sm font-medium text-gray-700">Letra del Grupo *</label>
                    <select name="group_letter" 
                            id="group_letter" 
                            class="mt-1 block w-full rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('group_letter') border-red-300 @else border-gray-300 @enderror"
                            required>
                        <option value="">Seleccionar letra...</option>
                        @foreach(['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J'] as $letter)
                            <option value="{{ $letter }}" {{ old('group_letter', $group->group_letter) == $letter ? 'selected' : '' }}>
                                Grupo {{ $letter }}
                            </option>
                        @endforeach
                    </select>
                    @error('group_letter')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Academic Year -->
                <div>
                    <label for="academic_year" class="block text-sm font-medium text-gray-700">Año Académico *</label>
                    <select name="academic_year" 
                            id="academic_year" 
                            class="mt-1 block w-full rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('academic_year') border-red-300 @else border-gray-300 @enderror"
                            required>
                        <option value="">Seleccionar año...</option>
                        @for($year = date('Y'); $year <= date('Y') + 2; $year++)
                            <option value="{{ $year }}" {{ old('academic_year', $group->academic_year) == $year ? 'selected' : '' }}>
                                {{ $year }}
                            </option>
                        @endfor
                    </select>
                    @error('academic_year')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Max Students -->
                <div>
                    <label for="max_students" class="block text-sm font-medium text-gray-700">Capacidad Máxima *</label>
                    <input type="number" 
                           name="max_students" 
                           id="max_students" 
                           value="{{ old('max_students', $group->max_students) }}"
                           min="5" 
                           max="60"
                           class="mt-1 block w-full rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('max_students') border-red-300 @else border-gray-300 @enderror"
                           required>
                    @error('max_students')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @else
                        <p class="mt-1 text-sm text-gray-500">
                            Mínimo: {{ $currentEnrollment }} (estudiantes actuales)
                        </p>
                    @enderror
                </div>

                <!-- Status -->
                <div class="md:col-span-2">
                    <div class="flex items-center">
                        <input id="status" 
                               name="status" 
                               type="checkbox" 
                               value="1"
                               {{ old('status', $group->status) ? 'checked' : '' }}
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="status" class="ml-2 block text-sm text-gray-900">
                            Grupo activo
                        </label>
                    </div>
                    <p class="mt-1 text-sm text-gray-500">
                        Los grupos activos estarán disponibles para inscripción de estudiantes
                    </p>
                </div>
            </div>

            <!-- Warning for Students -->
            @if($currentEnrollment > 0)
            <div class="mt-6 bg-amber-50 border border-amber-200 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-amber-800">⚠️ Grupo con estudiantes inscritos</h3>
                        <div class="mt-2 text-sm text-amber-700">
                            <p>Este grupo tiene {{ $currentEnrollment }} estudiantes inscritos.</p>
                            <ul class="list-disc pl-5 mt-1">
                                <li>No puede reducir la capacidad por debajo de {{ $currentEnrollment }}</li>
                                <li>Cambiar el nivel puede afectar la continuidad académica</li>
                                <li>Cambiar el año académico puede generar inconsistencias</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Action Buttons -->
            <div class="flex justify-end space-x-3 mt-6 pt-6 border-t border-gray-200">
                <a href="{{ route('groups.show', $group) }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">
                    ❌ Cancelar
                </a>
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md shadow-sm">
                    💾 Actualizar Grupo
                </button>
            </div>
        </form>
    </div>

    <!-- Change Log -->
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
                            <p class="text-sm font-medium text-gray-900">Grupo creado</p>
                            <p class="text-xs text-gray-500">{{ $group->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                    <span class="text-xs text-gray-400">hace {{ $group->created_at->diffForHumans() }}</span>
                </div>
            </div>
            
            @if($group->updated_at != $group->created_at)
            <div class="bg-white rounded-lg p-4 border border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-800 text-sm font-medium flex items-center justify-center">
                            E
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900">Última actualización</p>
                            <p class="text-xs text-gray-500">{{ $group->updated_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                    <span class="text-xs text-gray-400">hace {{ $group->updated_at->diffForHumans() }}</span>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="mt-6 flex justify-center">
        <div class="flex space-x-4">
            <a href="{{ route('groups.show', $group) }}" 
               class="inline-flex items-center px-3 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                👁️ Ver Detalles
            </a>
            <a href="{{ route('groups.statistics', $group) }}" 
               class="inline-flex items-center px-3 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                📊 Estadísticas
            </a>
            <a href="{{ route('groups.index') }}" 
               class="inline-flex items-center px-3 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                📋 Listar Todos
            </a>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Form validation
    document.querySelector('form').addEventListener('submit', function(e) {
        const currentEnrollment = {{ $currentEnrollment }};
        const maxStudents = parseInt(document.getElementById('max_students').value);
        
        if (maxStudents < currentEnrollment) {
            e.preventDefault();
            alert(`No puede reducir la capacidad por debajo de ${currentEnrollment} estudiantes inscritos.`);
            return;
        }
        
        // Confirm major changes
        const originalGradeLevel = {{ $group->grade_level_id }};
        const originalYear = {{ $group->academic_year }};
        const newGradeLevel = parseInt(document.getElementById('grade_level_id').value);
        const newYear = parseInt(document.getElementById('academic_year').value);
        
        if ((originalGradeLevel !== newGradeLevel || originalYear !== newYear) && currentEnrollment > 0) {
            if (!confirm('¿Estás seguro de cambiar el nivel de grado o año académico? Esto puede afectar a los ' + currentEnrollment + ' estudiantes inscritos.')) {
                e.preventDefault();
                return;
            }
        }
    });

    // Update minimum capacity display
    document.getElementById('max_students').addEventListener('input', function() {
        const currentEnrollment = {{ $currentEnrollment }};
        const maxStudents = parseInt(this.value);
        
        if (maxStudents < currentEnrollment) {
            this.style.borderColor = '#dc2626';
        } else {
            this.style.borderColor = '#d1d5db';
        }
    });
</script>
@endpush
@endsection
