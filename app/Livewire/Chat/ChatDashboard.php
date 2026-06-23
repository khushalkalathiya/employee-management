<?php

namespace App\Livewire\Chat;

use App\Actions\Chat\MarkConversationReadAction;
use App\Actions\Chat\SendMessageAction;
use App\Actions\Chat\StartDirectConversationAction;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Collection;
use Livewire\Attributes\On;
use Livewire\Component;

class ChatDashboard extends Component
{
    // ─── State ────────────────────────────────────────────────────────────────

    /** Currently open conversation ID */
    public ?int $activeConversationId = null;

    /** Message text being composed */
    public string $newMessage = '';

    /** ID of the message being replied to */
    public ?int $replyToMessageId = null;

    /** Reply preview text shown in the input bar */
    public ?string $replyPreviewText = null;

    /** Search query for the contact/conversation list */
    public string $search = '';

    /** Whether the "New conversation" user-picker panel is open */
    public bool $showNewConversationPanel = false;

    /** Search term inside the user-picker panel */
    public string $userSearch = '';

    // ─── Computed helpers (not persisted in state) ────────────────────────────

    /** @var Conversation|null */
    private ?Conversation $activeConversation = null;

    // ─── Lifecycle ────────────────────────────────────────────────────────────

    public function mount(): void
    {
        // Auto-open the first conversation for the current user, if any.
        $first = $this->buildConversationList()->first();
        if ($first) {
            $this->selectConversation($first->id);
        }
    }

    // ─── Actions ──────────────────────────────────────────────────────────────

    /**
     * Open a conversation and mark it as read.
     */
    public function selectConversation(int $conversationId): void
    {
        // Verify the user is actually a participant.
        $participant = Conversation::findOrFail($conversationId)
            ->participants()
            ->where('user_id', auth()->id())
            ->first();

        if (! $participant) {
            return;
        }

        $this->activeConversationId = $conversationId;
        $this->replyToMessageId     = null;
        $this->replyPreviewText     = null;
        $this->newMessage           = '';

        (new MarkConversationReadAction())->handle(
            Conversation::find($conversationId),
            auth()->id()
        );

        // Tell the JS side to scroll to the bottom of the message list.
        $this->dispatch('conversation-opened');
    }

    /**
     * Send the composed message.
     */
    public function sendMessage(): void
    {
        $this->validate([
            'newMessage' => 'required|string|max:5000',
        ]);

        if (! $this->activeConversationId) {
            return;
        }

        $message = (new SendMessageAction())->handle([
            'conversation_id'     => $this->activeConversationId,
            'sender_id'           => auth()->id(),
            'message'             => $this->newMessage,
            'type'                => 'text',
            'reply_to_message_id' => $this->replyToMessageId,
        ]);

        $this->newMessage       = '';
        $this->replyToMessageId = null;
        $this->replyPreviewText = null;

        // Dispatch a browser event so Alpine.js can scroll to the new message.
        $this->dispatch('message-sent', messageId: $message->id);
    }

    /**
     * Set a reply target.
     */
    public function setReply(int $messageId): void
    {
        $message = Message::find($messageId);
        if (! $message) {
            return;
        }

        $this->replyToMessageId = $messageId;
        $this->replyPreviewText = \Str::limit($message->message, 80);

        $this->dispatch('focus-input');
    }

    /**
     * Clear the reply target.
     */
    public function clearReply(): void
    {
        $this->replyToMessageId = null;
        $this->replyPreviewText = null;
    }

    /**
     * Delete own message (soft-delete).
     */
    public function deleteMessage(int $messageId): void
    {
        $message = Message::findOrFail($messageId);

        if ($message->sender_id !== auth()->id()) {
            return;
        }

        $message->delete();
    }

    /**
     * Open the new-conversation picker.
     */
    public function openNewConversationPanel(): void
    {
        $this->showNewConversationPanel = true;
        $this->userSearch               = '';
    }

    /**
     * Close the new-conversation picker.
     */
    public function closeNewConversationPanel(): void
    {
        $this->showNewConversationPanel = false;
        $this->userSearch               = '';
    }

    /**
     * Start or resume a DM with a given user and switch to that conversation.
     */
    public function startConversationWith(int $userId): void
    {
        $other        = User::findOrFail($userId);
        $conversation = (new StartDirectConversationAction())->handle(auth()->user(), $other);

        $this->closeNewConversationPanel();
        $this->selectConversation($conversation->id);
    }

    // ─── Polling refresh ─────────────────────────────────────────────────────

    /**
     * Called by Livewire's wire:poll to refresh messages without a full
     * component re-render.  We just touch the state so Livewire re-renders.
     */
    public function refreshMessages(): void
    {
        // Intentionally empty — re-render is the goal.
        // Livewire will diff the DOM and only patch what changed.
    }

    // ─── Data builders ────────────────────────────────────────────────────────

    private function buildConversationList(): Collection
    {
        return Conversation::query()
            ->whereHas('participants', fn ($q) => $q->where('user_id', auth()->id()))
            ->with([
                'participants.user',
                'latestMessage.sender',
            ])
            ->when($this->search, function ($query) {
                // For group conversations match name; for single match the other user's name.
                $query->where(function ($q) {
                    $q->where('type', 'group')
                      ->where('name', 'like', "%{$this->search}%");
                })->orWhere(function ($q) {
                    $q->where('type', 'single')
                      ->whereHas('participants.user', function ($uq) {
                          $uq->where('user_id', '!=', auth()->id())
                             ->where(function ($nameQ) {
                                 $nameQ->where('first_name', 'like', "%{$this->search}%")
                                       ->orWhere('last_name', 'like', "%{$this->search}%");
                             });
                      });
                });
            })
            ->orderByDesc(
                Message::select('created_at')
                    ->whereColumn('conversation_id', 'conversations.id')
                    ->latest()
                    ->limit(1)
            )
            ->get();
    }

    private function buildMessageList(): Collection
    {
        if (! $this->activeConversationId) {
            return collect();
        }

        return Message::query()
            ->where('conversation_id', $this->activeConversationId)
            ->with(['sender', 'replyTo.sender'])
            ->orderBy('created_at')
            ->get();
    }

    private function buildUserPickerList(): Collection
    {
        if (! $this->showNewConversationPanel) {
            return collect();
        }

        return User::query()
            ->where('id', '!=', auth()->id())
            ->where('is_active', true)
            ->when($this->userSearch, function ($query) {
                $query->where(function ($q) {
                    $q->where('first_name', 'like', "%{$this->userSearch}%")
                      ->orWhere('last_name', 'like', "%{$this->userSearch}%")
                      ->orWhere('email', 'like', "%{$this->userSearch}%");
                });
            })
            ->orderBy('first_name')
            ->limit(30)
            ->get();
    }

    // ─── Render ───────────────────────────────────────────────────────────────

    public function render()
    {
        $conversations       = $this->buildConversationList();
        $messages            = $this->buildMessageList();
        $activeConversation  = $this->activeConversationId
            ? $conversations->firstWhere('id', $this->activeConversationId)
              ?? Conversation::with(['participants.user'])->find($this->activeConversationId)
            : null;
        $users               = $this->buildUserPickerList();

        return view('livewire.chat.chat-dashboard', [
            'conversations'      => $conversations,
            'messages'           => $messages,
            'activeConversation' => $activeConversation,
            'users'              => $users,
            'authUser'           => auth()->user(),
        ])->layout('layouts.app');
    }
}
