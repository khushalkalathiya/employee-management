<div>
    <div style="margin-bottom:20px">
        <div
            style="padding:20px 20px 0;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;margin-bottom:16px">
            <div>
                <div class="section-title">Departments Management</div>
                <div class="section-sub">Manage organization departments</div>
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
                    <input class="search-inp" placeholder="Search departments" style="min-width:250px" type="text"
                        wire:model.live.debounce.500ms="search" />
                </div>

                @can('department.create')
                    <button class="btn-primary inline-flex h-fit items-center" onclick="openDepartmentModal('create')"
                        type="button">
                        Create Department
                    </button>
                @endcan
            </div>
        </div>

        <div class="card" style="overflow-x:auto">
            <table class="data-table">
                <thead>
                    <tr>
                        <x-table-sort :sort-direction="$sortDirection" :sort-field="$sortField" field="name" label="Name" />
                        <x-table-sort :sort-direction="$sortDirection" :sort-field="$sortField" field="is_active" label="Status" />
                        <th width="110">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($departments as $department)
                        <tr>
                            <td class="max-w-xs px-4 py-3">
                                <div class="font-display text-sm font-semibold text-[var(--text)]">
                                    {{ $department->name }}
                                </div>

                                <div class="mt-1 truncate text-xs text-[var(--muted)]"
                                    title="{{ $department->description }}">
                                    {{ $department->description ?? '' }}
                                </div>
                            </td>

                            <td>
                                <label class="inline-flex cursor-pointer items-center">

                                    <input @checked($department->is_active) class="department-status-toggle peer sr-only"
                                        data-url="{{ route('departments.toggle-status', $department) }}"
                                        type="checkbox">

                                    <div
                                        class="peer relative h-5 w-9 rounded-full bg-gray-300 transition-all after:absolute after:start-[2px] after:top-[2px] after:h-4 after:w-4 after:rounded-full after:bg-white after:transition-all after:content-[''] peer-checked:bg-blue-600 peer-checked:after:translate-x-full dark:bg-gray-700 dark:peer-checked:bg-blue-500">
                                    </div>

                                </label>
                            </td>

                            <td>
                                <div style="display:flex;align-items:center;gap:6px">
                                    @can('department.edit')
                                        <button class="btn-ghost js-edit-department"
                                            data-action="{{ route('departments.update', $department) }}"
                                            data-description="{{ $department->description }}"
                                            data-id="{{ $department->id }}" data-name="{{ $department->name }}"
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

                                    @can('department.delete')
                                        <button class="btn-ghost js-delete-confirm"
                                            data-description="Are you sure you want to delete {{ $department->name }}?"
                                            data-title="Delete Department"
                                            data-url="{{ route('departments.destroy', $department) }}" style="padding:6px"
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
                            <td class="p-0" colspan="6">
                                <div class="flex flex-col items-center justify-center px-6 py-14">
                                    <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                                        No departments found
                                    </h3>
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                        There are no departments matching your criteria.
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
                    Showing {{ $departments->firstItem() ?? 0 }} to {{ $departments->lastItem() ?? 0 }} of
                    {{ $departments->total() }} results
                </span>
            </div>
            <div>{{ $departments->links() }}</div>
        </div>
    </div>
</div>
