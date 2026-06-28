<x-app-layout>
    <div style="padding: 24px; max-width: 1200px; margin: 0 auto;">
        
        {{-- Header / Breadcrumbs --}}
        <div style="margin-bottom: 24px; display: flex; justify-content: space-between; align-items: center;">
            <div>
                <div style="display: flex; align-items: center; gap: 8px; font-size: 13px; color: var(--muted); margin-bottom: 4px;">
                    <a href="{{ route('attendance.index') }}" style="color: inherit; text-decoration: none; hover: color: var(--text)">Attendance</a>
                    <span>/</span>
                    <span>Edit Record</span>
                </div>
                <h1 class="section-title">Edit Attendance Record</h1>
                <p class="section-sub">Update check-in times, status, and manual details for employee <strong>{{ $attendance->user?->full_name }}</strong></p>
            </div>
            
            <a href="{{ route('attendance.index') }}" class="w-full cursor-pointer rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium transition hover:bg-gray-100 dark:border-gray-700 dark:hover:bg-gray-800" style="text-decoration: none; color: var(--text); display: inline-flex; align-items: center; gap: 6px; max-width: fit-content;">
                <svg fill="none" height="14" stroke-width="2.5" stroke="currentColor" viewBox="0 0 24 24" width="14">
                    <path d="M10 19l-7-7m0 0l7-7m-7 7h18" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                Back to List
            </a>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 24px;">
            
            {{-- Edit Form --}}
            <div class="card" style="padding: 24px;">
                <h3 style="font-size: 15px; font-weight: 700; color: var(--text); border-bottom: 1px solid var(--border); padding-bottom: 12px; margin-bottom: 20px;">
                    Attendance Details
                </h3>

                <form action="{{ route('attendance.update', $attendance) }}" method="POST" style="display: flex; flex-direction: column; gap: 16px;">
                    @csrf
                    @method('PUT')

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                        <div>
                            <label style="display: block; font-size: 12px; font-weight: 600; color: var(--muted); margin-bottom: 6px; text-transform: uppercase;">
                                Date
                            </label>
                            <input class="input" style="height: 38px; width: 100%; opacity: 0.7; cursor: not-allowed;" type="text" value="{{ dateFormat($attendance->attendance_date) }}" disabled />
                        </div>
                        <div>
                            <label style="display: block; font-size: 12px; font-weight: 600; color: var(--muted); margin-bottom: 6px; text-transform: uppercase;">
                                Status
                            </label>
                            <select name="status" class="input" style="height: 38px; width: 100%;" required>
                                <option value="present" @selected($attendance->status === 'present')>Present</option>
                                <option value="absent" @selected($attendance->status === 'absent')>Absent</option>
                                <option value="leave" @selected($attendance->status === 'leave')>On Leave</option>
                                <option value="holiday" @selected($attendance->status === 'holiday')>Holiday</option>
                                <option value="half_day" @selected($attendance->status === 'half_day')>Half Day</option>
                                <option value="week_off" @selected($attendance->status === 'week_off')>Week Off</option>
                            </select>
                            @error('status')
                                <span style="font-size: 11px; color: #ef4444; margin-top: 4px; display: block;">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                        <div>
                            <label style="display: block; font-size: 12px; font-weight: 600; color: var(--muted); margin-bottom: 6px; text-transform: uppercase;">
                                Check In Time
                            </label>
                            <input name="check_in" class="input" style="height: 38px; width: 100%;" type="text" 
                                placeholder="YYYY-MM-DD HH:MM:SS" 
                                value="{{ $attendance->check_in ? \Carbon\Carbon::parse($attendance->check_in)->format('Y-m-d H:i:s') : '' }}" />
                            @error('check_in')
                                <span style="font-size: 11px; color: #ef4444; margin-top: 4px; display: block;">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <label style="display: block; font-size: 12px; font-weight: 600; color: var(--muted); margin-bottom: 6px; text-transform: uppercase;">
                                Check Out Time
                            </label>
                            <input name="check_out" class="input" style="height: 38px; width: 100%;" type="text" 
                                placeholder="YYYY-MM-DD HH:MM:SS" 
                                value="{{ $attendance->check_out ? \Carbon\Carbon::parse($attendance->check_out)->format('Y-m-d H:i:s') : '' }}" />
                            @error('check_out')
                                <span style="font-size: 11px; color: #ef4444; margin-top: 4px; display: block;">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 12px;">
                        <div>
                            <label style="display: block; font-size: 12px; font-weight: 600; color: var(--muted); margin-bottom: 6px; text-transform: uppercase;">
                                Work Minutes
                            </label>
                            <input name="total_work_minutes" class="input" style="height: 38px; width: 100%;" type="number" min="0" value="{{ $attendance->total_work_minutes }}" />
                            @error('total_work_minutes')
                                <span style="font-size: 11px; color: #ef4444; margin-top: 4px; display: block;">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <label style="display: block; font-size: 12px; font-weight: 600; color: var(--muted); margin-bottom: 6px; text-transform: uppercase;">
                                Break Minutes
                            </label>
                            <input name="total_break_minutes" class="input" style="height: 38px; width: 100%;" type="number" min="0" value="{{ $attendance->total_break_minutes }}" />
                            @error('total_break_minutes')
                                <span style="font-size: 11px; color: #ef4444; margin-top: 4px; display: block;">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <label style="display: block; font-size: 12px; font-weight: 600; color: var(--muted); margin-bottom: 6px; text-transform: uppercase;">
                                Overtime Minutes
                            </label>
                            <input name="overtime_minutes" class="input" style="height: 38px; width: 100%;" type="number" min="0" value="{{ $attendance->overtime_minutes }}" />
                            @error('overtime_minutes')
                                <span style="font-size: 11px; color: #ef4444; margin-top: 4px; display: block;">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 600; color: var(--muted); margin-bottom: 6px; text-transform: uppercase;">
                            Notes / Remarks
                        </label>
                        <textarea name="notes" class="input" style="width: 100%; min-height: 80px; padding: 10px;" placeholder="Add details or reason for modification...">{{ $attendance->notes }}</textarea>
                        @error('notes')
                            <span style="font-size: 11px; color: #ef4444; margin-top: 4px; display: block;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div style="margin-top: 8px; display: flex; gap: 12px; justify-content: flex-end;">
                        <a href="{{ route('attendance.index') }}" class="w-full cursor-pointer rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium transition hover:bg-gray-100 dark:border-gray-700 dark:hover:bg-gray-800" style="text-decoration: none; color: var(--text); text-align: center; max-width: 100px;">
                            Cancel
                        </a>
                        <button type="submit" class="w-full cursor-pointer rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-blue-700" style="max-width: 150px;">
                            Save Changes
                        </button>
                    </div>

                </form>
            </div>

            {{-- Audit Logs & Timelines --}}
            <div style="display: flex; flex-direction: column; gap: 24px;">
                
                {{-- Raw activity logs --}}
                <div class="card" style="padding: 24px;">
                    <h3 style="font-size: 15px; font-weight: 700; color: var(--text); border-bottom: 1px solid var(--border); padding-bottom: 12px; margin-bottom: 16px;">
                        Today's Activity Logs
                    </h3>

                    @if($attendance->logs->isEmpty())
                        <div style="color: var(--muted); font-size: 13px; text-align: center; padding: 24px 0;">
                            No raw logs recorded for this day.
                        </div>
                    @else
                        <div style="position: relative; padding-left: 20px; border-left: 2px solid var(--border); margin-left: 8px; display: flex; flex-direction: column; gap: 16px;">
                            @foreach($attendance->logs->sortBy('action_time') as $log)
                                @php
                                    $meta = match ($log->action_type) {
                                        'clock_in'   => ['label' => 'Clock In', 'color' => '#10b981'],
                                        'clock_out'  => ['label' => 'Clock Out', 'color' => '#ef4444'],
                                        'break_start'=> ['label' => 'Break Started', 'color' => '#f59e0b'],
                                        'break_end'  => ['label' => 'Break Ended', 'color' => '#3b82f6'],
                                        default      => ['label' => ucfirst($log->action_type), 'color' => 'var(--text)'],
                                    };
                                @endphp
                                <div style="position: relative">
                                    <div style="position: absolute; left: -29px; top: 3px; width: 14px; height: 14px; border-radius: 50%; background: {{ $meta['color'] }}; border: 3px solid var(--border)"></div>
                                    <div style="display: flex; justify-content: space-between; align-items: center;">
                                        <span style="font-size: 13px; font-weight: 600;">{{ $meta['label'] }}</span>
                                        <span style="font-size: 12px; color: var(--muted); font-weight: 500;">
                                            {{ \Carbon\Carbon::parse($log->action_time)->format('h:i:s A') }}
                                        </span>
                                    </div>
                                    @if($log->notes)
                                        <p style="font-size: 11px; color: var(--muted); margin-top: 2px;">{{ $log->notes }}</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- Revision history --}}
                <div class="card" style="padding: 24px;">
                    <h3 style="font-size: 15px; font-weight: 700; color: var(--text); border-bottom: 1px solid var(--border); padding-bottom: 12px; margin-bottom: 16px;">
                        Revision History (Audit Trail)
                    </h3>

                    @php
                        $revisions = [];
                        $curr = $attendance->old_data;
                        while ($curr) {
                            $revisions[] = $curr;
                            $curr = $curr['previous'] ?? null;
                        }
                    @endphp

                    @if(empty($revisions))
                        <div style="color: var(--muted); font-size: 13px; text-align: center; padding: 24px 0;">
                            No edit history for this record.
                        </div>
                    @else
                        <div style="display: flex; flex-direction: column; gap: 16px;">
                            @foreach($revisions as $rev)
                                <div style="padding: 12px; border-radius: 8px; background: rgba(0,0,0,0.01); border: 1px solid var(--border);">
                                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px; border-bottom: 1px dashed var(--border); padding-bottom: 6px;">
                                        <span style="font-size: 11px; font-weight: 700; text-transform: uppercase; color: var(--muted)">
                                            Revision
                                        </span>
                                        <span style="font-size: 11px; color: var(--muted); font-weight: 500;">
                                            {{ \Carbon\Carbon::parse($rev['updated_at'])->format('M j, Y h:i A') }}
                                        </span>
                                    </div>
                                    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 6px; font-size: 12px;">
                                        <div>
                                            <span style="color: var(--muted)">Status:</span> 
                                            <span class="badge" style="font-size: 10px; padding: 2px 6px;">{{ $rev['status'] }}</span>
                                        </div>
                                        <div>
                                            <span style="color: var(--muted)">Work Time:</span> 
                                            <strong>{{ $rev['total_work_minutes'] ? intdiv($rev['total_work_minutes'], 60) . 'h ' . ($rev['total_work_minutes'] % 60) . 'm' : '-' }}</strong>
                                        </div>
                                        <div>
                                            <span style="color: var(--muted)">Check In:</span> 
                                            <strong>{{ $rev['check_in'] ? \Carbon\Carbon::parse($rev['check_in'])->format('h:i A') : '-' }}</strong>
                                        </div>
                                        <div>
                                            <span style="color: var(--muted)">Check Out:</span> 
                                            <strong>{{ $rev['check_out'] ? \Carbon\Carbon::parse($rev['check_out'])->format('h:i A') : '-' }}</strong>
                                        </div>
                                    </div>
                                    @if(!empty($rev['notes']))
                                        <div style="font-size: 11px; color: var(--muted); margin-top: 6px; padding-top: 4px; border-top: 1px dashed var(--border)">
                                            <em>Notes:</em> {{ $rev['notes'] }}
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

            </div>

        </div>

    </div>
</x-app-layout>
