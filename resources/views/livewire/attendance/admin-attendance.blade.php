<div wire:key="admin-attendance">

    {{-- ── Header ────────────────────────────────────────────── --}}
    <div
        style="padding:20px 20px 0;display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:12px">
        <div>
            <div class="section-title">Attendance Overview</div>
            <div class="section-sub">Company-wide attendance management and monitoring</div>
        </div>

        {{-- Filters row --}}
        <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap">
            <div class="search-wrap">
                <span class="search-icon">
                    <svg fill="none" height="14" stroke-width="2" stroke="currentColor" viewBox="0 0 24 24"
                        width="14">
                        <circle cx="11" cy="11" r="8" />
                        <line x1="21" x2="16.65" y1="21" y2="16.65" />
                    </svg>
                </span>
                <input class="search-inp" placeholder="Search employee..." style="min-width:200px" type="text"
                    wire:model.live.debounce.400ms="search" />
            </div>

            <div style="display:flex;gap:6px;align-items:center">
                <input class="input" style="height:36px;padding:0 10px;font-size:13px" type="date"
                    wire:model.live="dateFrom" />
                <span style="color:var(--muted);font-size:13px">to</span>
                <input class="input" style="height:36px;padding:0 10px;font-size:13px" type="date"
                    wire:model.live="dateTo" />
            </div>

            <select class="input" style="height:36px;padding:0 10px;font-size:13px;min-width:130px"
                wire:model.live="filterStatus">
                <option value="">All Status</option>
                <option value="present">Present</option>
                <option value="absent">Absent</option>
                <option value="leave">On Leave</option>
                <option value="holiday">Holiday</option>
                <option value="half_day">Half Day</option>
                <option value="week_off">Week Off</option>
            </select>
        </div>
    </div>

    {{-- ── Summary Tiles ───────────────────────────────────────── --}}
    <div style="padding:16px 20px;display:grid;grid-template-columns:repeat(auto-fit,minmax(150px,1fr));gap:14px">

        <div class="card">
            <div style="padding:14px 16px;display:flex;align-items:center;gap:12px">
                <div
                    style="width:38px;height:38px;border-radius:10px;background:rgba(16,185,129,.12);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                    <svg fill="none" height="18" stroke-width="2" stroke="#10b981" viewBox="0 0 24 24"
                        width="18">
                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                </div>
                <div>
                    <div
                        style="font-size:11px;color:var(--muted);text-transform:uppercase;letter-spacing:.06em;font-weight:600">
                        Present</div>
                    <div style="font-size:22px;font-weight:700;color:var(--text);line-height:1.1">{{ $totalPresent }}
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div style="padding:14px 16px;display:flex;align-items:center;gap:12px">
                <div
                    style="width:38px;height:38px;border-radius:10px;background:rgba(239,68,68,.10);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                    <svg fill="none" height="18" stroke-width="2" stroke="#ef4444" viewBox="0 0 24 24"
                        width="18">
                        <path d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"
                            stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </div>
                <div>
                    <div
                        style="font-size:11px;color:var(--muted);text-transform:uppercase;letter-spacing:.06em;font-weight:600">
                        Absent</div>
                    <div style="font-size:22px;font-weight:700;color:var(--text);line-height:1.1">{{ $totalAbsent }}
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div style="padding:14px 16px;display:flex;align-items:center;gap:12px">
                <div
                    style="width:38px;height:38px;border-radius:10px;background:rgba(245,158,11,.10);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                    <svg fill="none" height="18" stroke-width="2" stroke="#f59e0b" viewBox="0 0 24 24"
                        width="18">
                        <path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                            stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </div>
                <div>
                    <div
                        style="font-size:11px;color:var(--muted);text-transform:uppercase;letter-spacing:.06em;font-weight:600">
                        On Leave</div>
                    <div style="font-size:22px;font-weight:700;color:var(--text);line-height:1.1">{{ $totalOnLeave }}
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div style="padding:14px 16px;display:flex;align-items:center;gap:12px">
                <div
                    style="width:38px;height:38px;border-radius:10px;background:rgba(59,130,246,.10);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                    <span class="relative flex h-2.5 w-2.5">
                        <span
                            class="absolute inline-flex h-full w-full animate-ping rounded-full bg-blue-400 opacity-75"></span>
                        <span class="relative inline-flex h-2.5 w-2.5 rounded-full bg-blue-500"></span>
                    </span>
                </div>
                <div>
                    <div
                        style="font-size:11px;color:var(--muted);text-transform:uppercase;letter-spacing:.06em;font-weight:600">
                        Active Now</div>
                    <div style="font-size:22px;font-weight:700;color:#3b82f6;line-height:1.1">{{ $activeNow }}</div>
                </div>
            </div>
        </div>

    </div>

    {{-- ── Master Table ────────────────────────────────────────── --}}
    <div style="padding:0 20px 20px">
        <div class="card" style="overflow-x:auto">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Employee</th>
                        <th>
                            <button class="inline-flex items-center gap-1 font-semibold"
                                style="background:none;border:none;cursor:pointer;color:inherit;padding:0"
                                type="button" wire:click="sortBy('attendance_date')">
                                Date
                                @if ($sortField === 'attendance_date')
                                    <svg fill="none" height="12" stroke-width="2.5" stroke="currentColor"
                                        viewBox="0 0 24 24" width="12">
                                        @if ($sortDirection === 'asc')
                                            <path d="M5 15l7-7 7 7" stroke-linecap="round" stroke-linejoin="round" />
                                        @else
                                            <path d="M19 9l-7 7-7-7" stroke-linecap="round" stroke-linejoin="round" />
                                        @endif
                                    </svg>
                                @endif
                            </button>
                        </th>
                        <th>
                            <button class="inline-flex items-center gap-1 font-semibold"
                                style="background:none;border:none;cursor:pointer;color:inherit;padding:0"
                                type="button" wire:click="sortBy('check_in')">
                                Check In
                                @if ($sortField === 'check_in')
                                    <svg fill="none" height="12" stroke-width="2.5" stroke="currentColor"
                                        viewBox="0 0 24 24" width="12">
                                        @if ($sortDirection === 'asc')
                                            <path d="M5 15l7-7 7 7" stroke-linecap="round" stroke-linejoin="round" />
                                        @else
                                            <path d="M19 9l-7 7-7-7" stroke-linecap="round" stroke-linejoin="round" />
                                        @endif
                                    </svg>
                                @endif
                            </button>
                        </th>
                        <th>Check Out</th>
                        <th>Work Hours</th>
                        <th>Break</th>
                        <th>Live Status</th>
                        <th>
                            <button class="inline-flex items-center gap-1 font-semibold"
                                style="background:none;border:none;cursor:pointer;color:inherit;padding:0"
                                type="button" wire:click="sortBy('status')">
                                Status
                            </button>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($attendances as $record)
                        @php
                            $user = $record->user;
                            $avatarUrl = $user?->avatar
                                ? $user->avatar
                                : 'https://ui-avatars.com/api/?name=' .
                                    urlencode($user?->full_name ?? 'U') .
                                    '&background=3b82f6&color=fff&size=64';

                            $isActive = is_null($record->check_out);
                            $isOnBreak = false;
                            if ($isActive) {
                                $lastLog = $record->logs->sortByDesc('action_time')->first();
                                $isOnBreak = $lastLog && $lastLog->action_type === 'break_start';
                            }

                            $liveLabel = match (true) {
                                $isActive && $isOnBreak => ['text' => 'On Break', 'class' => 'badge-warning'],
                                $isActive => ['text' => 'Working', 'class' => 'badge-success'],
                                !is_null($record->check_out) && $record->attendance_date == today()->toDateString() => [
                                    'text' => 'Shift Completed',
                                    'class' => 'badge-info',
                                ],
                                default => ['text' => 'Offline', 'class' => ''],
                            };

                            $statusConfig = match ($record->status) {
                                'present' => ['class' => 'badge-success', 'label' => 'Present'],
                                'absent' => ['class' => 'badge-danger', 'label' => 'Absent'],
                                'leave' => ['class' => 'badge-warning', 'label' => 'On Leave'],
                                'holiday' => ['class' => 'badge-info', 'label' => 'Holiday'],
                                'half_day' => ['class' => 'badge-warning', 'label' => 'Half Day'],
                                'week_off' => ['class' => 'badge-info', 'label' => 'Week Off'],
                                default => ['class' => '', 'label' => ucfirst($record->status ?? '-')],
                            };

                            $workH = $record->total_work_minutes
                                ? intdiv($record->total_work_minutes, 60) .
                                    'h ' .
                                    $record->total_work_minutes % 60 .
                                    'm'
                                : '-';
                            $breakM = $record->total_break_minutes ? $record->total_break_minutes . 'm' : '-';
                        @endphp
                        <tr>
                            {{-- Employee cell --}}
                            <td>
                                <div style="display:flex;align-items:center;gap:10px">
                                    <img alt="{{ $user?->full_name }}" class="rounded-full object-cover"
                                        src="{{ $avatarUrl }}"
                                        style="width:32px;height:32px;flex-shrink:0;border:2px solid var(--border)" />
                                    <div>
                                        <div class="text-sm font-semibold" style="color:var(--text)">
                                            {{ $user?->full_name ?? '-' }}
                                        </div>
                                        <div class="text-xs" style="color:var(--muted)">
                                            {{ $user?->employee?->designation?->name ?? ($user?->role ?? '-') }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <td>
                                <div class="text-sm font-semibold" style="color:var(--text)">
                                    {{ dateFormat($record->attendance_date) }}
                                </div>
                                <div class="text-xs" style="color:var(--muted)">
                                    {{ \Carbon\Carbon::parse($record->attendance_date)->format('D') }}
                                </div>
                            </td>

                            <td>{{ $record->check_in ? timeFormat($record->check_in) : '-' }}</td>

                            <td>{{ $record->check_out ? timeFormat($record->check_out) : '-' }}</td>

                            <td class="text-sm font-semibold" style="color:var(--text)">{{ $workH }}</td>

                            <td>{{ $breakM }}</td>

                            <td>
                                @if ($isActive)
                                    <span class="badge {{ $liveLabel['class'] }}"
                                        style="display:inline-flex;align-items:center;gap:5px">
                                        <span class="relative flex h-1.5 w-1.5">
                                            <span
                                                class="{{ $isOnBreak ? 'bg-amber-400' : 'bg-emerald-400' }} absolute inline-flex h-full w-full animate-ping rounded-full opacity-75"></span>
                                            <span
                                                class="{{ $isOnBreak ? 'bg-amber-500' : 'bg-emerald-500' }} relative inline-flex h-1.5 w-1.5 rounded-full"></span>
                                        </span>
                                        {{ $liveLabel['text'] }}
                                    </span>
                                @elseif(!is_null($record->check_out) && $record->attendance_date == today()->toDateString())
                                    <span class="badge badge-info">Shift Completed</span>
                                @else
                                    <span style="color:var(--muted);font-size:12px">—</span>
                                @endif
                            </td>

                            <td>
                                <span class="badge {{ $statusConfig['class'] }}">
                                    {{ $statusConfig['label'] }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="p-0" colspan="8">
                                <div class="flex flex-col items-center justify-center px-6 py-14">
                                    <h3 class="text-sm font-semibold" style="color:var(--text)">No attendance records
                                        found</h3>
                                    <p class="mt-1 text-sm" style="color:var(--muted)">
                                        Try adjusting your date range or search filter.
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div
            style="padding:12px 0;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px">
            <div style="display:flex;align-items:center;gap:8px">
                <span style="font-size:13px;color:var(--muted)">Show</span>
                <select class="input" style="width:75px;height:34px;padding:0 8px" wire:model.live="perPage">
                    <option value="10">10</option>
                    <option value="15">15</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
                <span style="font-size:13px;color:var(--muted)">
                    Showing {{ $attendances->firstItem() ?? 0 }} to {{ $attendances->lastItem() ?? 0 }} of
                    {{ $attendances->total() }} records
                </span>
            </div>
            <div>{{ $attendances->links() }}</div>
        </div>
    </div>

</div>
