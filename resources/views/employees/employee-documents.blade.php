<div class="card" style="padding:20px">
    <div class="mb-4 flex items-center justify-between">
        <div>
            <h3 class="section-title">Documents</h3>
            <p class="section-sub">Manage employee identification and contracts</p>
        </div>
        <button class="btn-primary" onclick="openDocumentModal('create')" type="button">
            Add Document
        </button>
    </div>

    <div style="overflow-x:auto">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Document Type</th>
                    <th>File Name</th>
                    <th>Uploaded At</th>
                    <th>Notes</th>
                    <th width="120">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($documents as $doc)
                    @php $media = $doc->getFirstMedia('file'); @endphp
                    <tr>
                        <td class="font-semibold text-[var(--text)]">{{ $doc->document_type }}</td>
                        <td>
                            @if ($media)
                                <a class="inline-flex items-center gap-1 text-blue-500 hover:underline"
                                    href="{{ route('employees.documents.download', [$employee->id, $doc->id]) }}">
                                    <svg class="h-4 w-4" fill="none" stroke-width="2" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path
                                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    {{ $media->file_name }}
                                </a>
                            @else
                                <span class="text-gray-400">No file</span>
                            @endif
                        </td>
                        <td>{{ $doc->created_at->format('M d, Y') }}</td>
                        <td class="max-w-[200px] truncate text-xs" title="{{ $doc->notes }}">{{ $doc->notes ?? '-' }}
                        </td>
                        <td>
                            <div class="flex items-center gap-2">
                                <button class="btn-ghost"
                                    data-action="{{ route('employees.documents.update', [$employee->id, $doc->id]) }}"
                                    data-document-type="{{ $doc->document_type }}" data-id="{{ $doc->id }}"
                                    data-notes="{{ $doc->notes }}" onclick="openDocumentModal('edit', this.dataset)"
                                    style="padding:6px" title="Edit" type="button">
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
                                    data-url="{{ route('employees.documents.destroy', [$employee->id, $doc->id]) }}"
                                    style="padding:6px" title="Delete" type="button">
                                    <svg fill="none" height="16" stroke-width="2" stroke="currentColor"
                                        viewBox="0 0 24 24" width="16">
                                        <path d="M3 6h18" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M8 6V4a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                        <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                        <path d="M10 11v6M14 11v6" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="py-8 text-center text-gray-500" colspan="5">No documents uploaded yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Form Modal -->
<div class="modal fixed inset-0 z-[9998] hidden items-center justify-center bg-black/0 p-4 backdrop-blur-sm transition-all duration-300"
    id="documentFormModal">
    <div
        class="modal-content relative w-full max-w-xl scale-95 overflow-hidden rounded-2xl border border-gray-200 bg-white opacity-0 shadow-2xl transition-all duration-300 dark:border-gray-800 dark:bg-gray-950">
        <button
            class="modal-close-btn close-icon absolute right-4 top-4 cursor-pointer text-gray-400 transition hover:text-gray-700 dark:hover:text-white"
            type="button">
            <svg class="h-5 w-5" fill="none" stroke-width="2" stroke="currentColor" viewBox="0 0 24 24">
                <path d="M6 18L18 6M6 6l12 12" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </button>

        <div class="p-6">
            <h3 class="section-title" id="documentModalTitle">Add Document</h3>
        </div>

        <form action="{{ route('employees.documents.store', $employee->id) }}" enctype="multipart/form-data"
            id="documentForm" method="POST">
            @csrf
            <input id="documentFormMethod" name="_method" type="hidden" value="POST">

            <div class="p-6 pt-2">
                <div class="grid grid-cols-1 gap-5">
                    <div>
                        <label class="field-label">Document Type <span class="text-red-400">*</span></label>
                        <div class="field-wrap relative">
                            <input class="field-input" id="documentType" name="document_type"
                                placeholder="e.g. Passport, Contract, ID Card" required type="text">
                        </div>
                        @error('document_type')
                            <p class="err-msg">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="field-label">Notes</label>
                        <div class="field-wrap relative">
                            <textarea class="field-input min-h-[80px]" id="documentNotes" name="notes"
                                placeholder="Enter document notes or description"></textarea>
                        </div>
                        @error('notes')
                            <p class="err-msg">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="field-label">File <span class="text-red-400"
                                id="fileRequiredStar">*</span></label>
                        <div class="field-wrap relative">
                            <input class="field-input" id="documentFile" name="file" required type="file">
                        </div>
                        <p class="mt-1 text-xs text-gray-400" id="fileHelpText">Max file size: 5MB</p>
                        @error('file')
                            <p class="err-msg">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 bg-gray-50 p-4 pt-0 dark:bg-gray-950">
                <button class="btn-ghost modal-close-btn" type="button">
                    Cancel
                </button>
                <button class="btn-primary" id="documentSubmitButton" type="submit">
                    Add Document
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    window.openDocumentModal = function(mode = 'create', data = {}) {
        const form = document.getElementById('documentForm');
        const fileInput = document.getElementById('documentFile');
        const fileStar = document.getElementById('fileRequiredStar');
        const methodInput = document.getElementById('documentFormMethod');

        if (mode === 'edit') {
            document.getElementById('documentModalTitle').textContent = 'Edit Document';
            document.getElementById('documentSubmitButton').textContent = 'Update Document';
            form.action = data.action;
            methodInput.value = 'PUT';
            document.getElementById('documentType').value = data.documentType || '';
            document.getElementById('documentNotes').value = data.notes || '';
            fileInput.required = false;
            fileStar.style.display = 'none';
        } else {
            document.getElementById('documentModalTitle').textContent = 'Add Document';
            document.getElementById('documentSubmitButton').textContent = 'Add Document';
            form.action = "{{ route('employees.documents.store', $employee->id) }}";
            methodInput.value = 'POST';
            document.getElementById('documentType').value = '';
            document.getElementById('documentNotes').value = '';
            fileInput.required = true;
            fileStar.style.display = 'inline';
        }
        modalHelper.open('documentFormModal');
    };

    document.addEventListener('DOMContentLoaded', () => {
        window.addEventListener('refresh-table', () => {
            location.reload();
        });
    });
</script>
