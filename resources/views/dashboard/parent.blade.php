@extends('layouts.app')

@section('title', 'Dashboard - Padre de Familia')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Dashboard - Padre de Familia</h1>
        <p class="text-gray-600">Panel de control para padres de familia</p>
    </div>

    <!-- Children Information -->
    @if(isset($childrenData) && count($childrenData) > 0)
        @foreach($childrenData as $childData)
        <div class="bg-white p-6 rounded-lg shadow-md mb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ $childData['child']->full_name }}</h3>
            
            <!-- Child Basic Info -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div>
                    <p class="text-sm font-medium text-gray-600">Grupo</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $childData['enrollment']->group->full_name ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600">Grado</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $childData['enrollment']->group->gradeLevel->grade_name ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600">Año Académico</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $childData['enrollment']->group->academic_year ?? 'N/A' }}</p>
                </div>
            </div>

            <!-- Child Statistics -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-blue-50 p-4 rounded-lg">
                    <div class="flex items-center">
                        <div class="p-2 rounded-full bg-blue-100 mr-3">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Total Materias</p>
                            <p class="text-xl font-bold text-gray-900">{{ count($childData['subjects']) }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-green-50 p-4 rounded-lg">
                    <div class="flex items-center">
                        <div class="p-2 rounded-full bg-green-100 mr-3">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Actividades Próximas</p>
                            <p class="text-xl font-bold text-gray-900">{{ count($childData['upcomingActivities']) }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-yellow-50 p-4 rounded-lg">
                    <div class="flex items-center">
                        <div class="p-2 rounded-full bg-yellow-100 mr-3">
                            <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Promedio General</p>
                            <p class="text-xl font-bold text-gray-900">{{ $childData['averageGrade'] ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Upcoming Activities for this child -->
            @if(count($childData['upcomingActivities']) > 0)
            <div class="mb-6">
                <h4 class="text-md font-semibold text-gray-800 mb-3">Próximas Actividades</h4>
                <div class="space-y-2">
                    @foreach($childData['upcomingActivities'] as $activity)
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
            </div>
            @endif

            <!-- Recent Grades for this child -->
            @if(isset($childData['recentGrades']) && count($childData['recentGrades']) > 0)
            <div>
                <h4 class="text-md font-semibold text-gray-800 mb-3">Calificaciones Recientes</h4>
                <div class="space-y-2">
                    @foreach($childData['recentGrades'] as $grade)
                    <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                        <div class="flex-1">
                            <p class="font-medium text-gray-900">{{ $grade->activity->title ?? 'N/A' }}</p>
                            <p class="text-sm text-gray-600">{{ $grade->activity->subject->name ?? 'N/A' }}</p>
                        </div>
                        <span class="text-lg font-bold text-green-600">{{ $grade->grade }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
        @endforeach
    @else
        <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                    <path d="M34 40h10v-4a6 6 0 00-10.712-3.714M34 40H14m20 0v-4a9.971 9.971 0 00-.712-3.714M14 40H4v-4a6 6 0 0110.712-3.714M14 40v-4a9.971 9.971 0 01.712-3.714M34 40v-4a9.971 9.971 0 00-.712-3.714m0 0A9.971 9.971 0 0124 34a9.971 9.971 0 00-9.288 6.286m18.576 0A9.964 9.964 0 0124 32a9.964 9.964 0 00-9.288 2.286M15 22a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0zm-6-2a3 3 0 11-6 0 3 3 0 016 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No hay información de estudiantes</h3>
                <p class="mt-1 text-sm text-gray-500">No tienes estudiantes asociados a tu cuenta.</p>
            </div>
        </div>
    @endif
</div>
@endsection
