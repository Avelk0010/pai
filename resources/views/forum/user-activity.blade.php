@extends('layouts.app')

@section('title', 'Mi Actividad en el Foro')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="bg-white shadow rounded-lg overflow-hidden mb-6">
        <div class="px-6 py-4 bg-gradient-to-r from-blue-500 to-purple-600">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                        <span class="text-white text-lg font-bold">{{ Auth::user()->initials() }}</span>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-white">Mi Actividad en el Foro</h1>
                        <p class="text-blue-100">{{ Auth::user()->name }} • {{ ucfirst(Auth::user()->role) }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <div class="text-white text-sm">
                        <div class="font-semibold">📝 {{ $posts->total() }} {{ $posts->total() == 1 ? 'Publicación' : 'Publicaciones' }}</div>
                        <div class="font-semibold">💬 {{ $comments->total() }} {{ $comments->total() == 1 ? 'Comentario' : 'Comentarios' }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <div class="bg-white shadow rounded-lg overflow-hidden mb-6">
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex space-x-8 px-6" aria-label="Tabs">
                <button onclick="showTab('posts')" id="posts-tab" 
                        class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    📝 Mis Publicaciones ({{ $posts->total() }})
                </button>
                <button onclick="showTab('comments')" id="comments-tab"
                        class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    💬 Mis Comentarios ({{ $comments->total() }})
                </button>
            </nav>
        </div>
    </div>

    <!-- Posts Tab -->
    <div id="posts-content" class="tab-content">
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">📝 Mis Publicaciones</h3>
                <p class="text-sm text-gray-600 mt-1">Todas las publicaciones que has creado en el foro</p>
            </div>

            <div class="divide-y divide-gray-200">
                @forelse($posts as $post)
                <div class="px-6 py-4 hover:bg-gray-50 transition-colors duration-200">
                    <div class="flex items-start justify-between">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center space-x-3 mb-2">
                                <h4 class="text-lg font-medium text-gray-900 hover:text-blue-600">
                                    <a href="{{ route('forum.post', $post) }}" class="hover:underline">
                                        {{ $post->title }}
                                    </a>
                                </h4>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" 
                                      style="background-color: {{ $post->category->color }}20; color: {{ $post->category->color }};">
                                    {{ $post->category->name }}
                                </span>
                                @if(!$post->is_approved)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                    ⏳ Pendiente
                                </span>
                                @endif
                            </div>
                            
                            <p class="text-sm text-gray-600 mb-3 line-clamp-2">
                                {{ Str::limit(strip_tags($post->content), 150) }}
                            </p>
                            
                            <div class="flex items-center space-x-4 text-xs text-gray-500">
                                <span class="flex items-center space-x-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    <span>{{ $post->views }} vistas</span>
                                </span>
                                <span class="flex items-center space-x-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                    </svg>
                                    <span>{{ $post->approved_comments_count }} comentarios</span>
                                </span>
                                <span>📅 {{ $post->created_at->format('d/m/Y H:i') }}</span>
                                @if($post->created_at != $post->updated_at)
                                <span class="text-blue-600">✏️ Editado</span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="flex-shrink-0 ml-4">
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('forum.post', $post) }}" 
                                   class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    👁️ Ver
                                </a>
                                <a href="{{ route('forum.edit-post', $post) }}" 
                                   class="inline-flex items-center px-3 py-1.5 border border-transparent shadow-sm text-xs font-medium rounded text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    ✏️ Editar
                                </a>
                                <form action="{{ route('forum.delete-post', $post) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="inline-flex items-center px-3 py-1.5 border border-transparent shadow-sm text-xs font-medium rounded text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                        🗑️ Eliminar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="px-6 py-12 text-center">
                    <div class="text-gray-400 text-5xl mb-4">📝</div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No tienes publicaciones aún</h3>
                    <p class="text-gray-500 mb-6">¡Crea tu primera publicación y comparte con la comunidad!</p>
                    <a href="{{ route('forum.create-post') }}" 
                       class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        ✏️ Nueva Publicación
                    </a>
                </div>
                @endforelse
            </div>

            @if($posts->hasPages())
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                {{ $posts->appends(['comments_page' => request('comments_page')])->links() }}
            </div>
            @endif
        </div>
    </div>

    <!-- Comments Tab -->
    <div id="comments-content" class="tab-content hidden">
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">💬 Mis Comentarios</h3>
                <p class="text-sm text-gray-600 mt-1">Todos los comentarios que has realizado</p>
            </div>

            <div class="divide-y divide-gray-200">
                @forelse($comments as $comment)
                <div class="px-6 py-4 hover:bg-gray-50 transition-colors duration-200">
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-gray-500 rounded-full flex items-center justify-center text-white text-sm font-medium">
                                {{ Auth::user()->initials() }}
                            </div>
                        </div>
                        
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center space-x-2 mb-2">
                                <h4 class="text-sm font-medium text-gray-900">
                                    En: <a href="{{ route('forum.post', $comment->post) }}" 
                                           class="text-blue-600 hover:text-blue-500 hover:underline">
                                        {{ $comment->post->title }}
                                    </a>
                                </h4>
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium" 
                                      style="background-color: {{ $comment->post->category->color }}20; color: {{ $comment->post->category->color }};">
                                    {{ $comment->post->category->name }}
                                </span>
                                @if(!$comment->is_approved)
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-orange-100 text-orange-800">
                                    ⏳ Pendiente
                                </span>
                                @endif
                            </div>
                            
                            <div class="prose prose-sm max-w-none text-gray-700 mb-3">
                                {!! nl2br(e(Str::limit($comment->content, 200))) !!}
                                @if(strlen($comment->content) > 200)
                                <a href="{{ route('forum.post', $comment->post) }}#comment-{{ $comment->id }}" 
                                   class="text-blue-600 hover:text-blue-500 text-sm">
                                    Leer más...
                                </a>
                                @endif
                            </div>
                            
                            <div class="flex items-center space-x-4 text-xs text-gray-500">
                                <span>📅 {{ $comment->created_at->format('d/m/Y H:i') }}</span>
                                @if($comment->created_at != $comment->updated_at)
                                <span class="text-blue-600">✏️ Editado</span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="flex-shrink-0">
                            <a href="{{ route('forum.post', $comment->post) }}#comment-{{ $comment->id }}" 
                               class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                👁️ Ver en contexto
                            </a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="px-6 py-12 text-center">
                    <div class="text-gray-400 text-5xl mb-4">💬</div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No tienes comentarios aún</h3>
                    <p class="text-gray-500 mb-6">¡Participa en las discusiones y deja tu opinión!</p>
                    <a href="{{ route('forum.index') }}" 
                       class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        🔍 Explorar Foro
                    </a>
                </div>
                @endforelse
            </div>

            @if($comments->hasPages())
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                {{ $comments->appends(['posts_page' => request('posts_page')])->links() }}
            </div>
            @endif
        </div>
    </div>

    <!-- Back Button -->
    <div class="mt-6">
        <a href="{{ route('forum.index') }}" 
           class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            ← Volver al Foro
        </a>
    </div>
</div>

<script>
function showTab(tabName) {
    // Hide all tab contents
    const tabContents = document.querySelectorAll('.tab-content');
    tabContents.forEach(content => {
        content.classList.add('hidden');
    });
    
    // Remove active styles from all tab buttons
    const tabButtons = document.querySelectorAll('.tab-button');
    tabButtons.forEach(button => {
        button.classList.remove('border-blue-500', 'text-blue-600');
        button.classList.add('border-transparent', 'text-gray-500');
    });
    
    // Show selected tab content
    document.getElementById(tabName + '-content').classList.remove('hidden');
    
    // Add active styles to selected tab button
    const activeButton = document.getElementById(tabName + '-tab');
    activeButton.classList.remove('border-transparent', 'text-gray-500');
    activeButton.classList.add('border-blue-500', 'text-blue-600');
}

// Show posts tab by default
document.addEventListener('DOMContentLoaded', function() {
    showTab('posts');
    
    // Add enhanced delete confirmation
    const deleteForms = document.querySelectorAll('form[action*="delete-post"]');
    deleteForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const postTitle = this.querySelector('button').closest('.px-6').querySelector('h4 a').textContent.trim();
            const isApproved = !this.querySelector('button').closest('.px-6').querySelector('.bg-orange-100');
            
            let message = `¿Estás seguro de que quieres eliminar esta publicación?\n\n📝 "${postTitle}"\n\n`;
            if (isApproved) {
                message += 'Esta publicación está aprobada y es visible para otros usuarios.\n\n';
            } else {
                message += 'Esta publicación está pendiente de aprobación.\n\n';
            }
            message += '⚠️ Esta acción no se puede deshacer y se eliminarán también todos los comentarios.';
            
            if (confirm(message)) {
                this.submit();
            }
        });
    });
});
</script>

<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
@endsection
