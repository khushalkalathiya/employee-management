<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ConversationParticipant extends Pivot
{
    /**
     * The table associated with the model.
     * Pivot models still need this so Eloquent can use the model standalone.
     */
    public $table = 'conversation_participants';

    /**
     * Allow the pivot to have its own auto-incremented ID so we can load it
     * as a first-class model (e.g., for marking messages read).
     */
    public $incrementing = true;

    protected $fillable = [
        'conversation_id',
        'user_id',
        'role',
        'joined_at',
        'last_read_message_id',
        'last_read_at',
        'is_muted',
        'is_archived',
    ];

    protected $casts = [
        'joined_at'   => 'datetime',
        'last_read_at' => 'datetime',
        'is_muted'    => 'boolean',
        'is_archived' => 'boolean',
    ];

    // ─── Relationships ────────────────────────────────────────────────────────

    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function lastReadMessage(): BelongsTo
    {
        return $this->belongsTo(Message::class, 'last_read_message_id');
    }

    // ─── Helpers ──────────────────────────────────────────────────────────────

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Mark this participant as having read up to the given message.
     */
    public function markReadUpTo(int $messageId): void
    {
        $this->update([
            'last_read_message_id' => $messageId,
            'last_read_at'         => now(),
        ]);
    }
}
