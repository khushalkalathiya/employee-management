<?php

namespace App\Actions\Chat;

use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Support\Facades\DB;

class SendMessageAction
{
    /**
     * Persist a new message and update the sender's read cursor.
     *
     * @param  array{
     *     conversation_id: int,
     *     sender_id: int,
     *     message: string,
     *     type?: string,
     *     reply_to_message_id?: int|null,
     * } $data
     */
    public function handle(array $data): Message
    {
        return DB::transaction(function () use ($data) {
            /** @var Message $message */
            $message = Message::create([
                'conversation_id'     => $data['conversation_id'],
                'sender_id'           => $data['sender_id'],
                'message'             => $data['message'],
                'type'                => $data['type'] ?? 'text',
                'reply_to_message_id' => $data['reply_to_message_id'] ?? null,
            ]);

            // Advance the sender's read cursor so they don't see their own
            // message as unread.
            $conversation = Conversation::find($data['conversation_id']);
            $participant  = $conversation
                ->participants()
                ->where('user_id', $data['sender_id'])
                ->first();

            if ($participant) {
                $participant->markReadUpTo($message->id);
            }

            return $message->load('sender');
        });
    }
}
