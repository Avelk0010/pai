@extends('layouts.app')

@section('title', 'User Details')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-900">User Details</h1>
            <div class="flex space-x-3">
                <a href="{{ route('users.edit', $user) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Edit User
                </a>
                <a href="{{ route('users.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Back to Users
                </a>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow">
            <!-- User Header -->
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center">
                    <div class="flex-shrink-0 h-20 w-20">
                        <div class="h-20 w-20 rounded-full bg-gray-300 flex items-center justify-center">
                            <span class="text-2xl font-bold text-gray-700">{{ strtoupper(substr($user->first_name, 0, 1)) }}{{ strtoupper(substr($user->last_name, 0, 1)) }}</span>
                        </div>
                    </div>
                    <div class="ml-6">
                        <h2 class="text-2xl font-bold text-gray-900">{{ $user->first_name }} {{ $user->last_name }}</h2>
                        <p class="text-gray-600">{{ $user->email }}</p>
                        <div class="mt-2 flex space-x-2">
                            <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full 
                                @if($user->role === 'admin') bg-red-100 text-red-800
                                @elseif($user->role === 'teacher') bg-blue-100 text-blue-800
                                @elseif($user->role === 'student') bg-green-100 text-green-800
                                @else bg-yellow-100 text-yellow-800 @endif">
                                {{ ucfirst($user->role) }}
                            </span>
                            @if($user->status)
                                <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800">
                                    Active
                                </span>
                            @else
                                <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-red-100 text-red-800">
                                    Inactive
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- User Information -->
            <div class="px-6 py-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Basic Information -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Basic Information</h3>
                        <dl class="space-y-3">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Full Name</dt>
                                <dd class="text-sm text-gray-900">{{ $user->first_name }} {{ $user->last_name }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Email</dt>
                                <dd class="text-sm text-gray-900">{{ $user->email }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Role</dt>
                                <dd class="text-sm text-gray-900">{{ ucfirst($user->role) }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Status</dt>
                                <dd class="text-sm text-gray-900">{{ $user->status ? 'Active' : 'Inactive' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Email Verification</dt>
                                <dd class="text-sm text-gray-900">
                                    @if($user->email_verified_at)
                                        <span class="text-green-600">Verified</span>
                                        <span class="text-gray-500">({{ $user->email_verified_at->format('M j, Y g:i A') }})</span>
                                    @else
                                        <span class="text-red-600">Not Verified</span>
                                    @endif
                                </dd>
                            </div>
                        </dl>
                    </div>

                    <!-- Account Information -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Account Information</h3>
                        <dl class="space-y-3">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Created</dt>
                                <dd class="text-sm text-gray-900">{{ $user->created_at->format('M j, Y g:i A') }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Last Updated</dt>
                                <dd class="text-sm text-gray-900">{{ $user->updated_at->format('M j, Y g:i A') }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Account Age</dt>
                                <dd class="text-sm text-gray-900">{{ $user->created_at->diffForHumans() }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>

            <!-- Role-specific Information -->
            @if($user->role === 'student')
                @if($user->enrollments && $user->enrollments->count() > 0)
                <div class="px-6 py-6 border-t border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Student Information</h3>
                    @foreach($user->enrollments as $enrollment)
                        <div class="mb-4 p-4 bg-gray-50 rounded-lg">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Grade Level</dt>
                                    <dd class="text-sm text-gray-900">{{ $enrollment->gradeLevel->name ?? 'N/A' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Group</dt>
                                    <dd class="text-sm text-gray-900">{{ $enrollment->group->name ?? 'N/A' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Academic Year</dt>
                                    <dd class="text-sm text-gray-900">{{ $enrollment->academic_year ?? 'N/A' }}</dd>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                @endif
            @endif

            @if($user->role === 'teacher')
                <div class="px-6 py-6 border-t border-gray-200">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Asignaciones Académicas</h3>
                        <div class="flex space-x-2">
                            <a href="{{ route('assignments.teacher', $user) }}" 
                               class="inline-flex items-center px-3 py-1 border border-blue-300 text-sm font-medium rounded-md text-blue-700 bg-white hover:bg-blue-50">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                Ver Detalle
                            </a>
                            <a href="{{ route('assignments.create') }}?teacher_id={{ $user->id }}" 
                               class="inline-flex items-center px-3 py-1 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Nueva Asignación
                            </a>
                        </div>
                    </div>

                    @if($user->subjectAssignments && $user->subjectAssignments->count() > 0)
                        @php
                            $assignmentsBySubject = $user->subjectAssignments->groupBy('subject.name');
                        @endphp
                        
                        <div class="space-y-4">
                            @foreach($assignmentsBySubject as $subjectName => $assignments)
                                <div class="border border-gray-200 rounded-lg p-4 bg-gray-50">
                                    <div class="flex justify-between items-start mb-3">
                                        <div>
                                            <h4 class="font-semibold text-gray-900">{{ $subjectName }}</h4>
                                            <p class="text-sm text-gray-600">
                                                {{ $assignments->first()->subject->area }} • 
                                                {{ $assignments->first()->subject->credits }} créditos • 
                                                {{ $assignments->first()->subject->hours_per_week }}h/semana
                                            </p>
                                        </div>
                                        <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded">
                                            {{ $assignments->first()->subject->code }}
                                        </span>
                                    </div>
                                    
                                    <!-- Groups for this subject -->
                                    <div>
                                        <p class="text-sm font-medium text-gray-700 mb-2">Grupos asignados:</p>
                                        <div class="flex flex-wrap gap-2">
                                            @foreach($assignments as $assignment)
                                                @php
                                                    $gradeName = $assignment->subject->academicPlan->gradeLevel->grade_name ?? 'N/A';
                                                @endphp
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    {{ $gradeName }}{{ $assignment->group->group_letter }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>

                                    <!-- Quick Stats -->
                                    <div class="mt-3 pt-3 border-t border-gray-200 flex items-center justify-between text-xs text-gray-500">
                                        <span>{{ $assignments->count() }} grupos • Año {{ $assignments->first()->academic_year }}</span>
                                        <a href="{{ route('activities.by-subject', $assignments->first()->subject) }}" 
                                           class="text-blue-600 hover:text-blue-800">Ver actividades</a>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Summary Stats -->
                        <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="bg-blue-50 p-4 rounded-lg">
                                <div class="text-2xl font-bold text-blue-600">
                                    {{ $assignmentsBySubject->count() }}
                                </div>
                                <div class="text-sm text-blue-800">Materias Diferentes</div>
                            </div>
                            <div class="bg-green-50 p-4 rounded-lg">
                                <div class="text-2xl font-bold text-green-600">
                                    {{ $user->subjectAssignments->count() }}
                                </div>
                                <div class="text-sm text-green-800">Total Asignaciones</div>
                            </div>
                            <div class="bg-purple-50 p-4 rounded-lg">
                                <div class="text-2xl font-bold text-purple-600">
                                    {{ $user->subjectAssignments->groupBy('group_id')->count() }}
                                </div>
                                <div class="text-sm text-purple-800">Grupos Diferentes</div>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="mx-auto h-12 w-12 text-gray-400 mb-4">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-full h-full">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <h4 class="text-lg font-medium text-gray-900 mb-2">Sin Asignaciones</h4>
                            <p class="text-gray-600 mb-4">
                                Este profesor no tiene asignaciones académicas para el año {{ date('Y') }}.
                            </p>
                            <a href="{{ route('assignments.create') }}?teacher_id={{ $user->id }}" 
                               class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Crear Primera Asignación
                            </a>
                        </div>
                    @endif
                </div>
            @endif

            @if($user->role === 'parent')
                @if($user->children && $user->children->count() > 0)
                <div class="px-6 py-6 border-t border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Children</h3>
                    <div class="space-y-3">
                        @foreach($user->children as $child)
                            <div class="flex items-center justify-between p-4 bg-yellow-50 rounded-lg">
                                <div>
                                    <h4 class="font-semibold text-yellow-900">{{ $child->first_name }} {{ $child->last_name }}</h4>
                                    <p class="text-sm text-yellow-700">{{ $child->email }}</p>
                                </div>
                                <a href="{{ route('users.show', $child) }}" class="text-blue-600 hover:text-blue-800">View</a>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif
            @endif

            <!-- Recent Activity -->
            <div class="px-6 py-6 border-t border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
                <div class="flex flex-wrap gap-3">
                    <button onclick="toggleUserStatus({{ $user->id }})" 
                            class="bg-{{ $user->status ? 'red' : 'green' }}-500 hover:bg-{{ $user->status ? 'red' : 'green' }}-700 text-white font-bold py-2 px-4 rounded">
                        {{ $user->status ? 'Deactivate' : 'Activate' }} User
                    </button>
                    
                    @if(!$user->email_verified_at)
                        <button onclick="sendVerificationEmail({{ $user->id }})" 
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Send Verification Email
                        </button>
                    @endif
                    
                    <button onclick="resetPassword({{ $user->id }})" 
                            class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                        Reset Password
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function toggleUserStatus(userId) {
        if (confirm('Are you sure you want to change this user\'s status?')) {
            fetch(`/users/${userId}/toggle-status`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Error updating user status');
                }
            })
            .catch(error => {
                alert('Error updating user status');
            });
        }
    }

    function sendVerificationEmail(userId) {
        if (confirm('Send verification email to this user?')) {
            fetch(`/users/${userId}/send-verification`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Verification email sent successfully');
                } else {
                    alert('Error sending verification email');
                }
            })
            .catch(error => {
                alert('Error sending verification email');
            });
        }
    }

    function resetPassword(userId) {
        if (confirm('Send password reset email to this user?')) {
            fetch(`/users/${userId}/reset-password`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Password reset email sent successfully');
                } else {
                    alert('Error sending password reset email');
                }
            })
            .catch(error => {
                alert('Error sending password reset email');
            });
        }
    }
</script>
@endpush
@endsection
