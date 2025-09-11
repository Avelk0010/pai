<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Period extends Model
{
    use HasFactory;

    protected $fillable = [
        'academic_plan_id',
        'period_number',
        'start_date',
        'end_date',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'period_number' => 'integer',
        ];
    }

    // Relationships

    /**
     * Academic plan this period belongs to
     */
    public function academicPlan()
    {
        return $this->belongsTo(AcademicPlan::class, 'academic_plan_id');
    }

    /**
     * Activities in this period
     */
    public function activities()
    {
        return $this->hasMany(Activity::class, 'period_id');
    }

    /**
     * Period grades for this period
     */
    public function periodGrades()
    {
        return $this->hasMany(PeriodGrade::class, 'period_id');
    }

    // Scopes

    /**
     * Scope to get periods for a specific academic year
     */
    public function scopeForYear($query, $year)
    {
        return $query->whereHas('academicPlan', function ($query) use ($year) {
            $query->where('academic_year', $year);
        });
    }

    /**
     * Scope to get current year periods
     */
    public function scopeCurrent($query)
    {
        return $query->whereHas('academicPlan', function ($query) {
            $query->where('academic_year', date('Y'));
        });
    }

    /**
     * Scope to get periods for a specific academic plan
     */
    public function scopeForAcademicPlan($query, $academicPlanId)
    {
        return $query->where('academic_plan_id', $academicPlanId);
    }

    /**
     * Scope to get active periods
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope to get planned periods
     */
    public function scopePlanned($query)
    {
        return $query->where('status', 'planned');
    }

    /**
     * Scope to get upcoming periods
     */
    public function scopeUpcoming($query)
    {
        return $query->where('status', 'upcoming');
    }

    /**
     * Scope to get finished periods
     */
    public function scopeFinished($query)
    {
        return $query->where('status', 'finished');
    }

    // Methods

    /**
     * Check if the period is currently active
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Check if the period is planned
     */
    public function isPlanned(): bool
    {
        return $this->status === 'planned';
    }

    /**
     * Check if the period is finished
     */
    public function isFinished(): bool
    {
        return $this->status === 'finished';
    }

    /**
     * Get the academic year for this period
     */
    public function getAcademicYear()
    {
        return $this->academicPlan->academic_year;
    }

    /**
     * Get the name attribute (for backward compatibility with views)
     */
    public function getNameAttribute(): string
    {
        return "Período {$this->period_number}";
    }
}
