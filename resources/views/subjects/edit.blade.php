@extends('layouts.app')

@section('title', 'Editar Materia')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <div class="flex items-center">
            <a href="{{ route('subjects.show', $subject) }}" 
               class="text-gray-500 hover:text-gray-700 mr-4">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Editar Materia</h1>
                <p class="mt-2 text-sm text-gray-600">Modifica la información de {{ $subject->name }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white shadow-sm rounded-lg">
        <form action="{{ route('subjects.update', $subject) }}" method="POST" class="space-y-6 p-6">
            @csrf
            @method('PUT')

            <!-- Basic Information -->
            <div class="border-b border-gray-200 pb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Información Básica</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nombre de la Materia *</label>
                        <input type="text" 
                               name="name" 
                               id="name"
                               value="{{ old('name', $subject->name) }}"
                               class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('name') border-red-300 @enderror"
                               required>
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="code" class="block text-sm font-medium text-gray-700 mb-2">Código *</label>
                        <input type="text" 
                               name="code" 
                               id="code"
                               value="{{ old('code', $subject->code) }}"
                               placeholder="Ej: MAT101, ESP201"
                               class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('code') border-red-300 @enderror"
                               required>
                        @error('code')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="area" class="block text-sm font-medium text-gray-700 mb-2">Área Académica *</label>
                        <select name="area" 
                                id="area"
                                class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('area') border-red-300 @enderror"
                                required>
                            <option value="">Seleccionar área</option>
                            <option value="Matemáticas" {{ old('area', $subject->area) == 'Matemáticas' ? 'selected' : '' }}>Matemáticas</option>
                            <option value="Ciencias Naturales" {{ old('area', $subject->area) == 'Ciencias Naturales' ? 'selected' : '' }}>Ciencias Naturales</option>
                            <option value="Ciencias Sociales" {{ old('area', $subject->area) == 'Ciencias Sociales' ? 'selected' : '' }}>Ciencias Sociales</option>
                            <option value="Lenguaje" {{ old('area', $subject->area) == 'Lenguaje' ? 'selected' : '' }}>Lenguaje</option>
                            <option value="Inglés" {{ old('area', $subject->area) == 'Inglés' ? 'selected' : '' }}>Inglés</option>
                            <option value="Educación Física" {{ old('area', $subject->area) == 'Educación Física' ? 'selected' : '' }}>Educación Física</option>
                            <option value="Artes" {{ old('area', $subject->area) == 'Artes' ? 'selected' : '' }}>Artes</option>
                            <option value="Tecnología" {{ old('area', $subject->area) == 'Tecnología' ? 'selected' : '' }}>Tecnología</option>
                            <option value="Filosofía" {{ old('area', $subject->area) == 'Filosofía' ? 'selected' : '' }}>Filosofía</option>
                            <option value="Religión" {{ old('area', $subject->area) == 'Religión' ? 'selected' : '' }}>Religión</option>
                        </select>
                        @error('area')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="academic_plan_id" class="block text-sm font-medium text-gray-700 mb-2">Plan Académico *</label>
                        <select name="academic_plan_id" 
                                id="academic_plan_id"
                                class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('academic_plan_id') border-red-300 @enderror"
                                required>
                            <option value="">Seleccionar plan académico</option>
                            @foreach($academicPlans as $plan)
                                <option value="{{ $plan->id }}" {{ old('academic_plan_id', $subject->academic_plan_id) == $plan->id ? 'selected' : '' }}>
                                    {{ $plan->name }} - {{ $plan->gradeLevel->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('academic_plan_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Descripción</label>
                        <textarea name="description" 
                                  id="description" 
                                  rows="3"
                                  class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('description') border-red-300 @enderror"
                                  placeholder="Descripción general de la materia...">{{ old('description', $subject->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Academic Configuration -->
            <div class="border-b border-gray-200 pb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Configuración Académica</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="hours_per_week" class="block text-sm font-medium text-gray-700 mb-2">Horas por Semana *</label>
                        <input type="number" 
                               name="hours_per_week" 
                               id="hours_per_week"
                               min="1" 
                               max="40"
                               value="{{ old('hours_per_week', $subject->hours_per_week) }}"
                               class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('hours_per_week') border-red-300 @enderror"
                               required>
                        @error('hours_per_week')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="credits" class="block text-sm font-medium text-gray-700 mb-2">Créditos</label>
                        <input type="number" 
                               name="credits" 
                               id="credits"
                               min="0" 
                               max="20"
                               value="{{ old('credits', $subject->credits) }}"
                               class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('credits') border-red-300 @enderror">
                        @error('credits')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-4">
                        <div class="flex items-center">
                            <input type="checkbox" 
                                   name="is_mandatory" 
                                   id="is_mandatory"
                                   value="1"
                                   {{ old('is_mandatory', $subject->is_mandatory) ? 'checked' : '' }}
                                   class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                            <label for="is_mandatory" class="ml-2 block text-sm text-gray-700">Materia Obligatoria</label>
                        </div>

                        <div class="flex items-center">
                            <input type="checkbox" 
                                   name="status" 
                                   id="status"
                                   value="1"
                                   {{ old('status', $subject->status) ? 'checked' : '' }}
                                   class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                            <label for="status" class="ml-2 block text-sm text-gray-700">Materia Activa</label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Curriculum Content -->
            <div class="border-b border-gray-200 pb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Contenido Curricular</h3>
                
                <div class="space-y-6">
                    <div>
                        <label for="curriculum_content" class="block text-sm font-medium text-gray-700 mb-2">Contenido Curricular</label>
                        <textarea name="curriculum_content" 
                                  id="curriculum_content" 
                                  rows="4"
                                  class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('curriculum_content') border-red-300 @enderror"
                                  placeholder="Describe el contenido curricular de la materia...">{{ old('curriculum_content', $subject->curriculum_content) }}</textarea>
                        @error('curriculum_content')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="objectives" class="block text-sm font-medium text-gray-700 mb-2">Objetivos</label>
                        <textarea name="objectives" 
                                  id="objectives" 
                                  rows="4"
                                  class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('objectives') border-red-300 @enderror"
                                  placeholder="Define los objetivos de aprendizaje...">{{ old('objectives', $subject->objectives) }}</textarea>
                        @error('objectives')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="methodology" class="block text-sm font-medium text-gray-700 mb-2">Metodología</label>
                        <textarea name="methodology" 
                                  id="methodology" 
                                  rows="3"
                                  class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('methodology') border-red-300 @enderror"
                                  placeholder="Describe la metodología de enseñanza...">{{ old('methodology', $subject->methodology) }}</textarea>
                        @error('methodology')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="evaluation_criteria" class="block text-sm font-medium text-gray-700 mb-2">Criterios de Evaluación</label>
                        <textarea name="evaluation_criteria" 
                                  id="evaluation_criteria" 
                                  rows="3"
                                  class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('evaluation_criteria') border-red-300 @enderror"
                                  placeholder="Define los criterios de evaluación...">{{ old('evaluation_criteria', $subject->evaluation_criteria) }}</textarea>
                        @error('evaluation_criteria')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="prerequisites" class="block text-sm font-medium text-gray-700 mb-2">Prerrequisitos</label>
                        <textarea name="prerequisites" 
                                  id="prerequisites" 
                                  rows="2"
                                  class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('prerequisites') border-red-300 @enderror"
                                  placeholder="Lista los prerrequisitos necesarios...">{{ old('prerequisites', $subject->prerequisites) }}</textarea>
                        @error('prerequisites')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Dynamic Lists -->
            <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4">Temas y Recursos</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Topics -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Temas</label>
                        <div id="topics-container" class="space-y-2">
                            @php
                                $topics = old('topics', $subject->topics ?? []);
                                if (empty($topics)) $topics = [''];
                            @endphp
                            @foreach($topics as $index => $topic)
                            <div class="flex gap-2 topic-item">
                                <input type="text" 
                                       name="topics[]" 
                                       value="{{ $topic }}"
                                       placeholder="Ej: Álgebra básica"
                                       class="flex-1 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                <button type="button" onclick="removeItem(this)" class="text-red-600 hover:text-red-800 px-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                            @endforeach
                        </div>
                        <button type="button" onclick="addTopic()" class="mt-2 text-indigo-600 hover:text-indigo-800 text-sm">
                            + Agregar tema
                        </button>
                    </div>

                    <!-- Resources -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Recursos</label>
                        <div id="resources-container" class="space-y-2">
                            @php
                                $resources = old('resources', $subject->resources ?? []);
                                if (empty($resources)) $resources = [''];
                            @endphp
                            @foreach($resources as $index => $resource)
                            <div class="flex gap-2 resource-item">
                                <input type="text" 
                                       name="resources[]" 
                                       value="{{ $resource }}"
                                       placeholder="Ej: Libro de texto, calculadora"
                                       class="flex-1 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                <button type="button" onclick="removeItem(this)" class="text-red-600 hover:text-red-800 px-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                            @endforeach
                        </div>
                        <button type="button" onclick="addResource()" class="mt-2 text-indigo-600 hover:text-indigo-800 text-sm">
                            + Agregar recurso
                        </button>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                <a href="{{ route('subjects.show', $subject) }}" 
                   class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50">
                    Cancelar
                </a>
                <button type="submit" 
                        class="bg-indigo-600 py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Actualizar Materia
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function addTopic() {
    const container = document.getElementById('topics-container');
    const newItem = document.createElement('div');
    newItem.className = 'flex gap-2 topic-item';
    newItem.innerHTML = `
        <input type="text" 
               name="topics[]" 
               placeholder="Ej: Álgebra básica"
               class="flex-1 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
        <button type="button" onclick="removeItem(this)" class="text-red-600 hover:text-red-800 px-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    `;
    container.appendChild(newItem);
}

function addResource() {
    const container = document.getElementById('resources-container');
    const newItem = document.createElement('div');
    newItem.className = 'flex gap-2 resource-item';
    newItem.innerHTML = `
        <input type="text" 
               name="resources[]" 
               placeholder="Ej: Libro de texto, calculadora"
               class="flex-1 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
        <button type="button" onclick="removeItem(this)" class="text-red-600 hover:text-red-800 px-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    `;
    container.appendChild(newItem);
}

function removeItem(button) {
    const container = button.closest('#topics-container, #resources-container');
    if (container.children.length > 1) {
        button.closest('.topic-item, .resource-item').remove();
    }
}
</script>
@endsection
