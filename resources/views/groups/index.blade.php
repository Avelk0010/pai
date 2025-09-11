@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Page Header -->
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Grupos</h1>
                <p class="text-gray-600 mt-1">Gestión de grupos por niveles de grado</p>
            </div>
            <a href="{{ route('groups.create') }}" 
               class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md shadow-sm">
                ➕ Nuevo Grupo
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <!-- Total Groups -->
        <div class="bg-white card-shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 rounded-full bg-blue-100 text-blue-800 font-bold text-xl flex items-center justify-center">
                    👥
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Total Grupos</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</p>
                </div>
            </div>
        </div>

        <!-- Active Groups -->
        <div class="bg-white card-shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 rounded-full bg-green-100 text-green-800 font-bold text-xl flex items-center justify-center">
                    ✅
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Grupos Activos</p>
                    <p class="text-2xl font-bold text-green-600">{{ $stats['active'] }}</p>
                </div>
            </div>
        </div>

        <!-- Inactive Groups -->
        <div class="bg-white card-shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 rounded-full bg-red-100 text-red-800 font-bold text-xl flex items-center justify-center">
                    ❌
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Grupos Inactivos</p>
                    <p class="text-2xl font-bold text-red-600">{{ $stats['inactive'] }}</p>
                </div>
            </div>
        </div>

        <!-- Current Year -->
        <div class="bg-white card-shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 rounded-full bg-purple-100 text-purple-800 font-bold text-xl flex items-center justify-center">
                    📅
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Año Actual</p>
                    <p class="text-2xl font-bold text-purple-600">{{ $stats['current_year'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white card-shadow rounded-lg p-4 mb-6">
        <form method="GET" action="{{ route('groups.index') }}" class="flex flex-wrap items-end gap-4">
            <div class="flex-1 min-w-48">
                <label for="search" class="block text-sm font-medium text-gray-700">Buscar</label>
                <input type="text" 
                       name="search" 
                       id="search"
                       value="{{ request('search') }}"
                       placeholder="Buscar por letra de grupo, año o nivel..."
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>

            <div class="min-w-40">
                <label for="academic_year" class="block text-sm font-medium text-gray-700">Año Académico</label>
                <select name="academic_year" 
                        id="academic_year"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Todos los años</option>
                    @for($year = date('Y'); $year >= date('Y') - 5; $year--)
                        <option value="{{ $year }}" {{ request('academic_year') == $year ? 'selected' : '' }}>
                            {{ $year }}
                        </option>
                    @endfor
                </select>
            </div>

            <div class="min-w-32">
                <label for="status" class="block text-sm font-medium text-gray-700">Estado</label>
                <select name="status" 
                        id="status"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Todos</option>
                    <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Activos</option>
                    <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactivos</option>
                </select>
            </div>

            <div class="flex space-x-2">
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md">
                    🔍 Filtrar
                </button>
                <a href="{{ route('groups.index') }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">
                    🔄 Limpiar
                </a>
            </div>
        </form>
    </div>

    <!-- Groups Table -->
    <div class="bg-white card-shadow rounded-lg overflow-hidden">
        @if($groups->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Grupo
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Nivel de Grado
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Año Académico
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Estudiantes
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Estado
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Acciones
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($groups as $group)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-12 h-12 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 text-white font-bold text-lg flex items-center justify-center">
                                        {{ $group->group_letter }}
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $group->full_name }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            Capacidad: {{ $group->max_students }} estudiantes
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-800 font-medium text-sm flex items-center justify-center">
                                        {{ $group->gradeLevel->grade_number }}
                                    </div>
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $group->gradeLevel->grade_name }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            Grado {{ $group->gradeLevel->grade_number }}°
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $group->academic_year }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $group->enrollments_count }}/{{ $group->max_students }}
                                    </div>
                                    @if($group->enrollments_count > 0)
                                        <div class="ml-2 w-16 bg-gray-200 rounded-full h-2">
                                            <div class="bg-blue-600 h-2 rounded-full" 
                                                 style="width: {{ min(($group->enrollments_count / $group->max_students) * 100, 100) }}%"></div>
                                        </div>
                                        <span class="ml-2 text-xs text-gray-500">
                                            {{ round(($group->enrollments_count / $group->max_students) * 100) }}%
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $group->status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $group->status ? '🟢 Activo' : '🔴 Inactivo' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('groups.show', $group) }}" 
                                       class="text-blue-600 hover:text-blue-900"
                                       title="Ver detalles">
                                        👁️
                                    </a>
                                    <a href="{{ route('groups.edit', $group) }}" 
                                       class="text-indigo-600 hover:text-indigo-900"
                                       title="Editar">
                                        ✏️
                                    </a>
                                    <form action="{{ route('groups.toggle-status', $group) }}" 
                                          method="POST" 
                                          class="inline"
                                          onsubmit="return confirm('¿Cambiar el estado de este grupo?')">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" 
                                                class="text-yellow-600 hover:text-yellow-900"
                                                title="{{ $group->status ? 'Desactivar' : 'Activar' }}">
                                            {{ $group->status ? '⏸️' : '▶️' }}
                                        </button>
                                    </form>
                                    @if($group->enrollments_count == 0)
                                        <form action="{{ route('groups.destroy', $group) }}" 
                                              method="POST" 
                                              class="inline"
                                              onsubmit="return confirm('¿Estás seguro de eliminar este grupo? Esta acción no se puede deshacer.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="text-red-600 hover:text-red-900"
                                                    title="Eliminar">
                                                🗑️
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($groups->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $groups->links() }}
                </div>
            @endif
        @else
            <div class="text-center py-12">
                <div class="text-6xl mb-4">👥</div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No hay grupos registrados</h3>
                <p class="text-gray-500 mb-4">Comienza creando el primer grupo del sistema</p>
                <a href="{{ route('groups.create') }}" 
                   class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md">
                    ➕ Crear Primer Grupo
                </a>
            </div>
        @endif
    </div>

    <!-- Quick Stats -->
    <div class="mt-6 bg-gray-50 rounded-lg p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">📊 Resumen por Nivel</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @php
                $levelStats = $groups->groupBy('gradeLevel.grade_name');
            @endphp
            @forelse($levelStats->take(6) as $level => $levelGroups)
                <div class="bg-white rounded-lg p-4 border border-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <h4 class="font-medium text-gray-900">{{ $level }}</h4>
                            <p class="text-sm text-gray-500">{{ $levelGroups->count() }} grupos</p>
                        </div>
                        <div class="text-2xl">
                            @if($levelGroups->first()->gradeLevel->grade_number <= 5)
                                🎒
                            @elseif($levelGroups->first()->gradeLevel->grade_number <= 9)
                                📚
                            @else
                                🎓
                            @endif
                        </div>
                    </div>
                    <div class="mt-2 text-xs text-gray-500">
                        {{ $levelGroups->sum('enrollments_count') }} estudiantes total
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center text-gray-500">
                    No hay datos suficientes para mostrar estadísticas
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
