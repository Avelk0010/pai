<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'document',
        'phone',
        'role',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'status' => 'boolean',
        ];
    }

    /**
     * Get the user's full name
     */
    public function getFullNameAttribute(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    /**
     * Get the user's name (alias for full_name for compatibility)
     */
    public function getNameAttribute(): string
    {
        return $this->getFullNameAttribute();
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::substr($this->first_name, 0, 1) . Str::substr($this->last_name, 0, 1);
    }

    /**
     * Check if user is a student
     */
    public function isStudent(): bool
    {
        return $this->role === 'student';
    }

    /**
     * Check if user is a teacher
     */
    public function isTeacher(): bool
    {
        return $this->role === 'teacher';
    }

    /**
     * Check if user is a parent
     */
    public function isParent(): bool
    {
        return $this->role === 'parent';
    }

    /**
     * Check if user is an admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    // Relationships

    /**
     * Students that this parent is associated with (if user is parent)
     */
    public function children()
    {
        return $this->belongsToMany(User::class, 'parent_student', 'parent_id', 'student_id');
    }

    /**
     * Parents associated with this student (if user is student)
     */
    public function parents()
    {
        return $this->belongsToMany(User::class, 'parent_student', 'student_id', 'parent_id');
    }

    /**
     * Enrollments for this student
     */
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class, 'student_id');
    }

    /**
     * Subject assignments for this teacher
     */
    public function subjectAssignments()
    {
        return $this->hasMany(SubjectAssignment::class, 'teacher_id');
    }

    /**
     * Subjects that this teacher teaches
     */
    public function teachingSubjects()
    {
        return $this->hasManyThrough(Subject::class, SubjectAssignment::class, 'teacher_id', 'id', 'id', 'subject_id');
    }

    /**
     * Activities created by this teacher
     */
    public function activities()
    {
        return $this->hasMany(Activity::class, 'teacher_id');
    }

    /**
     * Groups where this teacher is the homeroom teacher
     */
    public function homeroomGroups()
    {
        return $this->hasMany(Group::class, 'homeroom_teacher_id');
    }

    /**
     * Grades received by this student
     */
    public function studentGrades()
    {
        return $this->hasMany(StudentGrade::class, 'student_id');
    }

    /**
     * Period grades for this student
     */
    public function periodGrades()
    {
        return $this->hasMany(PeriodGrade::class, 'student_id');
    }

    /**
     * Forum posts authored by this user
     */
    public function forumPosts()
    {
        return $this->hasMany(ForumPost::class, 'author_id');
    }

    /**
     * Forum comments authored by this user
     */
    public function forumComments()
    {
        return $this->hasMany(ForumComment::class, 'author_id');
    }

    /**
     * Library loans for this user
     */
    public function libraryLoans()
    {
        return $this->hasMany(LibraryLoan::class, 'user_id');
    }

    /**
     * Notifications for this user
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class, 'user_id');
    }
}
