<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    protected $fillable = [
        'grade_level_id',
        'homeroom_teacher_id',
        'group_letter',
        'academic_year',
        'max_students',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'status' => 'boolean',
            'academic_year' => 'integer',
        ];
    }

    // Relationships

    /**
     * Grade level this group belongs to
     */
    public function gradeLevel()
    {
        return $this->belongsTo(GradeLevel::class, 'grade_level_id');
    }

    /**
     * Homeroom teacher for this group
     */
    public function homeRoomTeacher()
    {
        return $this->belongsTo(User::class, 'homeroom_teacher_id');
    }

    /**
     * Enrollments in this group
     */
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class, 'group_id');
    }

    /**
     * Students enrolled in this group
     */
    public function students()
    {
        return $this->hasManyThrough(User::class, Enrollment::class, 'group_id', 'id', 'id', 'student_id');
    }

    /**
     * Subject assignments for this group
     */
    public function subjectAssignments()
    {
        return $this->hasMany(SubjectAssignment::class, 'group_id');
    }

    /**
     * Activities for this group
     */
    public function activities()
    {
        return $this->belongsToMany(Activity::class, 'activity_group');
    }

    // Scopes

    /**
     * Scope to get only active groups
     */
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    /**
     * Scope to get groups for a specific academic year
     */
    public function scopeForYear($query, $year)
    {
        return $query->where('academic_year', $year);
    }

    // Accessors

    /**
     * Get the group name (e.g., "11A", "10B")
     */
    public function getNameAttribute(): string
    {
        return $this->gradeLevel ? $this->gradeLevel->name . $this->group_letter : $this->group_letter;
    }

    /**
     * Get the full group name (e.g., "11th Grade A")
     */
    public function getFullNameAttribute(): string
    {
        return $this->gradeLevel->grade_name . ' ' . $this->group_letter;
    }
}
