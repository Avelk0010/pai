@extends('layouts.app')

@section('title', 'Asignaciones de ' . $user->full_name)

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Asignaciones de {{ $user->full_name }}</h1>
                <p class="mt-2 text-gray-600">{{ $user->email }} • Año académico {{ date('Y') }}</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('assignments.index') }}" 
                   class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg">
                    ← Volver a Asignaciones
                </a>
                <a href="{{ route('assignments.create') }}?teacher_id={{ $user->id }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg">
                    Agregar Asignación
                </a>
            </div>
        </div>
    </div>

    <!-- Teacher Info Card -->
    <div class="bg-white rounded-lg shadow p-6 mb-8">
        <div class="flex items-center space-x-4">
            <div class="flex-shrink-0 h-16 w-16">
                <div class="h-16 w-16 rounded-full bg-blue-100 flex items-center justify-center">
                    <span class="text-xl font-medium text-blue-800">
                        {{ $user->initials() }}
                    </span>
                </div>
            </div>
            <div class="flex-1">
                <h3 class="text-lg font-medium text-gray-900">{{ $user->full_name }}</h3>
                <p class="text-sm text-gray-500">{{ $user->email }}</p>
                <p class="text-sm text-gray-500">Profesor • Documento: {{ $user->document ?? 'No especificado' }}</p>
            </div>
            <div class="text-right">
                <div class="text-2xl font-bold text-blue-600">
                    {{ $assignments->flatten()->count() }}
                </div>
                <div class="text-sm text-gray-500">
                    Total Asignaciones
                </div>
            </div>
        </div>
    </div>

    @if($assignments->count() > 0)
        <!-- Assignments by Grade -->
        <div class="space-y-6">
            @foreach($assignments as $gradeName => $gradeAssignments)
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900 flex items-center">
                            <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm mr-3">
                                {{ $gradeName }}
                            </span>
                            <span class="text-gray-600 text-base">
                                {{ $gradeAssignments->count() }} asignaciones
                            </span>
                        </h3>
                    </div>

                    <div class="p-6">
                        @php
                            $assignmentsBySubject = $gradeAssignments->groupBy('subject.name');
                        @endphp

                        <div class="space-y-4">
                            @foreach($assignmentsBySubject as $subjectName => $subjectAssignments)
                                <div class="border border-gray-200 rounded-lg p-4">
                                    <div class="flex items-center justify-between mb-3">
                                        <div>
                                            <h4 class="font-medium text-gray-900">{{ $subjectName }}</h4>
                                            <p class="text-sm text-gray-500">
                                                {{ $subjectAssignments->first()->subject->area }} • 
                                                {{ $subjectAssignments->first()->subject->credits }} créditos • 
                                                {{ $subjectAssignments->first()->subject->hours_per_week }}h/semana
                                            </p>
                                        </div>
                                        <span class="text-xs bg-gray-200 text-gray-600 px-2 py-1 rounded">
                                            {{ $subjectAssignments->first()->subject->code }}
                                        </span>
                                    </div>

                                    <!-- Groups for this subject -->
                                    <div class="ml-4">
                                        <p class="text-sm font-medium text-gray-700 mb-2">Grupos asignados:</p>
                                        <div class="flex flex-wrap gap-2">
                                            @foreach($subjectAssignments as $assignment)
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-green-100 text-green-800">
                                                    {{ $gradeName }}{{ $assignment->group->group_letter }}
                                                    <form action="{{ route('assignments.destroy', $assignment) }}" method="POST" class="inline ml-2">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" 
                                                                class="text-green-600 hover:text-red-600 ml-1"
                                                                title="Eliminar asignación"
                                                                onclick="return confirm('¿Eliminar asignación de {{ $gradeName }}{{ $assignment->group->group_letter }}?')">
                                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                            </svg>
                                                        </button>
                                                    </form>
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>

                                    <!-- Quick actions for this subject -->
                                    <div class="mt-3 pt-3 border-t border-gray-100">
                                        <div class="flex items-center justify-between text-sm">
                                            <div class="flex items-center text-gray-500">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                                </svg>
                                                {{ $subjectAssignments->count() }} grupos asignados
                                            </div>
                                            <div class="flex space-x-3">
                                                <a href="{{ route('subjects.show', $subjectAssignments->first()->subject) }}" 
                                                   class="text-blue-600 hover:text-blue-800">
                                                    Ver materia
                                                </a>
                                                <a href="{{ route('activities.by-subject', $subjectAssignments->first()->subject) }}" 
                                                   class="text-green-600 hover:text-green-800">
                                                    Ver actividades
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Update Assignments Form -->
        <div class="mt-8 bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Actualizar Asignaciones</h3>
            <p class="text-sm text-gray-600 mb-4">
                Modifica las asignaciones de este profesor. Los cambios reemplazarán todas las asignaciones actuales.
            </p>
            
            <form action="{{ route('assignments.update-teacher', $user) }}" method="POST">
                @csrf
                @method('PATCH')
                
                <div class="flex space-x-3">
                    <a href="{{ route('assignments.create') }}?teacher_id={{ $user->id }}" 
                       class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Agregar Más Asignaciones
                    </a>
                    <button type="button" 
                            onclick="confirm('¿Estás seguro de eliminar todas las asignaciones de este profesor?') && document.getElementById('clear-form').submit()"
                            class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                        Eliminar Todas las Asignaciones
                    </button>
                </div>
            </form>

            <!-- Hidden form for clearing all assignments -->
            <form id="clear-form" action="{{ route('assignments.update-teacher', $user) }}" method="POST" class="hidden">
                @csrf
                @method('PATCH')
                <!-- Empty assignments array will clear all assignments -->
            </form>
        </div>

    @else
        <!-- No Assignments State -->
        <div class="bg-white rounded-lg shadow p-12 text-center">
            <div class="mx-auto h-24 w-24 text-gray-400 mb-4">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-full h-full">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Sin Asignaciones</h3>
            <p class="text-gray-600 mb-6">
                {{ $user->full_name }} no tiene asignaciones académicas para el año {{ date('Y') }}.
            </p>
            <a href="{{ route('assignments.create') }}?teacher_id={{ $user->id }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg inline-flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Crear Primera Asignación
            </a>
        </div>
    @endif

    <!-- Statistics -->
    <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Materias Diferentes</p>
                    <p class="text-2xl font-semibold text-gray-900">
                        {{ $assignments->flatten()->groupBy('subject_id')->count() }}
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Grupos Asignados</p>
                    <p class="text-2xl font-semibold text-gray-900">
                        {{ $assignments->flatten()->groupBy('group_id')->count() }}
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Grados Diferentes</p>
                    <p class="text-2xl font-semibold text-gray-900">
                        {{ $assignments->count() }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
