@extends('layouts.app')

@section('title', 'Dashboard - Estudiante')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Dashboard - Estudiante</h1>
        <p class="text-gray-600">Panel de control para estudiantes</p>
    </div>

    <!-- No Enrollment Message -->
    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-6 rounded-lg shadow-md mb-8">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-12 w-12 text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L4.314 18.5C3.544 19.333 4.506 21 6.046 21z"/>
                </svg>
            </div>
            <div class="ml-4">
                <h3 class="text-lg font-medium text-yellow-800">
                    Matrícula Pendiente
                </h3>
                <div class="mt-2 text-sm text-yellow-700">
                    <p>Actualmente no tienes una matrícula activa para el año académico actual.</p>
                    <p class="mt-2">Por favor, contacta a la administración para procesar tu matrícula y acceder a todas las funcionalidades del sistema.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Available Features -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="flex items-center mb-4">
                <div class="p-3 rounded-full bg-blue-100 mr-4">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <div>
                    <h4 class="text-lg font-semibold text-gray-900">Perfil</h4>
                    <p class="text-sm text-gray-600">Actualiza tu información personal</p>
                </div>
            </div>
            <a href="{{ route('users.edit', Auth::user()) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                Editar Perfil →
            </a>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="flex items-center mb-4">
                <div class="p-3 rounded-full bg-green-100 mr-4">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                </div>
                <div>
                    <h4 class="text-lg font-semibold text-gray-900">Foro</h4>
                    <p class="text-sm text-gray-600">Participa en discusiones</p>
                </div>
            </div>
            <a href="#" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                Ir al Foro →
            </a>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="flex items-center mb-4">
                <div class="p-3 rounded-full bg-purple-100 mr-4">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <div>
                    <h4 class="text-lg font-semibold text-gray-900">Biblioteca</h4>
                    <p class="text-sm text-gray-600">Explora recursos disponibles</p>
                </div>
            </div>
            <a href="#" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                Ir a Biblioteca →
            </a>
        </div>
    </div>

    <!-- Contact Information -->
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Información de Contacto</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                <span class="text-gray-600">administracion@escuela.edu</span>
            </div>
            <div class="flex items-center">
                <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                </svg>
                <span class="text-gray-600">+1 (555) 123-4567</span>
            </div>
        </div>
        <div class="mt-4 p-4 bg-blue-50 rounded-lg">
            <p class="text-sm text-blue-800">
                <strong>Horario de Atención:</strong> Lunes a Viernes de 8:00 AM a 5:00 PM
            </p>
        </div>
    </div>
</div>
@endsection
