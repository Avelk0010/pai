@extends('layouts.app')

@section('title', $subject->name)

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <a href="{{ route('subjects.index') }}" 
                   class="text-gray-500 hover:text-gray-700 mr-4">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $subject->name }}</h1>
                    <div class="flex items-center mt-2 space-x-4">
                        <span class="text-sm text-gray-600">{{ $subject->code }}</span>
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            {{ $subject->area }}
                        </span>
                        @if($subject->is_mandatory)
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                Obligatoria
                            </span>
                        @endif
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $subject->status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            <span class="w-2 h-2 mr-1 rounded-full {{ $subject->status ? 'bg-green-600' : 'bg-red-600' }}"></span>
                            {{ $subject->status ? 'Activa' : 'Inactiva' }}
                        </span>
                    </div>
                </div>
            </div>
            <div class="flex space-x-3">
                @if(auth()->user()->role !== 'teacher')
                    <form action="{{ route('subjects.toggle-status', $subject) }}" method="POST" class="inline-block">
                        @csrf
                        @method('PATCH')
                        <button type="submit" 
                                class="bg-{{ $subject->status ? 'red' : 'green' }}-600 hover:bg-{{ $subject->status ? 'red' : 'green' }}-700 text-white font-medium py-2 px-4 rounded-lg">
                            {{ $subject->status ? 'Desactivar' : 'Activar' }}
                        </button>
                    </form>
                    <a href="{{ route('subjects.edit', $subject) }}" 
                       class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg">
                        Editar
                    </a>
                @else
                    <!-- Acciones para profesores -->
                    <a href="{{ route('activities.create') }}?subject_id={{ $subject->id }}" 
                       class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg inline-flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Nueva Actividad
                    </a>
                    <a href="{{ route('activities.index') }}?subject_id={{ $subject->id }}" 
                       class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg inline-flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        Ver Actividades
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white overflow-hidden shadow-sm rounded-lg">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Asignaciones</dt>
                            <dd class="text-lg font-semibold text-gray-900">{{ $stats['assignments'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-sm rounded-lg">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Actividades</dt>
                            <dd class="text-lg font-semibold text-gray-900">{{ $stats['activities'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-sm rounded-lg">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Calificaciones</dt>
                            <dd class="text-lg font-semibold text-gray-900">{{ $stats['period_grades'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-sm rounded-lg">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Horas/Semana</dt>
                            <dd class="text-lg font-semibold text-gray-900">{{ $stats['average_hours'] }}h</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Basic Information -->
            <div class="bg-white shadow-sm rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Información General</h3>
                </div>
                <div class="p-6 space-y-6">
                    @if($subject->description)
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 mb-2">Descripción</h4>
                        <p class="text-sm text-gray-900">{{ $subject->description }}</p>
                    </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 mb-2">Plan Académico</h4>
                            <div class="text-sm text-gray-900">
                                <div>{{ $subject->academicPlan->name }}</div>
                                <div class="text-xs text-gray-500">{{ $subject->academicPlan->gradeLevel->name }}</div>
                            </div>
                        </div>

                        <div>
                            <h4 class="text-sm font-medium text-gray-500 mb-2">Carga Académica</h4>
                            <div class="text-sm text-gray-900">
                                <div>{{ $subject->hours_per_week }} horas por semana</div>
                                @if($subject->credits)
                                    <div class="text-xs text-gray-500">{{ $subject->credits }} créditos</div>
                                @endif
                            </div>
                        </div>
                    </div>

                    @if($subject->prerequisites)
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 mb-2">Prerrequisitos</h4>
                        <p class="text-sm text-gray-900">{{ $subject->prerequisites }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Curriculum Content -->
            @if($subject->curriculum_content || $subject->objectives || $subject->methodology || $subject->evaluation_criteria)
            <div class="bg-white shadow-sm rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Contenido Curricular</h3>
                </div>
                <div class="p-6 space-y-6">
                    @if($subject->curriculum_content)
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 mb-2">Contenido Curricular</h4>
                        <div class="text-sm text-gray-900 whitespace-pre-line">{{ $subject->curriculum_content }}</div>
                    </div>
                    @endif

                    @if($subject->objectives)
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 mb-2">Objetivos</h4>
                        <div class="text-sm text-gray-900 whitespace-pre-line">{{ $subject->objectives }}</div>
                    </div>
                    @endif

                    @if($subject->methodology)
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 mb-2">Metodología</h4>
                        <div class="text-sm text-gray-900 whitespace-pre-line">{{ $subject->methodology }}</div>
                    </div>
                    @endif

                    @if($subject->evaluation_criteria)
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 mb-2">Criterios de Evaluación</h4>
                        <div class="text-sm text-gray-900 whitespace-pre-line">{{ $subject->evaluation_criteria }}</div>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Teacher Assignments -->
            @if($subject->subjectAssignments->count() > 0)
            <div class="bg-white shadow-sm rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Profesores Asignados</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @foreach($subject->subjectAssignments as $assignment)
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $assignment->teacher->full_name }}</div>
                                    <div class="text-xs text-gray-500">{{ $assignment->teacher->email }}</div>
                                </div>
                            </div>
                            <div class="text-sm text-gray-500">
                                Asignado el {{ $assignment->created_at->format('d/m/Y') }}
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Recent Activities -->
            @if($subject->activities->count() > 0)
            <div class="bg-white shadow-sm rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Actividades Recientes</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @foreach($subject->activities->take(5) as $activity)
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div>
                                <div class="text-sm font-medium text-gray-900">{{ $activity->title }}</div>
                                <div class="text-xs text-gray-500">
                                    Vence: {{ \Carbon\Carbon::parse($activity->due_date)->format('d/m/Y') }}
                                </div>
                            </div>
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $activity->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ ucfirst($activity->status) }}
                            </span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-8">
            <!-- Topics -->
            @if($subject->topics && count($subject->topics) > 0)
            <div class="bg-white shadow-sm rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Temas</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-2">
                        @foreach($subject->topics as $topic)
                        @if(trim($topic))
                        <div class="flex items-center">
                            <div class="w-2 h-2 bg-indigo-600 rounded-full mr-3"></div>
                            <span class="text-sm text-gray-900">{{ $topic }}</span>
                        </div>
                        @endif
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Resources -->
            @if($subject->resources && count($subject->resources) > 0)
            <div class="bg-white shadow-sm rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Recursos</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-2">
                        @foreach($subject->resources as $resource)
                        @if(trim($resource))
                        <div class="flex items-center">
                            <div class="w-2 h-2 bg-green-600 rounded-full mr-3"></div>
                            <span class="text-sm text-gray-900">{{ $resource }}</span>
                        </div>
                        @endif
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Quick Actions -->
            <div class="bg-white shadow-sm rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Acciones Rápidas</h3>
                </div>
                <div class="p-6 space-y-3">
                    <a href="#" class="block w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded">
                        Ver Actividades
                    </a>
                    <a href="#" class="block w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded">
                        Ver Calificaciones
                    </a>
                    <a href="#" class="block w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded">
                        Asignar Profesor
                    </a>
                    <a href="{{ route('subjects.edit', $subject) }}" class="block w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded">
                        Editar Materia
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
