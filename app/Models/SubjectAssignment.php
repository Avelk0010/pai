<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubjectAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id',
        'subject_id',
        'group_id',
        'grade_level_id',
        'academic_year',
    ];

    protected function casts(): array
    {
        return [
            'academic_year' => 'integer',
        ];
    }

    // Relationships

    /**
     * Teacher assigned to this subject
     */
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    /**
     * Subject being assigned
     */
    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    /**
     * Group this assignment is for
     */
    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id');
    }

    /**
     * Grade level for this assignment
     */
    public function gradeLevel()
    {
        return $this->belongsTo(GradeLevel::class, 'grade_level_id');
    }

    // Scopes

    /**
     * Scope to get assignments for a specific academic year
     */
    public function scopeForYear($query, $year)
    {
        return $query->where('academic_year', $year);
    }

    /**
     * Scope to get current year assignments
     */
    public function scopeCurrent($query)
    {
        return $query->where('academic_year', date('Y'));
    }

    /**
     * Scope to get assignments for a specific teacher
     */
    public function scopeForTeacher($query, $teacherId)
    {
        return $query->where('teacher_id', $teacherId);
    }

    /**
     * Scope to get assignments for a specific group
     */
    public function scopeForGroup($query, $groupId)
    {
        return $query->where('group_id', $groupId);
    }
}
