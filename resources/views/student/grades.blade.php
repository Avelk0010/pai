@extends('layouts.app')

@section('title', 'Mis Calificaciones')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Mis Calificaciones</h1>
        <p class="text-gray-600">{{ $enrollment->group->full_name ?? 'N/A' }} - {{ $enrollment->group->gradeLevel->grade_name ?? 'N/A' }}</p>
    </div>

    <!-- Period Grades Summary -->
    @if($periodGrades->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            @foreach($periodGrades as $periodGrade)
                <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-indigo-500">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">{{ $periodGrade->period->name }}</h3>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            {{ $periodGrade->period->status === 'finished' ? 'bg-green-100 text-green-800' : 
                               ($periodGrade->period->status === 'active' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') }}">
                            {{ $periodGrade->period->status === 'finished' ? 'Finalizado' : 
                               ($periodGrade->period->status === 'active' ? 'Activo' : 'Planificado') }}
                        </span>
                    </div>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Promedio General:</span>
                            <span class="text-lg font-bold {{ $periodGrade->average_score >= 70 ? 'text-green-600' : ($periodGrade->average_score >= 60 ? 'text-yellow-600' : 'text-red-600') }}">
                                {{ number_format($periodGrade->average_score, 1) }}
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Actividades:</span>
                            <span class="text-sm font-medium text-gray-900">{{ $periodGrade->total_activities }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Calificadas:</span>
                            <span class="text-sm font-medium text-gray-900">{{ $periodGrade->graded_activities }}</span>
                        </div>
                        @php
                            $completionPercentage = $periodGrade->total_activities > 0 ? ($periodGrade->graded_activities / $periodGrade->total_activities) * 100 : 0;
                        @endphp
                        <div class="w-full bg-gray-200 rounded-full h-2 mt-3">
                            <div class="bg-indigo-600 h-2 rounded-full" style="width: {{ $completionPercentage }}%"></div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <form method="GET" action="{{ route('student.grades') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Materia</label>
                <select name="subject_id" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">Todas las materias</option>
                    @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>
                            {{ $subject->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Período</label>
                <select name="period_id" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">Todos los períodos</option>
                    @foreach($periods as $period)
                        <option value="{{ $period->id }}" {{ request('period_id') == $period->id ? 'selected' : '' }}>
                            {{ $period->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-end space-x-3">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg text-sm">
                    Filtrar
                </button>
                <a href="{{ route('student.grades') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-4 rounded-lg text-sm">
                    Limpiar
                </a>
            </div>
        </form>
    </div>

    <!-- Grades by Subject -->
    @if($gradesData->count() > 0)
        <div class="space-y-6">
            @foreach($gradesData as $subjectData)
                <div class="bg-white rounded-lg shadow-sm">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900">{{ $subjectData['subject']->name }}</h3>
                            <div class="flex items-center space-x-4">
                                <span class="text-sm text-gray-600">
                                    Promedio: 
                                    <span class="font-bold {{ $subjectData['average'] >= 70 ? 'text-green-600' : ($subjectData['average'] >= 60 ? 'text-yellow-600' : 'text-red-600') }}">
                                        {{ number_format($subjectData['average'], 1) }}
                                    </span>
                                </span>
                                <span class="text-sm text-gray-600">
                                    {{ $subjectData['graded_count'] }}/{{ $subjectData['total_count'] }} calificadas
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="p-6">
                        <!-- Activities Table -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Actividad
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Período
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Tipo
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Peso
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Calificación
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Estado
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Fecha límite
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($subjectData['activities'] as $activity)
                                        @php
                                            $studentGrade = $activity->studentGrades->first();
                                            $isOverdue = $activity->due_date && $activity->due_date->isPast() && !$studentGrade;
                                            $isPending = !$studentGrade;
                                        @endphp
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900">{{ $activity->title }}</div>
                                                    @if($activity->description)
                                                        <div class="text-sm text-gray-500">{{ Str::limit($activity->description, 50) }}</div>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $activity->period->name ?? 'N/A' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ ucfirst($activity->activity_type) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $activity->percentage }}%
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($studentGrade)
                                                    <div class="text-sm">
                                                        <span class="font-bold {{ $studentGrade->score >= ($activity->max_score * 0.7) ? 'text-green-600' : ($studentGrade->score >= ($activity->max_score * 0.6) ? 'text-yellow-600' : 'text-red-600') }}">
                                                            {{ number_format($studentGrade->score, 1) }}/{{ $activity->max_score }}
                                                        </span>
                                                        @if($studentGrade->graded_at)
                                                            <div class="text-xs text-gray-500">
                                                                {{ $studentGrade->graded_at->format('d/m/Y') }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                @else
                                                    <span class="text-sm text-gray-400">No calificada</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                    {{ $isOverdue ? 'bg-red-100 text-red-800' : ($isPending ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800') }}">
                                                    {{ $isOverdue ? 'Atrasada' : ($isPending ? 'Pendiente' : 'Completada') }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                @if($activity->due_date)
                                                    <span class="{{ $isOverdue ? 'text-red-600 font-medium' : '' }}">
                                                        {{ $activity->due_date->format('d/m/Y H:i') }}
                                                    </span>
                                                @else
                                                    <span class="text-gray-400">Sin límite</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Subject Summary -->
                        <div class="mt-4 bg-gray-50 rounded-lg p-4">
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-center">
                                <div>
                                    <div class="text-2xl font-bold text-indigo-600">{{ $subjectData['total_count'] }}</div>
                                    <div class="text-sm text-gray-600">Total</div>
                                </div>
                                <div>
                                    <div class="text-2xl font-bold text-green-600">{{ $subjectData['graded_count'] }}</div>
                                    <div class="text-sm text-gray-600">Calificadas</div>
                                </div>
                                <div>
                                    <div class="text-2xl font-bold text-yellow-600">{{ $subjectData['pending_count'] }}</div>
                                    <div class="text-sm text-gray-600">Pendientes</div>
                                </div>
                                <div>
                                    <div class="text-2xl font-bold {{ $subjectData['average'] >= 70 ? 'text-green-600' : ($subjectData['average'] >= 60 ? 'text-yellow-600' : 'text-red-600') }}">
                                        {{ number_format($subjectData['average'], 1) }}
                                    </div>
                                    <div class="text-sm text-gray-600">Promedio</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-white rounded-lg shadow-sm p-12 text-center">
            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
            </svg>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No hay calificaciones</h3>
            <p class="text-gray-500">No se encontraron calificaciones con los filtros seleccionados.</p>
        </div>
    @endif
</div>
@endsection
