@extends('layouts.app')

@section('title', 'Mis Actividades')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Mis Actividades</h1>
        <p class="text-gray-600">{{ $enrollment->group->full_name ?? 'N/A' }} - {{ $enrollment->group->gradeLevel->grade_name ?? 'N/A' }}</p>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 mr-4">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600">Total</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 mr-4">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600">Pendientes</p>
                    <p class="text-2xl font-bold text-yellow-600">{{ $stats['pending'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 mr-4">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600">Completadas</p>
                    <p class="text-2xl font-bold text-green-600">{{ $stats['completed'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-red-100 mr-4">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600">Atrasadas</p>
                    <p class="text-2xl font-bold text-red-600">{{ $stats['overdue'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <form method="GET" action="{{ route('student.activities') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
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
                <label class="block text-sm font-medium text-gray-700 mb-2">Estado</label>
                <select name="status" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">Todos los estados</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pendientes</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completadas</option>
                    <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>Atrasadas</option>
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
                <a href="{{ route('student.activities') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-4 rounded-lg text-sm">
                    Limpiar
                </a>
            </div>
        </form>
    </div>

    <!-- Activities Grid -->
    @if($activities->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
            @foreach($activities as $activity)
                @php
                    $studentGrade = $activity->studentGrades->first();
                    $isOverdue = $activity->due_date && $activity->due_date->isPast() && !$studentGrade;
                    $isPending = !$studentGrade;
                @endphp
                <div class="bg-white rounded-lg shadow-sm border-l-4 {{ $isOverdue ? 'border-red-500' : ($isPending ? 'border-yellow-500' : 'border-green-500') }} hover:shadow-md transition-shadow">
                    <div class="p-6">
                        <!-- Activity Header -->
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex-1">
                                <h3 class="font-semibold text-gray-900 text-lg mb-1">{{ $activity->title }}</h3>
                                <p class="text-sm text-indigo-600 font-medium">{{ $activity->subject->name ?? 'N/A' }}</p>
                            </div>
                            <span class="ml-3 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $isOverdue ? 'bg-red-100 text-red-800' : ($isPending ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800') }}">
                                {{ $isOverdue ? 'Atrasada' : ($isPending ? 'Pendiente' : 'Completada') }}
                            </span>
                        </div>

                        <!-- Activity Info -->
                        <div class="space-y-2 mb-4">
                            <div class="flex items-center text-sm text-gray-600">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a.997.997 0 01-1.414 0l-7-7A1.997 1.997 0 013 12V7a4 4 0 014-4z"></path>
                                </svg>
                                {{ ucfirst($activity->activity_type) }} - {{ $activity->percentage }}%
                            </div>
                            @if($activity->due_date)
                                <div class="flex items-center text-sm {{ $isOverdue ? 'text-red-600' : 'text-gray-600' }}">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a4 4 0 118 0v4m-4 12V11m0 0l-3-3m3 3l3-3"></path>
                                    </svg>
                                    Límite: {{ $activity->due_date->format('d/m/Y H:i') }}
                                </div>
                            @endif
                            <div class="flex items-center text-sm text-gray-600">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                {{ $activity->teacher->full_name ?? 'N/A' }}
                            </div>
                        </div>

                        <!-- Grade Display -->
                        @if($studentGrade)
                            <div class="bg-green-50 rounded-lg p-3 mb-4">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-green-800">Calificación:</span>
                                    <span class="text-lg font-bold text-green-600">{{ number_format($studentGrade->score, 1) }}/{{ $activity->max_score }}</span>
                                </div>
                                @if($studentGrade->feedback)
                                    <div class="mt-2">
                                        <p class="text-sm text-green-700">{{ $studentGrade->feedback }}</p>
                                    </div>
                                @endif
                                <div class="text-xs text-green-600 mt-1">
                                    Calificada el {{ $studentGrade->graded_at->format('d/m/Y H:i') }}
                                </div>
                            </div>
                        @endif

                        <!-- Actions -->
                        <div class="flex justify-between items-center">
                            <a href="{{ route('student.activity-detail', $activity) }}" 
                               class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                                Ver detalles →
                            </a>
                            <span class="text-xs text-gray-500">{{ $activity->period->name ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="flex justify-center">
            {{ $activities->appends(request()->query())->links() }}
        </div>
    @else
        <div class="bg-white rounded-lg shadow-sm p-12 text-center">
            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No hay actividades</h3>
            <p class="text-gray-500">No se encontraron actividades con los filtros seleccionados.</p>
        </div>
    @endif
</div>
@endsection
