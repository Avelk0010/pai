<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ForumCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'color',
        'order_number',
    ];

    protected function casts(): array
    {
        return [
            'order_number' => 'integer',
        ];
    }

    // Relationships

    /**
     * Posts in this category
     */
    public function posts()
    {
        return $this->hasMany(ForumPost::class, 'category_id');
    }

    /**
     * Approved posts in this category
     */
    public function approvedPosts()
    {
        return $this->hasMany(ForumPost::class, 'category_id')->where('is_approved', true);
    }

    // Scopes

    /**
     * Scope to order categories by their order number
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order_number');
    }

    // Methods

    /**
     * Get the total number of posts in this category
     */
    public function getPostsCountAttribute(): int
    {
        return $this->posts()->count();
    }

    /**
     * Get the total number of approved posts in this category
     */
    public function getApprovedPostsCountAttribute(): int
    {
        return $this->approvedPosts()->count();
    }

    /**
     * Get the latest post in this category
     */
    public function getLatestPostAttribute()
    {
        return $this->approvedPosts()->latest()->first();
    }
}
