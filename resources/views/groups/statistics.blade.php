@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Page Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Estadísticas del Grupo</h1>
        <nav class="flex mt-2" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-4">
                <li><a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-gray-700">Dashboard</a></li>
                <li><span class="text-gray-500">/</span></li>
                <li><a href="{{ route('groups.index') }}" class="text-gray-500 hover:text-gray-700">Grupos</a></li>
                <li><span class="text-gray-500">/</span></li>
                <li><a href="{{ route('groups.show', $group) }}" class="text-gray-500 hover:text-gray-700">{{ $group->full_name }}</a></li>
                <li><span class="text-gray-500">/</span></li>
                <li><span class="text-gray-900">Estadísticas</span></li>
            </ol>
        </nav>
    </div>

    <!-- Group Info Card -->
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 text-white card-shadow rounded-lg p-6 mb-6">
        <div class="flex items-center">
            <div class="w-20 h-20 rounded-full bg-white bg-opacity-20 text-white font-bold text-3xl flex items-center justify-center">
                {{ $group->group_letter }}
            </div>
            <div class="ml-6">
                <h2 class="text-2xl font-bold">{{ $group->full_name }}</h2>
                <p class="text-blue-100">{{ $group->gradeLevel->grade_name }} • Año {{ $group->academic_year }}</p>
                <div class="flex items-center mt-2 space-x-4">
                    <span class="text-blue-100">👥 {{ $stats['total_students'] }} estudiantes</span>
                    <span class="text-blue-100">📊 {{ $stats['capacity_percentage'] }}% ocupación</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Statistics Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <!-- Total Students -->
        <div class="bg-white card-shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 rounded-full bg-blue-100 text-blue-800 font-bold text-xl flex items-center justify-center">
                    👥
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Total Estudiantes</p>
                    <p class="text-3xl font-bold text-blue-600">{{ $stats['total_students'] }}</p>
                </div>
            </div>
            <div class="mt-4">
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-blue-600 h-2 rounded-full transition-all duration-500" 
                         style="width: {{ $stats['capacity_percentage'] }}%"></div>
                </div>
                <p class="text-xs text-gray-500 mt-1">de {{ $group->max_students }} máximo</p>
            </div>
        </div>

        <!-- Available Spots -->
        <div class="bg-white card-shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 rounded-full bg-green-100 text-green-800 font-bold text-xl flex items-center justify-center">
                    📈
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Cupos Disponibles</p>
                    <p class="text-3xl font-bold text-green-600">{{ $stats['available_spots'] }}</p>
                </div>
            </div>
            <div class="mt-4">
                <p class="text-sm text-gray-600">
                    {{ $stats['capacity_percentage'] }}% de ocupación
                </p>
            </div>
        </div>

        <!-- Male Students -->
        <div class="bg-white card-shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 rounded-full bg-indigo-100 text-indigo-800 font-bold text-xl flex items-center justify-center">
                    👦
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Estudiantes Masculinos</p>
                    <p class="text-3xl font-bold text-indigo-600">{{ $stats['male_students'] }}</p>
                </div>
            </div>
            <div class="mt-4">
                <p class="text-sm text-gray-600">
                    {{ $stats['gender_distribution']['male_percentage'] }}% del total
                </p>
            </div>
        </div>

        <!-- Female Students -->
        <div class="bg-white card-shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 rounded-full bg-pink-100 text-pink-800 font-bold text-xl flex items-center justify-center">
                    👧
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Estudiantes Femeninas</p>
                    <p class="text-3xl font-bold text-pink-600">{{ $stats['female_students'] }}</p>
                </div>
            </div>
            <div class="mt-4">
                <p class="text-sm text-gray-600">
                    {{ $stats['gender_distribution']['female_percentage'] }}% del total
                </p>
            </div>
        </div>
    </div>

    <!-- Charts and Analytics -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Gender Distribution Chart -->
        <div class="bg-white card-shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">👥 Distribución por Género</h3>
            
            @if($stats['total_students'] > 0)
                <div class="space-y-4">
                    <!-- Male -->
                    <div>
                        <div class="flex justify-between text-sm font-medium">
                            <span class="text-indigo-600">👦 Masculinos</span>
                            <span class="text-gray-900">{{ $stats['male_students'] }} ({{ $stats['gender_distribution']['male_percentage'] }}%)</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-3 mt-2">
                            <div class="bg-indigo-600 h-3 rounded-full transition-all duration-500" 
                                 style="width: {{ $stats['gender_distribution']['male_percentage'] }}%"></div>
                        </div>
                    </div>

                    <!-- Female -->
                    <div>
                        <div class="flex justify-between text-sm font-medium">
                            <span class="text-pink-600">👧 Femeninas</span>
                            <span class="text-gray-900">{{ $stats['female_students'] }} ({{ $stats['gender_distribution']['female_percentage'] }}%)</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-3 mt-2">
                            <div class="bg-pink-600 h-3 rounded-full transition-all duration-500" 
                                 style="width: {{ $stats['gender_distribution']['female_percentage'] }}%"></div>
                        </div>
                    </div>

                    <!-- Summary -->
                    <div class="pt-4 border-t border-gray-200">
                        <div class="text-sm text-gray-600 text-center">
                            Distribución equilibrada: 
                            @if(abs($stats['gender_distribution']['male_percentage'] - $stats['gender_distribution']['female_percentage']) <= 10)
                                <span class="text-green-600 font-medium">✅ Balanceada</span>
                            @else
                                <span class="text-orange-600 font-medium">⚠️ Desbalanceada</span>
                            @endif
                        </div>
                    </div>
                </div>
            @else
                <div class="text-center py-8 text-gray-500">
                    <div class="text-4xl mb-2">📊</div>
                    <p>No hay datos suficientes para mostrar la distribución</p>
                </div>
            @endif
        </div>

        <!-- Capacity Analysis -->
        <div class="bg-white card-shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">📊 Análisis de Capacidad</h3>
            
            <div class="space-y-6">
                <!-- Capacity Status -->
                <div class="text-center">
                    <div class="relative w-32 h-32 mx-auto">
                        <svg class="w-32 h-32 transform -rotate-90" viewBox="0 0 120 120">
                            <!-- Background circle -->
                            <circle cx="60" cy="60" r="50" fill="transparent" stroke="#e5e7eb" stroke-width="8"/>
                            <!-- Progress circle -->
                            <circle cx="60" cy="60" r="50" fill="transparent" 
                                    stroke="{{ $stats['capacity_percentage'] >= 90 ? '#ef4444' : ($stats['capacity_percentage'] >= 75 ? '#f59e0b' : '#10b981') }}" 
                                    stroke-width="8"
                                    stroke-dasharray="{{ 2 * pi() * 50 }}"
                                    stroke-dashoffset="{{ 2 * pi() * 50 * (1 - $stats['capacity_percentage'] / 100) }}"
                                    stroke-linecap="round"
                                    class="transition-all duration-1000"/>
                        </svg>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="text-center">
                                <div class="text-2xl font-bold text-gray-900">{{ $stats['capacity_percentage'] }}%</div>
                                <div class="text-xs text-gray-500">ocupación</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        @if($stats['capacity_percentage'] >= 90)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                🔴 Capacidad Crítica
                            </span>
                        @elseif($stats['capacity_percentage'] >= 75)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                🟡 Capacidad Alta
                            </span>
                        @elseif($stats['capacity_percentage'] >= 50)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                🟢 Capacidad Óptima
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                🔵 Capacidad Baja
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Recommendations -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <h4 class="font-medium text-gray-900 mb-2">💡 Recomendaciones</h4>
                    <ul class="text-sm text-gray-600 space-y-1">
                        @if($stats['capacity_percentage'] >= 90)
                            <li>• Considere crear un nuevo grupo paralelo</li>
                            <li>• Revise la lista de espera de estudiantes</li>
                        @elseif($stats['capacity_percentage'] >= 75)
                            <li>• Monitoree nuevas inscripciones de cerca</li>
                            <li>• Prepare recursos adicionales si es necesario</li>
                        @elseif($stats['capacity_percentage'] >= 50)
                            <li>• Capacidad ideal para el aprendizaje</li>
                            <li>• Continúe con las inscripciones normales</li>
                        @else
                            <li>• Promocione el grupo para nuevos estudiantes</li>
                            <li>• Revise las razones de baja inscripción</li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Academic Performance Overview -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <!-- Subjects Statistics -->
        <div class="bg-white card-shadow rounded-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">📚 Materias</h3>
                <div class="w-12 h-12 rounded-full bg-purple-100 text-purple-800 font-bold text-xl flex items-center justify-center">
                    {{ $stats['subjects_count'] }}
                </div>
            </div>
            
            <div class="space-y-3">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Materias asignadas</span>
                    <span class="font-medium text-gray-900">{{ $stats['subjects_count'] }}</span>
                </div>
                <div class="text-xs text-gray-500">
                    {{ $stats['subjects_count'] > 0 ? 'Carga académica completa' : 'Sin asignaciones académicas' }}
                </div>
            </div>
        </div>

        <!-- Activities Statistics -->
        <div class="bg-white card-shadow rounded-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">🎯 Actividades</h3>
                <div class="w-12 h-12 rounded-full bg-orange-100 text-orange-800 font-bold text-xl flex items-center justify-center">
                    {{ $stats['activities_count'] }}
                </div>
            </div>
            
            <div class="space-y-3">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Total actividades</span>
                    <span class="font-medium text-gray-900">{{ $stats['activities_count'] }}</span>
                </div>
                <div class="text-xs text-gray-500">
                    {{ $stats['activities_count'] > 0 ? 'Actividades programadas' : 'Sin actividades registradas' }}
                </div>
            </div>
        </div>

        <!-- Group Health Score -->
        <div class="bg-white card-shadow rounded-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">💯 Índice de Salud</h3>
                @php
                    $healthScore = 0;
                    if($stats['total_students'] > 0) $healthScore += 30;
                    if($stats['capacity_percentage'] >= 50 && $stats['capacity_percentage'] <= 85) $healthScore += 30;
                    if($stats['subjects_count'] > 0) $healthScore += 20;
                    if($stats['activities_count'] > 0) $healthScore += 20;
                @endphp
                <div class="w-12 h-12 rounded-full {{ $healthScore >= 80 ? 'bg-green-100 text-green-800' : ($healthScore >= 60 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }} font-bold text-xl flex items-center justify-center">
                    {{ $healthScore }}
                </div>
            </div>
            
            <div class="space-y-3">
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="h-2 rounded-full transition-all duration-1000 {{ $healthScore >= 80 ? 'bg-green-600' : ($healthScore >= 60 ? 'bg-yellow-600' : 'bg-red-600') }}" 
                         style="width: {{ $healthScore }}%"></div>
                </div>
                <div class="text-xs text-gray-500">
                    @if($healthScore >= 80)
                        ✅ Grupo en excelente estado
                    @elseif($healthScore >= 60)
                        ⚠️ Grupo necesita atención
                    @else
                        🔴 Grupo requiere intervención
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Breakdown -->
    <div class="bg-white card-shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">📊 Resumen Detallado</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Left Column -->
                <div>
                    <h4 class="font-medium text-gray-900 mb-3">👥 Información de Estudiantes</h4>
                    <dl class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <dt class="text-gray-600">Total de estudiantes:</dt>
                            <dd class="font-medium text-gray-900">{{ $stats['total_students'] }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-gray-600">Estudiantes masculinos:</dt>
                            <dd class="font-medium text-indigo-600">{{ $stats['male_students'] }} ({{ $stats['gender_distribution']['male_percentage'] }}%)</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-gray-600">Estudiantes femeninas:</dt>
                            <dd class="font-medium text-pink-600">{{ $stats['female_students'] }} ({{ $stats['gender_distribution']['female_percentage'] }}%)</dd>
                        </div>
                        <div class="flex justify-between border-t pt-2">
                            <dt class="text-gray-600">Cupos disponibles:</dt>
                            <dd class="font-medium text-green-600">{{ $stats['available_spots'] }}</dd>
                        </div>
                    </dl>
                </div>

                <!-- Right Column -->
                <div>
                    <h4 class="font-medium text-gray-900 mb-3">📚 Información Académica</h4>
                    <dl class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <dt class="text-gray-600">Materias asignadas:</dt>
                            <dd class="font-medium text-gray-900">{{ $stats['subjects_count'] }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-gray-600">Actividades totales:</dt>
                            <dd class="font-medium text-gray-900">{{ $stats['activities_count'] }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-gray-600">Capacidad máxima:</dt>
                            <dd class="font-medium text-gray-900">{{ $group->max_students }} estudiantes</dd>
                        </div>
                        <div class="flex justify-between border-t pt-2">
                            <dt class="text-gray-600">Porcentaje de ocupación:</dt>
                            <dd class="font-medium {{ $stats['capacity_percentage'] >= 90 ? 'text-red-600' : ($stats['capacity_percentage'] >= 75 ? 'text-yellow-600' : 'text-green-600') }}">{{ $stats['capacity_percentage'] }}%</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="mt-6 flex justify-center">
        <div class="flex space-x-4">
            <a href="{{ route('groups.show', $group) }}" 
               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                ← Volver al Grupo
            </a>
            <a href="{{ route('groups.edit', $group) }}" 
               class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md">
                ✏️ Editar Grupo
            </a>
            <a href="{{ route('groups.index') }}" 
               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                📋 Ver Todos los Grupos
            </a>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Animate numbers on page load
    document.addEventListener('DOMContentLoaded', function() {
        const numbers = document.querySelectorAll('[data-animate-number]');
        numbers.forEach(el => {
            const target = parseInt(el.textContent);
            let current = 0;
            const increment = target / 50;
            const timer = setInterval(() => {
                current += increment;
                if (current >= target) {
                    current = target;
                    clearInterval(timer);
                }
                el.textContent = Math.floor(current);
            }, 20);
        });
    });
</script>
@endpush
@endsection
