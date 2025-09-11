@extends('layouts.app')

@section('title', $post->title)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Breadcrumb -->
    <nav class="flex items-center space-x-2 text-sm text-gray-500 mb-6">
        <a href="{{ route('forum.index') }}" class="hover:text-gray-700">💬 Foro</a>
        <span>›</span>
        <a href="{{ route('forum.category', $post->category) }}" class="hover:text-gray-700">{{ $post->category->name }}</a>
        <span>›</span>
        <span class="text-gray-900">{{ $post->title }}</span>
    </nav>

    <!-- Post Content -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        @if(!$post->is_approved)
        <!-- Pending Approval Banner for Admins -->
        <div class="bg-orange-50 border-l-4 border-orange-400 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-orange-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-orange-700">
                        <strong>⏳ Publicación Pendiente:</strong> Esta publicación está esperando aprobación y no es visible para otros usuarios.
                    </p>
                </div>
            </div>
        </div>
        @endif
        
        <!-- Post Header -->
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ $post->title }}</h1>
                    <div class="flex items-center space-x-4 text-sm text-gray-600">
                        <div class="flex items-center space-x-2">
                            <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center text-white text-sm font-medium">
                                {{ strtoupper(substr($post->author->name, 0, 1)) }}
                            </div>
                            <span class="font-medium">{{ $post->author->name }}</span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($post->author->role === 'admin') bg-red-100 text-red-800
                                @elseif($post->author->role === 'teacher') bg-blue-100 text-blue-800
                                @else bg-green-100 text-green-800
                                @endif">
                                {{ ucfirst($post->author->role) }}
                            </span>
                        </div>
                        <span>•</span>
                        <span>{{ $post->created_at->format('d/m/Y H:i') }}</span>
                        <span>•</span>
                        <span>👁️ {{ $post->views }} {{ $post->views == 1 ? 'vista' : 'vistas' }}</span>
                        <span>•</span>
                        <span>💬 {{ $post->approved_comments_count }} {{ $post->approved_comments_count == 1 ? 'comentario' : 'comentarios' }}</span>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium" 
                          style="background-color: {{ $post->category->color }}20; color: {{ $post->category->color }};">
                        {{ $post->category->name }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Post Body -->
        <div class="px-6 py-6">
            <div class="prose prose-lg max-w-none">
                {!! nl2br(e($post->content)) !!}
            </div>
        </div>
    </div>

    <!-- Comments Section -->
    <div class="mt-8">
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">
                    💬 Comentarios ({{ $post->approvedComments->count() }})
                </h3>
            </div>

            <!-- Comment Form -->
            @auth
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <form action="{{ route('forum.store-comment', $post) }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label for="content" class="block text-sm font-medium text-gray-700 mb-2">
                            Agregar comentario
                        </label>
                        <textarea id="content" 
                                  name="content" 
                                  rows="4" 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                  placeholder="Escribe tu comentario aquí..."
                                  required>{{ old('content') }}</textarea>
                        @error('content')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" 
                                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            📝 Comentar
                        </button>
                    </div>
                </form>
            </div>
            @endauth

            <!-- Comments List -->
            <div class="divide-y divide-gray-200">
                @forelse($post->approvedComments as $comment)
                <div class="px-6 py-6">
                    <div class="flex items-start space-x-3">
                        <div class="w-10 h-10 bg-gray-500 rounded-full flex items-center justify-center text-white text-sm font-medium flex-shrink-0">
                            {{ strtoupper(substr($comment->author->name, 0, 1)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center space-x-2 mb-2">
                                <span class="font-medium text-gray-900">{{ $comment->author->name }}</span>
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                                    @if($comment->author->role === 'admin') bg-red-100 text-red-800
                                    @elseif($comment->author->role === 'teacher') bg-blue-100 text-blue-800
                                    @else bg-green-100 text-green-800
                                    @endif">
                                    {{ ucfirst($comment->author->role) }}
                                </span>
                                <span class="text-sm text-gray-500">{{ $comment->created_at->format('d/m/Y H:i') }}</span>
                                @if($comment->created_at != $comment->updated_at)
                                <span class="text-xs text-gray-400">(editado)</span>
                                @endif
                            </div>
                            <div class="prose prose-sm max-w-none text-gray-700">
                                {!! nl2br(e($comment->content)) !!}
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="px-6 py-12 text-center">
                    <div class="text-gray-400 text-4xl mb-4">💭</div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No hay comentarios aún</h3>
                    <p class="text-gray-500">
                        @auth
                        ¡Sé el primero en comentar!
                        @else
                        <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-500">Inicia sesión</a> para comentar.
                        @endauth
                    </p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Back Button -->
    <div class="mt-6 flex justify-between items-center">
        <a href="{{ route('forum.category', $post->category) }}" 
           class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            ← Volver a {{ $post->category->name }}
        </a>
        
        @auth
        <a href="{{ route('forum.create-post') }}" 
           class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            ✏️ Nueva publicación
        </a>
        @endauth
    </div>
</div>
@endsection
