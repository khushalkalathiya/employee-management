<div>
    <div style="margin-bottom:20px">
        <div
            style="padding:0px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;margin-bottom:16px">

            <div>
                <div class="section-title">Assets</div>
                <div class="section-sub">Manage company property allocated to employee</div>
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
                    <input class="search-inp" placeholder="Search assets" style="min-width:250px" type="text"
                        wire:model.live.debounce.500ms="search" />
                </div>

                <button class="btn-primary" onclick="openAssetModal('create')" type="button">
                    Assign Asset
                </button>

            </div>

        </div>

        <div class="card" style="overflow-x:auto">

            <table class="data-table">

                <thead>
                    <tr>
                        <x-table-sort :sort-direction="$sortDirection" :sort-field="$sortField" field="asset_name" label="Asset Name" />

                        <x-table-sort :sort-direction="$sortDirection" :sort-field="$sortField" field="asset_type" label="Asset Type" />

                        <x-table-sort :sort-direction="$sortDirection" :sort-field="$sortField" field="serial_number" label="Serial Number" />

                        <x-table-sort :sort-direction="$sortDirection" :sort-field="$sortField" field="issue_date" label="Issue Date" />

                        <x-table-sort :sort-direction="$sortDirection" :sort-field="$sortField" field="status" label="Status" />

                        <th width="120">Actions</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($assets as $asset)
                        <tr>

                            <td class="font-semibold text-[var(--text)]">{{ $asset->asset_name }}</td>

                            <td>{{ $asset->asset_type }}</td>

                            <td>{{ $asset->serial_number ?? '-' }}</td>

                            <td>{{ $asset->issue_date ? dateFormat($asset->issue_date) : '-' }}</td>

                            <td>
                                @if ($asset->status === 'allocated' || $asset->status === 'issued')
                                    <span class="status-pill pill-blue">Issued</span>
                                @elseif ($asset->status === 'returned')
                                    <span class="status-pill pill-green">Returned</span>
                                @elseif ($asset->status === 'damaged')
                                    <span class="status-pill pill-red">Damaged</span>
                                @else
                                    <span class="status-pill pill-gray">{{ ucfirst($asset->status) }}</span>
                                @endif
                            </td>

                            <td>
                                <div style="display:flex;align-items:center;gap:6px">
                                    <button class="btn-ghost" data-asset-name="{{ $asset->asset_name }}"
                                        data-asset-type="{{ $asset->asset_type }}"
                                        data-issue-date="{{ $asset->issue_date }}" data-notes="{{ $asset->notes }}"
                                        data-return-date="{{ $asset->return_date }}"
                                        data-serial-number="{{ $asset->serial_number }}"
                                        data-status="{{ $asset->status }}" onclick="openAssetViewModal(this.dataset)"
                                        style="padding:6px" title="View" type="button">

                                        <svg fill="none" height="16" stroke-width="2" stroke="currentColor"
                                            viewBox="0 0 24 24" width="16">
                                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8S1 12 1 12z"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                            <circle cx="12" cy="12" r="3" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                        </svg>

                                    </button>
                                    <button class="btn-ghost"
                                        data-action="{{ route('employees.assets.update', [$employee_id, $asset->id]) }}"
                                        data-asset-name="{{ $asset->asset_name }}"
                                        data-asset-type="{{ $asset->asset_type }}" data-id="{{ $asset->id }}"
                                        data-issue-date="{{ $asset->issue_date }}" data-notes="{{ $asset->notes }}"
                                        data-return-date="{{ $asset->return_date }}"
                                        data-serial-number="{{ $asset->serial_number }}"
                                        data-status="{{ $asset->status }}"
                                        onclick="openAssetModal('edit', this.dataset)" style="padding:6px"
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
                                        data-description="Are you sure you want to delete this asset allocation?"
                                        data-title="Delete Asset Assignment"
                                        data-url="{{ route('employees.assets.destroy', [$employee_id, $asset->id]) }}"
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

                                </div>
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td class="p-0" colspan="8">

                                <div class="flex flex-col items-center justify-center px-6 py-14">

                                    <div
                                        class="mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-gray-100 text-gray-400 dark:bg-gray-800 dark:text-gray-500">

                                        <svg class="h-7 w-7" fill="none" stroke-width="1.8" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path
                                                d="M20 7H4a2 2 0 00-2 2v6a2 2 0 002 2h16a2 2 0 002-2V9a2 2 0 00-2-2zM16 12a1 1 0 11-2 0 1 1 0 012 0z"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>

                                    </div>

                                    <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                                        No assets found
                                    </h3>

                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                        No company assets have been allocated to this employee.
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
                    {{ $assets->firstItem() ?? 0 }}
                    to
                    {{ $assets->lastItem() ?? 0 }}
                    of
                    {{ $assets->total() }}
                    results
                </span>

            </div>

            <div>
                {{ $assets->links() }}
            </div>

        </div>
    </div>

</div>
