<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentGrade extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'activity_id',
        'score',
        'feedback',
        'graded_at',
    ];

    protected function casts(): array
    {
        return [
            'score' => 'decimal:2',
            'graded_at' => 'datetime',
        ];
    }

    // Relationships

    /**
     * Student who received this grade
     */
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    /**
     * Activity this grade is for
     */
    public function activity()
    {
        return $this->belongsTo(Activity::class, 'activity_id');
    }

    // Scopes

    /**
     * Scope to get grades for a specific student
     */
    public function scopeForStudent($query, $studentId)
    {
        return $query->where('student_id', $studentId);
    }

    /**
     * Scope to get grades for a specific activity
     */
    public function scopeForActivity($query, $activityId)
    {
        return $query->where('activity_id', $activityId);
    }

    /**
     * Scope to get graded entries (with graded_at timestamp)
     */
    public function scopeGraded($query)
    {
        return $query->whereNotNull('graded_at');
    }

    /**
     * Scope to get ungraded entries
     */
    public function scopeUngraded($query)
    {
        return $query->whereNull('graded_at');
    }

    // Methods

    /**
     * Check if the grade has been assigned
     */
    public function isGraded(): bool
    {
        return !is_null($this->graded_at);
    }

    /**
     * Get the grade as a percentage
     */
    public function getPercentageAttribute(): float
    {
        if (!$this->activity || $this->activity->max_score == 0) {
            return 0;
        }
        
        return ($this->score / $this->activity->max_score) * 100;
    }

    /**
     * Get the grade status (passed/failed based on Colombian system)
     */
    public function getStatusAttribute(): string
    {
        return $this->score >= 3.0 ? 'passed' : 'failed';
    }
}
