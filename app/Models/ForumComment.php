<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ForumComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_id',
        'author_id',
        'content',
        'is_approved',
        'approved_by',
        'approved_at',
    ];

    protected function casts(): array
    {
        return [
            'is_approved' => 'boolean',
            'approved_at' => 'datetime',
        ];
    }

    // Relationships

    /**
     * Post this comment belongs to
     */
    public function post()
    {
        return $this->belongsTo(ForumPost::class, 'post_id');
    }

    /**
     * Author of this comment
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * User who approved this comment
     */
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Scopes

    /**
     * Scope to get only approved comments
     */
    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    /**
     * Scope to get pending approval comments
     */
    public function scopePending($query)
    {
        return $query->where('is_approved', false);
    }

    /**
     * Scope to get comments by a specific author
     */
    public function scopeByAuthor($query, $authorId)
    {
        return $query->where('author_id', $authorId);
    }

    /**
     * Scope to get comments for a specific post
     */
    public function scopeForPost($query, $postId)
    {
        return $query->where('post_id', $postId);
    }

    /**
     * Scope to get recent comments
     */
    public function scopeRecent($query, $limit = 10)
    {
        return $query->latest()->limit($limit);
    }

    // Methods

    /**
     * Check if the comment is approved
     */
    public function isApproved(): bool
    {
        return $this->is_approved;
    }

    /**
     * Check if the comment is pending approval
     */
    public function isPending(): bool
    {
        return !$this->is_approved;
    }
}
