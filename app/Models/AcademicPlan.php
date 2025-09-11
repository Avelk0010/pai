<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'grade_level_id',
        'name',
        'academic_year',
        'periods_count',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'status' => 'boolean',
            'academic_year' => 'integer',
            'periods_count' => 'integer',
        ];
    }

    // Relationships

    /**
     * Grade level this academic plan belongs to
     */
    public function gradeLevel()
    {
        return $this->belongsTo(GradeLevel::class, 'grade_level_id');
    }

    /**
     * Subjects in this academic plan
     */
    public function subjects()
    {
        return $this->hasMany(Subject::class, 'academic_plan_id');
    }

    /**
     * Periods in this academic plan
     */
    public function periods()
    {
        return $this->hasMany(Period::class, 'academic_plan_id')->orderBy('period_number');
    }

    // Scopes

    /**
     * Scope to get only active academic plans
     */
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    /**
     * Scope to get academic plans for a specific year
     */
    public function scopeForYear($query, $year)
    {
        return $query->where('academic_year', $year);
    }

    /**
     * Scope to get current year academic plans
     */
    public function scopeCurrent($query)
    {
        return $query->where('academic_year', date('Y'));
    }

    // Methods

    /**
     * Create periods for this academic plan
     */
    public function createPeriods()
    {
        // Delete existing periods if any
        $this->periods()->delete();
        
        // Create new periods
        for ($i = 1; $i <= $this->periods_count; $i++) {
            Period::create([
                'academic_plan_id' => $this->id,
                'period_number' => $i,
                'start_date' => null,
                'end_date' => null,
                'status' => 'planned',
            ]);
        }
    }
}
