<div wire:key="attendance-control-card" x-data="{
    isClockedIn: @js($isClockedIn),
    isOnBreak: @js($isOnBreak),
    workingSecs: @js($workingSeconds),
    breakSecs: @js($breakSeconds),
    ticker: null,
    
    // Auto Break properties
    autoBreakEnabled: @js($autoBreakEnabled),
    breakEnabled: @js($breakEnabled),
    breakInTime: @js($breakInTime),
    breakOutTime: @js($breakOutTime),
    breakNotificationBeforeSeconds: @js($breakNotificationBeforeSeconds),
    hasTodayBreakIn: @js($hasTodayBreakIn),
    hasTodayBreakOut: @js($hasTodayBreakOut),
    
    // Auto Break Modal properties
    autoBreakModalShow: false,
    autoBreakModalTitle: '',
    autoBreakModalSubtitle: '',
    autoBreakCountdown: 0,
    autoBreakActionType: '',
    autoBreakTriggered: false,
    
    // Regular Modal properties
    isShiftCompleted: @js($isShiftCompleted),
    showModal: false,
    earlyReason: '',
    projectTitle: '',
    description: '',
    selectedFiles: [],
    imagePreviews: [],
    submitting: false,

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
            this.checkCountdown();
        }, 1000);
    },
    stopTick() {
        if (this.ticker) { clearInterval(this.ticker);
            this.ticker = null; }
    },
    checkCountdown() {
        if (!this.autoBreakEnabled || !this.breakEnabled || !this.isClockedIn) {
            this.autoBreakModalShow = false;
            return;
        }

        let now = new Date();
        let todayStr = now.getFullYear() + '-' + String(now.getMonth() + 1).padStart(2, '0') + '-' + String(now.getDate()).padStart(2, '0');

        if (!this.breakInTime || !this.breakOutTime) {
            return;
        }

        let breakInDate = new Date(todayStr + 'T' + this.breakInTime);
        let breakOutDate = new Date(todayStr + 'T' + this.breakOutTime);

        if (!this.isOnBreak && !this.hasTodayBreakIn) {
            // Countdown to Break In
            let diffSecs = Math.floor((breakInDate.getTime() - now.getTime()) / 1000);
            if (diffSecs > 0 && diffSecs <= this.breakNotificationBeforeSeconds) {
                this.autoBreakModalShow = true;
                this.autoBreakModalTitle = 'Auto Break Starting';
                this.autoBreakModalSubtitle = 'Your scheduled break is about to begin. Please save your work.';
                this.autoBreakCountdown = diffSecs;
                this.autoBreakActionType = 'in';
            } else if (diffSecs <= 0 && this.autoBreakActionType === 'in' && !this.autoBreakTriggered) {
                this.autoBreakCountdown = 0;
                this.triggerAutoBreak('in');
            } else {
                if (!this.autoBreakTriggered && this.autoBreakActionType === 'in') {
                    this.autoBreakModalShow = false;
                }
            }
        } else if (this.isOnBreak && !this.hasTodayBreakOut) {
            // Countdown to Break Out
            let diffSecs = Math.floor((breakOutDate.getTime() - now.getTime()) / 1000);
            if (diffSecs > 0 && diffSecs <= this.breakNotificationBeforeSeconds) {
                this.autoBreakModalShow = true;
                this.autoBreakModalTitle = 'Auto Break Ending';
                this.autoBreakModalSubtitle = 'Your scheduled break is about to end. Prepare to resume work.';
                this.autoBreakCountdown = diffSecs;
                this.autoBreakActionType = 'out';
            } else if (diffSecs <= 0 && this.autoBreakActionType === 'out' && !this.autoBreakTriggered) {
                this.autoBreakCountdown = 0;
                this.triggerAutoBreak('out');
            } else {
                if (!this.autoBreakTriggered && this.autoBreakActionType === 'out') {
                    this.autoBreakModalShow = false;
                }
            }
        } else {
            if (!this.autoBreakTriggered) {
                this.autoBreakModalShow = false;
            }
        }
    },
    async triggerAutoBreak(type) {
        this.autoBreakTriggered = true;
        const csrfToken = document.querySelector('meta[name=\"csrf-token\"]')?.content;
        const url = type === 'in' ? '{{ route("attendance.auto-break-in") }}' : '{{ route("attendance.auto-break-out") }}';

        try {
            const res = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                }
            });
            const data = await res.json();
            if (data.success) {
                if (window.showToast) window.showToast(data.message || 'Auto break processed successfully.', 'success');
                this.autoBreakModalShow = false;
                this.autoBreakActionType = '';
                if (typeof Livewire !== 'undefined') {
                    Livewire.dispatch('refresh-control-card');
                    Livewire.dispatch('refresh-table');
                }
            } else {
                if (window.showToast) window.showToast(data.message || 'Auto break failed.', 'error');
                this.autoBreakModalShow = false;
            }
        } catch (e) {
            if (window.showToast) window.showToast('Server error during auto break.', 'error');
            this.autoBreakModalShow = false;
        } finally {
            setTimeout(() => {
                this.autoBreakTriggered = false;
            }, 3000);
        }
    },
    openClockOutModal() {
        this.earlyReason = '';
        this.projectTitle = '';
        this.description = '';
        this.selectedFiles = [];
        this.imagePreviews = [];
        this.showModal = true;
        this.$nextTick(() => {
            let ed = document.getElementById('editorDiv');
            if (ed) ed.innerHTML = '';
        });
    },
    formatText(cmd, val = null) {
        document.execCommand(cmd, false, val);
    },
    handleFileChange(e) {
        let files = Array.from(e.target.files);
        this.selectedFiles = files;
        this.imagePreviews = [];
        files.forEach(f => {
            let r = new FileReader();
            r.onload = (ev) => {
                this.imagePreviews.push(ev.target.result);
            };
            r.readAsDataURL(f);
        });
    },
    removeImage(idx) {
        this.selectedFiles.splice(idx, 1);
        this.imagePreviews.splice(idx, 1);
    },
    async submitClockOut() {
        this.submitting = true;
        const csrfToken = document.querySelector('meta[name=\"csrf-token\"]')?.content;
        const formData = new FormData();
        
        if (!this.isShiftCompleted) {
            formData.append('notes', this.earlyReason);
        } else {
            formData.append('project_title', this.projectTitle);
            formData.append('description', this.description);
            this.selectedFiles.forEach(file => {
                formData.append('work_images[]', file);
            });
        }

        try {
            const res = await fetch('{{ route("attendance.check-out") }}', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: formData
            });
            
            const data = await res.json();
            if (data.success) {
                if (window.showToast) window.showToast(data.message || 'Clocked out successfully.', 'success');
                this.showModal = false;
                if (typeof Livewire !== 'undefined') {
                    Livewire.dispatch('refresh-table');
                    Livewire.dispatch('refresh-control-card');
                }
            } else {
                if (window.showToast) window.showToast(data.message || 'Failed', 'error');
            }
        } catch (e) {
            if (window.showToast) window.showToast('Server error. Please try again.', 'error');
        } finally {
            this.submitting = false;
        }
    },
    init() {
        if (this.isClockedIn) this.startTick();
        this.$watch('isClockedIn', v => { if (v) this.startTick();
            else this.stopTick(); });
    }
}"
x-effect="
    isClockedIn = $wire.isClockedIn;
    isOnBreak = $wire.isOnBreak;
    workingSecs = $wire.workingSeconds;
    breakSecs = $wire.breakSeconds;
    autoBreakEnabled = $wire.autoBreakEnabled;
    breakEnabled = $wire.breakEnabled;
    breakInTime = $wire.breakInTime;
    breakOutTime = $wire.breakOutTime;
    breakNotificationBeforeSeconds = $wire.breakNotificationBeforeSeconds;
    hasTodayBreakIn = $wire.hasTodayBreakIn;
    hasTodayBreakOut = $wire.hasTodayBreakOut;
    isShiftCompleted = $wire.isShiftCompleted;
"
x-init="init()">
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

                            {{-- Clock Out (Disabled during active break) --}}
                            <button
                                class="inline-flex items-center gap-2 rounded-lg bg-rose-500 px-4 py-2 text-sm font-semibold text-white shadow-lg shadow-rose-500/20 transition-all hover:bg-rose-600 focus:outline-none active:scale-[.98]"
                                x-bind:disabled="isOnBreak"
                                x-bind:class="{'opacity-50 cursor-not-allowed': isOnBreak}"
                                x-on:click="openClockOutModal()"
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

    <!-- Clock Out Confirmation Modal -->
    <div x-show="showModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm" x-cloak>
        <div class="bg-white dark:bg-gray-900 rounded-3xl max-w-lg w-full p-6 shadow-2xl border border-gray-100 dark:border-gray-800 transition-all transform scale-100" @click.away="showModal = false">
            
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                    <svg class="h-6 w-6 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    Clock Out Confirmation
                </h3>
                <button @click="showModal = false" class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
                    ✕
                </button>
            </div>

            <div class="mb-5 text-sm text-gray-500 dark:text-gray-400">
                Are you sure you want to clock out for today?
            </div>

            <form id="clockOutForm" @submit.prevent="submitClockOut">
                <!-- Early Leaving Reason -->
                <div x-show="!isShiftCompleted">
                    <div class="mb-4">
                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-2">Why are you leaving early? <span class="text-rose-500">*</span></label>
                        <textarea x-model="earlyReason" :required="!isShiftCompleted" class="w-full rounded-xl border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-950 text-sm text-gray-900 dark:text-white p-3 outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500" placeholder="Please provide a reason..."></textarea>
                    </div>
                </div>

                <!-- Shift Completed Fields -->
                <div x-show="isShiftCompleted">
                    <div class="mb-4">
                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-2">Project Title <span class="text-rose-500">*</span></label>
                        <input type="text" x-model="projectTitle" :required="isShiftCompleted" class="w-full rounded-xl border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-950 text-sm text-gray-900 dark:text-white px-3 py-2 outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500" placeholder="e.g. Employee Portal Redesign">
                    </div>

                    <div class="mb-4">
                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-2">Work Description <span class="text-rose-500">*</span></label>
                        
                        <!-- Rich Text Editor (Tailwind compatible basic formatting) -->
                        <div class="border border-gray-200 dark:border-gray-800 rounded-xl overflow-hidden bg-gray-50 dark:bg-gray-950">
                            <div class="flex gap-1 p-1 bg-gray-100 dark:bg-gray-900 border-b border-gray-200 dark:border-gray-800">
                                <button type="button" @click="formatText('bold')" class="p-1 px-2.5 text-xs font-bold rounded hover:bg-gray-200 dark:hover:bg-gray-800 text-gray-700 dark:text-gray-300">B</button>
                                <button type="button" @click="formatText('insertUnorderedList')" class="p-1 px-2.5 text-xs font-bold rounded hover:bg-gray-200 dark:hover:bg-gray-800 text-gray-700 dark:text-gray-300">• List</button>
                                <button type="button" @click="formatText('formatBlock', 'h3')" class="p-1 px-2.5 text-xs font-bold rounded hover:bg-gray-200 dark:hover:bg-gray-800 text-gray-700 dark:text-gray-300">H3</button>
                            </div>
                            <div id="editorDiv" contenteditable="true" @input="description = $el.innerHTML" class="p-3 min-h-[120px] outline-none text-sm text-gray-800 dark:text-gray-200 max-h-[250px] overflow-y-auto" placeholder="Enter work details..."></div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-2">Attach Work Images</label>
                        <input type="file" multiple accept="image/*" @change="handleFileChange($event)" class="block w-full text-xs text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-gray-800 dark:file:text-gray-200">
                        <div class="flex gap-2 flex-wrap mt-2">
                            <template x-for="(imgSrc, idx) in imagePreviews">
                                <div class="relative w-16 h-16 rounded-lg overflow-hidden border border-gray-200 dark:border-gray-800">
                                    <img :src="imgSrc" class="w-full h-full object-cover">
                                    <button type="button" @click="removeImage(idx)" class="absolute top-0.5 right-0.5 bg-black/75 hover:bg-black text-white rounded-full w-4.5 h-4.5 flex items-center justify-center text-[8px]">✕</button>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-2 mt-6">
                    <button type="button" @click="showModal = false" class="btn bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-xl px-4 py-2 text-sm font-semibold">
                        Cancel
                    </button>
                    <button type="submit" :disabled="submitting" class="btn bg-rose-500 hover:bg-rose-600 disabled:opacity-50 text-white rounded-xl px-4 py-2 text-sm font-semibold flex items-center gap-1.5">
                        <svg x-show="submitting" class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                        Confirm Clock Out
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Full-Screen Auto Break Countdown Modal -->
    <div x-show="autoBreakModalShow" class="fixed inset-0 z-[9999] flex flex-col items-center justify-center bg-gray-900/95 backdrop-blur-md transition-all duration-300" x-cloak>
        <div class="text-center max-w-md px-6">
            <div class="relative mb-6 inline-flex">
                <span class="flex h-24 w-24 items-center justify-center rounded-full bg-amber-500/10 text-amber-500 ring-4 ring-amber-500/20 shadow-xl shadow-amber-500/10">
                    <svg class="h-12 w-12 animate-bounce" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10" />
                        <polyline points="12 6 12 12 16 14" />
                    </svg>
                </span>
                <span class="absolute inline-flex h-24 w-24 rounded-full bg-amber-400 opacity-20 animate-ping"></span>
            </div>

            <h2 class="text-3xl font-extrabold text-white tracking-tight mb-2" x-text="autoBreakModalTitle">
                Auto Break Starting
            </h2>
            
            <p class="text-gray-400 text-sm mb-8" x-text="autoBreakModalSubtitle">
                Your scheduled break is about to begin. Please save your work.
            </p>

            <div class="inline-flex items-center justify-center w-36 h-36 rounded-full border-4 border-amber-500 bg-amber-500/5 shadow-2xl mb-8">
                <span class="text-6xl font-black text-white font-variant-numeric:tabular-nums" x-text="autoBreakCountdown"></span>
            </div>

            <div class="px-4 py-3 rounded-2xl bg-white/5 border border-white/10 text-xs text-amber-400/90 font-medium">
                This window is locked and will automatically proceed when the timer hits zero.
            </div>
        </div>
    </div>
</div>

<script>
    (function() {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

        // Generic POST handler used by clock-in
        window.handleAttendanceAction = async function(url, btn, payload = {}) {
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
                    body: JSON.stringify(payload)
                });
                const data = await res.json();
                
                if (data.is_late) {
                    btn.disabled = false;
                    btn.innerHTML = originalHtml;
                    if (window.openLateModal) {
                        window.openLateModal(url, btn);
                    }
                    return;
                }

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
            const isOnBreak = alpineEl?.__x?.$data?.isOnBreak || false;

            const breakStartUrl = btn.dataset.breakStartUrl;
            const breakEndUrl = btn.dataset.breakEndUrl;
            const url = isOnBreak ? breakEndUrl : breakStartUrl;

            await window.handleAttendanceAction(url, btn);
        };
    })();
</script>
