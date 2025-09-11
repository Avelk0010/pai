@extends('layouts.app')

@section('title', 'Biblioteca Escolar')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="bg-white shadow rounded-lg overflow-hidden mb-6">
        <div class="px-6 py-4 bg-gradient-to-r from-purple-500 to-indigo-600">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                        <span class="text-white text-2xl">📚</span>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-white">Biblioteca Escolar</h1>
                        <p class="text-purple-100">Recursos educativos para la comunidad académica</p>
                    </div>
                </div>
                <div class="text-right text-white">
                    <div class="text-sm">
                        <div><strong>{{ $totalResources }}</strong> recursos totales</div>
                        <div><strong>{{ $availableResources }}</strong> disponibles</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="bg-white shadow rounded-lg overflow-hidden mb-6">
        <div class="p-6">
            <form action="{{ route('library.search') }}" method="GET" class="space-y-4">
                <div class="flex flex-col lg:flex-row lg:items-center lg:space-x-4 space-y-4 lg:space-y-0">
                    <div class="flex-1">
                        <label for="q" class="block text-sm font-medium text-gray-700 mb-1">
                            🔍 Buscar recursos
                        </label>
                        <input type="text" 
                               name="q" 
                               id="q"
                               value="{{ $search ?? '' }}"
                               class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                               placeholder="Buscar por título, autor, ISBN...">
                    </div>
                    <div class="lg:w-48">
                        <label for="type" class="block text-sm font-medium text-gray-700 mb-1">
                            📂 Tipo de recurso
                        </label>
                        <select name="type" 
                                id="type"
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Todos los tipos</option>
                            @foreach($resourceTypes as $type)
                            <option value="{{ $type }}" {{ ($resourceType ?? '') === $type ? 'selected' : '' }}>
                                @switch($type)
                                    @case('book') 📖 Libro @break
                                    @case('magazine') 📰 Revista @break
                                    @case('digital') 💻 Digital @break
                                    @case('multimedia') 🎬 Multimedia @break
                                    @default {{ ucfirst($type) }}
                                @endswitch
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="lg:w-32">
                        <label class="block text-sm font-medium text-gray-700 mb-1">&nbsp;</label>
                        <button type="submit" 
                                class="w-full bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                            Buscar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        @if(Auth::user()->role !== 'admin')
        <a href="{{ route('library.my-loans') }}" 
           class="bg-white p-6 rounded-lg shadow hover:shadow-md transition-shadow">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                    <span class="text-blue-600 text-lg">📋</span>
                </div>
                <div>
                    <h3 class="font-medium text-gray-900">Mis Préstamos</h3>
                    <p class="text-sm text-gray-500">Ver préstamos activos e historial</p>
                </div>
            </div>
        </a>
        @endif

        @if(Auth::user()->role === 'admin')
        <a href="{{ route('library.admin.resources') }}" 
           class="bg-white p-6 rounded-lg shadow hover:shadow-md transition-shadow">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                    <span class="text-purple-600 text-lg">🛠️</span>
                </div>
                <div>
                    <h3 class="font-medium text-gray-900">Gestionar Recursos</h3>
                    <p class="text-sm text-gray-500">Administrar biblioteca</p>
                </div>
            </div>
        </a>

        <a href="{{ route('library.admin.loans') }}" 
           class="bg-white p-6 rounded-lg shadow hover:shadow-md transition-shadow">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                    <span class="text-red-600 text-lg">📊</span>
                </div>
                <div>
                    <h3 class="font-medium text-gray-900">Gestionar Préstamos</h3>
                    <p class="text-sm text-gray-500">Ver todos los préstamos</p>
                </div>
            </div>
        </a>
        @else
        <a href="{{ route('library.search') }}" 
           class="bg-white p-6 rounded-lg shadow hover:shadow-md transition-shadow">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                    <span class="text-green-600 text-lg">🔍</span>
                </div>
                <div>
                    <h3 class="font-medium text-gray-900">Búsqueda Avanzada</h3>
                    <p class="text-sm text-gray-500">Encontrar recursos específicos</p>
                </div>
            </div>
        </a>

        <div class="bg-white p-6 rounded-lg shadow">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <span class="text-yellow-600 text-lg">ℹ️</span>
                </div>
                <div>
                    <h3 class="font-medium text-gray-900">Información</h3>
                    <p class="text-xs text-gray-500">Límite: 5 préstamos activos<br>Duración: 14 días</p>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Resources Grid -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-medium text-gray-900">
                    @if($search || $resourceType)
                        Resultados de búsqueda ({{ $resources->total() }})
                    @else
                        Recursos Disponibles ({{ $resources->total() }})
                    @endif
                </h3>
                @if($search || $resourceType)
                <a href="{{ route('library.index') }}" 
                   class="text-indigo-600 hover:text-indigo-500 text-sm">
                    Limpiar filtros
                </a>
                @endif
            </div>
        </div>

        @if($resources->count() > 0)
        <div class="divide-y divide-gray-200">
            @foreach($resources as $resource)
            <div class="p-6 hover:bg-gray-50 transition-colors">
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0">
                        <div class="w-16 h-20 bg-gradient-to-br from-indigo-400 to-purple-500 rounded-lg flex items-center justify-center shadow-sm">
                            <span class="text-white text-2xl">
                                @switch($resource->resource_type)
                                    @case('book') 📖 @break
                                    @case('magazine') 📰 @break
                                    @case('digital') 💻 @break
                                    @case('multimedia') 🎬 @break
                                    @default 📄
                                @endswitch
                            </span>
                        </div>
                    </div>
                    
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <h4 class="text-lg font-medium text-gray-900 hover:text-indigo-600 mb-1">
                                    <a href="{{ route('library.resource', $resource) }}" class="hover:underline">
                                        {{ $resource->title }}
                                    </a>
                                </h4>
                                
                                @if($resource->author)
                                <p class="text-sm text-gray-600 mb-2">
                                    <span class="font-medium">Autor:</span> {{ $resource->author }}
                                </p>
                                @endif
                                
                                @if($resource->description)
                                <p class="text-sm text-gray-500 mb-3 line-clamp-2">
                                    {{ Str::limit($resource->description, 150) }}
                                </p>
                                @endif
                                
                                <div class="flex items-center space-x-4 text-sm text-gray-500">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        @switch($resource->resource_type)
                                            @case('book') 📖 Libro @break
                                            @case('magazine') 📰 Revista @break
                                            @case('digital') 💻 Digital @break
                                            @case('multimedia') 🎬 Multimedia @break
                                            @default {{ ucfirst($resource->resource_type) }}
                                        @endswitch
                                    </span>
                                    
                                    @if($resource->isbn)
                                    <span>📋 ISBN: {{ $resource->isbn }}</span>
                                    @endif
                                    
                                    @if($resource->location)
                                    <span>📍 {{ $resource->location }}</span>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="flex-shrink-0 ml-6">
                                <div class="text-right mb-4">
                                    <div class="text-lg font-semibold mb-1">
                                        @if($resource->isAvailable())
                                        <span class="text-green-600">✅ Disponible</span>
                                        @else
                                        <span class="text-red-600">❌ No disponible</span>
                                        @endif
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ $resource->available_copies }} de {{ $resource->total_copies }} copias
                                    </div>
                                    
                                    @if($resource->available_copies > 0)
                                    <div class="w-20 bg-gray-200 rounded-full h-2 mt-2">
                                        <div class="bg-green-400 h-2 rounded-full" 
                                             style="width: {{ $resource->availability_percentage }}%"></div>
                                    </div>
                                    @endif
                                </div>
                                
                                <div class="space-y-2">
                                    <a href="{{ route('library.resource', $resource) }}" 
                                       class="block w-full bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded text-sm font-medium text-center">
                                        Ver Detalles
                                    </a>
                                    
                                    @if($resource->isAvailable())
                                    <form action="{{ route('library.request-loan', $resource) }}" method="POST">
                                        @csrf
                                        <button type="submit" 
                                                class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded text-sm font-medium">
                                            Solicitar Préstamo
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        @if($resources->hasPages())
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
            {{ $resources->appends(request()->query())->links() }}
        </div>
        @endif

        @else
        <div class="p-12 text-center">
            <div class="text-gray-400 text-6xl mb-4">📚</div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">
                @if($search || $resourceType)
                    No se encontraron recursos
                @else
                    No hay recursos disponibles
                @endif
            </h3>
            <p class="text-gray-500 mb-6">
                @if($search || $resourceType)
                    Intenta ajustar tus criterios de búsqueda
                @else
                    Los recursos serán agregados próximamente
                @endif
            </p>
            @if($search || $resourceType)
            <a href="{{ route('library.index') }}" 
               class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                Ver todos los recursos
            </a>
            @endif
        </div>
        @endif
    </div>
</div>

<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
@endsection
