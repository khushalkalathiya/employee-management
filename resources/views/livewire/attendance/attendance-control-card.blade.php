{{--
    Attendance Control Card
    ─────────────────────────────────────────────────────────────────────────
    Schedule config (from Livewire $scheduleConfig) is injected as JSON and
    consumed by Alpine.js for all client-side clock-in window logic:

      • is_working_day              – whether today is a scheduled work day
      • start_time                  – shift start (12-h "g:i A" format, e.g. "9:00 AM")
      • end_time                    – shift end
      • early_clock_in_minutes      – how many minutes before start_time is allowed
      • late_allowance_minutes      – grace period; beyond this requires a late reason
    ─────────────────────────────────────────────────────────────────────────
--}}
<div wire:key="attendance-control-card" x-data="attendanceCard(@js($isClockedIn), @js($isOnBreak), @js($workingSeconds), @js($breakSeconds), @js($scheduleConfig))" x-init="init()">

    {{-- ── Main card ─────────────────────────────────────────────────── --}}
    <div class="card" style="margin:0 20px 20px">
        <div style="padding:18px 20px">
            <div style="display:flex;flex-wrap:wrap;gap:20px;align-items:center;justify-content:space-between">

                {{-- Left: Status + Date --}}
                <div style="display:flex;align-items:center;gap:14px">
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
                        {{-- Clock-in availability hint (only when not yet clocked in) --}}
                        <div style="font-size:12px;margin-top:4px" x-bind:style="clockInHintStyle()"
                            x-show="!isClockedIn">
                            <span x-text="clockInHintText()"></span>
                        </div>
                    </div>
                </div>

                {{-- Center: Timers --}}
                <div style="display:flex;gap:24px;flex-wrap:wrap">
                    <div style="text-align:center">
                        <div
                            style="font-size:11px;color:var(--muted);text-transform:uppercase;letter-spacing:.06em;margin-bottom:4px">
                            Work Time</div>
                        <div style="font-family:'Outfit',sans-serif;font-size:22px;font-weight:700;color:#10b981;letter-spacing:.02em;font-variant-numeric:tabular-nums"
                            x-text="formatTime(workingSecs)">{{ gmdate('H:i:s', $workingSeconds) }}</div>
                    </div>

                    <div style="width:1px;background:var(--border);align-self:stretch"></div>

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

                    @can('attendance.create')
                        {{-- Clock In button (shown when NOT clocked in) --}}
                        <div x-show="!isClockedIn">
                            <button class="btn-primary inline-flex items-center gap-2"
                                style="padding:10px 20px;font-size:13px" type="button"
                                x-bind:class="{ 'opacity-50 cursor-not-allowed': !canClockIn() }"
                                x-bind:disabled="!canClockIn()" x-on:click="onClockInClick">
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

    {{-- ── Late Reason Modal ──────────────────────────────────────────── --}}
    {{--
        Opens automatically when the user clicks Clock In and current time
        is beyond (shift start + late_allowance_minutes).
        Requires ≥5 characters before the submit button becomes active.
    --}}
    <div class="fixed inset-0 z-50 flex items-center justify-center" style="background:rgba(0,0,0,.5);display:none"
        x-show="showLateModal" x-transition:enter-end="opacity-100" x-transition:enter-start="opacity-0"
        x-transition:enter="transition ease-out duration-200" x-transition:leave-end="opacity-0"
        x-transition:leave-start="opacity-100" x-transition:leave="transition ease-in duration-150">

        <div @click.outside="showLateModal = false"
            class="relative w-full max-w-md rounded-2xl bg-white p-6 shadow-xl dark:bg-gray-900"
            x-show="showLateModal" x-transition:enter-end="opacity-100 scale-100"
            x-transition:enter-start="opacity-0 scale-95" x-transition:enter="transition ease-out duration-200"
            x-transition:leave-end="opacity-0 scale-95" x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-150">

            {{-- Header --}}
            <div class="mb-4 flex items-start justify-between gap-3">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        You're Late
                    </h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Your shift started at <span class="font-medium text-gray-700 dark:text-gray-300"
                            x-text="schedule.start_time"></span>.
                        Please provide a reason to continue.
                    </p>
                </div>
                <button @click="showLateModal = false"
                    class="rounded-lg p-1 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300" type="button">
                    <svg fill="none" height="20" stroke-width="2" stroke="currentColor" viewBox="0 0 24 24"
                        width="20">
                        <path d="M6 18L18 6M6 6l12 12" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </button>
            </div>

            {{-- Reason textarea --}}
            <div class="mb-1">
                <label class="field-label mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Why are you late?
                    <span class="text-red-400">*</span>
                </label>
                <textarea class="field-input w-full resize-none rounded-lg" maxlength="1000"
                    placeholder="Minimum 5 characters required…" rows="4" x-model="lateReason"
                    x-on:input="lateReasonError = ''">
                </textarea>
            </div>

            {{-- Char count + validation hint --}}
            <div class="mb-4 flex items-center justify-between text-xs text-gray-400">
                <span class="text-red-500" x-show="lateReasonError" x-text="lateReasonError"></span>
                <span x-show="!lateReasonError">&nbsp;</span>
                <span x-text="lateReason.length + ' / 1000'"></span>
            </div>

            {{-- Actions --}}
            <div class="flex justify-end gap-3">
                <button @click="showLateModal = false"
                    class="rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700"
                    type="button">
                    Cancel
                </button>
                <button @click="submitLateClockIn"
                    class="btn-primary inline-flex items-center gap-2 px-4 py-2 text-sm" type="button"
                    x-bind:class="{ 'opacity-50 cursor-not-allowed': lateReason.trim().length < 5 || lateModalSubmitting }"
                    x-bind:disabled="lateReason.trim().length < 5 || lateModalSubmitting">
                    <svg class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24"
                        x-show="lateModalSubmitting">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke-width="4"
                            stroke="currentColor"></circle>
                        <path class="opacity-75" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" fill="currentColor">
                        </path>
                    </svg>
                    <span x-text="lateModalSubmitting ? 'Clocking In…' : 'Clock In'">Clock In</span>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    (function() {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

        // ── Alpine component factory ────────────────────────────────────────
        window.attendanceCard = function(isClockedIn, isOnBreak, workingSecs, breakSecs, schedule) {
            return {
                // ── reactive state ──────────────────────────────────────────
                isClockedIn,
                isOnBreak,
                workingSecs,
                breakSecs,
                schedule, // {is_working_day, start_time, end_time, early_clock_in_minutes, late_allowance_minutes}

                // Late-reason modal state
                showLateModal: false,
                lateReason: '',
                lateReasonError: '',
                lateModalSubmitting: false,

                // Ticker
                ticker: null,

                // ── helpers ─────────────────────────────────────────────────
                formatTime(s) {
                    let h = Math.floor(s / 3600);
                    let m = Math.floor((s % 3600) / 60);
                    let sec = s % 60;
                    return [h, m, sec].map(v => String(v).padStart(2, '0')).join(':');
                },

                /**
                 * Parse a 12-hour time string ("9:00 AM") into today's Date object.
                 * Returns null if start_time is not configured.
                 */
                parseShiftTime(timeStr) {
                    if (!timeStr) return null;
                    try {
                        const [timePart, meridiem] = timeStr.split(' ');
                        let [hours, minutes] = timePart.split(':').map(Number);
                        if (meridiem === 'PM' && hours !== 12) hours += 12;
                        if (meridiem === 'AM' && hours === 12) hours = 0;
                        const d = new Date();
                        d.setHours(hours, minutes, 0, 0);
                        return d;
                    } catch (e) {
                        return null;
                    }
                },

                /**
                 * Returns true when the Clock In button should be enabled.
                 *
                 * Rules:
                 *  1. Today must be a working day.
                 *  2. Current time must be ≥ (shift start − early_clock_in_minutes).
                 *  3. If no schedule is configured (start_time null) allow clock-in freely.
                 */
                canClockIn() {
                    if (!this.schedule.is_working_day) return false;
                    if (this.schedule.timing_mode === 'flexible') return true;

                    const startTime = this.parseShiftTime(this.schedule.start_time);
                    if (!startTime) return true; // no schedule configured → allow

                    const windowOpen = new Date(
                        startTime.getTime() - this.schedule.early_clock_in_minutes * 60 * 1000
                    );
                    return new Date() >= windowOpen;
                },

                /**
                 * Returns true when the user is clocking in late
                 * (current time > shift start + late_allowance_minutes).
                 */
                isLate() {
                    if (this.schedule.timing_mode === 'flexible') return false;

                    const startTime = this.parseShiftTime(this.schedule.start_time);
                    if (!startTime) return false;

                    const deadline = new Date(
                        startTime.getTime() + this.schedule.late_allowance_minutes * 60 * 1000
                    );
                    return new Date() > deadline;
                },

                /**
                 * Small hint text shown below the status date when not yet clocked in.
                 */
                clockInHintText() {
                    if (!this.schedule.is_working_day) return 'No scheduled work today.';
                    if (this.schedule.timing_mode === 'flexible') return 'Flexible Shift — Clock in anytime.';
                    if (!this.schedule.start_time) return '';

                    if (this.canClockIn()) {
                        return this.isLate() ?
                            'Clock in is open (you are late — a reason will be required).' :
                            'Clock in window is open.';
                    }

                    const startTime = this.parseShiftTime(this.schedule.start_time);
                    const windowOpen = new Date(
                        startTime.getTime() - this.schedule.early_clock_in_minutes * 60 * 1000
                    );
                    const hm = windowOpen.toLocaleTimeString([], {
                        hour: '2-digit',
                        minute: '2-digit'
                    });
                    return `Clock in opens at ${hm}.`;
                },

                clockInHintStyle() {
                    if (!this.schedule.is_working_day) return 'color:#9ca3af';
                    if (this.schedule.timing_mode === 'flexible') return 'color:#10b981';
                    if (!this.canClockIn()) return 'color:#ef4444';
                    if (this.isLate()) return 'color:#f59e0b';
                    return 'color:#10b981';
                },

                // ── clock-in click handler ──────────────────────────────────
                onClockInClick() {
                    if (!this.canClockIn()) return;

                    if (this.isLate()) {
                        // Open the late-reason modal
                        this.lateReason = '';
                        this.lateReasonError = '';
                        this.lateModalSubmitting = false;
                        this.showLateModal = true;
                    } else {
                        // On-time — clock in immediately with no reason
                        this.doClockIn(null);
                    }
                },

                // ── late-modal submit ───────────────────────────────────────
                submitLateClockIn() {
                    if (this.lateReason.trim().length < 5) {
                        this.lateReasonError = 'Please enter at least 5 characters.';
                        return;
                    }
                    this.lateModalSubmitting = true;
                    this.doClockIn(this.lateReason.trim());
                },

                // ── actual POST to clock-in endpoint ────────────────────────
                async doClockIn(lateReason) {
                    const url = '{{ route('attendance.check-in') }}';
                    const body = lateReason ? JSON.stringify({
                        late_reason: lateReason
                    }) : '{}';

                    try {
                        const res = await fetch(url, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': csrfToken,
                                'X-Requested-With': 'XMLHttpRequest',
                            },
                            body,
                        });
                        const data = await res.json();

                        if (data.success) {
                            this.showLateModal = false;
                            if (window.showToast) window.showToast(data.message || 'Clocked in!',
                            'success');
                            if (typeof Livewire !== 'undefined') {
                                Livewire.dispatch('refresh-table');
                                Livewire.dispatch('refresh-control-card');
                            }
                        } else {
                            if (window.showToast) window.showToast(data.message || 'Failed to clock in.',
                                'error');
                            this.lateModalSubmitting = false;
                        }
                    } catch (e) {
                        if (window.showToast) window.showToast('Server error. Please try again.', 'error');
                        this.lateModalSubmitting = false;
                    }
                },

                // ── ticker ──────────────────────────────────────────────────
                startTick() {
                    this.stopTick();
                    this.ticker = setInterval(() => {
                        if (!this.isClockedIn) return;
                        if (this.isOnBreak) {
                            this.breakSecs++;
                        } else {
                            this.workingSecs++;
                        }
                    }, 1000);
                },
                stopTick() {
                    if (this.ticker) {
                        clearInterval(this.ticker);
                        this.ticker = null;
                    }
                },
                init() {
                    if (this.isClockedIn) this.startTick();
                    this.$watch('isClockedIn', v => {
                        v ? this.startTick() : this.stopTick();
                    });
                },
            };
        };

        // ── Generic POST handler (clock-out, future re-use) ─────────────────
        window.handleAttendanceAction = async function(url, btn) {
            const originalHtml = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML =
                '<svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">' +
                '<circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>' +
                '<path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>' +
                '</svg>';

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

        // ── Break toggle ────────────────────────────────────────────────────
        window.handleBreakToggle = async function(btn) {
            const isOnBreak = btn.closest('[x-data]')?.__x?.$data?.isOnBreak ??
                (btn.textContent.trim().includes('End'));
            const breakStartUrl = btn.dataset.breakStartUrl;
            const breakEndUrl = btn.dataset.breakEndUrl;
            const url = isOnBreak ? breakEndUrl : breakStartUrl;

            await window.handleAttendanceAction(url, btn);
        };
    })();
</script>
