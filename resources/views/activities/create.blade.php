@extends('layouts.app')

@section('title', isset($activity) ? 'Editar Actividad' : 'Nueva Actividad')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">{{ isset($activity) ? 'Editar Actividad' : 'Nueva Actividad' }}</h1>
        <p class="mt-2 text-sm text-gray-600">
            {{ isset($activity) ? 'Modifica la información de la actividad' : 'Completa la información para crear una nueva actividad' }}
        </p>
    </div>

    <div class="bg-white shadow-sm rounded-lg">
        <form method="POST" action="{{ isset($activity) ? route('activities.update', $activity) : route('activities.store') }}" 
              class="p-6 space-y-6">
            @csrf
            @if(isset($activity))
                @method('PUT')
            @endif

            <!-- Basic Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Title -->
                <div class="md:col-span-2">
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                        Título de la Actividad *
                    </label>
                    <input type="text" 
                           id="title" 
                           name="title" 
                           value="{{ old('title', $activity->title ?? '') }}"
                           class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('title') border-red-300 @enderror"
                           placeholder="Ej: Examen de Matemáticas - Unidad 3"
                           required>
                    @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Activity Type -->
                <div>
                    <label for="activity_type" class="block text-sm font-medium text-gray-700 mb-2">
                        Tipo de Actividad *
                    </label>
                    <select id="activity_type" 
                            name="activity_type" 
                            class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('activity_type') border-red-300 @enderror"
                            required>
                        <option value="">Seleccionar tipo</option>
                        <option value="exam" {{ old('activity_type', $activity->activity_type ?? '') === 'exam' ? 'selected' : '' }}>
                            Examen
                        </option>
                        <option value="quiz" {{ old('activity_type', $activity->activity_type ?? '') === 'quiz' ? 'selected' : '' }}>
                            Quiz
                        </option>
                        <option value="assignment" {{ old('activity_type', $activity->activity_type ?? '') === 'assignment' ? 'selected' : '' }}>
                            Tarea
                        </option>
                        <option value="project" {{ old('activity_type', $activity->activity_type ?? '') === 'project' ? 'selected' : '' }}>
                            Proyecto
                        </option>
                        <option value="participation" {{ old('activity_type', $activity->activity_type ?? '') === 'participation' ? 'selected' : '' }}>
                            Participación
                        </option>
                    </select>
                    @error('activity_type')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                        Estado *
                    </label>
                    <select id="status" 
                            name="status" 
                            class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('status') border-red-300 @enderror"
                            required>
                        <option value="draft" {{ old('status', $activity->status ?? 'draft') === 'draft' ? 'selected' : '' }}>
                            Borrador
                        </option>
                        <option value="published" {{ old('status', $activity->status ?? '') === 'published' ? 'selected' : '' }}>
                            Publicada
                        </option>
                        <option value="finished" {{ old('status', $activity->status ?? '') === 'finished' ? 'selected' : '' }}>
                            Finalizada
                        </option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Academic Assignment -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Subject -->
                <div>
                    <label for="subject_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Materia *
                    </label>
                    <select id="subject_id" 
                            name="subject_id" 
                            class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('subject_id') border-red-300 @enderror"
                            required>
                        <option value="">Seleccionar materia</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}" {{ old('subject_id', $selectedSubject ?? $activity->subject_id ?? '') == $subject->id ? 'selected' : '' }}>
                                {{ $subject->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('subject_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Groups (Multiple Selection) -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Grupos * 
                        <span class="text-xs text-gray-500">(Selecciona uno o más grupos)</span>
                    </label>
                    
                    @if($groups->count() === 0)
                        <div class="p-3 bg-yellow-50 border border-yellow-200 rounded-md">
                            <div class="text-sm text-yellow-800">
                                ⚠️ No tienes grupos asignados. Contacta al administrador.
                            </div>
                        </div>
                    @else
                        <div class="space-y-3 max-h-48 overflow-y-auto border border-gray-300 rounded-md p-3 @error('group_ids') border-red-300 @enderror">
                            @php
                                $groupedByGrade = $groups->groupBy(function($group) {
                                    return $group->gradeLevel->grade_name ?? 'Sin Grado';
                                });
                            @endphp
                            
                            @foreach($groupedByGrade as $gradeName => $gradeGroups)
                                <div class="border-b border-gray-200 pb-2 last:border-b-0">
                                    <h4 class="text-sm font-medium text-gray-900 mb-2">{{ $gradeName }}</h4>
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                                        @foreach($gradeGroups as $group)
                                            <label class="flex items-center text-sm">
                                                <input type="checkbox" 
                                                       name="group_ids[]" 
                                                       value="{{ $group->id }}"
                                                       class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                                       {{ in_array($group->id, old('group_ids', [])) ? 'checked' : '' }}>
                                                <span class="ml-2">{{ $group->group_letter }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <!-- Quick select buttons -->
                        <div class="mt-2 flex flex-wrap gap-2">
                            <button type="button" 
                                    onclick="selectAllGroups(true)"
                                    class="text-xs bg-indigo-100 hover:bg-indigo-200 text-indigo-800 px-2 py-1 rounded">
                                Seleccionar todos
                            </button>
                            <button type="button" 
                                    onclick="selectAllGroups(false)"
                                    class="text-xs bg-gray-100 hover:bg-gray-200 text-gray-800 px-2 py-1 rounded">
                                Deseleccionar todos
                            </button>
                        </div>
                    @endif
                    @error('group_ids')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Period -->
                <div>
                    <label for="period_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Período *
                    </label>
                    @if($periods->count() === 0)
                        <div class="p-3 bg-yellow-50 border border-yellow-200 rounded-md">
                            <div class="text-sm text-yellow-800">
                                ⚠️ No hay períodos activos. Solo se pueden crear actividades en períodos activos.
                            </div>
                        </div>
                    @else
                        <select id="period_id" 
                                name="period_id" 
                                class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('period_id') border-red-300 @enderror"
                                required>
                            <option value="">Seleccionar período</option>
                            @foreach($periods as $period)
                                <option value="{{ $period->id }}" {{ old('period_id', $activity->period_id ?? '') == $period->id ? 'selected' : '' }}>
                                    {{ $period->name }} - {{ $period->academicPlan->name }} (Activo)
                                </option>
                            @endforeach
                        </select>
                        <div class="mt-1 text-xs text-green-600">
                            ℹ️ Solo se muestran períodos activos
                        </div>
                    @endif
                    @error('period_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Scoring Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Percentage -->
                <div>
                    <label for="percentage" class="block text-sm font-medium text-gray-700 mb-2">
                        Porcentaje *
                    </label>
                    <div class="relative">
                        <input type="number" 
                               id="percentage" 
                               name="percentage" 
                               value="{{ old('percentage', $activity->percentage ?? '') }}"
                               class="block w-full px-3 py-2 pr-8 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('percentage') border-red-300 @enderror"
                               placeholder="20"
                               min="1"
                               max="100"
                               required>
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 text-sm">%</span>
                        </div>
                    </div>
                    <!-- Available percentage info -->
                    <div id="percentage-info" class="mt-2 text-sm text-gray-600 hidden">
                        <div class="flex items-center space-x-2">
                            <svg class="w-4 h-4 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                            </svg>
                            <span id="percentage-text">Selecciona período y materia para ver porcentaje disponible</span>
                        </div>
                    </div>
                    <p class="mt-1 text-xs text-gray-500">Peso de la actividad en la calificación final del período</p>
                    @error('percentage')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Max Score -->
                <div>
                    <label for="max_score" class="block text-sm font-medium text-gray-700 mb-2">
                        Puntaje Máximo *
                    </label>
                    <input type="number" 
                           id="max_score" 
                           name="max_score" 
                           value="{{ old('max_score', $activity->max_score ?? '5.0') }}"
                           class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('max_score') border-red-300 @enderror"
                           placeholder="5.0"
                           min="1.0"
                           max="5.0"
                           step="0.1"
                           required>
                    <p class="mt-1 text-xs text-gray-500">Calificación máxima posible (escala colombiana 1.0 - 5.0)</p>
                    @error('max_score')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Due Date -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="due_date" class="block text-sm font-medium text-gray-700 mb-2">
                        Fecha Límite
                    </label>
                    <input type="date" 
                           id="due_date" 
                           name="due_date" 
                           value="{{ old('due_date', isset($activity->due_date) ? $activity->due_date->format('Y-m-d') : '') }}"
                           class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('due_date') border-red-300 @enderror">
                    @error('due_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="due_time" class="block text-sm font-medium text-gray-700 mb-2">
                        Hora Límite
                    </label>
                    <input type="time" 
                           id="due_time" 
                           name="due_time" 
                           value="{{ old('due_time', isset($activity->due_date) ? $activity->due_date->format('H:i') : '') }}"
                           class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('due_time') border-red-300 @enderror">
                    @error('due_time')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                    Descripción
                </label>
                <textarea id="description" 
                          name="description" 
                          rows="4"
                          class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('description') border-red-300 @enderror"
                          placeholder="Describe los objetivos, instrucciones y criterios de evaluación de la actividad...">{{ old('description', $activity->description ?? '') }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Instructions -->
            <div>
                <label for="instructions" class="block text-sm font-medium text-gray-700 mb-2">
                    Instrucciones Específicas
                </label>
                <textarea id="instructions" 
                          name="instructions" 
                          rows="3"
                          class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('instructions') border-red-300 @enderror"
                          placeholder="Instrucciones detalladas para los estudiantes...">{{ old('instructions', $activity->instructions ?? '') }}</textarea>
                @error('instructions')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                <a href="{{ route('activities.index') }}" 
                   class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Cancelar
                </a>

                @if(isset($activity) && $activity->status === 'draft')
                <button type="submit" 
                        name="action" 
                        value="save_and_publish"
                        class="bg-green-600 py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    Guardar y Publicar
                </button>
                @endif

                <button type="submit" 
                        name="action" 
                        value="save"
                        class="bg-indigo-600 py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    {{ isset($activity) ? 'Actualizar' : 'Crear' }} Actividad
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-suggest for common activities
    const titleInput = document.getElementById('title');
    const typeSelect = document.getElementById('activity_type');
    const percentageInput = document.getElementById('percentage');

    const suggestions = {
        'exam': { percentage: 40, titles: ['Examen Parcial', 'Examen Final', 'Prueba Saber'] },
        'quiz': { percentage: 15, titles: ['Quiz Rápido', 'Evaluación Corta', 'Prueba de Conocimiento'] },
        'assignment': { percentage: 20, titles: ['Tarea Práctica', 'Ejercicios', 'Actividad en Casa'] },
        'project': { percentage: 35, titles: ['Proyecto Final', 'Investigación', 'Trabajo Grupal'] },
        'participation': { percentage: 10, titles: ['Participación en Clase', 'Intervenciones', 'Asistencia'] }
    };

    typeSelect.addEventListener('change', function() {
        const type = this.value;
        if (suggestions[type]) {
            // Suggest percentage
            if (!percentageInput.value) {
                percentageInput.value = suggestions[type].percentage;
            }
            
            // Add title suggestions as placeholder
            if (!titleInput.value) {
                titleInput.placeholder = suggestions[type].titles[0];
            }
        }
    });

    // Date validation
    const dueDateInput = document.getElementById('due_date');
    dueDateInput.addEventListener('change', function() {
        const selectedDate = new Date(this.value);
        const today = new Date();
        today.setHours(0, 0, 0, 0);

        if (selectedDate < today) {
            alert('La fecha límite no puede ser anterior a hoy.');
            this.value = '';
        }
    });

    // Percentage validation
    percentageInput.addEventListener('input', function() {
        const value = parseFloat(this.value);
        if (value > 100) {
            this.value = 100;
        } else if (value < 0) {
            this.value = 0;
        }
    });
});

// Group selection functions
function selectAllGroups(selectAll) {
    const checkboxes = document.querySelectorAll('input[name="group_ids[]"]');
    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAll;
    });
    updateGroupSelection();
}

function updateGroupSelection() {
    const checkedGroups = document.querySelectorAll('input[name="group_ids[]"]:checked');
    const totalGroups = document.querySelectorAll('input[name="group_ids[]"]').length;
    
    // Update button text or add visual feedback if needed
    console.log(`${checkedGroups.length} de ${totalGroups} grupos seleccionados`);
}

// Available percentage checker
function updateAvailablePercentage() {
    const periodId = document.getElementById('period_id').value;
    const subjectId = document.getElementById('subject_id').value;
    const percentageInfo = document.getElementById('percentage-info');
    const percentageText = document.getElementById('percentage-text');
    
    if (periodId && subjectId) {
        const activityId = '{{ isset($activity) ? $activity->id : "" }}';
        const url = '{{ route("activities.available-percentage") }}';
        const params = new URLSearchParams({
            period_id: periodId,
            subject_id: subjectId
        });
        
        if (activityId) {
            params.append('activity_id', activityId);
        }
        
        fetch(`${url}?${params}`)
            .then(response => response.json())
            .then(data => {
                percentageInfo.classList.remove('hidden');
                if (data.available_percentage > 0) {
                    percentageText.textContent = `Porcentaje disponible: ${data.available_percentage}% (${data.used_percentage}% usado)`;
                    percentageText.className = 'text-green-600 font-medium';
                } else {
                    percentageText.textContent = `Sin porcentaje disponible (${data.used_percentage}% usado)`;
                    percentageText.className = 'text-red-600 font-medium';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                percentageInfo.classList.add('hidden');
            });
    } else {
        percentageInfo.classList.add('hidden');
    }
}

// Add event listeners to checkboxes when they change
document.addEventListener('DOMContentLoaded', function() {
    const groupCheckboxes = document.querySelectorAll('input[name="group_ids[]"]');
    groupCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateGroupSelection);
    });
    
    // Add event listeners for percentage checker
    const periodSelect = document.getElementById('period_id');
    const subjectSelect = document.getElementById('subject_id');
    
    if (periodSelect && subjectSelect) {
        periodSelect.addEventListener('change', updateAvailablePercentage);
        subjectSelect.addEventListener('change', updateAvailablePercentage);
        
        // Check on page load if both fields have values
        if (periodSelect.value && subjectSelect.value) {
            updateAvailablePercentage();
        }
    }
});
</script>
@endpush
@endsection
