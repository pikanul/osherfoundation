<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_id',
        'user_id',
        'context',
        'chat_history',
        'last_activity',
    ];

    protected $casts = [
        'chat_history' => 'array',
        'last_activity' => 'datetime',
    ];

    /**
     * Get the user that owns this chat session
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Add message to chat history
     */
    public function addMessage($role, $content)
    {
        $history = $this->chat_history ?? [];
        $history[] = [
            'role' => $role,
            'content' => $content,
            'timestamp' => now()->toISOString(),
        ];
        
        $this->update([
            'chat_history' => $history,
            'last_activity' => now(),
        ]);
    }

    /**
     * Get recent messages
     */
    public function getRecentMessages($limit = 10)
    {
        $history = $this->chat_history ?? [];
        return array_slice($history, -$limit);
    }

    /**
     * Clear chat history
     */
    public function clearHistory()
    {
        $this->update([
            'chat_history' => [],
            'last_activity' => now(),
        ]);
    }

    /**
     * Update context
     */
    public function updateContext($context)
    {
        $this->update([
            'context' => $context,
            'last_activity' => now(),
        ]);
    }
}

