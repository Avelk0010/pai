<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'message',
        'type',
        'is_read',
    ];

    protected function casts(): array
    {
        return [
            'is_read' => 'boolean',
        ];
    }

    // Relationships

    /**
     * User this notification belongs to
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Scopes

    /**
     * Scope to get unread notifications
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Scope to get read notifications
     */
    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }

    /**
     * Scope to get notifications for a specific user
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope to get notifications by type
     */
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope to get recent notifications
     */
    public function scopeRecent($query, $limit = 10)
    {
        return $query->latest()->limit($limit);
    }

    // Methods

    /**
     * Check if the notification is read
     */
    public function isRead(): bool
    {
        return $this->is_read;
    }

    /**
     * Check if the notification is unread
     */
    public function isUnread(): bool
    {
        return !$this->is_read;
    }

    /**
     * Mark the notification as read
     */
    public function markAsRead(): void
    {
        $this->update(['is_read' => true]);
    }

    /**
     * Mark the notification as unread
     */
    public function markAsUnread(): void
    {
        $this->update(['is_read' => false]);
    }

    /**
     * Get the notification icon based on type
     */
    public function getIconAttribute(): string
    {
        return match($this->type) {
            'grade' => 'academic-cap',
            'activity' => 'clipboard-document-list',
            'forum' => 'chat-bubble-left-right',
            'library' => 'book-open',
            'general' => 'bell',
            default => 'information-circle',
        };
    }

    /**
     * Get the notification color based on type
     */
    public function getColorAttribute(): string
    {
        return match($this->type) {
            'grade' => 'green',
            'activity' => 'blue',
            'forum' => 'purple',
            'library' => 'orange',
            'general' => 'gray',
            default => 'gray',
        };
    }

    // Static methods

    /**
     * Create a grade notification
     */
    public static function createGradeNotification($userId, $title, $message): self
    {
        return self::create([
            'user_id' => $userId,
            'title' => $title,
            'message' => $message,
            'type' => 'grade',
        ]);
    }

    /**
     * Create an activity notification
     */
    public static function createActivityNotification($userId, $title, $message): self
    {
        return self::create([
            'user_id' => $userId,
            'title' => $title,
            'message' => $message,
            'type' => 'activity',
        ]);
    }

    /**
     * Create a forum notification
     */
    public static function createForumNotification($userId, $title, $message): self
    {
        return self::create([
            'user_id' => $userId,
            'title' => $title,
            'message' => $message,
            'type' => 'forum',
        ]);
    }

    /**
     * Create a library notification
     */
    public static function createLibraryNotification($userId, $title, $message): self
    {
        return self::create([
            'user_id' => $userId,
            'title' => $title,
            'message' => $message,
            'type' => 'library',
        ]);
    }

    /**
     * Create a general notification
     */
    public static function createGeneralNotification($userId, $title, $message): self
    {
        return self::create([
            'user_id' => $userId,
            'title' => $title,
            'message' => $message,
            'type' => 'general',
        ]);
    }
}
