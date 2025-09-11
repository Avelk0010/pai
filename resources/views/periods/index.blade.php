@extends('layouts.app')

@section('title', 'Períodos Académicos')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Períodos Académicos</h1>
                <p class="mt-2 text-sm text-gray-600">Gestiona los períodos del año escolar</p>
            </div>
            <a href="{{ route('periods.create') }}" 
               class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg inline-flex items-center transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Nuevo Período
            </a>
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
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Períodos</dt>
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
                            <dt class="text-sm font-medium text-gray-500 truncate">Activo</dt>
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
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Próximos</dt>
                            <dd class="text-lg font-semibold text-gray-900">{{ $stats['upcoming'] }}</dd>
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
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Finalizados</dt>
                            <dd class="text-lg font-semibold text-gray-900">{{ $stats['finished'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Years Summary -->
    <div class="bg-white rounded-lg shadow-sm mb-8">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Años Académicos</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach($years as $year)
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="text-sm font-medium text-gray-900">{{ $year->academic_year }}</div>
                    <div class="text-2xl font-bold text-indigo-600">{{ $year->count }}</div>
                    <div class="text-xs text-gray-500">períodos</div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Periods Table -->
    <div class="bg-white shadow-sm rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Lista de Períodos</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Período</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fechas</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Año Académico</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actividades</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($periods as $period)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center mr-3">
                                    <span class="text-sm font-medium text-indigo-600">{{ $period->period_number }}</span>
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $period->name }}</div>
                                    <div class="text-xs text-gray-500">Período {{ $period->period_number }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <div>{{ $period->start_date->format('d/m/Y') }}</div>
                            <div class="text-xs text-gray-500">al {{ $period->end_date->format('d/m/Y') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $period->academic_year }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <div class="flex space-x-4">
                                <div class="text-center">
                                    <div class="text-sm font-medium">{{ $period->activities_count }}</div>
                                    <div class="text-xs text-gray-500">Actividades</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-sm font-medium">{{ $period->period_grades_count }}</div>
                                    <div class="text-xs text-gray-500">Calificaciones</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($period->status === 'active')
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <span class="w-2 h-2 mr-1 bg-green-600 rounded-full"></span>
                                    Activo
                                </span>
                            @elseif($period->status === 'upcoming')
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    <span class="w-2 h-2 mr-1 bg-blue-600 rounded-full"></span>
                                    Próximo
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    <span class="w-2 h-2 mr-1 bg-gray-600 rounded-full"></span>
                                    Finalizado
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex space-x-2">
                                <a href="{{ route('periods.show', $period) }}" 
                                   class="text-indigo-600 hover:text-indigo-900 p-1 rounded hover:bg-indigo-50" title="Ver">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </a>

                                @if($period->status !== 'active')
                                    <form action="{{ route('periods.activate', $period) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" 
                                                class="text-green-600 hover:text-green-900 p-1 rounded hover:bg-green-50" 
                                                title="Activar"
                                                onclick="return confirm('¿Activar este período? Esto desactivará otros períodos activos.')">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"></path>
                                            </svg>
                                        </button>
                                    </form>
                                @endif

                                @if($period->status === 'active')
                                    <form action="{{ route('periods.close', $period) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" 
                                                class="text-red-600 hover:text-red-900 p-1 rounded hover:bg-red-50" 
                                                title="Cerrar"
                                                onclick="return confirm('¿Cerrar este período?')">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                            </svg>
                                        </button>
                                    </form>
                                @endif

                                <a href="{{ route('periods.edit', $period) }}" 
                                   class="text-yellow-600 hover:text-yellow-900 p-1 rounded hover:bg-yellow-50" title="Editar">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </a>

                                <form action="{{ route('periods.destroy', $period) }}" method="POST" class="inline-block" 
                                      onsubmit="return confirm('¿Estás seguro de que quieres eliminar este período?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="text-red-600 hover:text-red-900 p-1 rounded hover:bg-red-50" title="Eliminar">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">No hay períodos registrados</h3>
                                <p class="text-gray-500 mb-4">Comienza creando el primer período académico</p>
                                <a href="{{ route('periods.create') }}" 
                                   class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg">
                                    Nuevo Período
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($periods->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $periods->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
