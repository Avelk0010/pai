@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Page Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Detalles del Grupo</h1>
        <nav class="flex mt-2" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-4">
                <li><a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-gray-700">Dashboard</a></li>
                <li><span class="text-gray-500">/</span></li>
                <li><a href="{{ route('groups.index') }}" class="text-gray-500 hover:text-gray-700">Grupos</a></li>
                <li><span class="text-gray-500">/</span></li>
                <li><span class="text-gray-900">{{ $group->full_name }}</span></li>
            </ol>
        </nav>
    </div>

    <!-- Main Info Card -->
    <div class="bg-white card-shadow rounded-lg mb-6">
        <div class="p-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-6">
                    <div class="w-24 h-24 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 text-white font-bold text-3xl flex items-center justify-center">
                        {{ $group->group_letter }}
                    </div>
                    <div>
                        <h2 class="text-3xl font-bold text-gray-900">{{ $group->full_name }}</h2>
                        <p class="text-lg text-gray-600">{{ $group->gradeLevel->grade_name }}</p>
                        <div class="flex items-center mt-2 space-x-4">
                            <span class="text-sm text-gray-500">📅 Año {{ $group->academic_year }}</span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $group->status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $group->status ? '🟢 Activo' : '🔴 Inactivo' }}
                            </span>
                        </div>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="flex space-x-3">
                    <a href="{{ route('groups.edit', $group) }}" 
                       class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md">
                        ✏️ Editar
                    </a>
                    <a href="{{ route('groups.statistics', $group) }}" 
                       class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-md">
                        📊 Estadísticas
                    </a>
                    <form action="{{ route('groups.toggle-status', $group) }}" 
                          method="POST" 
                          class="inline"
                          onsubmit="return confirm('¿Cambiar el estado de este grupo?')">
                        @csrf
                        @method('PATCH')
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">
                            {{ $group->status ? '⏸️ Desactivar' : '▶️ Activar' }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <!-- Students Card -->
        <div class="bg-white card-shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 rounded-full bg-blue-100 text-blue-800 font-bold text-xl flex items-center justify-center">
                    👥
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Estudiantes</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total_students'] }}/{{ $group->max_students }}</p>
                    <div class="w-full bg-gray-200 rounded-full h-2 mt-1">
                        <div class="bg-blue-600 h-2 rounded-full transition-all duration-300" 
                             style="width: {{ $stats['capacity_used'] }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Capacity Card -->
        <div class="bg-white card-shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 rounded-full bg-green-100 text-green-800 font-bold text-xl flex items-center justify-center">
                    📊
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Ocupación</p>
                    <p class="text-2xl font-bold text-green-600">{{ $stats['capacity_used'] }}%</p>
                    <p class="text-xs text-gray-500">{{ $stats['available_spots'] }} cupos disponibles</p>
                </div>
            </div>
        </div>

        <!-- Subjects Card -->
        <div class="bg-white card-shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 rounded-full bg-purple-100 text-purple-800 font-bold text-xl flex items-center justify-center">
                    📚
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Materias</p>
                    <p class="text-2xl font-bold text-purple-600">{{ $stats['total_subjects'] }}</p>
                    <p class="text-xs text-gray-500">asignadas</p>
                </div>
            </div>
        </div>

        <!-- Activities Card -->
        <div class="bg-white card-shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 rounded-full bg-orange-100 text-orange-800 font-bold text-xl flex items-center justify-center">
                    🎯
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Actividades</p>
                    <p class="text-2xl font-bold text-orange-600">{{ $stats['recent_activities'] }}</p>
                    <p class="text-xs text-gray-500">totales</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs Navigation -->
    <div class="bg-white card-shadow rounded-lg">
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex" aria-label="Tabs">
                <button class="tab-button active" onclick="showTab('students', this)">
                    👥 Estudiantes ({{ $stats['total_students'] }})
                </button>
                <button class="tab-button" onclick="showTab('subjects', this)">
                    📚 Materias ({{ $stats['total_subjects'] }})
                </button>
                <button class="tab-button" onclick="showTab('activities', this)">
                    🎯 Actividades ({{ $stats['recent_activities'] }})
                </button>
                <button class="tab-button" onclick="showTab('info', this)">
                    📋 Información
                </button>
            </nav>
        </div>

        <!-- Students Tab -->
        <div id="students-tab" class="tab-content">
            <div class="p-6">
                @if($group->enrollments->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Estudiante
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Email
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Fecha de Inscripción
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Estado
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($group->enrollments as $enrollment)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="w-10 h-10 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 text-white font-medium text-sm flex items-center justify-center">
                                                {{ substr($enrollment->student->name, 0, 2) }}
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $enrollment->student->name }}</div>
                                                <div class="text-sm text-gray-500">ID: {{ $enrollment->student->id }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $enrollment->student->email }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $enrollment->created_at->format('d/m/Y') }}</div>
                                        <div class="text-sm text-gray-500">{{ $enrollment->created_at->diffForHumans() }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            🟢 Inscrito
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="text-6xl mb-4">👥</div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No hay estudiantes</h3>
                        <p class="text-gray-500">Este grupo aún no tiene estudiantes inscritos</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Subjects Tab -->
        <div id="subjects-tab" class="tab-content hidden">
            <div class="p-6">
                @if($group->subjectAssignments->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($group->subjectAssignments as $assignment)
                        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200 hover:border-blue-300 transition-colors">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <h4 class="text-lg font-medium text-gray-900 mb-2">{{ $assignment->subject->name }}</h4>
                                    <p class="text-sm text-gray-600 mb-3">{{ $assignment->subject->description ?? 'Sin descripción' }}</p>
                                    <div class="flex items-center space-x-4 text-sm text-gray-500">
                                        <span>📖 {{ $assignment->subject->credits ?? 0 }} créditos</span>
                                        <span class="{{ $assignment->subject->status ? 'text-green-600' : 'text-red-600' }}">
                                            {{ $assignment->subject->status ? '🟢' : '🔴' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="text-6xl mb-4">📚</div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No hay materias asignadas</h3>
                        <p class="text-gray-500">Este grupo aún no tiene materias asignadas</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Activities Tab -->
        <div id="activities-tab" class="tab-content hidden">
            <div class="p-6">
                @if($group->activities->count() > 0)
                    <div class="space-y-4">
                        @foreach($group->activities as $activity)
                        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center mb-2">
                                        <h4 class="text-lg font-medium text-gray-900">{{ $activity->title }}</h4>
                                        <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ ucfirst($activity->type) }}
                                        </span>
                                    </div>
                                    <p class="text-sm text-gray-600 mb-2">{{ Str::limit($activity->description, 100) }}</p>
                                    <div class="flex items-center space-x-4 text-sm text-gray-500">
                                        <span>📅 {{ $activity->due_date ? $activity->due_date->format('d/m/Y') : 'Sin fecha límite' }}</span>
                                        <span>👤 {{ $activity->teacher->name ?? 'Sin asignar' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="text-6xl mb-4">🎯</div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No hay actividades</h3>
                        <p class="text-gray-500">Este grupo aún no tiene actividades registradas</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Information Tab -->
        <div id="info-tab" class="tab-content hidden">
            <div class="p-6">
                <div class="space-y-6">
                    <!-- Basic Information -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">📋 Información Básica</h3>
                        <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Nombre Completo</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $group->full_name }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Letra del Grupo</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $group->group_letter }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Nivel de Grado</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $group->gradeLevel->grade_name }} ({{ $group->gradeLevel->grade_number }}°)</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Año Académico</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $group->academic_year }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Capacidad Máxima</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $group->max_students }} estudiantes</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Estado</dt>
                                <dd class="mt-1">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $group->status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $group->status ? '🟢 Activo' : '🔴 Inactivo' }}
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
                                    {{ $group->created_at->format('d/m/Y H:i') }}
                                    <span class="text-gray-500">({{ $group->created_at->diffForHumans() }})</span>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Última Actualización</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    {{ $group->updated_at->format('d/m/Y H:i') }}
                                    <span class="text-gray-500">({{ $group->updated_at->diffForHumans() }})</span>
                                </dd>
                            </div>
                        </dl>
                    </div>

                    <!-- Complete Statistics -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">📊 Estadísticas Completas</h3>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <dl class="grid grid-cols-2 md:grid-cols-4 gap-4 text-center">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Estudiantes Activos</dt>
                                    <dd class="mt-1 text-2xl font-bold text-blue-600">{{ $stats['total_students'] }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Ocupación</dt>
                                    <dd class="mt-1 text-2xl font-bold text-green-600">{{ $stats['capacity_used'] }}%</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Cupos Libres</dt>
                                    <dd class="mt-1 text-2xl font-bold text-orange-600">{{ $stats['available_spots'] }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Materias</dt>
                                    <dd class="mt-1 text-2xl font-bold text-purple-600">{{ $stats['total_subjects'] }}</dd>
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
            <a href="{{ route('groups.index') }}" 
               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                ← Volver a la Lista
            </a>
            <a href="{{ route('groups.edit', $group) }}" 
               class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md">
                ✏️ Editar Grupo
            </a>
            <a href="{{ route('groups.statistics', $group) }}" 
               class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-md">
                📊 Ver Estadísticas
            </a>
        </div>
    </div>
</div>

@push('styles')
<style>
.tab-button {
    padding: 12px 24px;
    font-size: 0.875rem;
    font-weight: 500;
    color: #6b7280;
    border-bottom: 2px solid transparent;
    transition: all 0.2s;
}

.tab-button:hover {
    color: #374151;
    border-color: #d1d5db;
}

.tab-button.active {
    color: #2563eb;
    border-color: #2563eb;
}

.tab-content {
    min-height: 400px;
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
