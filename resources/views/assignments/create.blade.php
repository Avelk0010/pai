@extends('layouts.app')

@section('title', 'Crear Asignación Académica')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Crear Asignación Académica</h1>
                <p class="mt-2 text-gray-600">Asigna un profesor a materias específicas y grupos</p>
            </div>
            <a href="{{ route('assignments.index') }}" 
               class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg">
                ← Volver
            </a>
        </div>
    </div>

    <form action="{{ route('assignments.store') }}" method="POST" class="space-y-8">
        @csrf

        <!-- Teacher Selection -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Seleccionar Profesor</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="teacher_id" class="block text-sm font-medium text-gray-700">Profesor *</label>
                    <select name="teacher_id" id="teacher_id" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Seleccionar profesor...</option>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}" {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                {{ $teacher->full_name }} - {{ $teacher->email }}
                            </option>
                        @endforeach
                    </select>
                    @error('teacher_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Información del Profesor</label>
                    <div id="teacher_info" class="text-sm text-gray-500 bg-gray-50 p-3 rounded">
                        Selecciona un profesor para ver su información
                    </div>
                </div>
            </div>
        </div>

        <!-- Subject and Group Assignments -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Asignaciones por Grado</h3>
            
            @if($subjects->count() > 0)
                <div class="space-y-6">
                    @foreach($subjects as $gradeName => $gradeSubjects)
                        <div class="border border-gray-200 rounded-lg p-4">
                            <h4 class="font-medium text-gray-900 mb-3 flex items-center">
                                <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-sm mr-2">{{ $gradeName }}</span>
                                <span class="text-gray-600 text-sm">({{ $gradeSubjects->count() }} materias)</span>
                            </h4>
                            
                            <div class="space-y-4">
                                @foreach($gradeSubjects as $subject)
                                    <div class="bg-gray-50 p-4 rounded border">
                                        <div class="flex items-center justify-between mb-3">
                                            <div>
                                                <label class="flex items-center">
                                                    <input type="checkbox" 
                                                           name="subject_enabled[{{ $subject->id }}]" 
                                                           value="1"
                                                           class="rounded border-gray-300 text-blue-600 subject-checkbox"
                                                           data-subject-id="{{ $subject->id }}"
                                                           {{ old("subject_enabled.{$subject->id}") ? 'checked' : '' }}>
                                                    <span class="ml-2 font-medium text-gray-900">{{ $subject->name }}</span>
                                                </label>
                                                <p class="text-sm text-gray-500 ml-6">
                                                    {{ $subject->area }} • {{ $subject->credits }} créditos • {{ $subject->hours_per_week }}h/semana
                                                </p>
                                            </div>
                                            <span class="text-xs bg-gray-200 text-gray-600 px-2 py-1 rounded">
                                                {{ $subject->code }}
                                            </span>
                                        </div>
                                        
                                        <!-- Groups selection -->
                                        <div class="ml-6 groups-selection hidden" data-subject-id="{{ $subject->id }}">
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Grupos disponibles para {{ $gradeName }}:</label>
                                            <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                                                @php
                                                    // Only show groups from the same grade as the subject
                                                    $subjectGradeGroups = $groups->get($gradeName, collect());
                                                @endphp
                                                @if($subjectGradeGroups->count() > 0)
                                                    @foreach($subjectGradeGroups as $group)
                                                        <label class="flex items-center text-sm">
                                                            <input type="checkbox" 
                                                                   name="assignments[{{ $subject->id }}][groups][]" 
                                                                   value="{{ $group->id }}"
                                                                   class="rounded border-gray-300 text-blue-600"
                                                                   {{ in_array($group->id, old("assignments.{$subject->id}.groups", [])) ? 'checked' : '' }}>
                                                            <span class="ml-1">{{ $gradeName }}{{ $group->group_letter }}</span>
                                                        </label>
                                                    @endforeach
                                                @else
                                                    <div class="col-span-full text-sm text-gray-500">
                                                        No hay grupos disponibles para {{ $gradeName }}
                                                    </div>
                                                @endif
                                            </div>
                                            
                                            <!-- Quick select buttons -->
                                            <div class="mt-2 flex space-x-2">
                                                <button type="button" 
                                                        class="text-xs bg-blue-100 hover:bg-blue-200 text-blue-800 px-2 py-1 rounded select-all-groups"
                                                        data-subject-id="{{ $subject->id }}">
                                                    Seleccionar todos
                                                </button>
                                                <button type="button" 
                                                        class="text-xs bg-gray-100 hover:bg-gray-200 text-gray-800 px-2 py-1 rounded deselect-all-groups"
                                                        data-subject-id="{{ $subject->id }}">
                                                    Deseleccionar todos
                                                </button>
                                            </div>
                                        </div>
                                        
                                        <!-- Hidden input to store subject_id for selected subjects -->
                                        <input type="hidden" 
                                               name="assignments[{{ $subject->id }}][subject_id]" 
                                               value="{{ $subject->id }}">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8 text-gray-500">
                    <p>No hay materias disponibles.</p>
                    <p class="text-sm">Primero debe crear planes académicos y materias.</p>
                    <a href="{{ route('subjects.create') }}" class="text-blue-600 hover:text-blue-800 text-sm">
                        → Crear nueva materia
                    </a>
                </div>
            @endif
        </div>

        <!-- Summary Section -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Resumen de Asignación</h3>
            <div id="assignment-summary" class="text-sm text-gray-500">
                Selecciona un profesor y materias para ver el resumen
            </div>
        </div>

        <!-- Submit Buttons -->
        <div class="flex justify-end space-x-3">
            <a href="{{ route('assignments.index') }}" 
               class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                Cancelar
            </a>
            <button type="submit" 
                    class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Crear Asignaciones
            </button>
        </div>
    </form>
</div>

<!-- JavaScript for dynamic interactions -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const teacherSelect = document.getElementById('teacher_id');
    const teacherInfo = document.getElementById('teacher_info');
    const subjectCheckboxes = document.querySelectorAll('.subject-checkbox');
    const assignmentSummary = document.getElementById('assignment-summary');

    // Teacher data (you would normally pass this from the controller)
    const teachersData = {!! $teachers->map(function($teacher) {
        return [
            'id' => $teacher->id,
            'name' => $teacher->full_name,
            'email' => $teacher->email,
            'current_assignments' => $teacher->subjectAssignments->count()
        ];
    })->toJson() !!};

    // Handle teacher selection
    teacherSelect.addEventListener('change', function() {
        const teacherId = this.value;
        if (teacherId) {
            const teacher = teachersData.find(t => t.id == teacherId);
            teacherInfo.innerHTML = `
                <div class="space-y-1">
                    <p><strong>Nombre:</strong> ${teacher.name}</p>
                    <p><strong>Email:</strong> ${teacher.email}</p>
                    <p><strong>Asignaciones actuales:</strong> ${teacher.current_assignments}</p>
                </div>
            `;
        } else {
            teacherInfo.innerHTML = 'Selecciona un profesor para ver su información';
        }
        updateSummary();
    });

    // Handle subject checkbox changes
    subjectCheckboxes.forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            const subjectId = this.getAttribute('data-subject-id');
            const groupsDiv = document.querySelector('.groups-selection[data-subject-id="' + subjectId + '"]');
            
            if (this.checked) {
                groupsDiv.classList.remove('hidden');
            } else {
                groupsDiv.classList.add('hidden');
                // Uncheck all groups for this subject
                const groupCheckboxes = groupsDiv.querySelectorAll('input[type="checkbox"]');
                groupCheckboxes.forEach(cb => cb.checked = false);
            }
            updateSummary();
        });
    });

    // Handle select all/deselect all buttons
    document.querySelectorAll('.select-all-groups').forEach(button => {
        button.addEventListener('click', function() {
            const subjectId = this.getAttribute('data-subject-id');
            const groupsDiv = document.querySelector('.groups-selection[data-subject-id="' + subjectId + '"]');
            const checkboxes = groupsDiv.querySelectorAll('input[type="checkbox"]');
            checkboxes.forEach(cb => cb.checked = true);
            updateSummary();
        });
    });

    document.querySelectorAll('.deselect-all-groups').forEach(button => {
        button.addEventListener('click', function() {
            const subjectId = this.getAttribute('data-subject-id');
            const groupsDiv = document.querySelector('.groups-selection[data-subject-id="' + subjectId + '"]');
            const checkboxes = groupsDiv.querySelectorAll('input[type="checkbox"]');
            checkboxes.forEach(cb => cb.checked = false);
            updateSummary();
        });
    });

    // Update summary
    function updateSummary() {
        const selectedTeacher = teacherSelect.options[teacherSelect.selectedIndex]?.text || 'No seleccionado';
        const selectedSubjects = document.querySelectorAll('.subject-checkbox:checked').length;
        let totalGroups = 0;

        document.querySelectorAll('.subject-checkbox:checked').forEach(subjectCheckbox => {
            const subjectId = subjectCheckbox.getAttribute('data-subject-id');
            const groupsDiv = document.querySelector('.groups-selection[data-subject-id="' + subjectId + '"]');
            const selectedGroups = groupsDiv.querySelectorAll('input[type="checkbox"]:checked').length;
            totalGroups += selectedGroups;
        });

        assignmentSummary.innerHTML = `
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <p class="font-medium">Profesor seleccionado:</p>
                    <p class="text-gray-900">${selectedTeacher}</p>
                </div>
                <div>
                    <p class="font-medium">Materias seleccionadas:</p>
                    <p class="text-gray-900">${selectedSubjects}</p>
                </div>
                <div>
                    <p class="font-medium">Total asignaciones:</p>
                    <p class="text-gray-900">${totalGroups}</p>
                </div>
            </div>
        `;
    }

    // Initial summary update
    updateSummary();
});
</script>
@endsection
