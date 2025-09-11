@extends('layouts.app')

@section('title', 'Dashboard - Estudiante')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Dashboard - Estudiante</h1>
        <p class="text-gray-600">Panel de control para estudiantes</p>
    </div>

    <!-- Student Info -->
    @if(isset($enrollment))
    <div class="bg-white p-6 rounded-lg shadow-md mb-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Mi Información</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <p class="text-sm font-medium text-gray-600">Grupo</p>
                <p class="text-lg font-semibold text-gray-900">{{ $enrollment->group->full_name ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-600">Grado</p>
                <p class="text-lg font-semibold text-gray-900">{{ $enrollment->group->gradeLevel->grade_name ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-600">Año Académico</p>
                <p class="text-lg font-semibold text-gray-900">{{ $enrollment->group->academic_year ?? 'N/A' }}</p>
            </div>
        </div>
    </div>
    @endif

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
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
                    <p class="text-sm font-medium text-gray-600">Actividades Pendientes</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['pending_activities'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 mr-4">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a4 4 0 118 0v4m-4 12V11m0 0l-3-3m3 3l3-3"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600">Préstamos Activos</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['active_loans'] ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Upcoming Activities and Grades -->
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
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Calificaciones Recientes</h3>
            @if(isset($recentGrades) && $recentGrades->count() > 0)
                <div class="space-y-3">
                    @foreach($recentGrades as $grade)
                    <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                        <div class="flex-1">
                            <p class="font-medium text-gray-900">{{ $grade->activity->title ?? 'N/A' }}</p>
                            <p class="text-sm text-gray-600">{{ $grade->activity->subject->name ?? 'N/A' }}</p>
                        </div>
                        <span class="text-lg font-bold text-green-600">{{ $grade->grade }}</span>
                    </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500">No hay calificaciones recientes</p>
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
                <p class="text-xs text-gray-500">Profesor: {{ $subject->teacher->full_name ?? 'N/A' }}</p>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Active Library Loans -->
    @if(isset($activeLoans) && $activeLoans->count() > 0)
    <div class="bg-white p-6 rounded-lg shadow-md mt-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Mis Préstamos de Biblioteca</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Recurso</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha Préstamo</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha Límite</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($activeLoans as $loan)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $loan->resource->title ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ \Carbon\Carbon::parse($loan->loan_date)->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ \Carbon\Carbon::parse($loan->return_date)->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                @if(\Carbon\Carbon::parse($loan->return_date)->isPast()) bg-red-100 text-red-800 
                                @else bg-green-100 text-green-800 @endif">
                                @if(\Carbon\Carbon::parse($loan->return_date)->isPast()) Vencido @else Activo @endif
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>
@endsection
