<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeriodGrade extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'subject_id',
        'period_id',
        'final_grade',
        'status',
        'calculated_at',
    ];

    protected function casts(): array
    {
        return [
            'final_grade' => 'decimal:2',
            'calculated_at' => 'datetime',
        ];
    }

    // Relationships

    /**
     * Student this grade belongs to
     */
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    /**
     * Subject this grade is for
     */
    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    /**
     * Period this grade is for
     */
    public function period()
    {
        return $this->belongsTo(Period::class, 'period_id');
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
     * Scope to get grades for a specific subject
     */
    public function scopeForSubject($query, $subjectId)
    {
        return $query->where('subject_id', $subjectId);
    }

    /**
     * Scope to get grades for a specific period
     */
    public function scopeForPeriod($query, $periodId)
    {
        return $query->where('period_id', $periodId);
    }

    /**
     * Scope to get passed grades
     */
    public function scopePassed($query)
    {
        return $query->where('status', 'passed');
    }

    /**
     * Scope to get failed grades
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    /**
     * Scope to get pending grades
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope to get calculated grades
     */
    public function scopeCalculated($query)
    {
        return $query->whereNotNull('calculated_at');
    }

    // Methods

    /**
     * Check if the grade is passing (3.0 or higher in Colombian system)
     */
    public function isPassing(): bool
    {
        return $this->final_grade >= 3.0;
    }

    /**
     * Check if the grade is failing
     */
    public function isFailing(): bool
    {
        return $this->final_grade < 3.0;
    }

    /**
     * Check if the grade is pending calculation
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Get the grade as a percentage
     */
    public function getPercentageAttribute(): float
    {
        return ($this->final_grade / 5.0) * 100;
    }

    /**
     * Get the letter grade equivalent
     */
    public function getLetterGradeAttribute(): string
    {
        if ($this->final_grade >= 4.6) return 'A';
        if ($this->final_grade >= 4.0) return 'B';
        if ($this->final_grade >= 3.0) return 'C';
        return 'F';
    }
}
