<?php

namespace App\Actions\Chat;

use App\Models\Conversation;

class MarkConversationReadAction
{
    /**
     * Advance the read cursor for a participant to the latest message in the
     * conversation.
     */
    public function handle(Conversation $conversation, int $userId): void
    {
        $latestMessage = $conversation->messages()->latest()->first();

        if (! $latestMessage) {
            return;
        }

        $conversation
            ->participants()
            ->where('user_id', $userId)
            ->first()
            ?->markReadUpTo($latestMessage->id);
    }
}
