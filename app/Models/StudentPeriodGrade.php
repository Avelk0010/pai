<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentPeriodGrade extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'period_id',
        'subject_id',
        'final_grade',
        'weighted_grade',
        'status',
        'calculated_at',
    ];

    protected function casts(): array
    {
        return [
            'final_grade' => 'decimal:2',
            'weighted_grade' => 'decimal:2',
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

    // Methods

    /**
     * Check if the grade is passing (3.0 or higher)
     */
    public function isPassing(): bool
    {
        return $this->final_grade >= 3.0;
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

    /**
     * Calculate weighted grade based on subject credits
     */
    public function calculateWeightedGrade(): void
    {
        $this->weighted_grade = $this->final_grade * $this->subject->credits;
        $this->save();
    }

    /**
     * Calculate period average for a student
     */
    public static function calculatePeriodAverage($studentId, $periodId): float
    {
        $grades = self::with('subject')
            ->where('student_id', $studentId)
            ->where('period_id', $periodId)
            ->get();

        if ($grades->isEmpty()) {
            return 0;
        }

        $totalWeightedGrade = $grades->sum('weighted_grade');
        $totalCredits = $grades->sum(function ($grade) {
            return $grade->subject->credits;
        });

        return $totalCredits > 0 ? $totalWeightedGrade / $totalCredits : 0;
    }
}
