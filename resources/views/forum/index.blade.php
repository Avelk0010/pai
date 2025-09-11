@extends('layouts.app')

@section('title', 'Foro Académico')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">
                💬 Foro Académico
            </h1>
            <p class="text-gray-600 mt-1">
                Espacio de discusión académica para toda la comunidad educativa
            </p>
        </div>
        <div>
            <a href="{{ route('forum.create-post') }}" 
               class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-medium inline-flex items-center">
                ✏️ Nueva Publicación
            </a>
        </div>
    </div>

    <!-- Search Bar -->
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <form action="{{ route('forum.search') }}" method="GET" class="flex gap-4">
            <div class="flex-1">
                <input type="text" 
                       name="q" 
                       value="{{ request('q') }}"
                       placeholder="Buscar en el foro..." 
                       class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <button type="submit" 
                    class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-lg font-medium">
                🔍 Buscar
            </button>
        </form>
    </div>

    <!-- Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-blue-500 text-white rounded-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">Total Publicaciones</p>
                    <p class="text-3xl font-bold">{{ $stats['total_posts'] }}</p>
                </div>
                <div class="text-blue-200 text-2xl">📝</div>
            </div>
        </div>
        
        <div class="bg-green-500 text-white rounded-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium">Total Comentarios</p>
                    <p class="text-3xl font-bold">{{ $stats['total_comments'] }}</p>
                </div>
                <div class="text-green-200 text-2xl">💬</div>
            </div>
        </div>
        
        <div class="bg-purple-500 text-white rounded-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm font-medium">Categorías</p>
                    <p class="text-3xl font-bold">{{ $stats['total_categories'] }}</p>
                </div>
                <div class="text-purple-200 text-2xl">📁</div>
            </div>
        </div>
        
        @if(auth()->user()->role === 'admin')
        <div class="bg-orange-500 text-white rounded-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-orange-100 text-sm font-medium">Pendientes</p>
                    <p class="text-3xl font-bold">{{ $stats['pending_posts'] }}</p>
                </div>
                <div class="text-orange-200 text-2xl">⏳</div>
            </div>
        </div>
        @else
        <div class="bg-gray-500 text-white rounded-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-100 text-sm font-medium">Mi Actividad</p>
                    <a href="{{ route('forum.user-activity') }}" class="text-2xl font-bold hover:underline">Ver →</a>
                </div>
                <div class="text-gray-200 text-2xl">👤</div>
            </div>
        </div>
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Categories -->
        <div class="lg:col-span-2">
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">
                        📁 Categorías del Foro
                    </h3>
                </div>
                
                @if($categories->count() > 0)
                <div class="divide-y divide-gray-200">
                    @foreach($categories as $category)
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <a href="{{ route('forum.category', $category) }}" 
                                   class="block hover:bg-gray-50 -m-2 p-2 rounded">
                                    <div class="flex items-center">
                                        @if($category->color)
                                        <div class="w-4 h-4 rounded-full mr-3" 
                                             style="background-color: {{ $category->color }}"></div>
                                        @endif
                                        <div class="flex-1">
                                            <h4 class="text-lg font-semibold text-gray-900">
                                                {{ $category->name }}
                                            </h4>
                                            @if($category->description)
                                            <p class="text-sm text-gray-600 mt-1">
                                                {{ $category->description }}
                                            </p>
                                            @endif
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="ml-6 text-right">
                                <p class="text-2xl font-bold text-gray-900">{{ $category->approved_posts_count }}</p>
                                <p class="text-xs text-gray-500">publicaciones</p>
                                
                                @if($category->latestPost)
                                <div class="mt-2">
                                    <p class="text-xs text-gray-500">Última:</p>
                                    <a href="{{ route('forum.post', $category->latestPost) }}" 
                                       class="text-xs text-indigo-600 hover:underline">
                                        {{ Str::limit($category->latestPost->title, 30) }}
                                    </a>
                                    <p class="text-xs text-gray-400">
                                        {{ $category->latestPost->created_at->diffForHumans() }}
                                    </p>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-12">
                    <div class="text-gray-400 text-6xl mb-4">📁</div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No hay categorías disponibles</h3>
                    <p class="text-gray-500">Las categorías del foro se mostrarán aquí cuando estén disponibles.</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Recent Posts Sidebar -->
        <div class="space-y-6">
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">
                        🔥 Publicaciones Recientes
                    </h3>
                </div>
                
                @if($recentPosts->count() > 0)
                <div class="divide-y divide-gray-200">
                    @foreach($recentPosts as $post)
                    <div class="p-4">
                        <a href="{{ route('forum.post', $post) }}" 
                           class="block hover:bg-gray-50 -m-2 p-2 rounded">
                            <h4 class="text-sm font-medium text-gray-900 mb-1">
                                {{ Str::limit($post->title, 60) }}
                            </h4>
                            <div class="flex items-center text-xs text-gray-500 space-x-2">
                                <span>{{ $post->author->name }}</span>
                                <span>•</span>
                                <span>{{ $post->category->name }}</span>
                                <span>•</span>
                                <span>{{ $post->created_at->diffForHumans() }}</span>
                            </div>
                            <div class="flex items-center mt-2 text-xs text-gray-400">
                                <span>👁️ {{ $post->views }} vistas</span>
                                <span class="mx-2">•</span>
                                <span>💬 {{ $post->approved_comments_count }} respuestas</span>
                            </div>
                        </a>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-8">
                    <div class="text-gray-400 text-4xl mb-2">📝</div>
                    <p class="text-gray-500 text-sm">No hay publicaciones recientes</p>
                </div>
                @endif
            </div>

            <!-- Quick Links -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">
                        🔗 Enlaces Rápidos
                    </h3>
                </div>
                <div class="p-4 space-y-2">
                    <a href="{{ route('forum.create-post') }}" 
                       class="block text-sm text-indigo-600 hover:underline">
                        ✏️ Crear nueva publicación
                    </a>
                    <a href="{{ route('forum.user-activity') }}" 
                       class="block text-sm text-indigo-600 hover:underline">
                        👤 Mi actividad en el foro
                    </a>
                    @if(auth()->user()->role === 'admin')
                    <a href="{{ route('forum.moderation.dashboard') }}" 
                       class="block text-sm text-indigo-600 hover:underline">
                        🛡️ Panel de moderación
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
