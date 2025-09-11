@extends('layouts.app')

@section('title', 'Nueva Publicación - Foro Académico')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center space-x-2 text-sm text-gray-500 mb-2">
            <a href="{{ route('forum.index') }}" class="hover:text-indigo-600">Foro</a>
            <span>›</span>
            <span>Nueva Publicación</span>
        </div>
        <h1 class="text-3xl font-bold text-gray-900">
            ✏️ Nueva Publicación
        </h1>
        <p class="text-gray-600 mt-1">
            Comparte tus ideas, preguntas o conocimientos con la comunidad académica
        </p>
    </div>

    <!-- Form -->
    <div class="bg-white shadow rounded-lg">
        <form action="{{ route('forum.store-post') }}" method="POST" class="space-y-6 p-6">
            @csrf
            
            <!-- Category Selection -->
            <div>
                <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">
                    📁 Categoría *
                </label>
                <select name="category_id" 
                        id="category_id" 
                        required
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Selecciona una categoría...</option>
                    @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ (old('category_id') == $cat->id || (isset($category) && $category->id == $cat->id)) ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                    @endforeach
                </select>
                @error('category_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Title -->
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                    📝 Título *
                </label>
                <input type="text" 
                       name="title" 
                       id="title"
                       value="{{ old('title') }}"
                       required
                       maxlength="255"
                       class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                       placeholder="Escribe un título descriptivo para tu publicación...">
                @error('title')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Content -->
            <div>
                <label for="content" class="block text-sm font-medium text-gray-700 mb-2">
                    📄 Contenido *
                </label>
                <textarea name="content" 
                          id="content" 
                          rows="12"
                          required
                          class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                          placeholder="Escribe el contenido de tu publicación...">{{ old('content') }}</textarea>
                @error('content')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Guidelines -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <h3 class="text-sm font-medium text-blue-900 mb-2">📋 Pautas para publicar</h3>
                <ul class="text-sm text-blue-700 space-y-1">
                    <li>• Mantén un lenguaje respetuoso y apropiado para el ámbito académico</li>
                    <li>• Selecciona la categoría más adecuada para tu publicación</li>
                    <li>• Escribe un título claro y descriptivo</li>
                    <li>• Tu publicación será revisada antes de ser visible para otros usuarios</li>
                    <li>• Evita contenido ofensivo, spam o no relacionado con el ámbito educativo</li>
                </ul>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                <a href="{{ route('forum.index') }}" 
                   class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium">
                    ← Cancelar
                </a>
                <button type="submit" 
                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg text-sm font-medium">
                    📤 Publicar (Pendiente de aprobación)
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const titleInput = document.getElementById('title');
    const contentTextarea = document.getElementById('content');
    
    // Auto-resize textarea
    contentTextarea.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = this.scrollHeight + 'px';
    });
    
    // Character counter for title
    titleInput.addEventListener('input', function() {
        const maxLength = 255;
        const currentLength = this.value.length;
        const remaining = maxLength - currentLength;
        
        // Remove existing counter
        const existingCounter = document.querySelector('#title-counter');
        if (existingCounter) {
            existingCounter.remove();
        }
        
        // Add counter
        const counter = document.createElement('div');
        counter.id = 'title-counter';
        counter.className = 'text-xs text-gray-500 mt-1';
        counter.textContent = `${currentLength}/${maxLength} caracteres`;
        
        if (remaining < 20) {
            counter.classList.add('text-yellow-600');
        }
        if (remaining < 0) {
            counter.classList.remove('text-yellow-600');
            counter.classList.add('text-red-600');
        }
        
        this.parentNode.appendChild(counter);
    });
});
</script>
@endpush
@endsection
