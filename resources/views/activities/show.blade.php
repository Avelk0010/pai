@extends('layouts.app')

@section('title', 'Actividad - ' . $activity->title)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex justify-between items-start">
            <div class="flex-1">
                <div class="flex items-center mb-2">
                    <h1 class="text-2xl font-bold text-gray-900 mr-4">{{ $activity->title }}</h1>
                    
                    <!-- Status Badge -->
                    @if($activity->status === 'published')
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            <span class="w-2 h-2 mr-1 bg-green-600 rounded-full"></span>
                            Publicada
                        </span>
                    @elseif($activity->status === 'draft')
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                            <span class="w-2 h-2 mr-1 bg-yellow-600 rounded-full"></span>
                            Borrador
                        </span>
                    @else
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                            <span class="w-2 h-2 mr-1 bg-gray-600 rounded-full"></span>
                            Finalizada
                        </span>
                    @endif

                    <!-- Due Date Warning -->
                    @if($activity->due_date && $activity->due_date->isPast() && $activity->status === 'published')
                        <span class="ml-2 inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-red-100 text-red-800">
                            Vencida
                        </span>
                    @endif
                </div>
                
                <p class="text-sm text-gray-600">
                    {{ $activity->subject->name }} • 
                    @foreach($activity->groups as $group)
                        {{ $group->name }}@if(!$loop->last), @endif
                    @endforeach
                     • {{ $activity->period->name ?? 'Sin período' }}
                </p>
            </div>

            <div class="flex space-x-2">
                <a href="{{ route('activities.edit', $activity) }}" 
                   class="bg-yellow-600 hover:bg-yellow-700 text-white font-medium py-2 px-4 rounded-lg inline-flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Editar
                </a>

                <form action="{{ route('activities.toggle-status', $activity) }}" method="POST" class="inline-block">
                    @csrf
                    @method('PATCH')
                    <button type="submit" 
                            class="bg-{{ $activity->status === 'published' ? 'yellow' : 'green' }}-600 hover:bg-{{ $activity->status === 'published' ? 'yellow' : 'green' }}-700 text-white font-medium py-2 px-4 rounded-lg inline-flex items-center">
                        @if($activity->status === 'published')
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Marcar como Borrador
                        @else
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Publicar
                        @endif
                    </button>
                </form>

                <a href="{{ route('activities.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded-lg inline-flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Volver
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Activity Information -->
            <div class="bg-white rounded-lg shadow-sm">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Información de la Actividad</h3>
                </div>
                <div class="p-6 space-y-6">
                    <!-- Description -->
                    @if($activity->description)
                    <div>
                        <h4 class="text-sm font-medium text-gray-900 mb-2">Descripción</h4>
                        <div class="text-sm text-gray-700 whitespace-pre-wrap bg-gray-50 rounded-lg p-4">{{ $activity->description }}</div>
                    </div>
                    @endif

                    <!-- Instructions -->
                    @if($activity->instructions)
                    <div>
                        <h4 class="text-sm font-medium text-gray-900 mb-2">Instrucciones</h4>
                        <div class="text-sm text-gray-700 whitespace-pre-wrap bg-blue-50 rounded-lg p-4 border-l-4 border-blue-400">{{ $activity->instructions }}</div>
                    </div>
                    @endif

                    <!-- Activity Details Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Tipo de Actividad</dt>
                                <dd class="mt-1 text-sm text-gray-900 capitalize">
                                    @php
                                        $typeNames = [
                                            'exam' => 'Examen',
                                            'quiz' => 'Quiz',
                                            'assignment' => 'Tarea',
                                            'project' => 'Proyecto',
                                            'participation' => 'Participación'
                                        ];
                                    @endphp
                                    {{ $typeNames[$activity->activity_type] ?? $activity->activity_type }}
                                </dd>
                            </div>

                            <div>
                                <dt class="text-sm font-medium text-gray-500">Porcentaje</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $activity->percentage }}% del período</dd>
                            </div>

                            <div>
                                <dt class="text-sm font-medium text-gray-500">Puntaje Máximo</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $activity->max_score }} puntos</dd>
                            </div>
                        </div>

                        <div class="space-y-4">
                            @if($activity->due_date)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Fecha Límite</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    {{ $activity->due_date->format('d/m/Y H:i') }}
                                    @if($activity->due_date->isPast())
                                        <span class="ml-2 text-red-600 font-medium">(Vencida)</span>
                                    @else
                                        <span class="ml-2 text-green-600 font-medium">({{ $activity->due_date->diffForHumans() }})</span>
                                    @endif
                                </dd>
                            </div>
                            @endif

                            <div>
                                <dt class="text-sm font-medium text-gray-500">Creada</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $activity->created_at->format('d/m/Y H:i') }}</dd>
                            </div>

                            <div>
                                <dt class="text-sm font-medium text-gray-500">Última Actualización</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $activity->updated_at->format('d/m/Y H:i') }}</dd>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Student Grades -->
            <div class="bg-white rounded-lg shadow-sm">
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <h3 class="text-lg font-medium text-gray-900">Calificaciones de Estudiantes</h3>
                    @if($activity->status === 'published' && $students->count() > 0)
                    <button type="button" 
                            id="bulk-grade-btn"
                            class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg text-sm">
                        Calificar en Lote
                    </button>
                    @endif
                </div>
                
                @if($students->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estudiante</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Calificación</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($students as $student)
                            @php
                                $grade = $student->studentGrades->where('activity_id', $activity->id)->first();
                            @endphp
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center mr-3">
                                            <span class="text-xs font-medium text-gray-700">
                                                {{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}
                                            </span>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $student->first_name }} {{ $student->last_name }}
                                            </div>
                                            <div class="text-xs text-gray-500">{{ $student->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($grade)
                                        <div class="text-sm">
                                            <span class="font-medium text-gray-900">{{ number_format($grade->score, 1) }}</span>
                                            <span class="text-gray-500">/ {{ $activity->max_score }}</span>
                                        </div>
                                        @if($grade->feedback)
                                            <div class="text-xs text-gray-500 mt-1">{{ Str::limit($grade->feedback, 30) }}</div>
                                        @endif
                                    @else
                                        <span class="text-gray-400 text-sm">Sin calificar</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($grade)
                                        @if($grade->score >= 3.0)
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                Aprobado
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                Reprobado
                                            </span>
                                        @endif
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            Pendiente
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $grade ? $grade->created_at->format('d/m/Y') : '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    @if($activity->status === 'published')
                                    <button type="button" 
                                            onclick="openGradeModal({{ $student->id }}, '{{ $student->first_name }} {{ $student->last_name }}', {{ $grade ? $grade->score : 'null' }}, '{{ $grade ? $grade->feedback : '' }}')"
                                            class="text-indigo-600 hover:text-indigo-900">
                                        {{ $grade ? 'Editar' : 'Calificar' }}
                                    </button>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="p-8 text-center">
                    <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No hay estudiantes</h3>
                    <p class="text-gray-500">El grupo no tiene estudiantes inscritos</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-8">
            <!-- Quick Stats -->
            <div class="bg-white rounded-lg shadow-sm">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Estadísticas</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Estudiantes totales</span>
                        <span class="text-sm font-medium text-gray-900">{{ $students->count() }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Calificados</span>
                        <span class="text-sm font-medium text-gray-900">{{ $gradedCount }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Pendientes</span>
                        <span class="text-sm font-medium text-gray-900">{{ $students->count() - $gradedCount }}</span>
                    </div>
                    @if($gradedCount > 0)
                    <div class="pt-4 border-t border-gray-200">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Promedio</span>
                            <span class="text-sm font-medium text-gray-900">{{ number_format($averageGrade, 1) }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Aprobados</span>
                            <span class="text-sm font-medium text-green-600">{{ $passedCount }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Reprobados</span>
                            <span class="text-sm font-medium text-red-600">{{ $failedCount }}</span>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Academic Context -->
            <div class="bg-white rounded-lg shadow-sm">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Contexto Académico</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Materia</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $activity->subject->name }}</dd>
                        @if($activity->subject->description)
                            <dd class="mt-1 text-xs text-gray-500">{{ $activity->subject->description }}</dd>
                        @endif
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Grupos</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            @foreach($activity->groups as $group)
                                <div>{{ $group->name }}</div>
                                <div class="text-xs text-gray-500">{{ $group->gradeLevel->name ?? '' }}</div>
                                @if(!$loop->last)<hr class="my-1">@endif
                            @endforeach
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500">Período</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $activity->period->name ?? 'Sin período' }}</dd>
                        @if($activity->period)
                            <dd class="mt-1 text-xs text-gray-500">
                                {{ $activity->period->academic_year }} 
                                @if($activity->period->status === 'active')
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800 ml-1">
                                        Activo
                                    </span>
                                @endif
                            </dd>
                        @endif
                    </div>

                    @if($activity->subject->teacher)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Docente</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            {{ $activity->subject->teacher->first_name }} {{ $activity->subject->teacher->last_name }}
                        </dd>
                        <dd class="mt-1 text-xs text-gray-500">{{ $activity->subject->teacher->email }}</dd>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Grade Modal -->
<div id="grade-modal" class="fixed inset-0 z-50 overflow-y-auto hidden" style="background-color: rgba(0, 0, 0, 0.5);">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 transition-opacity" aria-hidden="true" onclick="closeGradeModal()">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>

        <!-- Modal content -->
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full relative z-10">
            <form id="grade-form" method="POST">
                @csrf
                @method('PUT')
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-indigo-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                Calificar Estudiante
                            </h3>
                            <div class="mt-4 space-y-4">
                                <div>
                                    <label for="grade-score" class="block text-sm font-medium text-gray-700">
                                        Calificación (1.0 - {{ $activity->max_score }})
                                    </label>
                                    <input type="number" 
                                           id="grade-score" 
                                           name="score" 
                                           min="1.0" 
                                           max="{{ $activity->max_score }}" 
                                           step="0.1" 
                                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                           required>
                                </div>
                                
                                <div>
                                    <label for="grade-feedback" class="block text-sm font-medium text-gray-700">
                                        Retroalimentación (opcional)
                                    </label>
                                    <textarea id="grade-feedback" 
                                              name="feedback" 
                                              rows="3"
                                              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                              placeholder="Comentarios sobre el desempeño del estudiante..."></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" 
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Guardar Calificación
                    </button>
                    <button type="button" 
                            onclick="closeGradeModal()"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
let currentStudentId = null;

function openGradeModal(studentId, studentName, currentScore, currentFeedback) {
    currentStudentId = studentId;
    document.getElementById('modal-title').textContent = `Calificar a ${studentName}`;
    document.getElementById('grade-score').value = currentScore || '';
    document.getElementById('grade-feedback').value = currentFeedback || '';
    document.getElementById('grade-form').action = `/activities/{{ $activity->id }}/grade/${studentId}`;
    
    const modal = document.getElementById('grade-modal');
    modal.classList.remove('hidden');
    
    // Focus on the score input
    setTimeout(() => {
        document.getElementById('grade-score').focus();
    }, 100);
}

function closeGradeModal() {
    const modal = document.getElementById('grade-modal');
    modal.classList.add('hidden');
    currentStudentId = null;
}

// Handle form submission
document.getElementById('grade-form').addEventListener('submit', function(e) {
    const score = document.getElementById('grade-score').value;
    const maxScore = {{ $activity->max_score }};
    
    if (score < 1.0 || score > maxScore) {
        e.preventDefault();
        alert(`La calificación debe estar entre 1.0 y ${maxScore}`);
        return false;
    }
});

// Close modal when pressing Escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeGradeModal();
    }
});

// Prevent modal from closing when clicking on modal content
document.querySelector('#grade-modal .inline-block').addEventListener('click', function(e) {
    e.stopPropagation();
});

// Bulk grading functionality
document.getElementById('bulk-grade-btn')?.addEventListener('click', function() {
    const defaultScore = prompt('Ingresa la calificación por defecto (1.0 - {{ $activity->max_score }}):');
    if (defaultScore && defaultScore >= 1.0 && defaultScore <= {{ $activity->max_score }}) {
        if (confirm('¿Estás seguro de que quieres asignar esta calificación a todos los estudiantes sin calificar?')) {
            // This would need to be implemented in the backend
            console.log('Bulk grading not implemented yet');
        }
    }
});
</script>
@endpush
@endsection
