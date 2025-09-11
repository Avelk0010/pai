@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Page Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Nivel de Grado</h1>
        <nav class="flex mt-2" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-4">
                <li><a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-gray-700">Dashboard</a></li>
                <li><span class="text-gray-500">/</span></li>
                <li><a href="{{ route('grade-levels.index') }}" class="text-gray-500 hover:text-gray-700">Niveles de Grado</a></li>
                <li><span class="text-gray-500">/</span></li>
                <li><span class="text-gray-900">{{ $gradeLevel->grade_name }}</span></li>
            </ol>
        </nav>
    </div>

    <!-- Main Info Card -->
    <div class="bg-white card-shadow rounded-lg mb-6">
        <div class="p-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-6">
                    <div class="w-20 h-20 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 text-white font-bold text-3xl flex items-center justify-center">
                        {{ $gradeLevel->grade_number }}
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">{{ $gradeLevel->grade_name }}</h2>
                        <p class="text-gray-600">Grado {{ $gradeLevel->grade_number }}°</p>
                        <div class="flex items-center mt-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $gradeLevel->status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $gradeLevel->status ? '🟢 Activo' : '🔴 Inactivo' }}
                            </span>
                        </div>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="flex space-x-3">
                    <a href="{{ route('grade-levels.edit', $gradeLevel) }}" 
                       class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md">
                        ✏️ Editar
                    </a>
                    <form action="{{ route('grade-levels.toggle-status', $gradeLevel) }}" 
                          method="POST" 
                          class="inline"
                          onsubmit="return confirm('¿Cambiar el estado de este nivel de grado?')">
                        @csrf
                        @method('PATCH')
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">
                            {{ $gradeLevel->status ? '⏸️ Desactivar' : '▶️ Activar' }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <!-- Groups Card -->
        <div class="bg-white card-shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 rounded-full bg-blue-100 text-blue-800 font-bold text-xl flex items-center justify-center">
                    👥
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Grupos Asociados</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $gradeLevel->groups_count }}</p>
                </div>
            </div>
        </div>

        <!-- Academic Plans Card -->
        <div class="bg-white card-shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 rounded-full bg-green-100 text-green-800 font-bold text-xl flex items-center justify-center">
                    📚
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Planes Académicos</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $gradeLevel->academic_plans_count }}</p>
                </div>
            </div>
        </div>

        <!-- Status Card -->
        <div class="bg-white card-shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 rounded-full {{ $gradeLevel->status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }} font-bold text-xl flex items-center justify-center">
                    {{ $gradeLevel->status ? '✅' : '❌' }}
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Estado Actual</p>
                    <p class="text-lg font-semibold {{ $gradeLevel->status ? 'text-green-600' : 'text-red-600' }}">
                        {{ $gradeLevel->status ? 'Activo' : 'Inactivo' }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs -->
    <div class="bg-white card-shadow rounded-lg">
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex" aria-label="Tabs">
                <button class="tab-button active" onclick="showTab('groups', this)">
                    👥 Grupos ({{ $gradeLevel->groups_count }})
                </button>
                <button class="tab-button" onclick="showTab('academic-plans', this)">
                    📚 Planes Académicos ({{ $gradeLevel->academic_plans_count }})
                </button>
                <button class="tab-button" onclick="showTab('details', this)">
                    📋 Detalles
                </button>
            </nav>
        </div>

        <!-- Groups Tab -->
        <div id="groups-tab" class="tab-content">
            <div class="p-6">
                @if($gradeLevel->groups->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Grupo
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Estado
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Estudiantes
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Creado
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Acciones
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($gradeLevel->groups as $group)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-800 font-medium text-sm flex items-center justify-center">
                                                {{ substr($group->name, 0, 2) }}
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $group->name }}</div>
                                                <div class="text-sm text-gray-500">Código: {{ $group->code ?? 'N/A' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $group->status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $group->status ? '🟢 Activo' : '🔴 Inactivo' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $group->enrollments_count ?? 0 }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $group->created_at->format('d/m/Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="#" class="text-blue-600 hover:text-blue-900">Ver</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="text-6xl mb-4">👥</div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No hay grupos</h3>
                        <p class="text-gray-500">Este nivel de grado aún no tiene grupos asociados</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Academic Plans Tab -->
        <div id="academic-plans-tab" class="tab-content hidden">
            <div class="p-6">
                @if($gradeLevel->academicPlans->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($gradeLevel->academicPlans as $plan)
                        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200 hover:border-blue-300 transition-colors">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <h4 class="text-lg font-medium text-gray-900 mb-2">{{ $plan->name }}</h4>
                                    <p class="text-sm text-gray-600 mb-3 line-clamp-2">{{ $plan->description }}</p>
                                    <div class="flex items-center space-x-4 text-sm text-gray-500">
                                        <span>📖 {{ $plan->subjects_count ?? 0 }} materias</span>
                                        <span class="{{ $plan->status ? 'text-green-600' : 'text-red-600' }}">
                                            {{ $plan->status ? '🟢' : '🔴' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-4 flex justify-end">
                                <a href="{{ route('academic-plans.show', $plan) }}" 
                                   class="text-sm text-blue-600 hover:text-blue-800">
                                    Ver detalles →
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="text-6xl mb-4">📚</div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No hay planes académicos</h3>
                        <p class="text-gray-500 mb-4">Este nivel de grado aún no tiene planes académicos asociados</p>
                        <a href="{{ route('academic-plans.create') }}" 
                           class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md">
                            ➕ Crear Plan Académico
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Details Tab -->
        <div id="details-tab" class="tab-content hidden">
            <div class="p-6">
                <div class="space-y-6">
                    <!-- Basic Information -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">📋 Información Básica</h3>
                        <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Número de Grado</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $gradeLevel->grade_number }}°</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Nombre del Grado</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $gradeLevel->grade_name }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Estado</dt>
                                <dd class="mt-1">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $gradeLevel->status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $gradeLevel->status ? '🟢 Activo' : '🔴 Inactivo' }}
                                    </span>
                                </dd>
                            </div>
                        </dl>
                    </div>

                    <!-- Timestamps -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">🕒 Fechas</h3>
                        <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Fecha de Creación</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    {{ $gradeLevel->created_at->format('d/m/Y H:i') }}
                                    <span class="text-gray-500">({{ $gradeLevel->created_at->diffForHumans() }})</span>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Última Actualización</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    {{ $gradeLevel->updated_at->format('d/m/Y H:i') }}
                                    <span class="text-gray-500">({{ $gradeLevel->updated_at->diffForHumans() }})</span>
                                </dd>
                            </div>
                        </dl>
                    </div>

                    <!-- Statistics -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">📊 Estadísticas</h3>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <dl class="grid grid-cols-2 md:grid-cols-4 gap-4 text-center">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Grupos</dt>
                                    <dd class="mt-1 text-2xl font-bold text-blue-600">{{ $gradeLevel->groups_count }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Planes Académicos</dt>
                                    <dd class="mt-1 text-2xl font-bold text-green-600">{{ $gradeLevel->academic_plans_count }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Total Estudiantes</dt>
                                    <dd class="mt-1 text-2xl font-bold text-purple-600">
                                        {{ $gradeLevel->groups->sum('enrollments_count') ?? 0 }}
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Nivel</dt>
                                    <dd class="mt-1 text-2xl font-bold text-gray-600">
                                        @if($gradeLevel->grade_number <= 5)
                                            Primaria
                                        @elseif($gradeLevel->grade_number <= 9)
                                            Secundaria
                                        @else
                                            Media
                                        @endif
                                    </dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="mt-6 flex justify-center">
        <div class="flex space-x-4">
            <a href="{{ route('grade-levels.index') }}" 
               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                ← Volver a la Lista
            </a>
            <a href="{{ route('grade-levels.edit', $gradeLevel) }}" 
               class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md">
                ✏️ Editar Nivel
            </a>
        </div>
    </div>
</div>

@push('styles')
<style>
.tab-button {
    @apply px-6 py-3 text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 border-b-2 border-transparent;
}

.tab-button.active {
    @apply text-blue-600 border-blue-500;
}

.tab-content {
    @apply min-h-96;
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
@endpush

@push('scripts')
<script>
function showTab(tabName, buttonElement) {
    // Hide all tabs
    document.querySelectorAll('.tab-content').forEach(tab => {
        tab.classList.add('hidden');
    });
    
    // Remove active class from all buttons
    document.querySelectorAll('.tab-button').forEach(btn => {
        btn.classList.remove('active');
    });
    
    // Show selected tab
    document.getElementById(tabName + '-tab').classList.remove('hidden');
    
    // Add active class to clicked button
    buttonElement.classList.add('active');
}
</script>
@endpush
@endsection
