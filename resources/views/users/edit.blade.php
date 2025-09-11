@extends('layouts.app')

@section('title', 'Edit User')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Edit User: {{ $user->first_name }} {{ $user->last_name }}</h1>
            <div class="flex space-x-3">
                <a href="{{ route('users.show', $user) }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    View User
                </a>
                <a href="{{ route('users.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Back to Users
                </a>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <form method="POST" action="{{ route('users.update', $user) }}" class="space-y-6">
                @csrf
                @method('PATCH')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- First Name -->
                    <div>
                        <label for="first_name" class="block text-sm font-medium text-gray-700">First Name</label>
                        <input type="text" 
                               name="first_name" 
                               id="first_name" 
                               value="{{ old('first_name', $user->first_name) }}" 
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
                               value="{{ old('last_name', $user->last_name) }}" 
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
                           value="{{ old('email', $user->email) }}" 
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
                               value="{{ old('document', $user->document) }}" 
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
                               value="{{ old('phone', $user->phone) }}" 
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
                            <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Administrator</option>
                            <option value="teacher" {{ old('role', $user->role) === 'teacher' ? 'selected' : '' }}>Teacher</option>
                            <option value="student" {{ old('role', $user->role) === 'student' ? 'selected' : '' }}>Student</option>
                            <option value="parent" {{ old('role', $user->role) === 'parent' ? 'selected' : '' }}>Parent</option>
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
                            <option value="1" {{ old('status', $user->status ? '1' : '0') === '1' ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ old('status', $user->status ? '1' : '0') === '0' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('status')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Password (Optional for editing) -->
                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Change Password (Optional)</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700">New Password</label>
                            <input type="password" 
                                   name="password" 
                                   id="password" 
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('password') border-red-300 @enderror">
                            @error('password')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">Leave blank to keep current password</p>
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm New Password</label>
                            <input type="password" 
                                   name="password_confirmation" 
                                   id="password_confirmation" 
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>
                </div>

                <!-- Role-specific fields -->
                <div id="studentFields" class="hidden border-t border-gray-200 pt-6 space-y-4">
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
                                        <option value="{{ $level->id }}" 
                                            {{ (old('grade_level_id') ?? ($user->enrollments->first()->grade_level_id ?? '')) == $level->id ? 'selected' : '' }}>
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
                                        <option value="{{ $group->id }}" 
                                            {{ (old('group_id') ?? ($user->enrollments->first()->group_id ?? '')) == $group->id ? 'selected' : '' }}>
                                            {{ $group->name }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                </div>

                <div id="teacherFields" class="hidden border-t border-gray-200 pt-6 space-y-4">
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
                                    Las asignaciones de materias se gestionan desde la sección 
                                    <strong>"Asignaciones Académicas"</strong> para mayor control y flexibilidad.
                                </p>
                                <div class="mt-3 flex space-x-3">
                                    <a href="{{ route('assignments.teacher', $user) }}" 
                                       class="inline-flex items-center px-3 py-1 border border-blue-300 text-xs font-medium rounded-md text-blue-700 bg-white hover:bg-blue-50">
                                        Ver Asignaciones
                                    </a>
                                    <a href="{{ route('assignments.create') }}?teacher_id={{ $user->id }}" 
                                       class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                        Crear Asignación
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Account Status -->
                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Account Status</h3>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="font-medium text-gray-700">Account Created:</span>
                                <span class="text-gray-900">{{ $user->created_at->format('M j, Y g:i A') }}</span>
                            </div>
                            <div>
                                <span class="font-medium text-gray-700">Last Updated:</span>
                                <span class="text-gray-900">{{ $user->updated_at->format('M j, Y g:i A') }}</span>
                            </div>
                            <div>
                                <span class="font-medium text-gray-700">Email Verified:</span>
                                @if($user->email_verified_at)
                                    <span class="text-green-600">Yes ({{ $user->email_verified_at->format('M j, Y') }})</span>
                                @else
                                    <span class="text-red-600">No</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                    <a href="{{ route('users.show', $user) }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                        Cancel
                    </a>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Update User
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

    // Trigger on page load to show current role fields
    document.addEventListener('DOMContentLoaded', function() {
        const roleSelect = document.getElementById('role');
        if (roleSelect.value) {
            roleSelect.dispatchEvent(new Event('change'));
        }
    });
</script>
@endpush
@endsection
