<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ForumPost extends Model
{
    use HasFactory;

    protected $fillable = [
        'author_id',
        'category_id',
        'title',
        'content',
        'is_approved',
        'approved_by',
        'approved_at',
        'views',
    ];

    protected function casts(): array
    {
        return [
            'is_approved' => 'boolean',
            'approved_at' => 'datetime',
            'views' => 'integer',
        ];
    }

    // Relationships

    /**
     * Author of this post
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * Category this post belongs to
     */
    public function category()
    {
        return $this->belongsTo(ForumCategory::class, 'category_id');
    }

    /**
     * User who approved this post
     */
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Comments on this post
     */
    public function comments()
    {
        return $this->hasMany(ForumComment::class, 'post_id');
    }

    /**
     * Approved comments on this post
     */
    public function approvedComments()
    {
        return $this->hasMany(ForumComment::class, 'post_id')->where('is_approved', true);
    }

    // Scopes

    /**
     * Scope to get only approved posts
     */
    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    /**
     * Scope to get pending approval posts
     */
    public function scopePending($query)
    {
        return $query->where('is_approved', false);
    }

    /**
     * Scope to get posts by a specific author
     */
    public function scopeByAuthor($query, $authorId)
    {
        return $query->where('author_id', $authorId);
    }

    /**
     * Scope to get posts in a specific category
     */
    public function scopeInCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    /**
     * Scope to get popular posts (by views)
     */
    public function scopePopular($query, $limit = 10)
    {
        return $query->orderBy('views', 'desc')->limit($limit);
    }

    /**
     * Scope to get recent posts
     */
    public function scopeRecent($query, $limit = 10)
    {
        return $query->latest()->limit($limit);
    }

    // Methods

    /**
     * Check if the post is approved
     */
    public function isApproved(): bool
    {
        return $this->is_approved;
    }

    /**
     * Check if the post is pending approval
     */
    public function isPending(): bool
    {
        return !$this->is_approved;
    }

    /**
     * Increment the view count
     */
    public function incrementViews(): void
    {
        $this->increment('views');
    }

    /**
     * Get the total number of comments
     */
    public function getCommentsCountAttribute(): int
    {
        return $this->comments()->count();
    }

    /**
     * Get the total number of approved comments
     */
    public function getApprovedCommentsCountAttribute(): int
    {
        return $this->approvedComments()->count();
    }

    /**
     * Get the latest comment
     */
    public function getLatestCommentAttribute()
    {
        return $this->approvedComments()->latest()->first();
    }
}
