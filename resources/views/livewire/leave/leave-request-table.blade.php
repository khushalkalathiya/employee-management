<div>
    <div style="margin-bottom:20px">
        <div
            style="padding:20px 20px 0;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;margin-bottom:16px">
            <div>
                <div class="section-title">Leave Management</div>
                <div class="section-sub">Manage employee leave requests</div>
            </div>

            <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap">
                <div class="search-wrap" style="margin-left:8px">
                    <span class="search-icon">
                        <svg fill="none" height="14" stroke-width="2" stroke="currentColor" viewBox="0 0 24 24"
                            width="14">
                            <circle cx="11" cy="11" r="8" />
                            <line x1="21" x2="16.65" y1="21" y2="16.65" />
                        </svg>
                    </span>
                    <input class="search-inp" placeholder="Search Leave Requests" style="min-width:260px" type="text"
                        wire:model.live.debounce.500ms="search" />
                </div>

                @can('leave.create')
                    <button class="btn-primary inline-flex h-fit items-center" onclick="openLeaveModal('create')"
                        type="button">
                        Create Leave
                    </button>
                @endcan
            </div>
        </div>

        <div class="card" style="overflow-x:auto">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Employee</th>
                        <th>Leave Type</th>
                        <th>Mode</th>
                        <th>Date</th>
                        <th class="flex justify-center">Status</th>
                        <th width="160">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($leaveRequests as $leaveRequest)
                        <tr>
                            <td>
                                <div class="emp-cell">

                                    @if (optional($leaveRequest->user)->avatar)
                                        <img alt="{{ optional($leaveRequest->user)->full_name }}"
                                            class="h-10 w-10 rounded-full object-cover ring-2 ring-white dark:ring-gray-800"
                                            src="{{ optional($leaveRequest->user)->avatar }}">
                                    @else
                                        <div
                                            class="emp-avatar flex h-10 w-10 items-center justify-center rounded-full bg-gradient-to-br from-blue-500 to-blue-700 p-5 text-sm font-semibold text-white">

                                            {{ optional($leaveRequest->user)->initials ?? 'NA' }}

                                        </div>
                                    @endif

                                    <div>
                                        <div class="font-display text-sm font-semibold text-[var(--text)]">
                                            {{ optional($leaveRequest->user)->full_name }}
                                        </div>

                                        <div class="text-xs text-[var(--muted)]">
                                            {{ optional($leaveRequest->user)->email }}
                                        </div>
                                    </div>

                                </div>
                            </td>
                            <td>{{ $leaveRequest->leaveType?->name ?? '-' }}</td>
                            <td class="capitalize">{{ str_replace('_', ' ', $leaveRequest->leave_mode ?? '-') }}</td>
                            <td>
                                @if ($leaveRequest->leave_mode != 'multiple_days')
                                    {{ $leaveRequest->start_datetime ? dateFormat($leaveRequest->start_datetime) : '-' }}
                                @else
                                    {{ $leaveRequest->start_datetime ? dateFormat($leaveRequest->start_datetime) : '-' }}
                                    -
                                    {{ $leaveRequest->end_datetime ? dateFormat($leaveRequest->end_datetime) : '-' }}
                                @endif
                            </td>
                            <td class="text-center">
                                @if ($leaveRequest->status == 0)
                                    @if (has_permission('leave.edit') && !has_permission('leave.own'))
                                        <select
                                            class="input js-update-leave-status rounded border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-gray-600"
                                            data-action="{{ route('leaves.status.update', $leaveRequest->id) }}"
                                            data-current-value="{{ $leaveRequest->status }}"
                                            style="width:100%;padding:6px;font-size:13px;max-width:150px;min-width:100px">
                                            <option {{ $leaveRequest->status == 0 ? 'selected' : '' }} value="0">
                                                Pending</option>
                                            <option {{ $leaveRequest->status == 1 ? 'selected' : '' }} value="1">
                                                Approved</option>
                                            <option {{ $leaveRequest->status == 2 ? 'selected' : '' }} value="2">
                                                Rejected</option>
                                        </select>
                                    @else
                                        <span
                                            class="inline-flex rounded bg-amber-500/10 px-2.5 py-1 text-xs font-semibold text-amber-700 dark:bg-amber-400/10 dark:text-amber-300">
                                            Pending
                                        </span>
                                    @endif
                                @else
                                    <span
                                        class="{{ match ($leaveRequest->status ?? 0) {
                                            1 => 'bg-green-500/10 text-green-700 dark:bg-green-400/10 dark:text-green-300',
                                            2 => 'bg-red-500/10 text-red-700 dark:bg-red-400/10 dark:text-red-300',
                                            3 => 'bg-gray-500/10 text-gray-700 dark:bg-gray-400/10 dark:text-gray-300',
                                            default => 'bg-amber-500/10 text-amber-700 dark:bg-amber-400/10 dark:text-amber-300',
                                        } }} inline-flex rounded px-2.5 py-1 text-xs font-semibold">
                                        @if ($leaveRequest->status == 1)
                                            Approved
                                        @elseif ($leaveRequest->status == 2)
                                            Rejected
                                        @elseif ($leaveRequest->status == 3)
                                            Cancelled
                                        @endif
                                    </span>
                                @endif
                            </td>
                            <td>
                                <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap">
                                    <button class="btn-ghost js-view-leave"
                                        data-action="{{ route('leaves.show', $leaveRequest->id) }}" style="padding:6px"
                                        title="View" type="button">
                                        <svg fill="none" height="16" stroke-width="2" stroke="currentColor"
                                            viewBox="0 0 24 24" width="16">
                                            <path d="M1 12C1 12 5 5 12 5s11 7 11 7-4 7-11 7S1 12 1 12z"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                            <circle cx="12" cy="12" r="3" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                        </svg>
                                    </button>
                                    @can('leave.edit')
                                        <button class="btn-ghost js-edit-leave"
                                            data-action="{{ route('leaves.update', $leaveRequest->id) }}"
                                            data-view-action="{{ route('leaves.show', $leaveRequest->id) }}"
                                            style="padding:6px" title="Edit" type="button">
                                            <svg fill="none" height="16" stroke-width="2" stroke="currentColor"
                                                viewBox="0 0 24 24" width="16" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M11 5H6a2 2 0 0 0-2 2v11a2 2 0 0 0 2 2h11a2 2 0 0 0 2-2v-5"
                                                    stroke-linecap="round" stroke-linejoin="round" />
                                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"
                                                    stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                        </button>
                                    @endcan

                                    @can('leave.delete')
                                        <button class="btn-ghost js-delete-confirm"
                                            data-description="Are you sure you want to delete this leave request?"
                                            data-title="Delete Leave Request"
                                            data-url="{{ route('leaves.destroy', $leaveRequest) }}" style="padding:6px"
                                            title="Delete" type="button">
                                            <svg fill="none" height="16" stroke-width="2" stroke="currentColor"
                                                viewBox="0 0 24 24" width="16">
                                                <path d="M3 6h18" stroke-linecap="round" stroke-linejoin="round" />
                                                <path d="M8 6V4a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                                <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"
                                                    stroke-linecap="round" stroke-linejoin="round" />
                                                <path d="M10 11v6M14 11v6" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                            </svg>
                                        </button>
                                    @endcan
                                    <button class="btn-ghost js-cancel-leave" style="padding:6px"
                                        title="Cancel Leave" type="button">
                                        <svg fill="none" height="16" stroke-width="2" stroke="currentColor"
                                            viewBox="0 0 24 24" width="16">
                                            <circle cx="12" cy="12" r="9" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                            <path d="M8 16L16 8" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="p-0" colspan="10">
                                <div class="flex flex-col items-center justify-center px-6 py-14">
                                    <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100">No leave
                                        requests found</h3>
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">There are no leave
                                        requests matching your criteria.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div
            style="padding:12px 20px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px">
            <div style="display:flex;align-items:center;gap:12px;flex-wrap:wrap">
                <div style="display:flex;align-items:center;gap:8px">
                    <span style="font-size:13px;color:var(--muted)">Show</span>
                    <select class="input" style="width:75px;height:34px;padding:0 8px" wire:model.live="perPage">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>
                <span style="font-size:13px;color:var(--muted)">Showing {{ $leaveRequests->firstItem() ?? 0 }} to
                    {{ $leaveRequests->lastItem() ?? 0 }} of {{ $leaveRequests->total() }} results</span>
            </div>
            <div>{{ $leaveRequests->links() }}</div>
        </div>
    </div>
</div>
