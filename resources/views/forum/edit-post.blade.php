@extends('layouts.app')

@section('title', 'Editar Publicación - Foro Académico')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center space-x-2 text-sm text-gray-500 mb-2">
            <a href="{{ route('forum.index') }}" class="hover:text-indigo-600">Foro</a>
            <span>›</span>
            <a href="{{ route('forum.category', $post->category) }}" class="hover:text-indigo-600">{{ $post->category->name }}</a>
            <span>›</span>
            <a href="{{ route('forum.post', $post) }}" class="hover:text-indigo-600">{{ $post->title }}</a>
            <span>›</span>
            <span>Editar</span>
        </div>
        <h1 class="text-3xl font-bold text-gray-900">
            ✏️ Editar Publicación
        </h1>
        <p class="text-gray-600 mt-1">
            Modifica tu publicación y compártela con la comunidad académica
        </p>
        
        @if(!$post->is_approved)
        <div class="mt-4 bg-orange-50 border-l-4 border-orange-400 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-orange-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-orange-700">
                        <strong>⏳ Publicación Pendiente:</strong> Esta publicación está esperando aprobación.
                        @if(Auth::user()->role !== 'admin')
                        Al editarla, deberá ser aprobada nuevamente.
                        @endif
                    </p>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Form -->
    <div class="bg-white shadow rounded-lg">
        <form action="{{ route('forum.update-post', $post) }}" method="POST" class="space-y-6 p-6">
            @csrf
            @method('PUT')
            
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
                    <option value="{{ $cat->id }}" {{ (old('category_id', $post->category_id) == $cat->id) ? 'selected' : '' }}>
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
                       value="{{ old('title', $post->title) }}"
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
                          placeholder="Escribe el contenido de tu publicación...">{{ old('content', $post->content) }}</textarea>
                @error('content')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            @if(Auth::user()->role === 'admin')
            <!-- Admin Options -->
            <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                <h3 class="text-sm font-medium text-red-900 mb-3">🛡️ Opciones de Administrador</h3>
                <div class="flex items-center">
                    <input type="checkbox" 
                           name="is_pinned" 
                           id="is_pinned"
                           value="1"
                           {{ old('is_pinned', $post->is_pinned) ? 'checked' : '' }}
                           class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                    <label for="is_pinned" class="ml-2 block text-sm text-red-700">
                        📌 Fijar esta publicación (aparecerá al inicio de la categoría)
                    </label>
                </div>
            </div>
            @endif

            <!-- Guidelines -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <h3 class="text-sm font-medium text-blue-900 mb-2">📋 Pautas para publicar</h3>
                <ul class="text-sm text-blue-700 space-y-1">
                    <li>• Mantén un lenguaje respetuoso y apropiado para el ámbito académico</li>
                    <li>• Selecciona la categoría más adecuada para tu publicación</li>
                    <li>• Escribe un título claro y descriptivo</li>
                    @if(Auth::user()->role !== 'admin')
                    <li>• Las ediciones serán revisadas antes de ser visibles para otros usuarios</li>
                    @endif
                    <li>• Evita contenido ofensivo, spam o no relacionado con el ámbito educativo</li>
                </ul>
            </div>

            <!-- Meta Information -->
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                <h3 class="text-sm font-medium text-gray-900 mb-2">📊 Información de la publicación</h3>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm text-gray-600">
                    <div>
                        <span class="font-medium">Creada:</span><br>
                        {{ $post->created_at->format('d/m/Y H:i') }}
                    </div>
                    @if($post->created_at != $post->updated_at)
                    <div>
                        <span class="font-medium">Última edición:</span><br>
                        {{ $post->updated_at->format('d/m/Y H:i') }}
                    </div>
                    @endif
                    <div>
                        <span class="font-medium">Estado:</span><br>
                        @if($post->is_approved)
                        <span class="text-green-600">✅ Aprobada</span>
                        @else
                        <span class="text-orange-600">⏳ Pendiente de aprobación</span>
                        @endif
                    </div>
                    <div>
                        <span class="font-medium">Vistas:</span><br>
                        {{ $post->views }}
                    </div>
                    <div>
                        <span class="font-medium">Comentarios:</span><br>
                        {{ $post->approved_comments_count }}
                    </div>
                    @if($post->is_pinned)
                    <div>
                        <span class="font-medium">Especial:</span><br>
                        <span class="text-indigo-600">📌 Fijada</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                <div class="flex space-x-3">
                    <a href="{{ route('forum.post', $post) }}" 
                       class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium">
                        ← Volver a la publicación
                    </a>
                    <a href="{{ route('forum.user-activity') }}" 
                       class="bg-blue-100 hover:bg-blue-200 text-blue-700 px-4 py-2 rounded-lg text-sm font-medium">
                        📋 Mi actividad
                    </a>
                </div>
                <button type="submit" 
                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg text-sm font-medium">
                    @if(Auth::user()->role === 'admin')
                    💾 Guardar cambios
                    @else
                    💾 Guardar (Pendiente de aprobación)
                    @endif
                </button>
            </div>
        </form>
    </div>

    <!-- Delete Section (for post author and admins) -->
    <div class="mt-6 bg-red-50 border border-red-200 rounded-lg p-4">
        <h3 class="text-sm font-medium text-red-900 mb-2">⚠️ Zona peligrosa</h3>
        <p class="text-sm text-red-700 mb-4">
            Una vez eliminada, esta acción no se puede deshacer. La publicación y todos sus comentarios se perderán permanentemente.
        </p>
        <form action="{{ route('forum.delete-post', $post) }}" method="POST" 
              onsubmit="return confirm('¿Estás seguro de que quieres eliminar esta publicación? Esta acción no se puede deshacer.')">
            @csrf
            @method('DELETE')
            <button type="submit" 
                    class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                🗑️ Eliminar publicación
            </button>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const titleInput = document.getElementById('title');
    const contentTextarea = document.getElementById('content');
    
    // Auto-resize textarea
    function autoResize() {
        contentTextarea.style.height = 'auto';
        contentTextarea.style.height = contentTextarea.scrollHeight + 'px';
    }
    
    // Initial resize
    autoResize();
    
    // Resize on input
    contentTextarea.addEventListener('input', autoResize);
    
    // Character counter for title
    const maxLength = 255;
    const titleCounter = document.createElement('div');
    titleCounter.className = 'text-sm text-gray-500 mt-1';
    titleInput.parentNode.appendChild(titleCounter);
    
    function updateTitleCounter() {
        const remaining = maxLength - titleInput.value.length;
        titleCounter.textContent = `${titleInput.value.length}/${maxLength} caracteres`;
        titleCounter.className = remaining < 20 ? 'text-sm text-red-500 mt-1' : 'text-sm text-gray-500 mt-1';
    }
    
    updateTitleCounter();
    titleInput.addEventListener('input', updateTitleCounter);
    
    // Unsaved changes warning
    let originalContent = {
        title: titleInput.value,
        content: contentTextarea.value,
        category: document.getElementById('category_id').value
    };
    
    function hasUnsavedChanges() {
        return titleInput.value !== originalContent.title ||
               contentTextarea.value !== originalContent.content ||
               document.getElementById('category_id').value !== originalContent.category;
    }
    
    window.addEventListener('beforeunload', function(e) {
        if (hasUnsavedChanges()) {
            e.preventDefault();
            e.returnValue = '';
        }
    });
    
    // Remove warning when form is submitted
    document.querySelector('form').addEventListener('submit', function() {
        window.removeEventListener('beforeunload', arguments.callee);
    });
});
</script>
@endpush
@endsection
