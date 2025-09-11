@extends('layouts.app')

@section('title', 'Nuevo Período')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <div class="flex items-center">
            <a href="{{ route('periods.index') }}" 
               class="text-gray-500 hover:text-gray-700 mr-4">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Nuevo Período Académico</h1>
                <p class="mt-2 text-sm text-gray-600">Crea un nuevo período para el año escolar</p>
            </div>
        </div>
    </div>

    <div class="bg-white shadow-sm rounded-lg">
        <form action="{{ route('periods.store') }}" method="POST" class="space-y-6 p-6">
            @csrf

            <!-- Basic Information -->
            <div class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="period_number" class="block text-sm font-medium text-gray-700 mb-2">Número del Período *</label>
                        <select name="period_number" 
                                id="period_number"
                                class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                required>
                            <option value="">Seleccionar número</option>
                            <option value="1" {{ old('period_number') == '1' ? 'selected' : '' }}>1 - Primer Período</option>
                            <option value="2" {{ old('period_number') == '2' ? 'selected' : '' }}>2 - Segundo Período</option>
                            <option value="3" {{ old('period_number') == '3' ? 'selected' : '' }}>3 - Tercer Período</option>
                            <option value="4" {{ old('period_number') == '4' ? 'selected' : '' }}>4 - Cuarto Período</option>
                            <option value="5" {{ old('period_number') == '5' ? 'selected' : '' }}>5 - Quinto Período</option>
                        </select>
                        @error('period_number')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                            <option value="3" {{ old('period_number') == '3' ? 'selected' : '' }}>3 - Tercer Período</option>
                            <option value="4" {{ old('period_number') == '4' ? 'selected' : '' }}>4 - Cuarto Período</option>
                        </select>
                        @error('period_number')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">Fecha de Inicio *</label>
                        <input type="date" 
                               name="start_date" 
                               id="start_date"
                               value="{{ old('start_date') }}"
                               class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                               required>
                        @error('start_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">Fecha de Fin *</label>
                        <input type="date" 
                               name="end_date" 
                               id="end_date"
                               value="{{ old('end_date') }}"
                               class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                               required>
                        @error('end_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="academic_year" class="block text-sm font-medium text-gray-700 mb-2">Año Académico *</label>
                        <select name="academic_year" 
                                id="academic_year"
                                class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                required>
                            <option value="">Seleccionar año</option>
                            @for($year = date('Y'); $year <= date('Y') + 2; $year++)
                                <option value="{{ $year }}" {{ old('academic_year') == $year || (!old('academic_year') && $year == date('Y')) ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                            @endfor
                            @for($year = date('Y') - 1; $year >= date('Y') - 3; $year--)
                                <option value="{{ $year }}" {{ old('academic_year') == $year ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                            @endfor
                        </select>
                        @error('academic_year')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Estado *</label>
                        <select name="status" 
                                id="status"
                                class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                required>
                            <option value="upcoming" {{ old('status', 'upcoming') == 'upcoming' ? 'selected' : '' }}>Próximo</option>
                            <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Activo</option>
                            <option value="finished" {{ old('status') == 'finished' ? 'selected' : '' }}>Finalizado</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">
                            Si seleccionas "Activo", se desactivarán otros períodos activos automáticamente.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Information Box -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-blue-800">Información importante</h3>
                        <div class="mt-2 text-sm text-blue-700">
                            <ul class="list-disc pl-5 space-y-1">
                                <li>Solo puede existir un período activo a la vez</li>
                                <li>No puede haber períodos con el mismo número en el mismo año académico</li>
                                <li>La fecha de fin debe ser posterior a la fecha de inicio</li>
                                <li>Los períodos sirven para organizar actividades y calificaciones</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                <a href="{{ route('periods.index') }}" 
                   class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50">
                    Cancelar
                </a>
                <button type="submit" 
                        class="bg-indigo-600 py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Crear Período
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-generate period name based on selection
    const periodNumber = document.getElementById('period_number');
    const periodName = document.getElementById('name');
    
    periodNumber.addEventListener('change', function() {
        if (this.value && !periodName.value) {
            const names = {
                '1': 'Primer Período',
                '2': 'Segundo Período', 
                '3': 'Tercer Período',
                '4': 'Cuarto Período'
            };
            periodName.value = names[this.value] || '';
        }
    });

    // Validate end date is after start date
    const startDate = document.getElementById('start_date');
    const endDate = document.getElementById('end_date');
    
    startDate.addEventListener('change', function() {
        endDate.min = this.value;
        if (endDate.value && endDate.value <= this.value) {
            endDate.value = '';
        }
    });
});
</script>
@endsection
