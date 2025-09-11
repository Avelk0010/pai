@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Page Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Detalle del Plan Académico</h1>
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-4">
                    <li><a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-gray-700">Dashboard</a></li>
                    <li><span class="text-gray-500">/</span></li>
                    <li><a href="{{ route('academic-plans.index') }}" class="text-gray-500 hover:text-gray-700">Planes Académicos</a></li>
                    <li><span class="text-gray-500">/</span></li>
                    <li><span class="text-gray-900">{{ $academicPlan->name }}</span></li>
                </ol>
            </nav>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('academic-plans.edit', $academicPlan) }}" 
               class="inline-flex items-center px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white text-sm font-medium rounded-md shadow-sm">
                ✏️ Editar
            </a>
            <a href="{{ route('academic-plans.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-md shadow-sm">
                ← Volver
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Plan Information -->
        <div class="lg:col-span-2">
            <div class="bg-white card-shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-medium text-gray-900">📚 Información del Plan</h2>
                </div>
                <div class="p-6">
                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Nombre del Plan</dt>
                            <dd class="mt-1 text-sm text-gray-900 font-semibold">{{ $academicPlan->name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Año Académico</dt>
                            <dd class="mt-1">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $academicPlan->academic_year }}
                                </span>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Nivel de Grado</dt>
                            <dd class="mt-1">
                                @if($academicPlan->gradeLevel)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                        🏫 {{ $academicPlan->gradeLevel->grade_name }}
                                    </span>
                                @else
                                    <span class="text-gray-400">Sin asignar</span>
                                @endif
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Estado</dt>
                            <dd class="mt-1">
                                @if($academicPlan->status)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        ✅ Activo
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        ⏸️ Inactivo
                                    </span>
                                @endif
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Períodos</dt>
                            <dd class="mt-1">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                    📅 {{ $academicPlan->periods_count }} períodos
                                </span>
                            </dd>
                        </div>
                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-gray-500">Fecha de Creación</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $academicPlan->created_at->format('d/m/Y H:i') }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="lg:col-span-1">
            <div class="bg-white card-shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-medium text-gray-900">⚡ Acciones Rápidas</h2>
                </div>
                <div class="p-6 space-y-4">
                    <!-- Toggle Status -->
                    <form action="{{ route('academic-plans.toggle-status', $academicPlan) }}" method="POST">
                        @csrf @method('PATCH')
                        <button type="submit" 
                                class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white 
                                       {{ $academicPlan->status ? 'bg-gray-600 hover:bg-gray-700' : 'bg-green-600 hover:bg-green-700' }}">
                            {{ $academicPlan->status ? '⏸️ Desactivar Plan' : '▶️ Activar Plan' }}
                        </button>
                    </form>

                    <!-- Edit -->
                    <a href="{{ route('academic-plans.edit', $academicPlan) }}" 
                       class="w-full inline-flex justify-center items-center px-4 py-2 border border-yellow-300 rounded-md shadow-sm bg-yellow-50 text-sm font-medium text-yellow-700 hover:bg-yellow-100">
                        ✏️ Editar Plan
                    </a>

                    <!-- Delete (if no subjects) -->
                    @if($academicPlan->subjects->count() == 0)
                        <form action="{{ route('academic-plans.destroy', $academicPlan) }}" method="POST"
                              onsubmit="return confirm('¿Está seguro de eliminar este plan académico? Esta acción no se puede deshacer.')">
                            @csrf @method('DELETE')
                            <button type="submit" 
                                    class="w-full inline-flex justify-center items-center px-4 py-2 border border-red-300 rounded-md shadow-sm bg-red-50 text-sm font-medium text-red-700 hover:bg-red-100">
                                🗑️ Eliminar Plan
                            </button>
                        </form>
                    @else
                        <div class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-200 rounded-md shadow-sm bg-gray-100 text-sm font-medium text-gray-400 cursor-not-allowed">
                            🗑️ No se puede eliminar
                        </div>
                        <p class="text-xs text-gray-500 text-center">Este plan tiene materias asociadas</p>
                    @endif
                </div>
            </div>

            <!-- Statistics -->
            <div class="bg-white card-shadow rounded-lg mt-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-medium text-gray-900">📊 Estadísticas</h2>
                </div>
                <div class="p-6">
                    <dl class="space-y-4">
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-gray-500">Total de Materias</dt>
                            <dd class="text-sm font-semibold text-gray-900">{{ $academicPlan->subjects->count() }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-gray-500">Materias Activas</dt>
                            <dd class="text-sm font-semibold text-green-600">{{ $academicPlan->subjects->where('status', true)->count() }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-gray-500">Materias Inactivas</dt>
                            <dd class="text-sm font-semibold text-red-600">{{ $academicPlan->subjects->where('status', false)->count() }}</dd>
                        </div>
                        <hr class="border-gray-200">
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-gray-500">Total de Períodos</dt>
                            <dd class="text-sm font-semibold text-gray-900">{{ $academicPlan->periods->count() }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-gray-500">Períodos Activos</dt>
                            <dd class="text-sm font-semibold text-green-600">{{ $academicPlan->periods->where('status', 'active')->count() }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-gray-500">Períodos Planificados</dt>
                            <dd class="text-sm font-semibold text-blue-600">{{ $academicPlan->periods->where('status', 'planned')->count() }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-gray-500">Períodos Próximos</dt>
                            <dd class="text-sm font-semibold text-orange-600">{{ $academicPlan->periods->where('status', 'upcoming')->count() }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-gray-500">Períodos Finalizados</dt>
                            <dd class="text-sm font-semibold text-gray-600">{{ $academicPlan->periods->where('status', 'finished')->count() }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    <!-- Periods Section -->
    @if($academicPlan->periods->count() > 0)
        <div class="bg-white card-shadow rounded-lg mt-6">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h2 class="text-lg font-medium text-gray-900">📅 Períodos del Plan</h2>
                <span class="text-sm text-gray-500">{{ $academicPlan->periods->count() }} períodos</span>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($academicPlan->periods->sortBy('period_number') as $period)
                        <div class="border rounded-lg p-4 hover:shadow-md transition-shadow
                                    {{ $period->status === 'active' ? 'border-green-300 bg-green-50' : 
                                       ($period->status === 'finished' ? 'border-gray-300 bg-gray-50' : 
                                        ($period->status === 'upcoming' ? 'border-orange-300 bg-orange-50' : 'border-blue-300 bg-blue-50')) }}">
                            <div class="flex justify-between items-start mb-2">
                                <h3 class="text-sm font-medium text-gray-900">{{ $period->name }}</h3>
                                @if($period->status === 'active')
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        ▶️ Activo
                                    </span>
                                @elseif($period->status === 'finished')
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        ✅ Finalizado
                                    </span>
                                @elseif($period->status === 'upcoming')
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                        ⏳ Próximo
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        📋 Planificado
                                    </span>
                                @endif
                            </div>
                            <div class="text-xs text-gray-500 space-y-1">
                                <div>🔢 Número: {{ $period->period_number }}</div>
                                @if($period->start_date)
                                    <div>📅 Inicio: {{ $period->start_date->format('d/m/Y') }}</div>
                                @endif
                                @if($period->end_date)
                                    <div>📅 Fin: {{ $period->end_date->format('d/m/Y') }}</div>
                                @endif
                                @if(!$period->start_date && !$period->end_date)
                                    <div class="text-gray-400">Sin fechas configuradas</div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Period Status Management -->
        <div id="periods-management" class="bg-white card-shadow rounded-lg mt-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-medium text-gray-900">⚙️ Gestionar Estados de Períodos</h2>
                <p class="text-sm text-gray-600 mt-1">Solo un período puede estar activo a la vez</p>
            </div>
            <div class="p-6">
                <form action="{{ route('academic-plans.update-periods-status', $academicPlan) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    
                    <div class="space-y-4">
                        @foreach($academicPlan->periods->sortBy('period_number') as $period)
                            <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg">
                                <div class="flex items-center">
                                    <span class="text-sm font-medium text-gray-900 min-w-[100px]">{{ $period->name }}</span>
                                    @if($period->start_date && $period->end_date)
                                        <span class="text-xs text-gray-500 ml-2">
                                            {{ $period->start_date->format('d/m/Y') }} - {{ $period->end_date->format('d/m/Y') }}
                                        </span>
                                    @endif
                                </div>
                                
                                <div class="flex items-center space-x-2">
                                    <!-- Estado Planificado -->
                                    <label class="flex items-center">
                                        <input type="radio" 
                                               name="periods[{{ $period->id }}]" 
                                               value="planned" 
                                               {{ $period->status === 'planned' ? 'checked' : '' }}
                                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                        <span class="ml-1 text-sm text-blue-700">📋 Planificado</span>
                                    </label>
                                    
                                    <!-- Estado Activo -->
                                    <label class="flex items-center">
                                        <input type="radio" 
                                               name="periods[{{ $period->id }}]" 
                                               value="active" 
                                               {{ $period->status === 'active' ? 'checked' : '' }}
                                               class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300">
                                        <span class="ml-1 text-sm text-green-700">▶️ Activo</span>
                                    </label>
                                    
                                    <!-- Estado Finalizado -->
                                    <label class="flex items-center">
                                        <input type="radio" 
                                               name="periods[{{ $period->id }}]" 
                                               value="finished" 
                                               {{ $period->status === 'finished' ? 'checked' : '' }}
                                               class="h-4 w-4 text-gray-600 focus:ring-gray-500 border-gray-300">
                                        <span class="ml-1 text-sm text-gray-700">✅ Finalizado</span>
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    @error('periods')
                        <div class="mt-3 text-sm text-red-600">{{ $message }}</div>
                    @enderror
                    
                    <div class="mt-6 flex justify-end">
                        <button type="submit" 
                                class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-md transition-colors duration-200">
                            💾 Guardar Estados
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Subjects List -->
    @if($academicPlan->subjects->count() > 0)
        <div class="bg-white card-shadow rounded-lg mt-6">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h2 class="text-lg font-medium text-gray-900">📖 Materias del Plan</h2>
                <span class="text-sm text-gray-500">{{ $academicPlan->subjects->count() }} materias</span>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($academicPlan->subjects as $subject)
                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                            <div class="flex justify-between items-start mb-2">
                                <h3 class="text-sm font-medium text-gray-900">{{ $subject->name }}</h3>
                                @if($subject->status)
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        ✅
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        ⏸️
                                    </span>
                                @endif
                            </div>
                            <div class="text-xs text-gray-500 space-y-1">
                                <div>📝 Código: {{ $subject->code }}</div>
                                <div>🎯 Área: {{ $subject->area }}</div>
                                <div>⭐ Créditos: {{ $subject->credits }}</div>
                                @if($subject->hours_per_week)
                                    <div>⏰ Horas/semana: {{ $subject->hours_per_week }}</div>
                                @endif
                                <div>📚 {{ $subject->is_mandatory ? 'Obligatoria' : 'Electiva' }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @else
        <div class="bg-white card-shadow rounded-lg mt-6">
            <div class="p-12 text-center">
                <div class="text-6xl mb-4">📖</div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Sin materias asignadas</h3>
                <p class="text-gray-500">Este plan académico aún no tiene materias asociadas.</p>
            </div>
        </div>
    @endif
</div>
@endsection
