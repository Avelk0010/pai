@extends('layouts.app')

@section('title', 'Mis Materias')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">
                📖 Mis Materias
            </h1>
            @if(isset($enrollment))
            <p class="text-gray-600 mt-1">
                👥 {{ $enrollment->group->name }} - {{ $academicPlan->name }}
            </p>
            @endif
        </div>
    </div>

    @if(isset($message))
    <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 px-4 py-3 rounded-lg mb-6">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="ml-3">
                <p class="font-medium">{{ $message }}</p>
            </div>
        </div>
    </div>
    @else
    <!-- Academic Summary -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-blue-500 text-white rounded-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">Total Materias</p>
                    <p class="text-3xl font-bold">{{ count($subjects) }}</p>
                </div>
                <div class="text-blue-200">
                    📚
                </div>
            </div>
        </div>
        
        <div class="bg-green-500 text-white rounded-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium">Con Calificaciones</p>
                    <p class="text-3xl font-bold">{{ collect($subjects)->where('graded_periods', '>', 0)->count() }}</p>
                </div>
                <div class="text-green-200">
                    ✅
                </div>
            </div>
        </div>
        
        <div class="bg-purple-500 text-white rounded-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm font-medium">Promedio General</p>
                    <p class="text-3xl font-bold">
                        @php
                            $allGrades = collect($subjects)->whereNotNull('overall_grade')->pluck('overall_grade');
                            $generalAverage = $allGrades->count() > 0 ? round($allGrades->avg(), 2) : 0;
                        @endphp
                        {{ $generalAverage > 0 ? $generalAverage : '--' }}
                    </p>
                </div>
                <div class="text-purple-200">
                    📊
                </div>
            </div>
        </div>
        
        <div class="bg-orange-500 text-white rounded-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-orange-100 text-sm font-medium">Períodos Totales</p>
                    <p class="text-3xl font-bold">{{ collect($subjects)->first()['total_periods'] ?? 0 }}</p>
                </div>
                <div class="text-orange-200">
                    📅
                </div>
            </div>
        </div>
    </div>

    <!-- Subjects List -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">
                📋 Materias por Área
            </h3>
        </div>
        
        @if(count($subjects) > 0)
        <div class="divide-y divide-gray-200">
            @foreach($subjects as $index => $subjectData)
            @php
                $subject = $subjectData['subject'];
                $gradedPeriods = $subjectData['graded_periods'];
                $totalPeriods = $subjectData['total_periods'];
                $overallGrade = $subjectData['overall_grade'];
            @endphp
            <div class="p-6">
                <!-- Subject Header -->
                <div class="flex items-center justify-between cursor-pointer" 
                     onclick="toggleSubject('subject-{{ $index }}')">
                    <div class="flex-1">
                        <h4 class="text-lg font-semibold text-gray-900">
                            {{ $subject->name }}
                        </h4>
                        <div class="flex items-center space-x-2 mt-1">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                {{ $subject->area }}
                            </span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ $gradedPeriods }}/{{ $totalPeriods }} períodos
                            </span>
                            @if($overallGrade)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                @if($overallGrade >= 4.0) bg-green-100 text-green-800
                                @elseif($overallGrade >= 3.0) bg-yellow-100 text-yellow-800
                                @else bg-red-100 text-red-800
                                @endif">
                                Promedio: {{ $overallGrade }}
                            </span>
                            @endif
                        </div>
                    </div>
                    <div class="ml-4">
                        <svg id="icon-subject-{{ $index }}" class="w-5 h-5 text-gray-500 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                </div>

                <!-- Subject Details (Hidden by default) -->
                <div id="subject-{{ $index }}" class="hidden mt-6">
                    <!-- Subject Info -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div>
                            <p class="text-sm text-gray-600"><strong>Código:</strong> {{ $subject->code }}</p>
                            <p class="text-sm text-gray-600"><strong>Créditos:</strong> {{ $subject->credits ?? 'N/A' }}</p>
                            <p class="text-sm text-gray-600"><strong>Horas semanales:</strong> {{ $subject->hours_per_week }}</p>
                        </div>
                        <div>
                            @if($subject->description)
                            <p class="text-sm text-gray-600"><strong>Descripción:</strong> {{ $subject->description }}</p>
                            @endif
                        </div>
                    </div>

                    <!-- Periods -->
                    <div class="space-y-4">
                        @foreach($subjectData['periods'] as $periodIndex => $periodData)
                        @php
                            $period = $periodData['period'];
                            $activities = $periodData['activities'];
                            $finalGrade = $periodData['final_grade'];
                            $periodStatus = $periodData['status'];
                        @endphp
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="flex items-center justify-between mb-4">
                                <h5 class="text-lg font-medium text-gray-900">
                                    📅 {{ $period->name }}
                                </h5>
                                <div class="flex items-center space-x-2">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($periodStatus === 'finished') bg-green-100 text-green-800
                                        @elseif($periodStatus === 'active') bg-blue-100 text-blue-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        @switch($periodStatus)
                                            @case('finished')
                                                Finalizado
                                                @break
                                            @case('active')
                                                Activo
                                                @break
                                            @case('upcoming')
                                                Próximo
                                                @break
                                            @default
                                                {{ ucfirst($periodStatus) }}
                                        @endswitch
                                    </span>
                                    @if($finalGrade)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($finalGrade >= 4.0) bg-green-100 text-green-800
                                        @elseif($finalGrade >= 3.0) bg-yellow-100 text-yellow-800
                                        @else bg-red-100 text-red-800
                                        @endif">
                                        Nota Final: {{ $finalGrade }}
                                    </span>
                                    @endif
                                </div>
                            </div>

                            @if(count($activities) > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actividad</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">%</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Calificación</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Puntuación</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($activities as $activityData)
                                        @php
                                            $activity = $activityData['activity'];
                                            $grade = $activityData['grade'];
                                            $maxScore = $activityData['max_score'];
                                            $percentage = $activityData['percentage'];
                                            $activityStatus = $activityData['status'];
                                        @endphp
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-4 py-2">
                                                <div>
                                                    <p class="text-sm font-medium text-gray-900">{{ $activity->title }}</p>
                                                    @if($activity->description)
                                                    <p class="text-xs text-gray-500">{{ Str::limit($activity->description, 50) }}</p>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-4 py-2">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                                    @switch($activity->activity_type)
                                                        @case('exam')
                                                            Examen
                                                            @break
                                                        @case('quiz')
                                                            Quiz
                                                            @break
                                                        @case('assignment')
                                                            Tarea
                                                            @break
                                                        @case('project')
                                                            Proyecto
                                                            @break
                                                        @case('participation')
                                                            Participación
                                                            @break
                                                        @default
                                                            {{ ucfirst($activity->activity_type) }}
                                                    @endswitch
                                                </span>
                                            </td>
                                            <td class="px-4 py-2">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                    @if($activityStatus === 'finished') bg-green-100 text-green-800
                                                    @elseif($activityStatus === 'published') bg-blue-100 text-blue-800
                                                    @else bg-gray-100 text-gray-800
                                                    @endif">
                                                    @switch($activityStatus)
                                                        @case('finished')
                                                            Finalizada
                                                            @break
                                                        @case('published')
                                                            Publicada
                                                            @break
                                                        @case('draft')
                                                            Borrador
                                                            @break
                                                        @default
                                                            {{ ucfirst($activityStatus) }}
                                                    @endswitch
                                                </span>
                                            </td>
                                            <td class="px-4 py-2 text-sm text-gray-900">{{ $percentage }}%</td>
                                            <td class="px-4 py-2">
                                                @if($grade !== null)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                    @if($grade >= 4.0) bg-green-100 text-green-800
                                                    @elseif($grade >= 3.0) bg-yellow-100 text-yellow-800
                                                    @else bg-red-100 text-red-800
                                                    @endif">
                                                    {{ $grade }}
                                                </span>
                                                @else
                                                <span class="text-sm text-gray-400">--</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-2 text-sm text-gray-900">
                                                @if($grade !== null)
                                                    {{ $grade }}/{{ $maxScore }}
                                                @else
                                                    --/{{ $maxScore }}
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @else
                            <div class="text-center py-8">
                                <div class="text-gray-400 text-4xl mb-2">📭</div>
                                <p class="text-gray-500">No hay actividades para este período</p>
                            </div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-12">
            <div class="text-gray-400 text-6xl mb-4">📚</div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No hay materias disponibles</h3>
            <p class="text-gray-500">No se encontraron materias para tu plan académico actual.</p>
        </div>
        @endif
    </div>
    @endif
</div>

@push('scripts')
<script>
function toggleSubject(elementId) {
    const element = document.getElementById(elementId);
    const icon = document.getElementById('icon-' + elementId);
    
    if (element.classList.contains('hidden')) {
        element.classList.remove('hidden');
        icon.classList.add('rotate-180');
    } else {
        element.classList.add('hidden');
        icon.classList.remove('rotate-180');
    }
}
</script>
@endpush
@endsection
