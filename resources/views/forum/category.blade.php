@extends('layouts.app')

@section('title', $category->name . ' - Foro Académico')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center space-x-2 text-sm text-gray-500 mb-2">
            <a href="{{ route('forum.index') }}" class="hover:text-indigo-600">Foro</a>
            <span>›</span>
            <span>{{ $category->name }}</span>
        </div>
        
        <div class="flex justify-between items-center">
            <div class="flex items-center">
                @if($category->color)
                <div class="w-6 h-6 rounded-full mr-3" 
                     style="background-color: {{ $category->color }}"></div>
                @endif
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">
                        {{ $category->name }}
                    </h1>
                    @if($category->description)
                    <p class="text-gray-600 mt-1">
                        {{ $category->description }}
                    </p>
                    @endif
                </div>
            </div>
            <div>
                <a href="{{ route('forum.create-post') }}?category={{ $category->id }}" 
                   class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-medium inline-flex items-center">
                    ✏️ Nueva Publicación
                </a>
            </div>
        </div>
    </div>

    <!-- Posts List -->
    <div class="bg-white shadow rounded-lg">
        @if($posts->count() > 0)
        <div class="divide-y divide-gray-200">
            @foreach($posts as $post)
            <div class="p-6 hover:bg-gray-50">
                <div class="flex justify-between items-start">
                    <div class="flex-1">
                        <div class="flex items-center space-x-2 mb-2">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                👤 {{ $post->author->name }}
                            </span>
                            <span class="text-gray-400">•</span>
                            <span class="text-sm text-gray-500">
                                {{ $post->created_at->diffForHumans() }}
                            </span>
                            @if($post->updated_at != $post->created_at)
                            <span class="text-gray-400">•</span>
                            <span class="text-sm text-gray-500">
                                Editado {{ $post->updated_at->diffForHumans() }}
                            </span>
                            @endif
                        </div>
                        
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">
                            <a href="{{ route('forum.post', $post) }}" 
                               class="hover:text-indigo-600">
                                {{ $post->title }}
                            </a>
                        </h3>
                        
                        <p class="text-gray-600 text-sm line-clamp-3">
                            {{ Str::limit(strip_tags($post->content), 200) }}
                        </p>
                        
                        <div class="flex items-center space-x-4 mt-3">
                            <span class="text-sm text-gray-500">
                                👁️ {{ $post->views }} vistas
                            </span>
                            <span class="text-sm text-gray-500">
                                💬 {{ $post->approved_comments_count }} respuestas
                            </span>
                            @if($post->latestComment)
                            <span class="text-gray-400">•</span>
                            <span class="text-sm text-gray-500">
                                Último comentario por {{ $post->latestComment->author->name }}
                                {{ $post->latestComment->created_at->diffForHumans() }}
                            </span>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Actions -->
                    @if(auth()->user()->role === 'admin' || auth()->id() === $post->author_id)
                    <div class="ml-4">
                        <div class="flex items-center space-x-2">
                            @if(auth()->id() === $post->author_id)
                            <a href="{{ route('forum.post', $post) }}" 
                               class="text-indigo-600 hover:text-indigo-800 text-sm">
                                📝 Ver/Editar
                            </a>
                            @endif
                            @if(auth()->user()->role === 'admin')
                            <span class="text-gray-400">|</span>
                            <span class="text-sm {{ $post->is_approved ? 'text-green-600' : 'text-yellow-600' }}">
                                {{ $post->is_approved ? '✅ Aprobado' : '⏳ Pendiente' }}
                            </span>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        
        <!-- Pagination -->
        @if($posts->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $posts->links() }}
        </div>
        @endif
        @else
        <div class="text-center py-12">
            <div class="text-gray-400 text-6xl mb-4">📝</div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No hay publicaciones en esta categoría</h3>
            <p class="text-gray-500 mb-4">¡Sé el primero en crear una publicación!</p>
            <a href="{{ route('forum.create-post') }}?category={{ $category->id }}" 
               class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-medium inline-flex items-center">
                ✏️ Crear Primera Publicación
            </a>
        </div>
        @endif
    </div>
</div>

<style>
.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
@endsection
