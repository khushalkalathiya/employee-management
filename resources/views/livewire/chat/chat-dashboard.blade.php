<div class="flex h-[calc(100vh-64px)] overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-950"
    x-data="chatApp()" x-on:conversation-opened.window="$nextTick(() => scrollToBottom())"
    x-on:focus-input.window="$nextTick(() => $refs.messageInput?.focus())"
    x-on:message-sent.window="$nextTick(() => scrollToBottom())">

    {{-- ═══════════════════════════════════════════════════════════════
         LEFT SIDEBAR — Conversation List
    ═══════════════════════════════════════════════════════════════ --}}
    <aside class="flex w-72 flex-shrink-0 flex-col border-r border-gray-200 dark:border-gray-800">

        {{-- Header --}}
        <div class="flex items-center justify-between border-b border-gray-200 px-4 py-3 dark:border-gray-800">
            <h2 class="text-base font-semibold text-gray-900 dark:text-white">Messages</h2>
            <button
                class="flex h-8 w-8 items-center justify-center rounded-lg text-gray-500 transition hover:bg-gray-100 hover:text-blue-600 dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-blue-400"
                title="New conversation" wire:click="openNewConversationPanel">
                <svg class="h-5 w-5" fill="none" stroke-width="2" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M12 4v16m8-8H4" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </button>
        </div>

        {{-- Search --}}
        <div class="px-3 py-2">
            <div class="relative">
                <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                    <svg class="h-4 w-4" fill="none" stroke-width="2" stroke="currentColor" viewBox="0 0 24 24">
                        <circle cx="11" cy="11" r="8" />
                        <path d="M21 21l-4.35-4.35" stroke-linecap="round" />
                    </svg>
                </span>
                <input
                    class="w-full rounded-lg border border-gray-200 bg-gray-50 py-2 pl-9 pr-3 text-sm text-gray-800 placeholder-gray-400 outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 dark:placeholder-gray-500 dark:focus:ring-blue-900"
                    placeholder="Search conversations…" type="text" wire:model.live.debounce.300ms="search" />
            </div>
        </div>

        {{-- Conversation list --}}
        <nav class="flex-1 overflow-y-auto">
            @forelse ($conversations as $conv)
                @php
                    $isActive = $activeConversationId === $conv->id;
                    $dispName = $conv->getDisplayNameFor($authUser);
                    $unread = $conv->unreadCountFor($authUser->id);
                    $latest = $conv->latestMessage;
                    $otherUser = $conv->type === 'single' ? $conv->users->firstWhere('id', '!=', $authUser->id) : null;
                @endphp
                <button
                    class="{{ $isActive ? 'bg-blue-50 dark:bg-blue-950/40' : 'hover:bg-gray-50 dark:hover:bg-gray-900' }} flex w-full items-center gap-3 px-4 py-3 text-left transition"
                    wire:click="selectConversation({{ $conv->id }})">
                    {{-- Avatar --}}
                    @if ($conv->type === 'group')
                        <div
                            class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-violet-500 text-sm font-bold text-white">
                            {{ strtoupper(substr($conv->name ?? 'G', 0, 1)) }}
                        </div>
                    @else
                        @if ($otherUser?->avatar)
                            <img alt="{{ $otherUser->full_name }}"
                                class="h-10 w-10 flex-shrink-0 rounded-full object-cover"
                                src="{{ $otherUser->avatar }}" />
                        @else
                            <div
                                class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-blue-500 to-blue-700 text-sm font-bold text-white">
                                {{ $otherUser?->initials ?? '?' }}
                            </div>
                        @endif
                    @endif

                    {{-- Text --}}
                    <div class="min-w-0 flex-1">
                        <div class="flex items-center justify-between">
                            <span
                                class="{{ $isActive ? 'text-blue-600 dark:text-blue-400' : 'text-gray-900 dark:text-white' }} truncate text-sm font-semibold">
                                {{ $dispName }}
                            </span>
                            @if ($latest)
                                <span class="ml-1 flex-shrink-0 text-[11px] text-gray-400">
                                    {{ $latest->created_at->diffForHumans(short: true) }}
                                </span>
                            @endif
                        </div>
                        <div class="flex items-center justify-between">
                            <p class="truncate text-xs text-gray-500 dark:text-gray-400">
                                @if ($latest)
                                    @if ($latest->sender_id === $authUser->id)
                                        <span class="text-gray-400">You: </span>
                                    @endif{{ \Str::limit($latest->message, 40) }}
                                @else
                                    <span class="italic">No messages yet</span>
                                @endif
                            </p>
                            @if ($unread > 0)
                                <span
                                    class="ml-2 flex h-5 min-w-[20px] flex-shrink-0 items-center justify-center rounded-full bg-blue-600 px-1.5 text-[10px] font-bold text-white">
                                    {{ $unread > 99 ? '99+' : $unread }}
                                </span>
                            @endif
                        </div>
                    </div>
                </button>
            @empty
                <div class="px-4 py-8 text-center text-sm text-gray-400">No conversations yet.</div>
            @endforelse
        </nav>
    </aside>

    {{-- ═══════════════════════════════════════════════════════════════
         RIGHT PANEL — Active Conversation
    ═══════════════════════════════════════════════════════════════ --}}
    <div class="flex flex-1 flex-col overflow-hidden">

        @if ($activeConversation)
            @php
                $convPartner =
                    $activeConversation->type === 'single'
                        ? $activeConversation->users->firstWhere('id', '!=', $authUser->id)
                        : null;
                $convTitle = $activeConversation->getDisplayNameFor($authUser);
            @endphp

            {{-- Conversation header --}}
            <div
                class="flex flex-shrink-0 items-center gap-3 border-b border-gray-200 bg-white px-5 py-3 dark:border-gray-800 dark:bg-gray-950">
                @if ($activeConversation->type === 'group')
                    <div
                        class="flex h-9 w-9 items-center justify-center rounded-full bg-violet-500 text-sm font-bold text-white">
                        {{ strtoupper(substr($activeConversation->name ?? 'G', 0, 1)) }}
                    </div>
                @else
                    @if ($convPartner?->avatar)
                        <img class="h-9 w-9 rounded-full object-cover" src="{{ $convPartner->avatar }}" />
                    @else
                        <div
                            class="flex h-9 w-9 items-center justify-center rounded-full bg-gradient-to-br from-blue-500 to-blue-700 text-sm font-bold text-white">
                            {{ $convPartner?->initials ?? '?' }}
                        </div>
                    @endif
                @endif

                <div class="flex-1">
                    <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $convTitle }}</p>
                    @if ($activeConversation->type === 'group')
                        <p class="text-xs text-gray-400">{{ $activeConversation->participants->count() }} members</p>
                    @else
                        <p class="text-xs text-gray-400">
                            {{ $convPartner?->employee?->designation?->name ?? 'Team member' }}</p>
                    @endif
                </div>

                {{-- Poll refresh indicator --}}
                <div class="select-none text-xs text-gray-300 dark:text-gray-700" wire:poll.5s="refreshMessages">●</div>
            </div>

            {{-- Messages area --}}
            <div class="flex-1 space-y-1 overflow-y-auto px-5 py-4" id="chat-messages" x-ref="messagesContainer">
                @php
                    $prevDate = null;
                    $prevSender = null;
                @endphp

                @forelse ($messages as $msg)
                    @php
                        $isMine = $msg->sender_id === $authUser->id;
                        $msgDate = $msg->created_at->toDateString();
                        $showDate = $msgDate !== $prevDate;
                        $showAvatar = $prevSender !== $msg->sender_id || $showDate;
                        $prevDate = $msgDate;
                        $prevSender = $msg->sender_id;
                    @endphp

                    {{-- Date divider --}}
                    @if ($showDate)
                        <div class="flex items-center gap-3 py-2">
                            <div class="flex-1 border-t border-gray-200 dark:border-gray-800"></div>
                            <span
                                class="flex-shrink-0 rounded-full bg-gray-100 px-3 py-0.5 text-[11px] font-medium text-gray-500 dark:bg-gray-800 dark:text-gray-400">
                                {{ $msg->created_at->isToday() ? 'Today' : ($msg->created_at->isYesterday() ? 'Yesterday' : $msg->created_at->format('M j, Y')) }}
                            </span>
                            <div class="flex-1 border-t border-gray-200 dark:border-gray-800"></div>
                        </div>
                    @endif

                    {{-- Message row --}}
                    <div class="{{ $isMine ? 'flex-row-reverse' : '' }} {{ $showAvatar ? 'mt-3' : 'mt-0.5' }} group flex items-end gap-2"
                        wire:key="msg-{{ $msg->id }}">
                        {{-- Avatar --}}
                        <div class="{{ $showAvatar ? '' : 'invisible' }} w-8 flex-shrink-0">
                            @if ($msg->sender?->avatar)
                                <img class="h-8 w-8 rounded-full object-cover" src="{{ $msg->sender->avatar }}" />
                            @else
                                <div
                                    class="flex h-8 w-8 items-center justify-center rounded-full bg-gradient-to-br from-blue-500 to-blue-700 text-xs font-bold text-white">
                                    {{ $msg->sender?->initials ?? '?' }}
                                </div>
                            @endif
                        </div>

                        {{-- Bubble --}}
                        <div class="{{ $isMine ? 'items-end' : 'items-start' }} flex max-w-[70%] flex-col">
                            @if ($showAvatar && !$isMine)
                                <span class="mb-0.5 text-xs font-medium text-gray-500 dark:text-gray-400">
                                    {{ $msg->sender?->full_name }}
                                </span>
                            @endif

                            {{-- Reply preview inside bubble --}}
                            @if ($msg->replyTo)
                                <div
                                    class="{{ $isMine ? 'border-blue-300 bg-blue-100/60 dark:bg-blue-900/30' : 'border-gray-400 bg-gray-100 dark:bg-gray-800' }} mb-1 rounded-lg border-l-4 px-3 py-1 text-xs text-gray-500 dark:text-gray-400">
                                    <span class="font-semibold">{{ $msg->replyTo->sender?->full_name }}</span>
                                    <p class="truncate">{{ \Str::limit($msg->replyTo->message, 60) }}</p>
                                </div>
                            @endif

                            <div class="relative flex items-end gap-1">
                                <div
                                    class="{{ $isMine
                                        ? 'rounded-br-none bg-blue-600 text-white'
                                        : 'rounded-bl-none bg-gray-100 text-gray-900 dark:bg-gray-800 dark:text-gray-100' }} rounded-2xl px-4 py-2 text-sm leading-relaxed shadow-sm">
                                    {!! nl2br(e($msg->message)) !!}
                                    @if ($msg->is_edited)
                                        <span class="ml-1 text-[10px] opacity-60">(edited)</span>
                                    @endif
                                </div>

                                {{-- Action buttons (appear on hover) --}}
                                <div
                                    class="{{ $isMine ? 'order-first' : '' }} invisible flex items-center gap-0.5 group-hover:visible">
                                    <button
                                        class="flex h-6 w-6 items-center justify-center rounded-full text-gray-400 transition hover:bg-gray-200 hover:text-gray-700 dark:hover:bg-gray-700 dark:hover:text-white"
                                        title="Reply" wire:click="setReply({{ $msg->id }})">
                                        <svg class="h-3.5 w-3.5" fill="none" stroke-width="2" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path d="M3 10l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                            <polyline points="9 22 9 12 15 12 15 22" />
                                        </svg>
                                    </button>
                                    @if ($isMine)
                                        <button
                                            class="flex h-6 w-6 items-center justify-center rounded-full text-gray-400 transition hover:bg-red-100 hover:text-red-600 dark:hover:bg-red-900/40 dark:hover:text-red-400"
                                            title="Delete" wire:click="deleteMessage({{ $msg->id }})"
                                            wire:confirm="Delete this message?">
                                            <svg class="h-3.5 w-3.5" fill="none" stroke-width="2"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path d="M3 6h18M8 6V4h8v2M19 6l-1 14H6L5 6" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                            </svg>
                                        </button>
                                    @endif
                                </div>
                            </div>

                            <span class="mt-0.5 text-[10px] text-gray-400">
                                {{ $msg->created_at->format('g:i A') }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="flex flex-1 items-center justify-center py-20 text-sm text-gray-400">
                        No messages yet. Start the conversation!
                    </div>
                @endforelse
            </div>

            {{-- Message composer --}}
            <div
                class="flex-shrink-0 border-t border-gray-200 bg-white px-4 py-3 dark:border-gray-800 dark:bg-gray-950">

                {{-- Reply bar --}}
                @if ($replyPreviewText)
                    <div
                        class="mb-2 flex items-center gap-2 rounded-lg border border-blue-200 bg-blue-50 px-3 py-2 dark:border-blue-900 dark:bg-blue-950/40">
                        <svg class="h-4 w-4 flex-shrink-0 text-blue-500" fill="none" stroke-width="2"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M3 10l9-7 9 7v11H5V10z" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <p class="flex-1 truncate text-xs text-blue-700 dark:text-blue-300">
                            Replying to: <span class="font-medium">{{ $replyPreviewText }}</span>
                        </p>
                        <button class="text-gray-400 hover:text-gray-700 dark:hover:text-white"
                            wire:click="clearReply">
                            <svg class="h-4 w-4" fill="none" stroke-width="2.5" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path d="M6 18L18 6M6 6l12 12" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </button>
                    </div>
                @endif

                <form class="flex items-end gap-2" wire:submit="sendMessage">
                    <textarea
                        class="flex-1 resize-none rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 dark:placeholder-gray-500 dark:focus:ring-blue-900"
                        placeholder="Type a message… (Enter to send, Shift+Enter for new line)" rows="1"
                        style="max-height: 120px; overflow-y: auto;" wire:model="newMessage"
                        x-on:input="$el.style.height = 'auto'; $el.style.height = Math.min($el.scrollHeight, 120) + 'px'"
                        x-on:keydown.enter.prevent="!$event.shiftKey && $wire.sendMessage()" x-ref="messageInput"></textarea>
                    <button
                        class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-xl bg-blue-600 text-white shadow transition hover:bg-blue-700 disabled:opacity-50"
                        type="submit">
                        <svg class="h-5 w-5 translate-x-0.5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z" />
                        </svg>
                    </button>
                </form>
            </div>
        @else
            {{-- Empty state --}}
            <div class="flex flex-1 flex-col items-center justify-center gap-4 text-center">
                <div class="flex h-20 w-20 items-center justify-center rounded-full bg-blue-50 dark:bg-blue-950/30">
                    <svg class="h-10 w-10 text-blue-400" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm0 14H5.17L4 17.17V4h16v12z" />
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300">No conversation selected</h3>
                <p class="max-w-xs text-sm text-gray-400">Pick an existing conversation from the left, or start a new
                    one.</p>
                <button
                    class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow transition hover:bg-blue-700"
                    wire:click="openNewConversationPanel">
                    Start a conversation
                </button>
            </div>
        @endif
    </div>

    {{-- ═══════════════════════════════════════════════════════════════
         NEW CONVERSATION PANEL (modal overlay)
    ═══════════════════════════════════════════════════════════════ --}}
    @if ($showNewConversationPanel)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm"
            x-on:keydown.escape.window="$wire.closeNewConversationPanel()">
            <div
                class="w-full max-w-md rounded-2xl border border-gray-200 bg-white shadow-2xl dark:border-gray-800 dark:bg-gray-950">

                {{-- Panel header --}}
                <div class="flex items-center justify-between border-b border-gray-200 px-5 py-4 dark:border-gray-800">
                    <h3 class="text-base font-semibold text-gray-900 dark:text-white">New Direct Message</h3>
                    <button
                        class="rounded-lg p-1 text-gray-400 transition hover:bg-gray-100 hover:text-gray-700 dark:hover:bg-gray-800 dark:hover:text-white"
                        wire:click="closeNewConversationPanel">
                        <svg class="h-5 w-5" fill="none" stroke-width="2.5" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path d="M6 18L18 6M6 6l12 12" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </button>
                </div>

                {{-- User search --}}
                <div class="px-4 py-3">
                    <div class="relative">
                        <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                            <svg class="h-4 w-4" fill="none" stroke-width="2" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <circle cx="11" cy="11" r="8" />
                                <path d="M21 21l-4.35-4.35" stroke-linecap="round" />
                            </svg>
                        </span>
                        <input autofocus
                            class="w-full rounded-lg border border-gray-200 bg-gray-50 py-2 pl-9 pr-3 text-sm outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 dark:focus:ring-blue-900"
                            placeholder="Search teammates…" type="text"
                            wire:model.live.debounce.300ms="userSearch" />
                    </div>
                </div>

                {{-- User list --}}
                <ul class="max-h-72 divide-y divide-gray-100 overflow-y-auto dark:divide-gray-800">
                    @forelse ($users as $user)
                        <li>
                            <button
                                class="flex w-full items-center gap-3 px-5 py-3 text-left transition hover:bg-blue-50 dark:hover:bg-blue-950/40"
                                wire:click="startConversationWith({{ $user->id }})">
                                @if ($user->avatar)
                                    <img class="h-9 w-9 flex-shrink-0 rounded-full object-cover"
                                        src="{{ $user->avatar }}" />
                                @else
                                    <div
                                        class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-blue-500 to-blue-700 text-sm font-bold text-white">
                                        {{ $user->initials }}
                                    </div>
                                @endif
                                <div>
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white">
                                        {{ $user->full_name }}</p>
                                    <p class="text-xs text-gray-400">{{ $user->email }}</p>
                                </div>
                            </button>
                        </li>
                    @empty
                        <li class="px-5 py-8 text-center text-sm text-gray-400">
                            @if ($userSearch)
                                No teammates found for "{{ $userSearch }}".
                            @else
                                Start typing to find a teammate.
                            @endif
                        </li>
                    @endforelse
                </ul>
            </div>
        </div>
    @endif

</div>

{{-- ─── Alpine.js component ─────────────────────────────────────────────── --}}
@script
    <script>
        function chatApp() {
            return {
                scrollToBottom() {
                    const container = this.$refs.messagesContainer;
                    if (container) {
                        container.scrollTop = container.scrollHeight;
                    }
                },
                init() {
                    this.$nextTick(() => this.scrollToBottom());
                },
            };
        }
    </script>
@endscript
