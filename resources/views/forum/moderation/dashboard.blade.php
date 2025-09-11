@extends('layouts.app')

@section('title', 'Panel de Moderación - Foro')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">
                🛡️ Panel de Moderación
            </h1>
            <p class="text-gray-600 mt-1">
                Administra y modera el contenido del foro académico
            </p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('forum.index') }}" 
               class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                ← Volver al Foro
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-orange-500 text-white rounded-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-orange-100 text-sm font-medium">Publicaciones Pendientes</p>
                    <p class="text-3xl font-bold">{{ $stats['pending_posts'] }}</p>
                </div>
                <div class="text-orange-200 text-2xl">📝</div>
            </div>
        </div>
        
        <div class="bg-yellow-500 text-white rounded-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-yellow-100 text-sm font-medium">Comentarios Pendientes</p>
                    <p class="text-3xl font-bold">{{ $stats['pending_comments'] }}</p>
                </div>
                <div class="text-yellow-200 text-2xl">💬</div>
            </div>
        </div>
        
        <div class="bg-green-500 text-white rounded-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium">Posts Aprobados</p>
                    <p class="text-3xl font-bold">{{ $stats['approved_posts'] }}</p>
                </div>
                <div class="text-green-200 text-2xl">✅</div>
            </div>
        </div>
        
        <div class="bg-blue-500 text-white rounded-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">Comentarios Aprobados</p>
                    <p class="text-3xl font-bold">{{ $stats['approved_comments'] }}</p>
                </div>
                <div class="text-blue-200 text-2xl">💭</div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">
        <!-- Pending Posts -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900 flex items-center">
                    📝 Publicaciones Pendientes
                    @if($stats['pending_posts'] > 0)
                    <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                        {{ $stats['pending_posts'] }}
                    </span>
                    @endif
                </h3>
            </div>
            
            @if($pendingPosts->count() > 0)
            <div class="divide-y divide-gray-200">
                @foreach($pendingPosts as $post)
                <div class="p-6">
                    <div class="flex justify-between items-start">
                        <div class="flex-1 min-w-0">
                            <h4 class="text-lg font-semibold text-gray-900 mb-2">
                                {{ $post->title }}
                            </h4>
                            <div class="flex items-center space-x-4 text-sm text-gray-600 mb-3">
                                <div class="flex items-center space-x-2">
                                    <div class="w-6 h-6 bg-gray-500 rounded-full flex items-center justify-center text-white text-xs font-medium">
                                        {{ strtoupper(substr($post->author->name ?: 'U', 0, 1)) }}
                                    </div>
                                    <span>{{ $post->author->name ?: 'Usuario sin nombre' }}</span>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
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
                                <span class="text-purple-600">{{ $post->category->name }}</span>
                            </div>
                            <p class="text-gray-700 text-sm mb-4">
                                {{ Str::limit(strip_tags($post->content), 150) }}
                            </p>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                        <a href="{{ route('forum.post', $post) }}" 
                           class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            👁️ Ver completo
                        </a>
                        <div class="flex space-x-2">
                            <form action="{{ route('forum.post.approve', $post) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" 
                                        class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-sm font-medium"
                                        onclick="return confirm('¿Aprobar esta publicación?')">
                                    ✅ Aprobar
                                </button>
                            </form>
                            <form action="{{ route('forum.post.reject', $post) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-sm font-medium"
                                        onclick="return confirm('¿Rechazar y eliminar esta publicación?')">
                                    ❌ Rechazar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            
            @if($pendingPosts->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $pendingPosts->appends(request()->query())->links() }}
            </div>
            @endif
            @else
            <div class="text-center py-12">
                <div class="text-gray-400 text-4xl mb-4">✅</div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No hay publicaciones pendientes</h3>
                <p class="text-gray-500">Todas las publicaciones han sido revisadas.</p>
            </div>
            @endif
        </div>

        <!-- Pending Comments -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900 flex items-center">
                    💬 Comentarios Pendientes
                    @if($stats['pending_comments'] > 0)
                    <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                        {{ $stats['pending_comments'] }}
                    </span>
                    @endif
                </h3>
            </div>
            
            @if($pendingComments->count() > 0)
            <div class="divide-y divide-gray-200">
                @foreach($pendingComments as $comment)
                <div class="p-6">
                    <div class="flex justify-between items-start">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center space-x-4 text-sm text-gray-600 mb-2">
                                <div class="flex items-center space-x-2">
                                    <div class="w-6 h-6 bg-gray-500 rounded-full flex items-center justify-center text-white text-xs font-medium">
                                        {{ strtoupper(substr($comment->author->name ?: 'U', 0, 1)) }}
                                    </div>
                                    <span>{{ $comment->author->name ?: 'Usuario sin nombre' }}</span>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                                        @if($comment->author->role === 'admin') bg-red-100 text-red-800
                                        @elseif($comment->author->role === 'teacher') bg-blue-100 text-blue-800
                                        @else bg-green-100 text-green-800
                                        @endif">
                                        {{ ucfirst($comment->author->role) }}
                                    </span>
                                </div>
                                <span>•</span>
                                <span>{{ $comment->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                            
                            <div class="mb-3">
                                <p class="text-xs text-gray-500 mb-1">
                                    Comentario en: <a href="{{ route('forum.post', $comment->post) }}" class="text-blue-600 hover:underline">{{ Str::limit($comment->post->title, 50) }}</a>
                                </p>
                                <div class="bg-gray-50 rounded-lg p-3">
                                    <p class="text-gray-700 text-sm">
                                        {{ $comment->content }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                        <a href="{{ route('forum.post', $comment->post) }}" 
                           class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            👁️ Ver en contexto
                        </a>
                        <div class="flex space-x-2">
                            <form action="{{ route('forum.comment.approve', $comment) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" 
                                        class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-sm font-medium"
                                        onclick="return confirm('¿Aprobar este comentario?')">
                                    ✅ Aprobar
                                </button>
                            </form>
                            <form action="{{ route('forum.comment.reject', $comment) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-sm font-medium"
                                        onclick="return confirm('¿Rechazar y eliminar este comentario?')">
                                    ❌ Rechazar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            
            @if($pendingComments->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $pendingComments->appends(request()->query())->links() }}
            </div>
            @endif
            @else
            <div class="text-center py-12">
                <div class="text-gray-400 text-4xl mb-4">✅</div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No hay comentarios pendientes</h3>
                <p class="text-gray-500">Todos los comentarios han sido revisados.</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="mt-8 bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">
                ⚡ Acciones Rápidas
            </h3>
        </div>
        <div class="px-6 py-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <a href="{{ route('forum.index') }}" 
                   class="flex items-center p-4 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center">
                            <span class="text-white text-lg">💬</span>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h4 class="text-sm font-medium text-gray-900">Ver Foro Principal</h4>
                        <p class="text-sm text-gray-600">Navegar por todas las categorías</p>
                    </div>
                </a>
                
                <a href="{{ route('forum.user-activity') }}" 
                   class="flex items-center p-4 bg-green-50 hover:bg-green-100 rounded-lg transition-colors">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center">
                            <span class="text-white text-lg">👤</span>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h4 class="text-sm font-medium text-gray-900">Mi Actividad</h4>
                        <p class="text-sm text-gray-600">Ver mis publicaciones y comentarios</p>
                    </div>
                </a>
                
                <a href="{{ route('forum.create-post') }}" 
                   class="flex items-center p-4 bg-purple-50 hover:bg-purple-100 rounded-lg transition-colors">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-purple-500 rounded-lg flex items-center justify-center">
                            <span class="text-white text-lg">✏️</span>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h4 class="text-sm font-medium text-gray-900">Nueva Publicación</h4>
                        <p class="text-sm text-gray-600">Crear nueva publicación</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
