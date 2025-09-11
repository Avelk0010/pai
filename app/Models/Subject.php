<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'academic_plan_id',
        'name',
        'code',
        'description',
        'credits',
        'area',
        'is_mandatory',
        'status',
        'curriculum_content',
        'topics',
        'objectives',
        'methodology',
        'evaluation_criteria',
        'resources',
        'prerequisites',
        'hours_per_week',
    ];

    protected function casts(): array
    {
        return [
            'is_mandatory' => 'boolean',
            'status' => 'boolean',
            'credits' => 'integer',
            'hours_per_week' => 'integer',
            'topics' => 'array',
            'resources' => 'array',
        ];
    }

    // Relationships

    /**
     * Academic plan this subject belongs to
     */
    public function academicPlan()
    {
        return $this->belongsTo(AcademicPlan::class, 'academic_plan_id');
    }

    /**
     * Subject assignments for this subject
     */
    public function subjectAssignments()
    {
        return $this->hasMany(SubjectAssignment::class, 'subject_id');
    }

    /**
     * Activities for this subject
     */
    public function activities()
    {
        return $this->hasMany(Activity::class, 'subject_id');
    }

    /**
     * Period grades for this subject
     */
    public function periodGrades()
    {
        return $this->hasMany(PeriodGrade::class, 'subject_id');
    }

    /**
     * Teachers assigned to this subject
     */
    public function teachers()
    {
        return $this->hasManyThrough(User::class, SubjectAssignment::class, 'subject_id', 'id', 'id', 'teacher_id');
    }

    // Scopes

    /**
     * Scope to get only active subjects
     */
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    /**
     * Scope to get only mandatory subjects
     */
    public function scopeMandatory($query)
    {
        return $query->where('is_mandatory', true);
    }

    /**
     * Scope to get subjects by area
     */
    public function scopeByArea($query, $area)
    {
        return $query->where('area', $area);
    }
}
