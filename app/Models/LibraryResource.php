<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LibraryResource extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'author',
        'isbn',
        'resource_type',
        'total_copies',
        'available_copies',
        'location',
        'description',
        'cover_image',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'status' => 'boolean',
            'total_copies' => 'integer',
            'available_copies' => 'integer',
        ];
    }

    // Relationships

    /**
     * Loans for this resource
     */
    public function loans()
    {
        return $this->hasMany(LibraryLoan::class, 'resource_id');
    }

    /**
     * Active loans for this resource
     */
    public function activeLoans()
    {
        return $this->hasMany(LibraryLoan::class, 'resource_id')->where('status', 'active');
    }

    /**
     * Overdue loans for this resource
     */
    public function overdueLoans()
    {
        return $this->hasMany(LibraryLoan::class, 'resource_id')->where('status', 'overdue');
    }

    // Scopes

    /**
     * Scope to get only active resources
     */
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    /**
     * Scope to get resources by type
     */
    public function scopeByType($query, $type)
    {
        return $query->where('resource_type', $type);
    }

    /**
     * Scope to get available resources (with available copies > 0)
     */
    public function scopeAvailable($query)
    {
        return $query->where('available_copies', '>', 0);
    }

    /**
     * Scope to search resources by title or author
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
              ->orWhere('author', 'like', "%{$search}%")
              ->orWhere('isbn', 'like', "%{$search}%");
        });
    }

    // Methods

    /**
     * Check if the resource is available for loan
     */
    public function isAvailable(): bool
    {
        return $this->status && $this->available_copies > 0;
    }

    /**
     * Check if the resource is out of stock
     */
    public function isOutOfStock(): bool
    {
        return $this->available_copies <= 0;
    }

    /**
     * Decrease available copies when loaned
     */
    public function decreaseAvailableCopies(): void
    {
        if ($this->available_copies > 0) {
            $this->decrement('available_copies');
        }
    }

    /**
     * Increase available copies when returned
     */
    public function increaseAvailableCopies(): void
    {
        if ($this->available_copies < $this->total_copies) {
            $this->increment('available_copies');
        }
    }

    /**
     * Get the number of copies currently on loan
     */
    public function getLoanedCopiesAttribute(): int
    {
        return $this->total_copies - $this->available_copies;
    }

    /**
     * Get the availability percentage
     */
    public function getAvailabilityPercentageAttribute(): float
    {
        if ($this->total_copies == 0) {
            return 0;
        }
        
        return ($this->available_copies / $this->total_copies) * 100;
    }
}
