@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Page Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Crear Nuevo Grupo</h1>
        <nav class="flex mt-2" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-4">
                <li><a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-gray-700">Dashboard</a></li>
                <li><span class="text-gray-500">/</span></li>
                <li><a href="{{ route('groups.index') }}" class="text-gray-500 hover:text-gray-700">Grupos</a></li>
                <li><span class="text-gray-500">/</span></li>
                <li><span class="text-gray-900">Crear Nuevo</span></li>
            </ol>
        </nav>
    </div>

    <!-- Form -->
    <div class="bg-white card-shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-medium text-gray-900">👥 Información del Grupo</h2>
        </div>
        
        <form action="{{ route('groups.store') }}" method="POST" class="p-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Grade Level -->
                <div>
                    <label for="grade_level_id" class="block text-sm font-medium text-gray-700">Nivel de Grado *</label>
                    <select name="grade_level_id" 
                            id="grade_level_id" 
                            class="mt-1 block w-full rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('grade_level_id') border-red-300 @else border-gray-300 @enderror"
                            required>
                        <option value="">Seleccionar nivel...</option>
                        @foreach($gradeLevels as $level)
                            <option value="{{ $level->id }}" {{ old('grade_level_id') == $level->id ? 'selected' : '' }}>
                                {{ $level->grade_name }} ({{ $level->grade_number }}°)
                            </option>
                        @endforeach
                    </select>
                    @error('grade_level_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Group Letter -->
                <div>
                    <label for="group_letter" class="block text-sm font-medium text-gray-700">Letra del Grupo *</label>
                    <select name="group_letter" 
                            id="group_letter" 
                            class="mt-1 block w-full rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('group_letter') border-red-300 @else border-gray-300 @enderror"
                            required>
                        <option value="">Seleccionar letra...</option>
                        @foreach(['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J'] as $letter)
                            <option value="{{ $letter }}" {{ old('group_letter') == $letter ? 'selected' : '' }}>
                                Grupo {{ $letter }}
                            </option>
                        @endforeach
                    </select>
                    @error('group_letter')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Academic Year -->
                <div>
                    <label for="academic_year" class="block text-sm font-medium text-gray-700">Año Académico *</label>
                    <select name="academic_year" 
                            id="academic_year" 
                            class="mt-1 block w-full rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('academic_year') border-red-300 @else border-gray-300 @enderror"
                            required>
                        <option value="">Seleccionar año...</option>
                        @for($year = $currentYear; $year <= $currentYear + 2; $year++)
                            <option value="{{ $year }}" {{ old('academic_year', $currentYear) == $year ? 'selected' : '' }}>
                                {{ $year }}
                            </option>
                        @endfor
                    </select>
                    @error('academic_year')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Max Students -->
                <div>
                    <label for="max_students" class="block text-sm font-medium text-gray-700">Capacidad Máxima *</label>
                    <input type="number" 
                           name="max_students" 
                           id="max_students" 
                           value="{{ old('max_students', 35) }}"
                           min="5" 
                           max="60"
                           class="mt-1 block w-full rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('max_students') border-red-300 @else border-gray-300 @enderror"
                           required>
                    @error('max_students')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @else
                        <p class="mt-1 text-sm text-gray-500">Número máximo de estudiantes (5-60)</p>
                    @enderror
                </div>

                <!-- Status -->
                <div class="md:col-span-2">
                    <div class="flex items-center">
                        <input id="status" 
                               name="status" 
                               type="checkbox" 
                               value="1"
                               {{ old('status', true) ? 'checked' : '' }}
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="status" class="ml-2 block text-sm text-gray-900">
                            Grupo activo
                        </label>
                    </div>
                    <p class="mt-1 text-sm text-gray-500">
                        Los grupos activos estarán disponibles para inscripción de estudiantes
                    </p>
                </div>
            </div>

            <!-- Preview Section -->
            <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-blue-800">Vista Previa del Grupo</h3>
                        <div id="preview-content" class="mt-2 text-sm text-blue-700">
                            <div class="text-center p-4">
                                <div class="text-4xl mb-2">👥</div>
                                <p>Selecciona los datos para ver la vista previa</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Guidelines -->
            <div class="mt-6 bg-gray-50 border border-gray-200 rounded-lg p-4">
                <h3 class="text-sm font-medium text-gray-800 mb-3">📝 Recomendaciones</h3>
                <div class="text-sm text-gray-600 space-y-2">
                    <ul class="list-disc pl-5 space-y-1">
                        <li><strong>Letras de grupo:</strong> Use A, B, C... en orden alfabético</li>
                        <li><strong>Capacidad:</strong> 20-30 estudiantes para primaria, 30-40 para secundaria</li>
                        <li><strong>Año académico:</strong> Seleccione el año en curso o siguiente</li>
                        <li><strong>Unicidad:</strong> No puede haber dos grupos con la misma letra en el mismo nivel y año</li>
                    </ul>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end space-x-3 mt-6 pt-6 border-t border-gray-200">
                <a href="{{ route('groups.index') }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">
                    ❌ Cancelar
                </a>
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md shadow-sm">
                    💾 Crear Grupo
                </button>
            </div>
        </form>
    </div>

    <!-- Capacity Guidelines -->
    <div class="bg-white card-shadow rounded-lg p-6 mt-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">📊 Guía de Capacidades por Nivel</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                <div class="flex items-center">
                    <div class="text-2xl mr-3">🎒</div>
                    <div>
                        <h4 class="font-medium text-green-800">Primaria (1°-5°)</h4>
                        <p class="text-sm text-green-600">20-25 estudiantes</p>
                    </div>
                </div>
            </div>
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex items-center">
                    <div class="text-2xl mr-3">📚</div>
                    <div>
                        <h4 class="font-medium text-blue-800">Secundaria (6°-9°)</h4>
                        <p class="text-sm text-blue-600">25-35 estudiantes</p>
                    </div>
                </div>
            </div>
            <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                <div class="flex items-center">
                    <div class="text-2xl mr-3">🎓</div>
                    <div>
                        <h4 class="font-medium text-purple-800">Media (10°-12°)</h4>
                        <p class="text-sm text-purple-600">30-40 estudiantes</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Form elements
    const gradeLevelSelect = document.getElementById('grade_level_id');
    const groupLetterSelect = document.getElementById('group_letter');
    const academicYearSelect = document.getElementById('academic_year');
    const maxStudentsInput = document.getElementById('max_students');
    const previewContent = document.getElementById('preview-content');

    // Grade levels data (from backend)
    const gradeLevels = @json($gradeLevels->keyBy('id'));

    // Update preview when form changes
    function updatePreview() {
        const gradeLevelId = gradeLevelSelect.value;
        const groupLetter = groupLetterSelect.value;
        const academicYear = academicYearSelect.value;
        const maxStudents = maxStudentsInput.value;

        if (gradeLevelId && groupLetter && academicYear) {
            const gradeLevel = gradeLevels[gradeLevelId];
            const fullName = `${gradeLevel.grade_name} ${groupLetter}`;
            
            previewContent.innerHTML = `
                <div class="flex items-center justify-center space-x-4">
                    <div class="w-16 h-16 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 text-white font-bold text-xl flex items-center justify-center">
                        ${groupLetter}
                    </div>
                    <div class="text-left">
                        <div class="text-lg font-medium text-blue-900">${fullName}</div>
                        <div class="text-sm text-blue-700">Año ${academicYear}</div>
                        <div class="text-sm text-blue-600">Capacidad: ${maxStudents || 35} estudiantes</div>
                    </div>
                </div>
            `;
        } else {
            previewContent.innerHTML = `
                <div class="text-center p-4">
                    <div class="text-4xl mb-2">👥</div>
                    <p>Selecciona los datos para ver la vista previa</p>
                </div>
            `;
        }
    }

    // Auto-adjust capacity based on grade level
    gradeLevelSelect.addEventListener('change', function() {
        const gradeLevelId = this.value;
        if (gradeLevelId) {
            const gradeLevel = gradeLevels[gradeLevelId];
            const gradeNumber = gradeLevel.grade_number;
            
            let suggestedCapacity;
            if (gradeNumber <= 5) {
                suggestedCapacity = 25; // Primaria
            } else if (gradeNumber <= 9) {
                suggestedCapacity = 30; // Secundaria
            } else {
                suggestedCapacity = 35; // Media
            }
            
            if (!maxStudentsInput.value || maxStudentsInput.value == 35) {
                maxStudentsInput.value = suggestedCapacity;
            }
        }
        updatePreview();
    });

    // Event listeners
    groupLetterSelect.addEventListener('change', updatePreview);
    academicYearSelect.addEventListener('change', updatePreview);
    maxStudentsInput.addEventListener('input', updatePreview);

    // Form validation
    document.querySelector('form').addEventListener('submit', function(e) {
        const gradeLevelId = gradeLevelSelect.value;
        const groupLetter = groupLetterSelect.value;
        const academicYear = academicYearSelect.value;
        
        if (!gradeLevelId || !groupLetter || !academicYear) {
            e.preventDefault();
            alert('Por favor complete todos los campos requeridos.');
            return;
        }
        
        const maxStudents = parseInt(maxStudentsInput.value);
        if (maxStudents < 5 || maxStudents > 60) {
            e.preventDefault();
            alert('La capacidad máxima debe estar entre 5 y 60 estudiantes.');
            return;
        }
    });
</script>
@endpush
@endsection
