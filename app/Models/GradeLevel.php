<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GradeLevel extends Model
{
    use HasFactory;

    protected $fillable = [
        'grade_number',
        'grade_name',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'status' => 'boolean',
        ];
    }

    // Relationships

    /**
     * Groups belonging to this grade level
     */
    public function groups()
    {
        return $this->hasMany(Group::class, 'grade_level_id');
    }

    /**
     * Academic plans for this grade level
     */
    public function academicPlans()
    {
        return $this->hasMany(AcademicPlan::class, 'grade_level_id');
    }

    // Scopes

    /**
     * Scope to get only active grade levels
     */
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    // Accessors

    /**
     * Get the name attribute (alias for grade_name for compatibility)
     */
    public function getNameAttribute()
    {
        return $this->grade_name;
    }
}
