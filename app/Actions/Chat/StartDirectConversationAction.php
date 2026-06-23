<?php

namespace App\Actions\Chat;

use App\Models\Conversation;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class StartDirectConversationAction
{
    /**
     * Find an existing DM conversation between two users, or create one.
     */
    public function handle(User $userA, User $userB): Conversation
    {
        // Look for an existing single conversation shared by both users.
        $existing = Conversation::query()
            ->where('type', 'single')
            ->whereHas('participants', fn ($q) => $q->where('user_id', $userA->id))
            ->whereHas('participants', fn ($q) => $q->where('user_id', $userB->id))
            ->withCount('participants')
            ->having('participants_count', '=', 2)
            ->first();

        if ($existing) {
            return $existing;
        }

        return DB::transaction(function () use ($userA, $userB) {
            /** @var Conversation $conversation */
            $conversation = Conversation::create([
                'type'       => 'single',
                'created_by' => $userA->id,
                'is_active'  => true,
            ]);

            $now = now();

            $conversation->participants()->createMany([
                ['user_id' => $userA->id, 'role' => 'member', 'joined_at' => $now],
                ['user_id' => $userB->id, 'role' => 'member', 'joined_at' => $now],
            ]);

            return $conversation;
        });
    }
}
