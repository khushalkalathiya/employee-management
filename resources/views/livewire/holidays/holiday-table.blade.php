<div>
    <div style="margin-bottom:20px">
        <div
            style="padding:20px 20px 0;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;margin-bottom:16px">
            <div>
                <div class="section-title">Holidays Management</div>
                <div class="section-sub">Manage organization holidays</div>
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
                    <input class="search-inp" placeholder="Search holidays" style="min-width:250px" type="text"
                        wire:model.live.debounce.500ms="search" />
                </div>

                @can('holiday.create')
                    <button class="btn-primary inline-flex h-fit items-center" onclick="openHolidayModal('create')"
                        type="button">
                        Create Holiday
                    </button>
                @endcan
            </div>
        </div>

        <div class="card" style="overflow-x:auto">
            <table class="data-table">
                <thead>
                    <tr>
                        <x-table-sort :sort-direction="$sortDirection" :sort-field="$sortField" field="name" label="Name" />
                        <th>Type</th>
                        <x-table-sort field="start" label="Start" />
                        <x-table-sort field="end" label="End" />
                        <th width="110">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($holidays as $holiday)
                        <tr>
                            <td class="max-w-xs">
                                <div class="font-display text-sm font-semibold text-[var(--text)]">
                                    {{ $holiday->name }}
                                </div>

                                @if ($holiday->notes)
                                    <div class="mt-1 truncate text-xs text-gray-500 dark:text-gray-400"
                                        title="{{ $holiday->notes }}">
                                        {{ $holiday->notes }}
                                    </div>
                                @endif
                            </td>
                            <td>
                                @if ($holiday->type == 1)
                                    Full Day
                                @elseif($holiday->type == 2)
                                    Partial Day
                                @elseif($holiday->type == 3)
                                    Multiple Days
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                <div class="text-sm">
                                    {{ $holiday->start ? dateFormat($holiday->start) : '-' }}
                                </div>
                                @if ($holiday->type != 1)
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $holiday->start ? timeFormat($holiday->start) : '-' }}
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div class="text-sm">
                                    {{ $holiday->end ? dateFormat($holiday->end) : '-' }}
                                </div>
                                @if ($holiday->type != 1)
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $holiday->end ? timeFormat($holiday->end) : '-' }}
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div style="display:flex;align-items:center;gap:6px">
                                    @can('holiday.edit')
                                        <button class="btn-ghost js-edit-holiday"
                                            data-action="{{ route('holidays.update', $holiday) }}"
                                            data-end="{{ $holiday->end->format('Y-m-d H:i:s') }}"
                                            data-id="{{ $holiday->id }}" data-name="{{ $holiday->name ?? '' }}"
                                            data-notes="{{ $holiday->notes ?? '' }}"
                                            data-start="{{ $holiday->start->format('Y-m-d H:i:s') }}"
                                            data-type="{{ $holiday->type ?? '' }}" style="padding:6px" title="Edit"
                                            type="button">
                                            <svg fill="none" height="16" stroke-width="2" stroke="currentColor"
                                                viewBox="0 0 24 24" width="16" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M11 5H6a2 2 0 0 0-2 2v11a2 2 0 0 0 2 2h11a2 2 0 0 0 2-2v-5"
                                                    stroke-linecap="round" stroke-linejoin="round" />
                                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"
                                                    stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                        </button>
                                    @endcan

                                    @can('holiday.delete')
                                        <button class="btn-ghost js-delete-confirm"
                                            data-description="Are you sure you want to delete {{ $holiday->name }}?"
                                            data-title="Delete Holiday"
                                            data-url="{{ route('holidays.destroy', $holiday) }}" style="padding:6px"
                                            title="Delete" type="button">
                                            <svg fill="none" height="16" stroke-width="2" stroke="currentColor"
                                                viewBox="0 0 24 24" width="16">
                                                <path d="M3 6h18" stroke-linecap="round" stroke-linejoin="round" />
                                                <path d="M8 6V4a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                                <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"
                                                    stroke-linecap="round" stroke-linejoin="round" />
                                                <path d="M10 11v6M14 11v6" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                        </button>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="p-0" colspan="8">
                                <div class="flex flex-col items-center justify-center px-6 py-14">
                                    <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                                        No holidays found
                                    </h3>
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                        There are no holidays matching your criteria.
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
                    Showing {{ $holidays->firstItem() ?? 0 }} to {{ $holidays->lastItem() ?? 0 }} of
                    {{ $holidays->total() }} results
                </span>
            </div>
            <div>{{ $holidays->links() }}</div>
        </div>
    </div>
</div>
