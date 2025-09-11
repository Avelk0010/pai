<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'group_id',
        'grade_level_id',
        'academic_year',
        'enrollment_date',
        'status',
        'is_active', // Para compatibilidad con EnrollmentController existente
    ];

    protected function casts(): array
    {
        return [
            'academic_year' => 'integer',
            'enrollment_date' => 'date',
        ];
    }

    // Relationships

    /**
     * Student enrolled
     */
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    /**
     * Group the student is enrolled in
     */
    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id');
    }

    /**
     * Grade level the student is enrolled in
     */
    public function gradeLevel()
    {
        return $this->belongsTo(GradeLevel::class, 'grade_level_id');
    }

    // Scopes

    /**
     * Scope to get enrollments for a specific academic year
     */
    public function scopeForYear($query, $year)
    {
        return $query->where('academic_year', $year);
    }

    /**
     * Scope to get current year enrollments
     */
    public function scopeCurrent($query)
    {
        return $query->where('academic_year', date('Y'));
    }

    /**
     * Scope to get active enrollments
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope to get enrollments for a specific status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }
}
