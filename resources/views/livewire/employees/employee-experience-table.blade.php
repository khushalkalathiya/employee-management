<div>
    <div style="margin-bottom:20px">
        <div
            style="padding:0px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;margin-bottom:16px">

            <div>
                <div class="section-title">Experience</div>
                <div class="section-sub">Manage employee past employment and work experience</div>
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
                    <input class="search-inp" placeholder="Search experience" style="min-width:250px" type="text"
                        wire:model.live.debounce.500ms="search" />
                </div>

                <button class="btn-primary" onclick="openExperienceModal('create')" type="button">
                    Add Experience
                </button>

            </div>

        </div>

        <div class="card" style="overflow-x:auto">

            <table class="data-table">

                <thead>
                    <tr>
                        <x-table-sort :sort-direction="$sortDirection" :sort-field="$sortField" field="company_name" label="Company Name" />

                        <x-table-sort :sort-direction="$sortDirection" :sort-field="$sortField" field="designation" label="Designation" />

                        <x-table-sort :sort-direction="$sortDirection" :sort-field="$sortField" field="location" label="Location" />

                        <x-table-sort :sort-direction="$sortDirection" :sort-field="$sortField" field="start_date" label="Start Date" />

                        <x-table-sort :sort-direction="$sortDirection" :sort-field="$sortField" field="end_date" label="End Date" />

                        <x-table-sort :sort-direction="$sortDirection" :sort-field="$sortField" field="description" label="Description" />

                        <th width="120">Actions</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($experiences as $exp)
                        <tr>

                            <td class="font-semibold text-[var(--text)]">{{ $exp->company_name }}</td>

                            <td>{{ $exp->designation }}</td>

                            <td>{{ $exp->location ?? '-' }}</td>

                            <td>{{ dateFormat($exp->start_date) }}</td>

                            <td>{{ $exp->end_date ? dateFormat($exp->end_date) : 'Present' }}</td>

                            <td class="max-w-[200px] truncate text-xs" title="{{ $exp->description }}">{{ $exp->description ?? '-' }}</td>

                            <td>
                                <div style="display:flex;align-items:center;gap:6px">

                                    <button class="btn-ghost"
                                        data-id="{{ $exp->id }}"
                                        data-company-name="{{ $exp->company_name }}"
                                        data-designation="{{ $exp->designation }}"
                                        data-location="{{ $exp->location }}"
                                        data-start-date="{{ $exp->start_date }}"
                                        data-end-date="{{ $exp->end_date }}"
                                        data-description="{{ $exp->description }}"
                                        data-action="{{ route('employees.experience.update', [$employee_id, $exp->id]) }}"
                                        onclick="openExperienceModal('edit', this.dataset)" style="padding:6px"
                                        title="Edit" type="button">

                                        <svg fill="none" height="16" stroke-width="2" stroke="currentColor"
                                            viewBox="0 0 24 24" width="16">
                                            <path d="M11 5H6a2 2 0 0 0-2 2v11a2 2 0 0 0 2 2h11a2 2 0 0 0 2-2v-5"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>

                                    </button>

                                    <button class="btn-ghost js-delete-confirm"
                                        data-description="Are you sure you want to delete this experience record?"
                                        data-title="Delete Experience Record"
                                        data-url="{{ route('employees.experience.destroy', [$employee_id, $exp->id]) }}"
                                        style="padding:6px" title="Delete" type="button">

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

                                </div>
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td class="p-0" colspan="7">

                                <div class="flex flex-col items-center justify-center px-6 py-14">

                                    <div
                                        class="mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-gray-100 text-gray-400 dark:bg-gray-800 dark:text-gray-500">

                                        <svg class="h-7 w-7" fill="none" stroke-width="1.8" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path
                                                d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>

                                    </div>

                                    <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                                        No experience records found
                                    </h3>

                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                        No experience details have been recorded for this employee.
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
                    Showing
                    {{ $experiences->firstItem() ?? 0 }}
                    to
                    {{ $experiences->lastItem() ?? 0 }}
                    of
                    {{ $experiences->total() }}
                    results
                </span>

            </div>

            <div>
                {{ $experiences->links() }}
            </div>

        </div>
    </div>

</div>
