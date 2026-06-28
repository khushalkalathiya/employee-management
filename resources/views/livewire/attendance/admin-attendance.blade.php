<div wire:key="admin-attendance">

    {{-- ── Header ────────────────────────────────────────────── --}}
    <div style="padding:20px 20px 0;display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:12px">
        <div>
            <div class="section-title">Attendance Overview</div>
            <div class="section-sub">Company-wide attendance management and monitoring</div>
        </div>

        {{-- Filters row --}}
        <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap">
            <div class="search-wrap">
                <span class="search-icon">
                    <svg fill="none" height="14" stroke-width="2" stroke="currentColor" viewBox="0 0 24 24" width="14">
                        <circle cx="11" cy="11" r="8" />
                        <line x1="21" x2="16.65" y1="21" y2="16.65" />
                    </svg>
                </span>
                <input class="search-inp" placeholder="Search employee..." style="min-width:200px" type="text" wire:model.live.debounce.400ms="search" />
            </div>

            <div wire:ignore style="position:relative">
                <input id="admin-attendance-range" class="input" style="height:36px;padding:0 10px 0 32px;font-size:13px;min-width:220px;cursor:pointer;" placeholder="Select Date Range" type="text" readonly />
                <span style="position:absolute;left:10px;top:50%;transform:translateY(-50%);color:var(--muted);pointer-events:none;display:flex;align-items:center">
                    <svg fill="none" height="14" stroke-width="2" stroke="currentColor" viewBox="0 0 24 24" width="14">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                        <line x1="16" y1="2" x2="16" y2="6"></line>
                        <line x1="8" y1="2" x2="8" y2="6"></line>
                        <line x1="3" y1="10" x2="21" y2="10"></line>
                    </svg>
                </span>
            </div>

            <select class="input" style="height:36px;padding:0 10px;font-size:13px;min-width:130px" wire:model.live="filterStatus">
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
                <div style="width:38px;height:38px;border-radius:10px;background:rgba(16,185,129,.12);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                    <svg fill="none" height="18" stroke-width="2" stroke="#10b981" viewBox="0 0 24 24" width="18">
                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </div>
                <div>
                    <div style="font-size:11px;color:var(--muted);text-transform:uppercase;letter-spacing:.06em;font-weight:600">Present</div>
                    <div style="font-size:22px;font-weight:700;color:var(--text);line-height:1.1">{{ $totalPresent }}</div>
                </div>
            </div>
        </div>

        <div class="card">
            <div style="padding:14px 16px;display:flex;align-items:center;gap:12px">
                <div style="width:38px;height:38px;border-radius:10px;background:rgba(239,68,68,.10);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                    <svg fill="none" height="18" stroke-width="2" stroke="#ef4444" viewBox="0 0 24 24" width="18">
                        <path d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </div>
                <div>
                    <div style="font-size:11px;color:var(--muted);text-transform:uppercase;letter-spacing:.06em;font-weight:600">Absent</div>
                    <div style="font-size:22px;font-weight:700;color:var(--text);line-height:1.1">{{ $totalAbsent }}</div>
                </div>
            </div>
        </div>

        <div class="card">
            <div style="padding:14px 16px;display:flex;align-items:center;gap:12px">
                <div style="width:38px;height:38px;border-radius:10px;background:rgba(245,158,11,.10);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                    <svg fill="none" height="18" stroke-width="2" stroke="#f59e0b" viewBox="0 0 24 24" width="18">
                        <path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </div>
                <div>
                    <div style="font-size:11px;color:var(--muted);text-transform:uppercase;letter-spacing:.06em;font-weight:600">On Leave</div>
                    <div style="font-size:22px;font-weight:700;color:var(--text);line-height:1.1">{{ $totalOnLeave }}</div>
                </div>
            </div>
        </div>

        <div class="card">
            <div style="padding:14px 16px;display:flex;align-items:center;gap:12px">
                <div style="width:38px;height:38px;border-radius:10px;background:rgba(59,130,246,.10);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                    <span class="relative flex h-2.5 w-2.5">
                        <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-blue-400 opacity-75"></span>
                        <span class="relative inline-flex h-2.5 w-2.5 rounded-full bg-blue-500"></span>
                    </span>
                </div>
                <div>
                    <div style="font-size:11px;color:var(--muted);text-transform:uppercase;letter-spacing:.06em;font-weight:600">Active Now</div>
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
                            <button class="inline-flex items-center gap-1 font-semibold" style="background:none;border:none;cursor:pointer;color:inherit;padding:0" type="button" wire:click="sortBy('attendance_date')">
                                Date
                                @if ($sortField === 'attendance_date')
                                    <svg fill="none" height="12" stroke-width="2.5" stroke="currentColor" viewBox="0 0 24 24" width="12">
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
                            <button class="inline-flex items-center gap-1 font-semibold" style="background:none;border:none;cursor:pointer;color:inherit;padding:0" type="button" wire:click="sortBy('check_in')">
                                Check In
                                @if ($sortField === 'check_in')
                                    <svg fill="none" height="12" stroke-width="2.5" stroke="currentColor" viewBox="0 0 24 24" width="12">
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
                            <button class="inline-flex items-center gap-1 font-semibold" style="background:none;border:none;cursor:pointer;color:inherit;padding:0" type="button" wire:click="sortBy('status')">
                                Status
                            </button>
                        </th>
                        <th style="text-align:right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($attendances as $record)
                        @php
                            $user = $record->user;
                            $avatarUrl = $user?->avatar
                                ? $user->avatar
                                : 'https://ui-avatars.com/api/?name=' . urlencode($user?->full_name ?? 'U') . '&background=3b82f6&color=fff&size=64';

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
                                'present'  => ['class' => 'badge-success', 'label' => 'Present'],
                                'absent'   => ['class' => 'badge-danger', 'label' => 'Absent'],
                                'leave'    => ['class' => 'badge-warning', 'label' => 'On Leave'],
                                'holiday'  => ['class' => 'badge-info', 'label' => 'Holiday'],
                                'half_day' => ['class' => 'badge-warning', 'label' => 'Half Day'],
                                'week_off' => ['class' => 'badge-info', 'label' => 'Week Off'],
                                default    => ['class' => '', 'label' => ucfirst($record->status ?? '-')],
                            };

                            $workH = $record->total_work_minutes
                                ? intdiv($record->total_work_minutes, 60) . 'h ' . ($record->total_work_minutes % 60) . 'm'
                                : '-';
                            $breakM = $record->total_break_minutes ? $record->total_break_minutes . 'm' : '-';
                        @endphp
                        <tr>
                            {{-- Employee cell --}}
                            <td>
                                <div style="display:flex;align-items:center;gap:10px">
                                    <img alt="{{ $user?->full_name }}" class="rounded-full object-cover" src="{{ $avatarUrl }}" style="width:32px;height:32px;flex-shrink:0;border:2px solid var(--border)" />
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
                                    <span class="badge {{ $liveLabel['class'] }}" style="display:inline-flex;align-items:center;gap:5px">
                                        <span class="relative flex h-1.5 w-1.5">
                                            <span class="{{ $isOnBreak ? 'bg-amber-400' : 'bg-emerald-400' }} absolute inline-flex h-full w-full animate-ping rounded-full opacity-75"></span>
                                            <span class="{{ $isOnBreak ? 'bg-amber-500' : 'bg-emerald-500' }} relative inline-flex h-1.5 w-1.5 rounded-full"></span>
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

                            <td style="text-align:right">
                                <div style="display:inline-flex;gap:6px;justify-content:flex-end">
                                    {{-- View --}}
                                    <button class="icon-btn" style="width:28px;height:28px" title="View details" wire:click="viewAttendance({{ $record->id }})">
                                        <svg fill="none" height="14" stroke-width="2" stroke="currentColor" viewBox="0 0 24 24" width="14">
                                            <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" stroke-linecap="round" stroke-linejoin="round"/>
                                            <path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </button>

                                    {{-- Edit --}}
                                    @can('attendance.edit')
                                        <a href="{{ route('attendance.edit', $record) }}" class="icon-btn" style="width:28px;height:28px;display:flex;align-items:center;justify-content:center" title="Edit record">
                                            <svg fill="none" height="13" stroke-width="2.5" stroke="currentColor" viewBox="0 0 24 24" width="13">
                                                <path d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                        </a>
                                    @endcan

                                    {{-- Delete --}}
                                    @can('attendance.delete')
                                        <button class="icon-btn danger" style="width:28px;height:28px" title="Delete record" wire:click="confirmDelete({{ $record->id }})">
                                            <svg fill="none" height="13" stroke-width="2.5" stroke="currentColor" viewBox="0 0 24 24" width="13">
                                                <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                        </button>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="p-0" colspan="9">
                                <div class="flex flex-col items-center justify-center px-6 py-14">
                                    <h3 class="text-sm font-semibold" style="color:var(--text)">No attendance records found</h3>
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
        <div style="padding:12px 0;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px">
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
                    Showing {{ $attendances->firstItem() ?? 0 }} to {{ $attendances->lastItem() ?? 0 }} of {{ $attendances->total() }} records
                </span>
            </div>
            <div>{{ $attendances->links() }}</div>
        </div>
    </div>

    {{-- ── View Modal ─────────────────────────────────────────── --}}
    @if ($viewingRecord)
        <div class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/60 p-4 backdrop-blur-sm" wire:click.self="closeViewModal">
            <div class="relative w-full max-w-lg overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-2xl dark:border-gray-800 dark:bg-gray-950" style="color:var(--text)">
                {{-- Modal Header --}}
                <div style="padding:16px 20px;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;align-items:center">
                    <div>
                        <h3 class="text-base font-bold">{{ $viewingRecord['employee_name'] ?? 'Employee' }}'s Logs</h3>
                        <p style="font-size:12px;color:var(--muted)">{{ dateFormat($viewingRecord['date']) }}</p>
                    </div>
                    <button class="icon-btn" style="width:28px;height:28px" wire:click="closeViewModal">
                        <svg class="h-4 w-4" fill="none" stroke-width="2.5" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M6 18L18 6M6 6l12 12" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </button>
                </div>

                {{-- Modal Body --}}
                <div style="padding:20px;max-height:450px;overflow-y:auto">
                    {{-- Grid Summary --}}
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:20px">
                        <div style="padding:10px;border-radius:8px;background:rgba(0,0,0,0.02);border:1px solid var(--border)">
                            <div style="font-size:11px;color:var(--muted);font-weight:600;text-transform:uppercase">Status</div>
                            <div style="font-size:14px;font-weight:700;margin-top:2px">
                                @php
                                    $statusBadge = match ($viewingRecord['status']) {
                                        'present'  => 'badge-success',
                                        'absent'   => 'badge-danger',
                                        'leave'    => 'badge-warning',
                                        'holiday'  => 'badge-info',
                                        'half_day' => 'badge-warning',
                                        'week_off' => 'badge-info',
                                        default    => '',
                                    };
                                @endphp
                                <span class="badge {{ $statusBadge }}">
                                    {{ ucfirst(str_replace('_', ' ', $viewingRecord['status'])) }}
                                </span>
                            </div>
                        </div>

                        <div style="padding:10px;border-radius:8px;background:rgba(0,0,0,0.02);border:1px solid var(--border)">
                            <div style="font-size:11px;color:var(--muted);font-weight:600;text-transform:uppercase">Working Time</div>
                            <div style="font-size:14px;font-weight:700;margin-top:2px">
                                {{ $viewingRecord['work_minutes'] ? intdiv($viewingRecord['work_minutes'], 60) . 'h ' . ($viewingRecord['work_minutes'] % 60) . 'm' : '-' }}
                            </div>
                        </div>

                        <div style="padding:10px;border-radius:8px;background:rgba(0,0,0,0.02);border:1px solid var(--border)">
                            <div style="font-size:11px;color:var(--muted);font-weight:600;text-transform:uppercase">Break Time</div>
                            <div style="font-size:14px;font-weight:700;margin-top:2px">
                                {{ $viewingRecord['break_minutes'] ? $viewingRecord['break_minutes'] . ' mins' : '-' }}
                            </div>
                        </div>

                        <div style="padding:10px;border-radius:8px;background:rgba(0,0,0,0.02);border:1px solid var(--border)">
                            <div style="font-size:11px;color:var(--muted);font-weight:600;text-transform:uppercase">Overtime</div>
                            <div style="font-size:14px;font-weight:700;margin-top:2px;color:{{ $viewingRecord['overtime'] > 0 ? '#2563eb' : 'inherit' }}">
                                {{ $viewingRecord['overtime'] > 0 ? intdiv($viewingRecord['overtime'], 60) . 'h ' . ($viewingRecord['overtime'] % 60) . 'm' : '-' }}
                            </div>
                        </div>
                    </div>

                    @if($viewingRecord['notes'])
                        <div style="margin-bottom:20px;padding:10px;border-radius:8px;background:rgba(245,158,11,.05);border:1px dashed rgba(245,158,11,.2)">
                            <div style="font-size:11px;color:#f59e0b;font-weight:600;text-transform:uppercase">Notes</div>
                            <div style="font-size:13px;margin-top:4px;white-space:pre-line">{{ $viewingRecord['notes'] }}</div>
                        </div>
                    @endif

                    {{-- Activity Timeline --}}
                    <div style="margin-top:10px">
                        <h4 style="font-size:13px;font-weight:700;margin-bottom:12px;text-transform:uppercase;letter-spacing:.05em;color:var(--muted)">Activity Timeline</h4>

                        @if(empty($viewingLogs))
                            <div style="color:var(--muted);font-size:13px;text-align:center;padding:16px 0">
                                No raw logs recorded for this day.
                            </div>
                        @else
                            <div style="position:relative;padding-left:24px;border-left:2px solid var(--border);margin-left:8px;display:flex;flex-direction:column;gap:20px">
                                @foreach($viewingLogs as $log)
                                    @php
                                        $meta = match ($log['type']) {
                                            'clock_in'   => ['label' => 'Clock In', 'color' => '#10b981', 'bg' => 'rgba(16,185,129,.12)'],
                                            'clock_out'  => ['label' => 'Clock Out', 'color' => '#ef4444', 'bg' => 'rgba(239,68,68,.12)'],
                                            'break_start'=> ['label' => 'Break Started', 'color' => '#f59e0b', 'bg' => 'rgba(245,158,11,.12)'],
                                            'break_end'  => ['label' => 'Break Ended', 'color' => '#3b82f6', 'bg' => 'rgba(59,130,246,.12)'],
                                            default      => ['label' => ucfirst($log['type']), 'color' => 'var(--text)', 'bg' => 'var(--border)'],
                                        };
                                    @endphp
                                    <div style="position:relative">
                                        {{-- Timeline Node --}}
                                        <div style="position:absolute;left:-33px;top:2px;width:16px;height:16px;border-radius:50%;background:{{ $meta['color'] }};border:4px solid var(--border);display:flex;align-items:center;justify-content:center"></div>
                                        
                                        <div style="display:flex;justify-content:space-between;align-items:center">
                                            <span style="font-size:13px;font-weight:600">{{ $meta['label'] }}</span>
                                            <span style="font-size:12px;color:var(--muted);font-weight:500">
                                                {{ \Carbon\Carbon::parse($log['time'])->format('h:i:s A') }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Modal Footer --}}
                <div style="padding:12px 20px;background:rgba(0,0,0,0.01);border-top:1px solid var(--border);display:flex;justify-content:flex-end">
                    <button class="w-full cursor-pointer rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium transition hover:bg-gray-100 dark:border-gray-700 dark:hover:bg-gray-800" style="max-width:100px" wire:click="closeViewModal">
                        Close
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- ── Delete Confirmation Modal ──────────────────────────── --}}
    @if ($deletingId)
        <div class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/60 p-4 backdrop-blur-sm" wire:click.self="cancelDelete">
            <div class="relative w-full max-w-md overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-2xl dark:border-gray-800 dark:bg-gray-950">
                <div style="padding:24px">
                    <div style="display:flex;justify-content:center;margin-bottom:16px">
                        <div style="width:56px;height:56px;border-radius:50%;background:rgba(239,68,68,.12);display:flex;align-items:center;justify-content:center;color:#ef4444">
                            <svg class="h-6 w-6" fill="none" stroke-width="2" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                    </div>

                    <h3 class="text-center text-lg font-bold" style="color:var(--text)">Delete Attendance Record</h3>
                    <p class="mt-2 text-center text-sm" style="color:var(--muted)">
                        Are you sure you want to delete this attendance record? This action cannot be undone and will permanently remove this entry.
                    </p>
                </div>

                <div class="grid grid-cols-2 gap-3 bg-gray-50 p-4 dark:bg-gray-950" style="border-top:1px solid var(--border)">
                    <button class="w-full cursor-pointer rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium transition hover:bg-gray-100 dark:border-gray-700 dark:hover:bg-gray-800" wire:click="cancelDelete">
                        Cancel
                    </button>
                    <button class="w-full cursor-pointer rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-red-700" wire:click="deleteAttendance">
                        Delete
                    </button>
                </div>
            </div>
        </div>
    @endif

</div>

@script
<script>
    flatpickr("#admin-attendance-range", {
        mode: "range",
        dateFormat: "Y-m-d",
        defaultDate: ["{{ $dateFrom }}", "{{ $dateTo }}"],
        maxDate: "{{ now()->format('Y-m-d') }}",
        onClose: function(selectedDates, dateStr, instance) {
            if (selectedDates.length === 2) {
                let from = instance.formatDate(selectedDates[0], "Y-m-d");
                let to = instance.formatDate(selectedDates[1], "Y-m-d");
                $wire.set('dateFrom', from);
                $wire.set('dateTo', to);
            }
        }
    });
</script>
@endscript
