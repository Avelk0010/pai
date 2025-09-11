@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Page Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Editar Plan Académico</h1>
        <nav class="flex mt-2" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-4">
                <li><a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-gray-700">Dashboard</a></li>
                <li><span class="text-gray-500">/</span></li>
                <li><a href="{{ route('academic-plans.index') }}" class="text-gray-500 hover:text-gray-700">Planes Académicos</a></li>
                <li><span class="text-gray-500">/</span></li>
                <li><a href="{{ route('academic-plans.show', $academicPlan) }}" class="text-gray-500 hover:text-gray-700">{{ $academicPlan->name }}</a></li>
                <li><span class="text-gray-500">/</span></li>
                <li><span class="text-gray-900">Editar</span></li>
            </ol>
        </nav>
    </div>

    <!-- Current Plan Info -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
            </svg>
        </div>
        <div class="ml-3">
            <h3 class="text-sm font-medium text-blue-800">Editando: {{ $academicPlan->name }}</h3>
            <div class="mt-2 text-sm text-blue-700">
                <p>Este plan tiene <strong>{{ $academicPlan->subjects->count() }}</strong> materias asociadas. 
                Cambiar el grado o año puede afectar la organización curricular.</p>
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white card-shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-medium text-gray-900">📚 Editar Información del Plan</h2>
        </div>
        
        <form action="{{ route('academic-plans.update', $academicPlan) }}" method="POST" class="p-6">
            @csrf
            @method('PATCH')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Name -->
                <div class="md:col-span-2">
                    <label for="name" class="block text-sm font-medium text-gray-700">Nombre del Plan *</label>
                    <input type="text" 
                           name="name" 
                           id="name" 
                           value="{{ old('name', $academicPlan->name) }}"
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
                            <option value="{{ $gradeLevel->id }}" 
                                {{ old('grade_level_id', $academicPlan->grade_level_id) == $gradeLevel->id ? 'selected' : '' }}>
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
                            <option value="{{ $year }}" 
                                {{ old('academic_year', $academicPlan->academic_year) == $year ? 'selected' : '' }}>
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
                            <option value="{{ $i }}" {{ old('periods_count', $academicPlan->periods_count) == $i ? 'selected' : '' }}>
                                {{ $i }} {{ $i == 1 ? 'período' : 'períodos' }}
                            </option>
                        @endfor
                    </select>
                    @error('periods_count')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">
                        @if($academicPlan->periods->count() > 0)
                            Si cambias la cantidad, se recrearán los períodos (se perderán las fechas configuradas)
                        @else
                            Se crearán automáticamente los períodos para este plan académico
                        @endif
                    </p>
                </div>

                <!-- Status -->
                <div class="md:col-span-2">
                    <div class="flex items-center">
                        <input id="status" 
                               name="status" 
                               type="checkbox" 
                               value="1"
                               {{ old('status', $academicPlan->status) ? 'checked' : '' }}
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

            <!-- Warning if has subjects -->
            @if($academicPlan->subjects->count() > 0)
                <div class="mt-6 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-yellow-800">⚠️ Advertencia</h3>
                            <div class="mt-2 text-sm text-yellow-700">
                                <p>Este plan académico tiene <strong>{{ $academicPlan->subjects->count() }} materias</strong> asociadas. 
                                Cambiar el grado o desactivar el plan puede afectar:</p>
                                <ul class="list-disc pl-5 mt-2 space-y-1">
                                    <li>Asignación de profesores a materias</li>
                                    <li>Inscripción de estudiantes</li>
                                    <li>Generación de horarios</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Action Buttons -->
            <div class="flex justify-between mt-6 pt-6 border-t border-gray-200">
                <div class="flex space-x-3">
                    <a href="{{ route('academic-plans.show', $academicPlan) }}" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">
                        👁️ Ver Plan
                    </a>
                    @if($academicPlan->periods->count() > 0)
                        <a href="{{ route('academic-plans.show', $academicPlan) }}#periods-management" 
                           class="inline-flex items-center px-4 py-2 border border-indigo-300 rounded-md shadow-sm bg-indigo-50 text-sm font-medium text-indigo-700 hover:bg-indigo-100">
                            📅 Gestionar Períodos
                        </a>
                    @endif
                    <a href="{{ route('academic-plans.index') }}" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">
                        📋 Volver a Lista
                    </a>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('academic-plans.show', $academicPlan) }}" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">
                        ❌ Cancelar
                    </a>
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md shadow-sm">
                        💾 Guardar Cambios
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Subjects Preview -->
    @if($academicPlan->subjects->count() > 0)
        <div class="bg-white card-shadow rounded-lg mt-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-medium text-gray-900">📖 Materias Actuales (Vista Previa)</h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 max-h-64 overflow-y-auto">
                    @foreach($academicPlan->subjects->take(9) as $subject)
                        <div class="border border-gray-200 rounded-lg p-3">
                            <div class="flex justify-between items-start mb-1">
                                <h4 class="text-sm font-medium text-gray-900 truncate">{{ $subject->name }}</h4>
                                @if($subject->status)
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">✅</span>
                                @else
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">⏸️</span>
                                @endif
                            </div>
                            <div class="text-xs text-gray-500">
                                <div>📝 {{ $subject->code }}</div>
                                <div>🎯 {{ $subject->area }}</div>
                            </div>
                        </div>
                    @endforeach
                    @if($academicPlan->subjects->count() > 9)
                        <div class="border border-gray-200 rounded-lg p-3 flex items-center justify-center bg-gray-50">
                            <span class="text-sm text-gray-500">+ {{ $academicPlan->subjects->count() - 9 }} más</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
