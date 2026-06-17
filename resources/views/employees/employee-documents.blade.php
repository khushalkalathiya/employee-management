<div>
    <livewire:employees.employee-document-table :employee="$user->employee" />
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

            <form action="{{ route('employees.documents.store', $user->id) }}" enctype="multipart/form-data"
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
                            <p class="err-msg hidden"></p>
                        </div>

                        <div>
                            <label class="field-label">Notes</label>
                            <div class="field-wrap relative">
                                <textarea class="field-input min-h-[80px]" id="documentNotes" name="notes"
                                    placeholder="Enter document notes or description"></textarea>
                            </div>
                            <p class="err-msg hidden"></p>
                        </div>

                        <div>
                            <label class="field-label">File <span class="text-red-400"
                                    id="fileRequiredStar">*</span></label>
                            <div class="field-wrap relative">
                                <input class="field-input" id="documentFile" name="file" required type="file">
                            </div>
                            <p class="mt-1 text-xs text-gray-400" id="fileHelpText">Max file size: 5MB</p>
                            <p class="err-msg hidden"></p>
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
                form.action = "{{ route('employees.documents.store', $user->id) }}";
                methodInput.value = 'POST';
                document.getElementById('documentType').value = '';
                document.getElementById('documentNotes').value = '';
                fileInput.required = true;
                fileStar.style.display = 'inline';
            }
            modalHelper.open('documentFormModal');
        };
        document.addEventListener('DOMContentLoaded', function() {

            const form = document.getElementById('documentForm');

            form.addEventListener('submit', async function(e) {
                e.preventDefault();
                const button = document.getElementById('documentSubmitButton');
                const originalText = button.innerHTML;

                button.disabled = true;
                button.innerHTML = 'Saving...';

                try {
                    const formData = new FormData(form);
                    const response = await fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': formData.get('_token'),
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        body: formData
                    });
                    const result = await response.json();

                    document.querySelectorAll('.err-msg').forEach(error => {
                        error.textContent = '';
                        error.classList.add('hidden');
                    });

                    if (!response.ok || !result.success) {
                        if (result.errors) {
                            Object.entries(result.errors).forEach(([field, messages]) => {
                                const input = form.querySelector(
                                    `[name="${field}"]`
                                );

                                if (!input) {
                                    return;
                                }

                                const errorElement = input.closest('div')
                                    ?.parentElement
                                    ?.querySelector('.err-msg');

                                if (errorElement) {
                                    errorElement.textContent = messages[0];
                                    errorElement.classList.remove('hidden');
                                }
                            });
                            return;
                        }

                        showToast(
                            result.message || 'Something went wrong',
                            'error'
                        );

                        return;
                    }

                    modalHelper.close('documentFormModal');

                    form.reset();

                    document.getElementById('documentFormMethod').value = 'POST';
                    document.getElementById('documentModalTitle').textContent = 'Add Document';
                    document.getElementById('documentSubmitButton').textContent = 'Add Document';
                    document.getElementById('documentFile').required = true;
                    document.getElementById('fileRequiredStar').style.display = 'inline';
                    Livewire.dispatch('refresh-table');
                    showToast(
                        result.message || 'Document saved successfully',
                        'success'
                    );
                } catch (error) {
                    console.error(error);
                    showToast(
                        'Something went wrong',
                        'error'
                    );
                } finally {
                    button.disabled = false;
                    button.innerHTML = originalText;
                }
            });
        });
    </script>
</div>
