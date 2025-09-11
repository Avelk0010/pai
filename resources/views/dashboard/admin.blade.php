@extends('layouts.app')

@section('title', 'Dashboard - Administrador')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Dashboard - Administrador</h1>
        <p class="text-gray-600">Bienvenido al panel de administración</p>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 mr-4">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Estudiantes</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total_students'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 mr-4">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Profesores</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total_teachers'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 mr-4">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600">Grupos Activos</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['active_groups'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 mr-4">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Materias</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total_subjects'] ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activities and Posts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Actividades Recientes</h3>
            @if(isset($recentActivities) && $recentActivities->count() > 0)
                <div class="space-y-3">
                    @foreach($recentActivities as $activity)
                    <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                        <div class="flex-1">
                            <p class="font-medium text-gray-900">{{ $activity->title }}</p>
                            <p class="text-sm text-gray-600">{{ $activity->subject->name ?? 'N/A' }}</p>
                        </div>
                        <span class="text-sm text-gray-500">{{ $activity->created_at->diffForHumans() }}</span>
                    </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500">No hay actividades recientes</p>
            @endif
        </div>

        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Posts Recientes del Foro</h3>
            @if(isset($recentPosts) && $recentPosts->count() > 0)
                <div class="space-y-3">
                    @foreach($recentPosts as $post)
                    <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                        <div class="flex-1">
                            <p class="font-medium text-gray-900">{{ $post->title }}</p>
                            <p class="text-sm text-gray-600">{{ $post->author->full_name ?? 'N/A' }}</p>
                        </div>
                        <span class="text-sm text-gray-500">{{ $post->created_at->diffForHumans() }}</span>
                    </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500">No hay posts recientes</p>
            @endif
        </div>
    </div>

    <!-- Low Enrollment Groups -->
    @if(isset($lowEnrollmentGroups) && $lowEnrollmentGroups->count() > 0)
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Grupos con Baja Matriculación</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Grupo</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estudiantes</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Capacidad</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($lowEnrollmentGroups as $group)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $group->full_name ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $group->enrollments_count ?? 0 }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $group->max_students ?? 0 }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>
@endsection
