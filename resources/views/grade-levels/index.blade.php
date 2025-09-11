@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Page Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Niveles de Grado</h1>
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-4">
                    <li><a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-gray-700">Dashboard</a></li>
                    <li><span class="text-gray-500">/</span></li>
                    <li><span class="text-gray-900">Niveles de Grado</span></li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('grade-levels.create') }}" 
           class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md shadow-sm">
            ➕ Nuevo Grado
        </a>
    </div>

    <!-- Content Card -->
    <div class="bg-white card-shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-medium text-gray-900">🎓 Lista de Niveles de Grado</h2>
        </div>

        <div class="p-6">
            @if($gradeLevels->count() > 0)
                <!-- Table -->
                <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 rounded-lg">
                    <table class="min-w-full divide-y divide-gray-300">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-16">
                                    Número
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Nombre del Grado
                                </th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-24">
                                    Grupos
                                </th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-24">
                                    Planes
                                </th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-24">
                                    Estado
                                </th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-48">
                                    Acciones
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($gradeLevels as $gradeLevel)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center justify-center w-10 h-10 rounded-full bg-blue-100 text-blue-800 font-bold text-lg">
                                            {{ $gradeLevel->grade_number }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $gradeLevel->grade_name }}</div>
                                            <div class="text-sm text-gray-500">Grado {{ $gradeLevel->grade_number }}°</div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            {{ $gradeLevel->groups_count }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                            {{ $gradeLevel->academic_plans_count }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        @if($gradeLevel->status)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                ✅ Activo
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                ⏸️ Inactivo
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <div class="btn-group flex justify-center space-x-1">
                                            <!-- Ver -->
                                            <a href="{{ route('grade-levels.show', $gradeLevel) }}" 
                                               class="inline-flex items-center p-2 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-700 hover:bg-gray-50"
                                               title="Ver detalles">
                                                👁️
                                            </a>
                                            
                                            <!-- Editar -->
                                            <a href="{{ route('grade-levels.edit', $gradeLevel) }}" 
                                               class="inline-flex items-center p-2 border border-yellow-300 rounded-md shadow-sm bg-yellow-50 text-sm font-medium text-yellow-700 hover:bg-yellow-100"
                                               title="Editar">
                                                ✏️
                                            </a>
                                            
                                            <!-- Toggle Estado -->
                                            <form action="{{ route('grade-levels.toggle-status', $gradeLevel) }}" method="POST" class="inline">
                                                @csrf @method('PATCH')
                                                <button type="submit" 
                                                        class="inline-flex items-center p-2 border rounded-md shadow-sm text-sm font-medium
                                                               {{ $gradeLevel->status ? 'border-gray-300 bg-gray-50 text-gray-700 hover:bg-gray-100' : 'border-green-300 bg-green-50 text-green-700 hover:bg-green-100' }}"
                                                        title="{{ $gradeLevel->status ? 'Desactivar' : 'Activar' }}">
                                                    {{ $gradeLevel->status ? '⏸️' : '▶️' }}
                                                </button>
                                            </form>
                                            
                                            <!-- Eliminar -->
                                            @if($gradeLevel->groups_count == 0 && $gradeLevel->academic_plans_count == 0)
                                                <form action="{{ route('grade-levels.destroy', $gradeLevel) }}" method="POST" class="inline"
                                                      onsubmit="return confirm('¿Eliminar este nivel de grado?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" 
                                                            class="inline-flex items-center p-2 border border-red-300 rounded-md shadow-sm bg-red-50 text-sm font-medium text-red-700 hover:bg-red-100"
                                                            title="Eliminar">
                                                        🗑️
                                                    </button>
                                                </form>
                                            @else
                                                <button class="inline-flex items-center p-2 border border-gray-200 rounded-md shadow-sm bg-gray-100 text-sm font-medium text-gray-400 cursor-not-allowed"
                                                        disabled title="No se puede eliminar (tiene relaciones asociadas)">
                                                    🗑️
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="flex items-center justify-between mt-6">
                    <div class="text-sm text-gray-700">
                        Mostrando {{ $gradeLevels->firstItem() }} a {{ $gradeLevels->lastItem() }} 
                        de {{ $gradeLevels->total() }} niveles de grado
                    </div>
                    <div>
                        {{ $gradeLevels->links() }}
                    </div>
                </div>

            @else
                <!-- Empty State -->
                <div class="text-center py-12">
                    <div class="text-6xl mb-4">🎓</div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No hay niveles de grado registrados</h3>
                    <p class="text-gray-500 mb-6">Comience creando los grados académicos para organizar la estructura educativa</p>
                    <a href="{{ route('grade-levels.create') }}" 
                       class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md shadow-sm">
                        ➕ Crear Primer Grado
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-6">
        <div class="bg-white card-shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                        <span class="text-blue-600 font-semibold text-sm">📚</span>
                    </div>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-500">Total Grados</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $gradeLevels->total() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white card-shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                        <span class="text-green-600 font-semibold text-sm">✅</span>
                    </div>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-500">Activos</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $gradeLevels->where('status', true)->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white card-shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                        <span class="text-purple-600 font-semibold text-sm">👥</span>
                    </div>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-500">Con Grupos</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $gradeLevels->where('groups_count', '>', 0)->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white card-shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center">
                        <span class="text-yellow-600 font-semibold text-sm">📋</span>
                    </div>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-500">Con Planes</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $gradeLevels->where('academic_plans_count', '>', 0)->count() }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
