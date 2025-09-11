@extends('layouts.app')

@section('title', 'Dashboard - Profesor')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Dashboard - Profesor</h1>
        <p class="text-gray-600">Panel de control para profesores</p>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 mr-4">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600">Mis Materias</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total_subjects'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 mr-4">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Actividades</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total_activities'] ?? 0 }}</p>
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
                    <p class="text-sm font-medium text-gray-600">Pendientes de Calificar</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['pending_grades'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 mr-4">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600">Estudiantes de Grupo</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total_students'] ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Upcoming Activities -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Próximas Actividades</h3>
            @if(isset($upcomingActivities) && $upcomingActivities->count() > 0)
                <div class="space-y-3">
                    @foreach($upcomingActivities as $activity)
                    <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                        <div class="flex-1">
                            <p class="font-medium text-gray-900">{{ $activity->title }}</p>
                            <p class="text-sm text-gray-600">{{ $activity->subject->name ?? 'N/A' }}</p>
                        </div>
                        <span class="text-sm text-gray-500">
                            @if($activity->due_date)
                                {{ \Carbon\Carbon::parse($activity->due_date)->format('d/m/Y') }}
                            @endif
                        </span>
                    </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500">No hay actividades próximas</p>
            @endif
        </div>

        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Actividades por Calificar</h3>
            @if(isset($activitiesNeedingGrades) && $activitiesNeedingGrades->count() > 0)
                <div class="space-y-3">
                    @foreach($activitiesNeedingGrades as $activity)
                    <div class="flex items-center p-3 bg-red-50 rounded-lg">
                        <div class="flex-1">
                            <p class="font-medium text-gray-900">{{ $activity->title }}</p>
                            <p class="text-sm text-gray-600">{{ $activity->subject->name ?? 'N/A' }}</p>
                        </div>
                        <span class="text-sm text-red-600">
                            @if($activity->due_date)
                                {{ \Carbon\Carbon::parse($activity->due_date)->format('d/m/Y') }}
                            @endif
                        </span>
                    </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500">No hay actividades por calificar</p>
            @endif
        </div>
    </div>

    <!-- My Subjects -->
    @if(isset($subjects) && $subjects->count() > 0)
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Mis Materias</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($subjects as $subject)
            <div class="p-4 bg-gray-50 rounded-lg">
                <h4 class="font-medium text-gray-900">{{ $subject->name }}</h4>
                <p class="text-sm text-gray-600">{{ $subject->code }} - {{ $subject->credits }} créditos</p>
                <p class="text-xs text-gray-500">{{ $subject->area }}</p>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection
