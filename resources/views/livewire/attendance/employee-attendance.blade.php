<div wire:key="employee-attendance">

    {{-- ── Header ────────────────────────────────────────────── --}}
    <div
        style="padding:20px 20px 0;display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:12px">
        <div>
            <div class="section-title">My Attendance</div>
            <div class="section-sub">Track your work hours, breaks, and overtime</div>
        </div>

        {{-- Date range + status filter --}}
        <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap">
            <div style="display:flex;gap:6px;align-items:center">
                <input class="input" max="{{ now()->format('Y-m-d') }}" style="height:36px;padding:0 10px;font-size:13px"
                    type="date" wire:model.live="dateFrom" />
                <span style="color:var(--muted);font-size:13px">to</span>
                <input class="input" max="{{ now()->format('Y-m-d') }}"
                    style="height:36px;padding:0 10px;font-size:13px" type="date" wire:model.live="dateTo" />
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

    {{-- ── Analytics Cards ────────────────────────────────────── --}}
    <div style="padding:16px 20px;display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:14px">

        {{-- Present Days --}}
        <div class="card">
            <div style="padding:16px 18px">
                <div style="display:flex;justify-content:space-between;align-items:flex-start">
                    <div>
                        <div
                            style="font-size:12px;color:var(--muted);font-weight:600;text-transform:uppercase;letter-spacing:.06em">
                            Present</div>
                        <div style="font-size:28px;font-weight:700;color:var(--text);margin-top:6px;line-height:1">
                            {{ $presentDays }}</div>
                        <div style="font-size:11px;color:#10b981;margin-top:5px">days this period</div>
                    </div>
                    <div
                        style="width:36px;height:36px;border-radius:10px;background:rgba(16,185,129,.12);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                        <svg fill="none" height="18" stroke-width="2" stroke="#10b981" viewBox="0 0 24 24"
                            width="18">
                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- Absent Days --}}
        <div class="card">
            <div style="padding:16px 18px">
                <div style="display:flex;justify-content:space-between;align-items:flex-start">
                    <div>
                        <div
                            style="font-size:12px;color:var(--muted);font-weight:600;text-transform:uppercase;letter-spacing:.06em">
                            Absent</div>
                        <div style="font-size:28px;font-weight:700;color:var(--text);margin-top:6px;line-height:1">
                            {{ $absentDays }}</div>
                        <div style="font-size:11px;color:#ef4444;margin-top:5px">days missed</div>
                    </div>
                    <div
                        style="width:36px;height:36px;border-radius:10px;background:rgba(239,68,68,.10);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                        <svg fill="none" height="18" stroke-width="2" stroke="#ef4444" viewBox="0 0 24 24"
                            width="18">
                            <path d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"
                                stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- Leave Days --}}
        <div class="card">
            <div style="padding:16px 18px">
                <div style="display:flex;justify-content:space-between;align-items:flex-start">
                    <div>
                        <div
                            style="font-size:12px;color:var(--muted);font-weight:600;text-transform:uppercase;letter-spacing:.06em">
                            On Leave</div>
                        <div style="font-size:28px;font-weight:700;color:var(--text);margin-top:6px;line-height:1">
                            {{ $leaveDays }}</div>
                        <div style="font-size:11px;color:#f59e0b;margin-top:5px">approved days</div>
                    </div>
                    <div
                        style="width:36px;height:36px;border-radius:10px;background:rgba(245,158,11,.10);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                        <svg fill="none" height="18" stroke-width="2" stroke="#f59e0b" viewBox="0 0 24 24"
                            width="18">
                            <path
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                                stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- Work Hours --}}
        <div class="card">
            <div style="padding:16px 18px">
                <div style="display:flex;justify-content:space-between;align-items:flex-start">
                    <div>
                        <div
                            style="font-size:12px;color:var(--muted);font-weight:600;text-transform:uppercase;letter-spacing:.06em">
                            Work Hours</div>
                        <div style="font-size:22px;font-weight:700;color:var(--text);margin-top:6px;line-height:1">
                            {{ $totalWorkHours }}</div>
                        <div style="font-size:11px;color:var(--muted);margin-top:5px">of {{ $requiredHours }} required
                        </div>
                    </div>
                    <div
                        style="width:36px;height:36px;border-radius:10px;background:rgba(59,130,246,.10);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                        <svg fill="none" height="18" stroke-width="2" stroke="#3b82f6" viewBox="0 0 24 24"
                            width="18">
                            <circle cx="12" cy="12" r="10" />
                            <polyline points="12 6 12 12 16 14" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- Overtime --}}
        <div class="card">
            <div style="padding:16px 18px">
                <div style="display:flex;justify-content:space-between;align-items:flex-start">
                    <div>
                        <div
                            style="font-size:12px;color:var(--muted);font-weight:600;text-transform:uppercase;letter-spacing:.06em">
                            Overtime</div>
                        <div style="font-size:22px;font-weight:700;color:var(--text);margin-top:6px;line-height:1">
                            {{ $overtimeHours }}</div>
                        <div style="font-size:11px;color:#2563eb;margin-top:5px">extra worked</div>
                    </div>
                    <div
                        style="width:36px;height:36px;border-radius:10px;background:rgba(37,99,235,.10);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                        <svg fill="none" height="18" stroke-width="2" stroke="#2563eb" viewBox="0 0 24 24"
                            width="18">
                            <path d="M13 10V3L4 14h7v7l9-11h-7z" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- Pending Hours --}}
        <div class="card">
            <div style="padding:16px 18px">
                <div style="display:flex;justify-content:space-between;align-items:flex-start">
                    <div>
                        <div
                            style="font-size:12px;color:var(--muted);font-weight:600;text-transform:uppercase;letter-spacing:.06em">
                            Pending</div>
                        <div style="font-size:22px;font-weight:700;color:var(--text);margin-top:6px;line-height:1">
                            {{ $pendingHours }}</div>
                        <div style="font-size:11px;color:#a78bfa;margin-top:5px">hours to complete</div>
                    </div>
                    <div
                        style="width:36px;height:36px;border-radius:10px;background:rgba(167,139,250,.10);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                        <svg fill="none" height="18" stroke-width="2" stroke="#a78bfa" viewBox="0 0 24 24"
                            width="18">
                            <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- ── Attendance Logs Table ───────────────────────────────── --}}
    <div style="padding:0 20px 20px">
        <div class="card" style="overflow-x:auto">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Check In</th>
                        <th>Check Out</th>
                        <th>Work Hours</th>
                        <th>Break</th>
                        <th>Overtime</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($attendances as $record)
                        @php
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
                            $otH =
                                $record->overtime_minutes > 0
                                    ? intdiv($record->overtime_minutes, 60) .
                                        'h ' .
                                        $record->overtime_minutes % 60 .
                                        'm'
                                    : '-';
                        @endphp
                        <tr>
                            <td>
                                <div class="text-sm font-semibold" style="color:var(--text)">
                                    {{ dateFormat($record->attendance_date) }}
                                </div>
                                <div class="text-xs" style="color:var(--muted)">
                                    {{ \Carbon\Carbon::parse($record->attendance_date)->format('l') }}
                                </div>
                            </td>
                            <td>
                                {{ $record->check_in ? timeFormat($record->check_in) : '-' }}
                            </td>
                            <td>
                                {{ $record->check_out ? timeFormat($record->check_out) : '-' }}
                            </td>
                            <td class="text-sm font-semibold" style="color:var(--text)">
                                {{ $workH }}
                            </td>
                            <td>{{ $breakM }}</td>
                            <td>
                                @if ($record->overtime_minutes > 0)
                                    <span
                                        style="color:#2563eb;font-weight:600;font-size:13px">{{ $otH }}</span>
                                @else
                                    <span style="color:var(--muted)">-</span>
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
                            <td class="p-0" colspan="7">
                                <div class="flex flex-col items-center justify-center px-6 py-14">
                                    <h3 class="text-sm font-semibold" style="color:var(--text)">No attendance records
                                        found</h3>
                                    <p class="mt-1 text-sm" style="color:var(--muted)">
                                        No records match the selected date range or filter.
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
