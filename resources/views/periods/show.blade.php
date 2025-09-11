@extends('layouts.app')

@section('title', 'Período - ' . $period->name)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex justify-between items-start">
            <div class="flex-1">
                <div class="flex items-center mb-2">
                    <h1 class="text-2xl font-bold text-gray-900 mr-4">{{ $period->name }}</h1>
                    
                    <!-- Status Badge -->
                    @if($period->status === 'active')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                            <span class="w-2 h-2 mr-2 bg-green-600 rounded-full"></span>
                            Activo
                        </span>
                    @elseif($period->status === 'inactive')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                            <span class="w-2 h-2 mr-2 bg-gray-600 rounded-full"></span>
                            Inactivo
                        </span>
                    @else
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                            <span class="w-2 h-2 mr-2 bg-red-600 rounded-full"></span>
                            Cerrado
                        </span>
                    @endif
                </div>
                
                <p class="text-sm text-gray-600">
                    Año Académico: {{ $period->academic_year }}
                    @if($period->start_date && $period->end_date)
                        • {{ $period->start_date->format('d/m/Y') }} - {{ $period->end_date->format('d/m/Y') }}
                    @endif
                </p>
                
                @if($period->description)
                    <p class="text-sm text-gray-500 mt-2">{{ $period->description }}</p>
                @endif
            </div>

            <div class="flex space-x-2">
                @if($period->status !== 'active')
                    <form action="{{ route('periods.activate', $period) }}" method="POST" class="inline-block">
                        @csrf
                        @method('PATCH')
                        <button type="submit" 
                                onclick="return confirm('¿Estás seguro de que quieres activar este período? Esto desactivará otros períodos activos.')"
                                class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg inline-flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 4h8m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Activar Período
                        </button>
                    </form>
                @endif

                @if($period->status === 'active')
                    <form action="{{ route('periods.close', $period) }}" method="POST" class="inline-block">
                        @csrf
                        @method('PATCH')
                        <button type="submit" 
                                onclick="return confirm('¿Estás seguro de que quieres cerrar este período? Esta acción no se puede deshacer.')"
                                class="bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-lg inline-flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Cerrar Período
                        </button>
                    </form>
                @endif

                <a href="{{ route('periods.edit', $period) }}" 
                   class="bg-yellow-600 hover:bg-yellow-700 text-white font-medium py-2 px-4 rounded-lg inline-flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Editar
                </a>

                <a href="{{ route('periods.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded-lg inline-flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Volver
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Activities -->
            <div class="bg-white rounded-lg shadow-sm">
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <h3 class="text-lg font-medium text-gray-900">Actividades del Período</h3>
                    <a href="{{ route('activities.create') }}?period_id={{ $period->id }}" 
                       class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg text-sm inline-flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Nueva Actividad
                    </a>
                </div>
                
                @if($activities->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actividad</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Materia/Grupo</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Peso</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Calificaciones</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($activities as $activity)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center mr-3">
                                            @if($activity->activity_type === 'exam')
                                                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                </svg>
                                            @else
                                                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $activity->title }}</div>
                                            <div class="text-xs text-gray-500 capitalize">{{ $activity->activity_type }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <div class="text-gray-900">{{ $activity->subject->name }}</div>
                                    <div class="text-gray-500">
                                        @foreach($activity->groups as $group)
                                            {{ $group->name }}@if(!$loop->last), @endif
                                        @endforeach
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $activity->percentage }}%
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($activity->status === 'published')
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Publicada
                                        </span>
                                    @elseif($activity->status === 'draft')
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            Borrador
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            Finalizada
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-900">
                                    {{ $activity->student_grades_count }}/{{ $activity->total_students ?? 0 }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('activities.show', $activity) }}" 
                                       class="text-indigo-600 hover:text-indigo-900">
                                        Ver
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="p-8 text-center">
                    <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No hay actividades</h3>
                    <p class="text-gray-500 mb-4">No se han creado actividades para este período</p>
                    <a href="{{ route('activities.create') }}?period_id={{ $period->id }}" 
                       class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg">
                        Crear Primera Actividad
                    </a>
                </div>
                @endif
            </div>

            <!-- Grade Statistics by Activity Type -->
            @if($activitiesByType->count() > 0)
            <div class="bg-white rounded-lg shadow-sm">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Estadísticas por Tipo</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($activitiesByType as $typeData)
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm font-medium text-gray-900 capitalize">
                                    @php
                                        $typeNames = [
                                            'exam' => 'Exámenes',
                                            'quiz' => 'Quices', 
                                            'assignment' => 'Tareas',
                                            'project' => 'Proyectos',
                                            'participation' => 'Participación'
                                        ];
                                    @endphp
                                    {{ $typeNames[$typeData->activity_type] ?? $typeData->activity_type }}
                                </span>
                                <span class="text-lg font-bold text-indigo-600">{{ $typeData->total_activities }}</span>
                            </div>
                            @if($typeData->avg_percentage)
                            <div class="text-xs text-gray-500">
                                Peso promedio: {{ number_format($typeData->avg_percentage, 1) }}%
                            </div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-8">
            <!-- Quick Stats -->
            <div class="bg-white rounded-lg shadow-sm">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Estadísticas del Período</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Total Actividades</span>
                        <span class="text-sm font-medium text-gray-900">{{ $stats['total_activities'] }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Publicadas</span>
                        <span class="text-sm font-medium text-green-600">{{ $stats['published_activities'] }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Borradores</span>
                        <span class="text-sm font-medium text-yellow-600">{{ $stats['draft_activities'] }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Finalizadas</span>
                        <span class="text-sm font-medium text-gray-600">{{ $stats['finished_activities'] }}</span>
                    </div>
                    
                    @if($stats['total_activities'] > 0)
                    <div class="pt-4 border-t border-gray-200">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Total Calificaciones</span>
                            <span class="text-sm font-medium text-gray-900">{{ $stats['total_grades'] }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Promedio General</span>
                            <span class="text-sm font-medium text-gray-900">{{ number_format($stats['average_grade'], 1) }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Estudiantes Aprobados</span>
                            <span class="text-sm font-medium text-green-600">{{ $stats['passed_students'] }}</span>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Period Details -->
            <div class="bg-white rounded-lg shadow-sm">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Detalles del Período</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Código</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $period->code ?? 'Sin código' }}</dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Año Académico</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $period->academic_year }}</dd>
                    </div>

                    @if($period->start_date)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Fecha de Inicio</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $period->start_date->format('d/m/Y') }}</dd>
                    </div>
                    @endif

                    @if($period->end_date)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Fecha de Fin</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $period->end_date->format('d/m/Y') }}</dd>
                    </div>
                    @endif

                    @if($period->start_date && $period->end_date)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Duración</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            {{ $period->start_date->diffInDays($period->end_date) + 1 }} días
                        </dd>
                    </div>
                    @endif

                    <div>
                        <dt class="text-sm font-medium text-gray-500">Creado</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $period->created_at->format('d/m/Y H:i') }}</dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500">Última Actualización</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $period->updated_at->format('d/m/Y H:i') }}</dd>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
