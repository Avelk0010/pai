<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sistema Educativo')</title>
    
    <!-- Tailwind CSS + Custom styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Custom styles for better UX -->
    <style>
        .btn-group > * {
            margin-left: 0.125rem;
            margin-right: 0.125rem;
        }
        .btn-group > *:first-child {
            margin-left: 0;
        }
        .btn-group > *:last-child {
            margin-right: 0;
        }
        .table-fixed {
            table-layout: fixed;
        }
        .card-shadow {
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
        }
        .card-shadow:hover {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
    </style>
    @stack('styles')
</head>
<body class="bg-gray-100 font-sans antialiased">
    <div class="min-h-screen">
        <!-- Navigation -->
        <nav class="bg-white shadow-sm border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <div class="flex-shrink-0 flex items-center">
                            <h1 class="text-xl font-bold text-gray-900">
                                🎓 Sistema Educativo
                            </h1>
                        </div>
                        
                        <!-- Navigation Links -->
                        @auth
                        <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                            <a href="{{ route('dashboard') }}" 
                               class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                                📊 Dashboard
                            </a>
                            
                            @if(auth()->user()->role === 'admin')
                            <a href="{{ route('users.index') }}" 
                               class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                                👥 Usuarios
                            </a>
                            @endif
                            
                            @if(in_array(auth()->user()->role, ['admin', 'teacher']))
                            <a href="{{ route('academic-plans.index') }}" 
                               class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                                📚 Planes Académicos
                            </a>
                            
                            <a href="{{ route('subjects.index') }}" 
                               class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                                📖 Materias
                            </a>
                            @endif
                            
                            @if(auth()->user()->role === 'student')
                            <a href="{{ route('subjects.index') }}" 
                               class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                                📖 Mis Materias
                            </a>
                            
                            <a href="{{ route('student.activities') }}" 
                               class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                                📝 Mis Actividades
                            </a>
                            
                            <a href="{{ route('student.grades') }}" 
                               class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                                📊 Mis Calificaciones
                            </a>
                            @endif
                        </div>
                        @endauth
                    </div>
                    
                    @auth
                    <div class="hidden sm:ml-6 sm:flex sm:items-center">
                        <!-- Profile dropdown -->
                        <div class="ml-3 relative">
                            <div class="flex items-center space-x-3">
                                <span class="text-gray-700">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if(Auth::user()->role === 'admin') bg-red-100 text-red-800
                                    @elseif(Auth::user()->role === 'teacher') bg-blue-100 text-blue-800  
                                    @elseif(Auth::user()->role === 'student') bg-green-100 text-green-800
                                    @else bg-yellow-100 text-yellow-800 @endif">
                                    {{ ucfirst(Auth::user()->role) }}
                                </span>
                                <form method="POST" action="{{ route('logout') }}" class="inline">
                                    @csrf
                                    <button type="submit" class="text-gray-600 hover:text-gray-900 ml-4">
                                        🚪 Cerrar Sesión
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endauth
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="py-6">
            <!-- Flash Messages -->
            @if(session('success'))
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-6">
                    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg relative" role="alert">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium">{{ session('success') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-6">
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg relative" role="alert">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium">{{ session('error') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if(session('warning'))
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-6">
                    <div class="bg-yellow-50 border border-yellow-200 text-yellow-700 px-4 py-3 rounded-lg relative" role="alert">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium">{{ session('warning') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <!-- Scripts -->
    @stack('scripts')
</body>
</html>
