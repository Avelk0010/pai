@extends('layouts.app')

@section('title', 'Materias')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">
                    {{ auth()->user()->role === 'teacher' ? 'Mis Materias' : 'Materias' }}
                </h1>
                <p class="mt-2 text-sm text-gray-600">
                    {{ auth()->user()->role === 'teacher' ? 'Materias asignadas a tu cargo' : 'Gestiona las materias del plan académico' }}
                </p>
            </div>
            @if(auth()->user()->role !== 'teacher')
                <a href="{{ route('subjects.create') }}" 
                   class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg inline-flex items-center transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Nueva Materia
                </a>
            @else
                <div class="text-sm text-gray-500 bg-blue-50 px-4 py-2 rounded-lg">
                    📚 {{ $stats['total'] }} {{ $stats['total'] == 1 ? 'materia asignada' : 'materias asignadas' }}
                </div>
            @endif
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white overflow-hidden shadow-sm rounded-lg">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Materias</dt>
                            <dd class="text-lg font-semibold text-gray-900">{{ $stats['total'] }}</dd>
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
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Activas</dt>
                            <dd class="text-lg font-semibold text-gray-900">{{ $stats['active'] }}</dd>
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
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.464 0L4.34 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Inactivas</dt>
                            <dd class="text-lg font-semibold text-gray-900">{{ $stats['inactive'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-sm rounded-lg">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Obligatorias</dt>
                            <dd class="text-lg font-semibold text-gray-900">{{ $stats['mandatory'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Areas Summary -->
    <div class="bg-white rounded-lg shadow-sm mb-8">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Áreas Académicas</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach($areas as $area)
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="text-sm font-medium text-gray-900">{{ $area->area }}</div>
                    <div class="text-2xl font-bold text-indigo-600">{{ $area->count }}</div>
                    <div class="text-xs text-gray-500">materias</div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Subjects Table -->
    <div class="bg-white shadow-sm rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Lista de Materias</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Materia</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Plan Académico</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Área</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Horas/Semana</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Asignaciones</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($subjects as $subject)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div>
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $subject->name }}
                                        @if($subject->is_mandatory)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800 ml-2">
                                                Obligatoria
                                            </span>
                                        @endif
                                    </div>
                                    <div class="text-sm text-gray-500">{{ $subject->code }}</div>
                                    @if($subject->description)
                                        <div class="text-xs text-gray-400 mt-1">{{ Str::limit($subject->description, 50) }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <div>{{ $subject->academicPlan->name }}</div>
                            <div class="text-xs text-gray-500">{{ $subject->academicPlan->gradeLevel->name }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ $subject->area }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $subject->hours_per_week }}h
                            @if($subject->credits)
                                <div class="text-xs text-gray-500">{{ $subject->credits }} créditos</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <div class="flex space-x-4">
                                <div class="text-center">
                                    <div class="text-sm font-medium">{{ $subject->subject_assignments_count }}</div>
                                    <div class="text-xs text-gray-500">Profesores</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-sm font-medium">{{ $subject->activities_count }}</div>
                                    <div class="text-xs text-gray-500">Actividades</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-sm font-medium">{{ $subject->period_grades_count }}</div>
                                    <div class="text-xs text-gray-500">Calificaciones</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if(auth()->user()->role !== 'teacher')
                                <form action="{{ route('subjects.toggle-status', $subject) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" 
                                            class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium transition-colors {{ $subject->status ? 'bg-green-100 text-green-800 hover:bg-green-200' : 'bg-red-100 text-red-800 hover:bg-red-200' }}">
                                        <span class="w-2 h-2 mr-1 rounded-full {{ $subject->status ? 'bg-green-600' : 'bg-red-600' }}"></span>
                                        {{ $subject->status ? 'Activa' : 'Inactiva' }}
                                    </button>
                                </form>
                            @else
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $subject->status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    <span class="w-2 h-2 mr-1 rounded-full {{ $subject->status ? 'bg-green-600' : 'bg-red-600' }}"></span>
                                    {{ $subject->status ? 'Activa' : 'Inactiva' }}
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex space-x-2">
                                <a href="{{ route('subjects.show', $subject) }}" 
                                   class="text-indigo-600 hover:text-indigo-900 p-1 rounded hover:bg-indigo-50" title="Ver">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </a>
                                
                                @if(auth()->user()->role !== 'teacher')
                                    <a href="{{ route('subjects.edit', $subject) }}" 
                                       class="text-yellow-600 hover:text-yellow-900 p-1 rounded hover:bg-yellow-50" title="Editar">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>
                                    <form action="{{ route('subjects.destroy', $subject) }}" method="POST" class="inline-block" 
                                          onsubmit="return confirm('¿Estás seguro de que quieres eliminar esta materia?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="text-red-600 hover:text-red-900 p-1 rounded hover:bg-red-50" title="Eliminar">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </form>
                                @else
                                    <!-- Botón para crear actividades (solo profesores) -->
                                    <a href="{{ route('activities.create') }}?subject_id={{ $subject->id }}" 
                                       class="text-green-600 hover:text-green-900 p-1 rounded hover:bg-green-50" title="Crear Actividad">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                        </svg>
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">No hay materias registradas</h3>
                                <p class="text-gray-500 mb-4">Comienza creando tu primera materia</p>
                                <a href="{{ route('subjects.create') }}" 
                                   class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg">
                                    Nueva Materia
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($subjects->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $subjects->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
