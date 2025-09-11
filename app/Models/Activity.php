<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id',
        'subject_id',
        'period_id',
        'title',
        'description',
        'activity_type',
        'due_date',
        'max_score',
        'percentage',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'due_date' => 'datetime',
            'max_score' => 'decimal:2',
            'percentage' => 'decimal:2',
        ];
    }

    // Relationships

    /**
     * Teacher who created this activity
     */
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    /**
     * Subject this activity belongs to
     */
    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    /**
     * Groups this activity is assigned to
     */
    public function groups()
    {
        return $this->belongsToMany(Group::class, 'activity_group');
    }

    /**
     * Period this activity belongs to
     */
    public function period()
    {
        return $this->belongsTo(Period::class, 'period_id');
    }

    /**
     * Student grades for this activity
     */
    public function studentGrades()
    {
        return $this->hasMany(StudentGrade::class, 'activity_id');
    }

    // Scopes

    /**
     * Scope to get published activities
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    /**
     * Scope to get draft activities
     */
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    /**
     * Scope to get finished activities
     */
    public function scopeFinished($query)
    {
        return $query->where('status', 'finished');
    }

    /**
     * Scope to get activities by type
     */
    public function scopeByType($query, $type)
    {
        return $query->where('activity_type', $type);
    }

    /**
     * Scope to get activities for a specific teacher
     */
    public function scopeForTeacher($query, $teacherId)
    {
        return $query->where('teacher_id', $teacherId);
    }

    /**
     * Scope to get activities for specific groups
     */
    public function scopeForGroups($query, $groupIds)
    {
        return $query->whereHas('groups', function($q) use ($groupIds) {
            $q->whereIn('group_id', (array) $groupIds);
        });
    }

    /**
     * Scope to get overdue activities
     */
    public function scopeOverdue($query)
    {
        return $query->where('due_date', '<', now())
                    ->where('status', 'published');
    }

    // Methods

    /**
     * Check if the activity is published
     */
    public function isPublished(): bool
    {
        return $this->status === 'published';
    }

    /**
     * Check if the activity is a draft
     */
    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }

    /**
     * Check if the activity is finished
     */
    public function isFinished(): bool
    {
        return $this->status === 'finished';
    }

    /**
     * Check if the activity is overdue
     */
    public function isOverdue(): bool
    {
        return $this->due_date && $this->due_date->isPast() && $this->isPublished();
    }
}
