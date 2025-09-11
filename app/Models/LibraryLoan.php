<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class LibraryLoan extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'resource_id',
        'loan_date',
        'return_date',
        'actual_return_date',
        'status',
        'requested_at',
        'approved_at',
        'approved_by',
        'rejection_reason',
        'rejected_at',
        'rejected_by',
    ];

    protected function casts(): array
    {
        return [
            'loan_date' => 'date',
            'return_date' => 'date',
            'actual_return_date' => 'date',
            'requested_at' => 'datetime',
            'approved_at' => 'datetime',
            'rejected_at' => 'datetime',
        ];
    }

    // Relationships

    /**
     * User who borrowed this resource
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Resource that was borrowed
     */
    public function resource()
    {
        return $this->belongsTo(LibraryResource::class, 'resource_id');
    }

    /**
     * User who approved this loan
     */
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * User who rejected this loan
     */
    public function rejector()
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }

    // Scopes

    /**
     * Scope to get requested loans
     */
    public function scopeRequested($query)
    {
        return $query->where('status', 'requested');
    }

    /**
     * Scope to get approved loans
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope to get active loans
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope to get returned loans
     */
    public function scopeReturned($query)
    {
        return $query->where('status', 'returned');
    }

    /**
     * Scope to get overdue loans
     */
    public function scopeOverdue($query)
    {
        return $query->where('status', 'overdue');
    }

    /**
     * Scope to get rejected loans
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    /**
     * Scope to get loans for a specific user
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope to get loans for a specific resource
     */
    public function scopeForResource($query, $resourceId)
    {
        return $query->where('resource_id', $resourceId);
    }

    /**
     * Scope to get loans that should be overdue (past return date)
     */
    public function scopeShouldBeOverdue($query)
    {
        return $query->where('status', 'active')
                    ->whereDate('return_date', '<', now());
    }

    /**
     * Scope to get recent loans
     */
    public function scopeRecent($query, $limit = 10)
    {
        return $query->latest('loan_date')->limit($limit);
    }

    // Methods

    /**
     * Check if the loan is requested
     */
    public function isRequested(): bool
    {
        return $this->status === 'requested';
    }

    /**
     * Check if the loan is approved
     */
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    /**
     * Check if the loan is active
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Check if the loan is returned
     */
    public function isReturned(): bool
    {
        return $this->status === 'returned';
    }

    /**
     * Check if the loan is overdue
     */
    public function isOverdue(): bool
    {
        return $this->status === 'overdue' || 
               ($this->status === 'active' && $this->return_date->isPast());
    }

    /**
     * Check if the loan is rejected
     */
    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    /**
     * Approve the loan request
     */
    public function approve($approver_id): void
    {
        $this->update([
            'status' => 'active',
            'approved_at' => now(),
            'approved_by' => $approver_id,
            'loan_date' => now(),
            'return_date' => now()->addDays(14),
        ]);
        
        // Decrease available copies in the resource
        $this->resource->decreaseAvailableCopies();
    }

    /**
     * Reject the loan request
     */
    public function reject($rejector_id, $reason = null): void
    {
        $this->update([
            'status' => 'rejected',
            'rejected_at' => now(),
            'rejected_by' => $rejector_id,
            'rejection_reason' => $reason,
        ]);
    }

    /**
     * Mark the loan as returned
     */
    public function markAsReturned(): void
    {
        $this->update([
            'status' => 'returned',
            'actual_return_date' => now(),
        ]);
        
        // Increase available copies in the resource
        $this->resource->increaseAvailableCopies();
    }

    /**
     * Mark the loan as overdue
     */
    public function markAsOverdue(): void
    {
        $this->update(['status' => 'overdue']);
    }

    /**
     * Get the number of days until return date
     */
    public function getDaysUntilReturnAttribute(): int
    {
        return now()->diffInDays($this->return_date, false);
    }

    /**
     * Get the number of days overdue
     */
    public function getDaysOverdueAttribute(): int
    {
        if (!$this->isOverdue()) {
            return 0;
        }
        
        return $this->return_date->diffInDays(now());
    }

    /**
     * Get the loan duration in days
     */
    public function getLoanDurationAttribute(): int
    {
        $endDate = $this->actual_return_date ?? now();
        return $this->loan_date->diffInDays($endDate);
    }

    /**
     * Check if the loan is due soon (within 3 days)
     */
    public function isDueSoon(): bool
    {
        return $this->isActive() && $this->days_until_return <= 3 && $this->days_until_return >= 0;
    }
}
