@extends('layouts.app')

@section('title', 'Asignaciones Académicas')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Asignaciones Académicas</h1>
                <p class="mt-2 text-gray-600">Gestión de asignaciones profesor-materia-grupo para el año académico {{ date('Y') }}</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('assignments.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg inline-flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Nueva Asignación
                </a>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Total Asignaciones</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_assignments'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Profesores Asignados</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['teachers_with_assignments'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Materias Asignadas</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['subjects_assigned'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="p-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Filtros</h3>
        </div>
        <div class="p-4">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label for="teacher_search" class="block text-sm font-medium text-gray-700">Profesor</label>
                    <input type="text" name="teacher_search" id="teacher_search" 
                           value="{{ request('teacher_search') }}" 
                           placeholder="Buscar por nombre..."
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div>
                    <label for="subject_search" class="block text-sm font-medium text-gray-700">Materia</label>
                    <input type="text" name="subject_search" id="subject_search" 
                           value="{{ request('subject_search') }}" 
                           placeholder="Buscar materia..."
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div>
                    <label for="grade_filter" class="block text-sm font-medium text-gray-700">Grado</label>
                    <select name="grade_filter" id="grade_filter" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Todos los grados</option>
                        <!-- Add grade options dynamically -->
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                        Filtrar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Assignments Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-4 py-5 sm:p-6">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Profesor
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Materia
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Grado
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Grupo
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Año Académico
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Acciones
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($assignments as $assignment)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                                <span class="text-sm font-medium text-gray-700">
                                                    {{ $assignment->teacher->initials() }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $assignment->teacher->full_name }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ $assignment->teacher->email }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $assignment->subject->name }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ $assignment->subject->area }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $assignment->subject->academicPlan->gradeLevel->grade_name ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $assignment->subject->academicPlan->gradeLevel->grade_name ?? '' }}{{ $assignment->group->group_letter }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $assignment->academic_year }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <form action="{{ route('assignments.destroy', $assignment) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="text-red-600 hover:text-red-900"
                                                onclick="return confirm('¿Estás seguro de eliminar esta asignación?')">
                                            Eliminar
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                    No hay asignaciones registradas
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $assignments->links() }}
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Acciones Rápidas</h3>
            <div class="space-y-3">
                <a href="{{ route('assignments.create') }}" 
                   class="block w-full text-center bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Crear Nueva Asignación
                </a>
                <a href="#" 
                   class="block w-full text-center bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                    Importar desde Excel
                </a>
                <a href="#" 
                   class="block w-full text-center bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Exportar Reporte
                </a>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Enlaces Rápidos</h3>
            <div class="space-y-2">
                <a href="{{ route('users.by-role', 'teacher') }}" class="block text-blue-600 hover:text-blue-800">
                    → Ver todos los profesores
                </a>
                <a href="{{ route('subjects.index') }}" class="block text-blue-600 hover:text-blue-800">
                    → Gestionar materias
                </a>
                <a href="{{ route('groups.index') }}" class="block text-blue-600 hover:text-blue-800">
                    → Gestionar grupos
                </a>
                <a href="{{ route('academic-plans.index') }}" class="block text-blue-600 hover:text-blue-800">
                    → Planes académicos y períodos
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
