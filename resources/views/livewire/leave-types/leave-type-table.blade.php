<div>
    <div style="margin-bottom:20px">
        <div
            style="padding:20px 20px 0;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;margin-bottom:16px">
            <div>
                <div class="section-title">Leave Types Management</div>
                <div class="section-sub">Manage organization leave types</div>
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
                    <input class="search-inp" placeholder="Search leave types" style="min-width:250px"
                        type="text" wire:model.live.debounce.500ms="search" />
                </div>

                @can('leave_type.create')
                    <button class="btn-primary inline-flex h-fit items-center" onclick="openLeaveTypeModal('create')"
                        type="button">
                        Create Leave Type
                    </button>
                @endcan
            </div>
        </div>

        <div class="card" style="overflow-x:auto">
            <table class="data-table">
                <thead>
                    <tr>
                        <x-table-sort :sort-direction="$sortDirection" :sort-field="$sortField" field="name" label="Name" />
                        <x-table-sort :sort-direction="$sortDirection" :sort-field="$sortField" field="monthly_limit" label="Monthly Limit" />
                        <x-table-sort :sort-direction="$sortDirection" :sort-field="$sortField" field="description" label="Description" />
                        <x-table-sort :sort-direction="$sortDirection" :sort-field="$sortField" field="created_at" label="Created Date" />
                        <th width="110">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($leaveTypes as $leaveType)
                        <tr>
                            <td>
                                <div class="font-display text-sm font-semibold text-[var(--text)]">
                                    {{ $leaveType->name }}
                                </div>
                            </td>
                            <td>{{ $leaveType->monthly_limit !== null ? (int)$leaveType->monthly_limit : '-' }}</td>
                            <td class="max-w-xs">
                                <div class="truncate text-sm text-[var(--muted)]" title="{{ $leaveType->description }}">
                                    {{ $leaveType->description ?? '-' }}
                                </div>
                            </td>
                            <td>{{ $leaveType->created_at ? dateFormat($leaveType->created_at) : '-' }}</td>
                            <td>
                                <div style="display:flex;align-items:center;gap:6px">
                                    @can('leave_type.edit')
                                        <button class="btn-ghost js-edit-leave-type"
                                            data-action="{{ route('leave-types.update', $leaveType) }}"
                                            data-description="{{ $leaveType->description }}"
                                            data-id="{{ $leaveType->id }}" data-name="{{ $leaveType->name }}"
                                            data-monthly-limit="{{ $leaveType->monthly_limit }}"
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

                                    @can('leave_type.delete')
                                        <button class="btn-ghost js-delete-confirm"
                                            data-description="Are you sure you want to delete {{ $leaveType->name }}?"
                                            data-title="Delete Leave Type"
                                            data-url="{{ route('leave-types.destroy', $leaveType) }}"
                                            style="padding:6px" title="Delete" type="button">
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
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="p-0" colspan="5">
                                <div class="flex flex-col items-center justify-center px-6 py-14">
                                    <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                                        No leave types found
                                    </h3>
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                        There are no leave types matching your criteria.
                                    </p>
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
                <span style="font-size:13px;color:var(--muted)">
                    Showing {{ $leaveTypes->firstItem() ?? 0 }} to {{ $leaveTypes->lastItem() ?? 0 }} of
                    {{ $leaveTypes->total() }} results
                </span>
            </div>
            <div>{{ $leaveTypes->links() }}</div>
        </div>
    </div>
</div>
