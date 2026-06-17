<div>
    <div style="margin-bottom:20px">
        <div
            style="padding:0px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;margin-bottom:16px">

            <div>
                <div class="section-title">Documents</div>
                <div class="section-sub">Manage employee identification and contracts</div>
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
                    <input class="search-inp" placeholder="Search employees" style="min-width:250px" type="text"
                        wire:model.live.debounce.500ms="search" />
                </div>

                <button class="btn-primary" onclick="openDocumentModal('create')" type="button">
                    Add Document
                </button>

            </div>

        </div>

        <div class="card" style="overflow-x:auto">

            <table class="data-table">

                <thead>
                    <tr>
                        <x-table-sort :sort-direction="$sortDirection" :sort-field="$sortField" field="document_type" label="Document Type" />

                        <x-table-sort :sort-direction="$sortDirection" :sort-field="$sortField" field="created_at" label="Uploaded At" />

                        <x-table-sort :sort-direction="$sortDirection" :sort-field="$sortField" field="notes" label="Notes" />

                        <th width="120">Actions</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($documents as $doc)
                        @php
                            $media = $doc->getFirstMedia('file');
                        @endphp
                        <tr>

                            <td>
                                {{ ucfirst(str_replace('_', ' ', $doc->document_type)) }}
                            </td>

                            <td>
                                {{ dateFormat($doc->created_at) }}
                            </td>

                            <td>
                                {{ $doc->notes ?: '-' }}
                            </td>

                            <td>
                                <div style="display:flex;align-items:center;gap:6px">

                                    @if ($media)
                                        <a class="btn-ghost"
                                            href="{{ route('employees.documents.download', [$employee_id, $doc->id]) }}"
                                            style="padding:6px" title="Download">

                                            <svg fill="none" height="16" stroke-width="2" stroke="currentColor"
                                                viewBox="0 0 24 24" width="16">
                                                <path d="M12 3v12" stroke-linecap="round" stroke-linejoin="round" />
                                                <path d="M7 10l5 5 5-5" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                                <path d="M5 21h14" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                        </a>
                                    @endif

                                    <button class="btn-ghost"
                                        data-action="{{ route('employees.documents.update', [$employee_id, $doc->id]) }}"
                                        data-document-type="{{ $doc->document_type }}" data-id="{{ $doc->id }}"
                                        data-notes="{{ $doc->notes }}"
                                        onclick="openDocumentModal('edit', this.dataset)" style="padding:6px"
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
                                        data-description="Are you sure you want to delete this document?"
                                        data-title="Delete Document"
                                        data-url="{{ route('employees.documents.destroy', [$employee_id, $doc->id]) }}"
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
                            <td class="p-0" colspan="5">

                                <div class="flex flex-col items-center justify-center px-6 py-14">

                                    <div
                                        class="mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-gray-100 text-gray-400 dark:bg-gray-800 dark:text-gray-500">

                                        <svg class="h-7 w-7" fill="none" stroke-width="1.8" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path
                                                d="M9 17h6m-6-4h6m-8 8h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>

                                    </div>

                                    <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                                        No documents found
                                    </h3>

                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                        This employee has no uploaded documents.
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
                    {{ $documents->firstItem() ?? 0 }}
                    to
                    {{ $documents->lastItem() ?? 0 }}
                    of
                    {{ $documents->total() }}
                    results
                </span>

            </div>

            <div>
                {{ $documents->links() }}
            </div>

        </div>
    </div>

</div>
