<div>
    <div style="margin-bottom:20px">
        <div
            style="padding:0px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;margin-bottom:16px">

            <div>
                <div class="section-title">Education</div>
                <div class="section-sub">Manage academic qualifications and degrees</div>
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
                    <input class="search-inp" placeholder="Search education" style="min-width:250px" type="text"
                        wire:model.live.debounce.500ms="search" />
                </div>

                <button class="btn-primary" onclick="openEducationModal('create')" type="button">
                    Add Education
                </button>

            </div>

        </div>

        <div class="card" style="overflow-x:auto">

            <table class="data-table">

                <thead>
                    <tr>
                        <x-table-sort :sort-direction="$sortDirection" :sort-field="$sortField" field="qualification" label="Qualification" />

                        <x-table-sort :sort-direction="$sortDirection" :sort-field="$sortField" field="institute_name" label="Institute Name" />

                        <x-table-sort :sort-direction="$sortDirection" :sort-field="$sortField" field="board_university" label="Board / University" />

                        <x-table-sort :sort-direction="$sortDirection" :sort-field="$sortField" field="passing_year" label="Passing Year" />

                        <x-table-sort :sort-direction="$sortDirection" :sort-field="$sortField" field="percentage_grade" label="Percentage / Grade" />

                        <th width="120">Actions</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($educationList as $edu)
                        <tr>

                            <td class="font-semibold text-[var(--text)]">{{ $edu->qualification }}</td>

                            <td>{{ $edu->institute_name }}</td>

                            <td>{{ $edu->board_university ?? '-' }}</td>

                            <td>{{ $edu->passing_year ?? '-' }}</td>

                            <td>{{ $edu->percentage_grade ?? '-' }}</td>

                            <td>
                                <div style="display:flex;align-items:center;gap:6px">

                                    <button class="btn-ghost"
                                        data-action="{{ route('employees.education.update', [$employee_id, $edu->id]) }}"
                                        data-board-university="{{ $edu->board_university }}"
                                        data-id="{{ $edu->id }}"
                                        data-institute-name="{{ $edu->institute_name }}"
                                        data-passing-year="{{ $edu->passing_year }}"
                                        data-percentage-grade="{{ $edu->percentage_grade }}"
                                        data-qualification="{{ $edu->qualification }}"
                                        onclick="openEducationModal('edit', this.dataset)" style="padding:6px"
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
                                        data-description="Are you sure you want to delete this education record?"
                                        data-title="Delete Education Record"
                                        data-url="{{ route('employees.education.destroy', [$employee_id, $edu->id]) }}"
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
                            <td class="p-0" colspan="6">

                                <div class="flex flex-col items-center justify-center px-6 py-14">

                                    <div
                                        class="mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-gray-100 text-gray-400 dark:bg-gray-800 dark:text-gray-500">

                                        <svg class="h-7 w-7" fill="none" stroke-width="1.8" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path
                                                d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>

                                    </div>

                                    <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                                        No education records found
                                    </h3>

                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                        No education details have been recorded for this employee.
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
                    {{ $educationList->firstItem() ?? 0 }}
                    to
                    {{ $educationList->lastItem() ?? 0 }}
                    of
                    {{ $educationList->total() }}
                    results
                </span>

            </div>

            <div>
                {{ $educationList->links() }}
            </div>

        </div>
    </div>

</div>
