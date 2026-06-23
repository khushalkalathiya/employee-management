<div wire:key="attendance-control-card" x-data="{
    isClockedIn: @js($isClockedIn),
    isOnBreak: @js($isOnBreak),
    workingSecs: @js($workingSeconds),
    breakSecs: @js($breakSeconds),
    ticker: null,
    formatTime(s) {
        let h = Math.floor(s / 3600);
        let m = Math.floor((s % 3600) / 60);
        let sec = s % 60;
        return [h, m, sec].map(v => String(v).padStart(2, '0')).join(':');
    },
    startTick() {
        this.stopTick();
        this.ticker = setInterval(() => {
            if (!this.isClockedIn) return;
            if (this.isOnBreak) { this.breakSecs++; } else { this.workingSecs++; }
        }, 1000);
    },
    stopTick() {
        if (this.ticker) { clearInterval(this.ticker);
            this.ticker = null; }
    },
    init() {
        if (this.isClockedIn) this.startTick();
        this.$watch('isClockedIn', v => { if (v) this.startTick();
            else this.stopTick(); });
    }
}" x-init="init()">
    {{-- Card --}}
    <div class="card" style="margin:0 20px 20px">
        <div style="padding:18px 20px">
            <div style="display:flex;flex-wrap:wrap;gap:20px;align-items:center;justify-content:space-between">

                {{-- Left: Status + Date --}}
                <div style="display:flex;align-items:center;gap:14px">
                    {{-- Animated status dot --}}
                    <div style="position:relative;flex-shrink:0">
                        <span class="flex h-11 w-11 items-center justify-center rounded-full shadow-md"
                            x-bind:class="{
                                'bg-emerald-500': isClockedIn && !isOnBreak,
                                'bg-amber-400': isClockedIn && isOnBreak,
                                'bg-gray-400': !isClockedIn
                            }">
                            <span class="absolute inline-flex h-11 w-11 rounded-full"
                                x-bind:class="{
                                    'animate-ping bg-emerald-400 opacity-50': isClockedIn && !isOnBreak,
                                    'animate-ping bg-amber-300 opacity-50': isClockedIn && isOnBreak,
                                    'bg-transparent opacity-0': !isClockedIn
                                }"></span>
                            {{-- Clock icon --}}
                            <svg class="relative z-10 text-white" fill="none" height="20" stroke-width="2"
                                stroke="currentColor" viewBox="0 0 24 24" width="20">
                                <circle cx="12" cy="12" r="10" />
                                <polyline points="12 6 12 12 16 14" />
                            </svg>
                        </span>
                    </div>

                    <div>
                        <div
                            style="font-size:11px;text-transform:uppercase;letter-spacing:.06em;color:var(--muted);font-weight:600;margin-bottom:3px">
                            Today's Status
                        </div>
                        <div style="font-size:18px;font-weight:700;color:var(--text);line-height:1.2">
                            <span x-show="!isClockedIn">Clocked Out</span>
                            <span style="color:#10b981" x-show="isClockedIn && !isOnBreak">Clocked In</span>
                            <span style="color:#f59e0b" x-show="isClockedIn && isOnBreak">On Break</span>
                        </div>
                        <div style="font-size:13px;color:var(--muted);margin-top:2px">
                            @if ($clockInDate)
                                {{ $clockInDate }}
                            @else
                                {{ now()->format('l, d F Y') }}
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Center: Timers --}}
                <div style="display:flex;gap:24px;flex-wrap:wrap">
                    {{-- Working Timer --}}
                    <div style="text-align:center">
                        <div
                            style="font-size:11px;color:var(--muted);text-transform:uppercase;letter-spacing:.06em;margin-bottom:4px">
                            Work Time</div>
                        <div style="font-family:'Outfit',sans-serif;font-size:22px;font-weight:700;color:#10b981;letter-spacing:.02em;font-variant-numeric:tabular-nums"
                            x-text="formatTime(workingSecs)">{{ gmdate('H:i:s', $workingSeconds) }}</div>
                    </div>

                    <div style="width:1px;background:var(--border);align-self:stretch"></div>

                    {{-- Break Timer --}}
                    <div style="text-align:center">
                        <div
                            style="font-size:11px;color:var(--muted);text-transform:uppercase;letter-spacing:.06em;margin-bottom:4px">
                            Break Time</div>
                        <div style="font-family:'Outfit',sans-serif;font-size:22px;font-weight:700;color:#f59e0b;letter-spacing:.02em;font-variant-numeric:tabular-nums"
                            x-text="formatTime(breakSecs)">{{ gmdate('H:i:s', $breakSeconds) }}</div>
                    </div>

                    @if ($clockInTime)
                        <div style="width:1px;background:var(--border);align-self:stretch"></div>
                        <div style="text-align:center">
                            <div
                                style="font-size:11px;color:var(--muted);text-transform:uppercase;letter-spacing:.06em;margin-bottom:4px">
                                Clock In</div>
                            <div style="font-size:16px;font-weight:700;color:var(--text)">{{ $clockInTime }}</div>
                        </div>
                    @endif
                </div>

                {{-- Right: Action Buttons --}}
                <div style="display:flex;gap:10px;flex-wrap:wrap;align-items:center">

                    {{-- Clock In button (shown when NOT clocked in) --}}
                    @can('attendance.create')
                        <div x-show="!isClockedIn">
                            <button class="btn-primary inline-flex items-center gap-2"
                                onclick="handleAttendanceAction('{{ route('attendance.check-in') }}', this)"
                                style="padding:10px 20px;font-size:13px" type="button">
                                <svg fill="none" height="15" stroke-width="2.5" stroke="currentColor"
                                    viewBox="0 0 24 24" width="15">
                                    <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <polyline points="10 17 15 12 10 7" stroke-linecap="round" stroke-linejoin="round" />
                                    <line stroke-linecap="round" stroke-linejoin="round" x1="15" x2="3"
                                        y1="12" y2="12" />
                                </svg>
                                Clock In
                            </button>
                        </div>

                        {{-- Clock Out + Break toggle (shown when clocked in) --}}
                        <div style="display:flex;gap:10px;flex-wrap:wrap" x-show="isClockedIn">

                            {{-- Break toggle --}}
                            <button
                                class="inline-flex items-center gap-2 rounded-lg px-4 py-2 text-sm font-semibold text-white shadow-lg transition-all focus:outline-none active:scale-[.98]"
                                data-break-end-url="{{ route('attendance.break-end') }}"
                                data-break-start-url="{{ route('attendance.break-start') }}" data-is-on-break="0"
                                type="button"
                                x-bind:class="{
                                    'bg-amber-500 hover:bg-amber-600 shadow-amber-500/20': !isOnBreak,
                                    'bg-emerald-500 hover:bg-emerald-600 shadow-emerald-500/20': isOnBreak
                                }"
                                x-on:click="handleBreakToggle($el)">
                                <svg fill="none" height="15" stroke-width="2.5" stroke="currentColor"
                                    viewBox="0 0 24 24" width="15" x-show="!isOnBreak">
                                    <path d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                                <svg fill="none" height="15" stroke-width="2.5" stroke="currentColor"
                                    viewBox="0 0 24 24" width="15" x-show="isOnBreak">
                                    <path
                                        d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                                <span x-text="isOnBreak ? 'End Break' : 'Start Break'">Start Break</span>
                            </button>

                            {{-- Clock Out --}}
                            <button
                                class="inline-flex items-center gap-2 rounded-lg bg-rose-500 px-4 py-2 text-sm font-semibold text-white shadow-lg shadow-rose-500/20 transition-all hover:bg-rose-600 focus:outline-none active:scale-[.98]"
                                onclick="handleAttendanceAction('{{ route('attendance.check-out') }}', this)"
                                type="button">
                                <svg fill="none" height="15" stroke-width="2.5" stroke="currentColor"
                                    viewBox="0 0 24 24" width="15">
                                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <polyline points="16 17 21 12 16 7" stroke-linecap="round" stroke-linejoin="round" />
                                    <line stroke-linecap="round" stroke-linejoin="round" x1="21" x2="9"
                                        y1="12" y2="12" />
                                </svg>
                                Clock Out
                            </button>
                        </div>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    (function() {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

        // Generic POST handler used by clock-in and clock-out buttons
        window.handleAttendanceAction = async function(url, btn) {
            const originalHtml = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML =
                '<svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>';

            try {
                const res = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                });
                const data = await res.json();
                if (data.success) {
                    if (window.showToast) window.showToast(data.message || 'Done', 'success');
                    // Reload Livewire components
                    if (typeof Livewire !== 'undefined') {
                        Livewire.dispatch('refresh-table');
                        Livewire.dispatch('refresh-control-card');
                    }
                } else {
                    if (window.showToast) window.showToast(data.message || 'Failed', 'error');
                    btn.disabled = false;
                    btn.innerHTML = originalHtml;
                }
            } catch (e) {
                if (window.showToast) window.showToast('Server error. Please try again.', 'error');
                btn.disabled = false;
                btn.innerHTML = originalHtml;
            }
        };

        // Break toggle – reads Alpine state to determine which URL to call
        window.handleBreakToggle = async function(btn) {
            const alpineEl = btn.closest('[x-data]');
            const alpine = alpineEl ? window.Alpine?.getComponent?.(alpineEl) ?? null : null;

            const isOnBreak = btn.closest('[x-data]')?.__x?.$data?.isOnBreak ??
                (btn.textContent.trim().includes('End'));

            const breakStartUrl = btn.dataset.breakStartUrl;
            const breakEndUrl = btn.dataset.breakEndUrl;
            const url = isOnBreak ? breakEndUrl : breakStartUrl;

            await window.handleAttendanceAction(url, btn);
        };
    })();
</script>
