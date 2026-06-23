<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Conversation extends Model implements HasMedia
{
    use SoftDeletes, InteractsWithMedia;

    protected $fillable = [
        'type',
        'name',
        'description',
        'created_by',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // ─── Media ───────────────────────────────────────────────────────────────

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('conversation_avatar')
            ->useDisk(config('media-library.disk_name'))
            ->singleFile();
    }

    public function getAvatarUrlAttribute(): ?string
    {
        return $this->getFirstMediaUrl('conversation_avatar') ?: null;
    }

    // ─── Relationships ────────────────────────────────────────────────────────

    public function participants(): HasMany
    {
        return $this->hasMany(ConversationParticipant::class);
    }

    /**
     * All users who participate in this conversation (pivot).
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'conversation_participants')
            ->using(ConversationParticipant::class)
            ->withPivot([
                'id',
                'role',
                'joined_at',
                'last_read_message_id',
                'last_read_at',
                'is_muted',
                'is_archived',
            ])
            ->withTimestamps();
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class)->orderBy('created_at');
    }

    public function latestMessage(): HasOne
    {
        return $this->hasOne(Message::class)->latestOfMany();
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // ─── Helpers ──────────────────────────────────────────────────────────────

    /**
     * Get the display name for this conversation relative to a given user.
     * For single (DM) conversations, returns the other participant's name.
     * For group conversations, returns the group name.
     */
    public function getDisplayNameFor(User $user): string
    {
        if ($this->type === 'group') {
            return $this->name ?? 'Unnamed Group';
        }

        $other = $this->users->firstWhere('id', '!=', $user->id);

        return $other?->full_name ?? 'Unknown';
    }

    /**
     * Get the unread message count for a participant.
     */
    public function unreadCountFor(int $userId): int
    {
        $participant = $this->participants->firstWhere('user_id', $userId);

        if (! $participant) {
            return 0;
        }

        return $this->messages()
            ->where('sender_id', '!=', $userId)
            ->when($participant->last_read_message_id, function ($query) use ($participant) {
                $query->where('id', '>', $participant->last_read_message_id);
            })
            ->count();
    }
}
