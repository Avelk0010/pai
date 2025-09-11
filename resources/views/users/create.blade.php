@extends('layouts.app')

@section('title', 'Create User')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Create New User</h1>
            <a href="{{ route('users.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Back to Users
            </a>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <form method="POST" action="{{ route('users.store') }}" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- First Name -->
                    <div>
                        <label for="first_name" class="block text-sm font-medium text-gray-700">First Name</label>
                        <input type="text" 
                               name="first_name" 
                               id="first_name" 
                               value="{{ old('first_name') }}" 
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('first_name') border-red-300 @enderror"
                               required>
                        @error('first_name')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Last Name -->
                    <div>
                        <label for="last_name" class="block text-sm font-medium text-gray-700">Last Name</label>
                        <input type="text" 
                               name="last_name" 
                               id="last_name" 
                               value="{{ old('last_name') }}" 
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('last_name') border-red-300 @enderror"
                               required>
                        @error('last_name')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                    <input type="email" 
                           name="email" 
                           id="email" 
                           value="{{ old('email') }}" 
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('email') border-red-300 @enderror"
                           required>
                    @error('email')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Document -->
                    <div>
                        <label for="document" class="block text-sm font-medium text-gray-700">Document ID</label>
                        <input type="text" 
                               name="document" 
                               id="document" 
                               value="{{ old('document') }}" 
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('document') border-red-300 @enderror"
                               required>
                        @error('document')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Phone -->
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
                        <input type="text" 
                               name="phone" 
                               id="phone" 
                               value="{{ old('phone') }}" 
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('phone') border-red-300 @enderror">
                        @error('phone')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Role -->
                    <div>
                        <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
                        <select name="role" 
                                id="role" 
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('role') border-red-300 @enderror"
                                required>
                            <option value="">Select a role</option>
                            <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Administrator</option>
                            <option value="teacher" {{ old('role') === 'teacher' ? 'selected' : '' }}>Teacher</option>
                            <option value="student" {{ old('role') === 'student' ? 'selected' : '' }}>Student</option>
                            <option value="parent" {{ old('role') === 'parent' ? 'selected' : '' }}>Parent</option>
                        </select>
                        @error('role')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                        <select name="status" 
                                id="status" 
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('status') border-red-300 @enderror"
                                required>
                            <option value="1" {{ old('status', '1') === '1' ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ old('status') === '0' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('status')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Password -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                        <input type="password" 
                               name="password" 
                               id="password" 
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('password') border-red-300 @enderror"
                               required>
                        @error('password')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Minimum 8 characters</p>
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                        <input type="password" 
                               name="password_confirmation" 
                               id="password_confirmation" 
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                               required>
                    </div>
                </div>

                <!-- Role-specific fields -->
                <div id="studentFields" class="hidden space-y-4">
                    <h3 class="text-lg font-medium text-gray-900">Student Information</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="grade_level_id" class="block text-sm font-medium text-gray-700">Grade Level</label>
                            <select name="grade_level_id" 
                                    id="grade_level_id" 
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Select grade level</option>
                                @if(isset($gradeLevels))
                                    @foreach($gradeLevels as $level)
                                        <option value="{{ $level->id }}" {{ old('grade_level_id') == $level->id ? 'selected' : '' }}>
                                            {{ $level->name }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <div>
                            <label for="group_id" class="block text-sm font-medium text-gray-700">Group</label>
                            <select name="group_id" 
                                    id="group_id" 
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Select group</option>
                                @if(isset($groups))
                                    @foreach($groups as $group)
                                        <option value="{{ $group->id }}" {{ old('group_id') == $group->id ? 'selected' : '' }}>
                                            {{ $group->name }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                </div>

                <div id="teacherFields" class="hidden space-y-4">
                    <h3 class="text-lg font-medium text-gray-900">Teacher Information</h3>
                    
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h4 class="text-sm font-medium text-blue-800">Asignación de Materias</h4>
                                <p class="text-sm text-blue-700 mt-1">
                                    Una vez creado el profesor, podrás asignarle materias y grupos específicos desde la sección 
                                    <strong>"Asignaciones Académicas"</strong> en el menú principal.
                                </p>
                                <p class="text-xs text-blue-600 mt-2">
                                    Esto permite un control más granular sobre qué profesor enseña qué materia a cuáles grupos.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                    <a href="{{ route('users.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                        Cancel
                    </a>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Create User
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Show/hide role-specific fields
    document.getElementById('role').addEventListener('change', function() {
        const role = this.value;
        const studentFields = document.getElementById('studentFields');
        const teacherFields = document.getElementById('teacherFields');
        
        // Hide all role-specific fields first
        studentFields.classList.add('hidden');
        teacherFields.classList.add('hidden');
        
        // Show relevant fields based on role
        if (role === 'student') {
            studentFields.classList.remove('hidden');
        } else if (role === 'teacher') {
            teacherFields.classList.remove('hidden');
        }
    });

    // Trigger on page load if role is already selected (for validation errors)
    document.addEventListener('DOMContentLoaded', function() {
        const roleSelect = document.getElementById('role');
        if (roleSelect.value) {
            roleSelect.dispatchEvent(new Event('change'));
        }
    });
</script>
@endpush
@endsection
