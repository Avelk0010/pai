@extends('layouts.app')

@section('title', 'Editar Período')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Editar Período</h1>
        <p class="mt-2 text-sm text-gray-600">Modifica la información del período académico</p>
    </div>

    <div class="bg-white shadow-sm rounded-lg">
        <form method="POST" action="{{ route('periods.update', $period) }}" class="p-6 space-y-6">
            @csrf
            @method('PUT')

            <!-- Basic Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Period Number -->
                <div>
                    <label for="period_number" class="block text-sm font-medium text-gray-700 mb-2">
                        Número del Período *
                    </label>
                    <select name="period_number" 
                            id="period_number"
                            class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('period_number') border-red-300 @enderror"
                            required>
                        <option value="">Seleccionar número</option>
                        <option value="1" {{ old('period_number', $period->period_number) == '1' ? 'selected' : '' }}>1 - Primer Período</option>
                        <option value="2" {{ old('period_number', $period->period_number) == '2' ? 'selected' : '' }}>2 - Segundo Período</option>
                        <option value="3" {{ old('period_number', $period->period_number) == '3' ? 'selected' : '' }}>3 - Tercer Período</option>
                        <option value="4" {{ old('period_number', $period->period_number) == '4' ? 'selected' : '' }}>4 - Cuarto Período</option>
                        <option value="5" {{ old('period_number', $period->period_number) == '5' ? 'selected' : '' }}>5 - Quinto Período</option>
                    </select>
                    @error('period_number')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Code -->
                <div>
                    <label for="code" class="block text-sm font-medium text-gray-700 mb-2">
                        Código
                    </label>
                    <input type="text" 
                           id="code" 
                           name="code" 
                           value="{{ old('code', $period->code) }}"
                           class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('code') border-red-300 @enderror"
                           placeholder="Ej: P1">
                    @error('code')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Academic Year -->
                <div>
                    <label for="academic_year" class="block text-sm font-medium text-gray-700 mb-2">
                        Año Académico *
                    </label>
                    <input type="text" 
                           id="academic_year" 
                           name="academic_year" 
                           value="{{ old('academic_year', $period->academic_year) }}"
                           class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('academic_year') border-red-300 @enderror"
                           placeholder="Ej: 2024"
                           required>
                    @error('academic_year')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                        Estado *
                    </label>
                    <select id="status" 
                            name="status" 
                            class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('status') border-red-300 @enderror"
                            required>
                        <option value="inactive" {{ old('status', $period->status) === 'inactive' ? 'selected' : '' }}>
                            Inactivo
                        </option>
                        <option value="active" {{ old('status', $period->status) === 'active' ? 'selected' : '' }}>
                            Activo
                        </option>
                        <option value="closed" {{ old('status', $period->status) === 'closed' ? 'selected' : '' }}>
                            Cerrado
                        </option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Dates -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Start Date -->
                <div>
                    <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">
                        Fecha de Inicio
                    </label>
                    <input type="date" 
                           id="start_date" 
                           name="start_date" 
                           value="{{ old('start_date', $period->start_date?->format('Y-m-d')) }}"
                           class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('start_date') border-red-300 @enderror">
                    @error('start_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- End Date -->
                <div>
                    <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">
                        Fecha de Fin
                    </label>
                    <input type="date" 
                           id="end_date" 
                           name="end_date" 
                           value="{{ old('end_date', $period->end_date?->format('Y-m-d')) }}"
                           class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('end_date') border-red-300 @enderror">
                    @error('end_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                    Descripción
                </label>
                <textarea id="description" 
                          name="description" 
                          rows="4"
                          class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('description') border-red-300 @enderror"
                          placeholder="Describe las características y objetivos de este período académico...">{{ old('description', $period->description) }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Warning about active period -->
            @if(old('status', $period->status) !== 'active')
            <div id="active-warning" class="hidden bg-yellow-50 border-l-4 border-yellow-400 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-yellow-800">
                            Advertencia sobre período activo
                        </h3>
                        <div class="mt-2 text-sm text-yellow-700">
                            <p>Al activar este período, todos los demás períodos serán automáticamente desactivados. Solo puede haber un período activo a la vez.</p>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Form Actions -->
            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                <a href="{{ route('periods.show', $period) }}" 
                   class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Cancelar
                </a>

                <button type="submit" 
                        class="bg-indigo-600 py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Actualizar Período
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const statusSelect = document.getElementById('status');
    const activeWarning = document.getElementById('active-warning');
    const startDateInput = document.getElementById('start_date');
    const endDateInput = document.getElementById('end_date');

    // Show/hide active warning
    function toggleActiveWarning() {
        if (statusSelect.value === 'active') {
            activeWarning.classList.remove('hidden');
        } else {
            activeWarning.classList.add('hidden');
        }
    }

    statusSelect.addEventListener('change', toggleActiveWarning);
    
    // Initial check
    toggleActiveWarning();

    // Date validation
    startDateInput.addEventListener('change', function() {
        if (endDateInput.value && this.value > endDateInput.value) {
            alert('La fecha de inicio no puede ser posterior a la fecha de fin.');
            this.value = '';
        }
    });

    endDateInput.addEventListener('change', function() {
        if (startDateInput.value && this.value < startDateInput.value) {
            alert('La fecha de fin no puede ser anterior a la fecha de inicio.');
            this.value = '';
        }
    });

    // Academic year auto-suggestion
    const academicYearInput = document.getElementById('academic_year');
    const currentYear = new Date().getFullYear();
    
    if (!academicYearInput.value) {
        academicYearInput.placeholder = `Ej: ${currentYear}`;
    }
});
</script>
@endpush
@endsection
