@extends('layouts.app')

@section('title', 'Gestión de Recursos - Biblioteca')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Gestión de Recursos</h1>
                    <p class="mt-2 text-gray-600">Administra el catálogo de la biblioteca</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('library.admin.loans') }}" 
                       class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors">
                        Gestionar Préstamos
                    </a>
                    <a href="{{ route('library.admin.create-resource') }}" 
                       class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                        + Agregar Recurso
                    </a>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white p-6 rounded-lg shadow">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Total Recursos</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $statistics['total_resources'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Disponibles</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $statistics['available_resources'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-yellow-500 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">En Préstamo</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $statistics['loaned_resources'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-red-500 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">No Disponibles</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $statistics['unavailable_resources'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white p-6 rounded-lg shadow mb-8">
            <form method="GET" action="{{ route('library.admin.resources') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Buscar</label>
                    <input type="text" id="search" name="search" value="{{ request('search') }}"
                           placeholder="Título, autor, ISBN..."
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <div>
                    <label for="resource_type" class="block text-sm font-medium text-gray-700 mb-2">Tipo de Recurso</label>
                    <select id="resource_type" name="resource_type"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Todos los tipos</option>
                        <option value="book" {{ request('resource_type') == 'book' ? 'selected' : '' }}>Libro</option>
                        <option value="magazine" {{ request('resource_type') == 'magazine' ? 'selected' : '' }}>Revista</option>
                        <option value="digital" {{ request('resource_type') == 'digital' ? 'selected' : '' }}>Digital</option>
                        <option value="multimedia" {{ request('resource_type') == 'multimedia' ? 'selected' : '' }}>Multimedia</option>
                    </select>
                </div>

                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Estado</label>
                    <select id="status" name="status"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Todos los estados</option>
                        <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Disponible</option>
                        <option value="unavailable" {{ request('status') == 'unavailable' ? 'selected' : '' }}>No Disponible</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Activo</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactivo</option>
                    </select>
                </div>

                <div class="flex items-end">
                    <button type="submit" 
                            class="w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors">
                        Filtrar
                    </button>
                </div>
            </form>
        </div>

        <!-- Resources Table -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-medium text-gray-900">Recursos de la Biblioteca</h3>
                    <div class="text-sm text-gray-500">
                        {{ $resources->total() }} recursos encontrados
                    </div>
                </div>
            </div>

            @if($resources->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Recurso</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ubicación</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Disponibilidad</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($resources as $resource)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-12 w-12">
                                                <div class="h-12 w-12 rounded bg-gray-100 flex items-center justify-center">
                                                    <span class="text-lg">
                                                        @switch($resource->resource_type)
                                                            @case('book')
                                                                📚
                                                                @break
                                                            @case('magazine')
                                                                📰
                                                                @break
                                                            @case('digital')
                                                                💾
                                                                @break
                                                            @case('multimedia')
                                                                🎬
                                                                @break
                                                            @default
                                                                📄
                                                        @endswitch
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $resource->title }}</div>
                                                <div class="text-sm text-gray-500">{{ $resource->author }}</div>
                                                @if($resource->isbn)
                                                    <div class="text-xs text-gray-400">ISBN: {{ $resource->isbn }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            {{ $resource->resource_type == 'book' ? 'bg-blue-100 text-blue-800' : '' }}
                                            {{ $resource->resource_type == 'magazine' ? 'bg-green-100 text-green-800' : '' }}
                                            {{ $resource->resource_type == 'digital' ? 'bg-purple-100 text-purple-800' : '' }}
                                            {{ $resource->resource_type == 'multimedia' ? 'bg-yellow-100 text-yellow-800' : '' }}">
                                            {{ ucfirst($resource->resource_type) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $resource->location }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{ $resource->available_copies }} / {{ $resource->total_copies }}
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-2 mt-1">
                                            <div class="bg-blue-600 h-2 rounded-full" 
                                                 style="width: {{ $resource->available_copies > 0 ? ($resource->available_copies / $resource->total_copies) * 100 : 0 }}%"></div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($resource->status && $resource->isAvailable())
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                Disponible
                                            </span>
                                        @elseif($resource->status && !$resource->isAvailable())
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                En préstamo
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                Inactivo
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('library.admin.edit-resource', $resource) }}" 
                                               class="text-blue-600 hover:text-blue-900">Editar</a>
                                            
                                            <button onclick="confirmDelete({{ $resource->id }}, '{{ $resource->title }}')" 
                                                    class="text-red-600 hover:text-red-900">Eliminar</button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $resources->appends(request()->query())->links() }}
                </div>
            @else
                <div class="px-6 py-8 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No hay recursos</h3>
                    <p class="mt-1 text-sm text-gray-500">
                        @if(request()->hasAny(['search', 'resource_type', 'status']))
                            No se encontraron recursos que coincidan con los filtros aplicados.
                        @else
                            Comienza agregando tu primer recurso a la biblioteca.
                        @endif
                    </p>
                    <div class="mt-6">
                        <a href="{{ route('library.admin.create-resource') }}" 
                           class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Agregar Recurso
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Eliminar Recurso</h3>
                <button onclick="hideDeleteModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <div class="mb-4">
                <p class="text-sm text-gray-600" id="deleteModalText"></p>
            </div>
            
            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')
                
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="hideDeleteModal()" 
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition-colors">
                        Cancelar
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors">
                        Eliminar Recurso
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function confirmDelete(resourceId, resourceTitle) {
    document.getElementById('deleteModalText').innerText = 
        `¿Estás seguro de que deseas eliminar el recurso "${resourceTitle}"? Esta acción no se puede deshacer.`;
    
    document.getElementById('deleteForm').action = 
        `/library/admin/resources/${resourceId}`;
    
    document.getElementById('deleteModal').classList.remove('hidden');
}

function hideDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}
    
    form.appendChild(csrfToken);
    form.appendChild(method);
    form.appendChild(status);
    form.appendChild(title);
    
    document.body.appendChild(form);
    form.submit();
}
</script>
@endsection
