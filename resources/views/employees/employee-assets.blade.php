<div>
    <livewire:employees.employee-asset-table :employee="$user->employee" />

    <!-- Form Modal -->
    <div class="modal fixed inset-0 z-[9998] hidden items-center justify-center bg-black/0 p-4 backdrop-blur-sm transition-all duration-300"
        id="assetFormModal">
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
                <h3 class="section-title" id="assetModalTitle">Assign Asset</h3>
            </div>

            <form action="{{ route('employees.assets.store', $user->id) }}" id="assetForm" method="POST">
                @csrf
                <input id="assetFormMethod" name="_method" type="hidden" value="POST">

                <div class="p-6 pt-2">
                    <div class="grid grid-cols-1 gap-5">
                        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                            <div>
                                <label class="field-label">Asset Name <span class="text-red-400">*</span></label>
                                <div class="field-wrap relative">
                                    <input class="field-input" id="assetName" name="asset_name"
                                        placeholder="e.g. MacBook Pro, Monitor" required type="text">
                                </div>
                                <p class="err-msg hidden"></p>
                            </div>

                            <div>
                                <label class="field-label">Asset Type <span class="text-red-400">*</span></label>
                                <div class="field-wrap relative">
                                    <select class="field-input tom-select" data-placeholder="Select Type" id="assetType"
                                        name="asset_type" required>
                                        <option value="">Select Type</option>
                                        <option value="laptop">Laptop</option>
                                        <option value="mobile">Mobile Phone</option>
                                        <option value="accessory">Accessory</option>
                                        <option value="furniture">Furniture</option>
                                        <option value="other">Other</option>
                                    </select>
                                </div>
                                <p class="err-msg hidden"></p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                            <div>
                                <label class="field-label">Serial Number</label>
                                <div class="field-wrap relative">
                                    <input class="field-input" id="assetSerialNumber" name="serial_number"
                                        placeholder="Enter serial number" type="text">
                                </div>
                                <p class="err-msg hidden"></p>
                            </div>

                            <div>
                                <label class="field-label">Status <span class="text-red-400">*</span></label>
                                <div class="field-wrap relative">
                                    <select class="field-input tom-select" data-placeholder="Select Status"
                                        id="assetStatus" name="status" required>
                                        <option value="issued">Issued</option>
                                        <option value="returned">Returned</option>
                                        <option value="damaged">Damaged</option>
                                    </select>
                                </div>
                                <p class="err-msg hidden"></p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                            <div>
                                <label class="field-label">Issue Date</label>
                                <div class="field-wrap relative">
                                    <input class="field-input" id="assetIssueDate" name="issue_date" type="date">
                                </div>
                                <p class="err-msg hidden"></p>
                            </div>

                            <div>
                                <label class="field-label">Return Date</label>
                                <div class="field-wrap relative">
                                    <input class="field-input" id="assetReturnDate" name="return_date" type="date">
                                </div>
                                <p class="err-msg hidden"></p>
                            </div>
                        </div>

                        <div>
                            <label class="field-label">Notes</label>
                            <div class="field-wrap relative">
                                <textarea class="field-input min-h-[80px]" id="assetNotes" name="notes" placeholder="Enter any notes or remarks"></textarea>
                            </div>
                            <p class="err-msg hidden"></p>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 bg-gray-50 p-4 pt-0 dark:bg-gray-950">
                    <button class="btn-ghost modal-close-btn" type="button">
                        Cancel
                    </button>
                    <button class="btn-primary" id="assetSubmitButton" type="submit">
                        Assign Asset
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fixed inset-0 z-[9998] hidden items-center justify-center bg-black/0 p-4 backdrop-blur-sm transition-all duration-300"
        id="assetViewModal">
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
                <h3 class="section-title">
                    Asset Details
                </h3>
            </div>
            <div class="px-6 pb-6">
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div class="rounded-xl bg-gray-50 p-4 dark:bg-gray-900">
                        <div class="text-xs font-medium uppercase tracking-wide text-gray-500">
                            Asset Name
                        </div>
                        <div class="mt-1 font-medium text-gray-900 dark:text-white" id="viewAssetName">
                            -
                        </div>
                    </div>
                    <div class="rounded-xl bg-gray-50 p-4 dark:bg-gray-900">
                        <div class="text-xs font-medium uppercase tracking-wide text-gray-500">
                            Asset Type
                        </div>
                        <div class="mt-1 text-gray-900 dark:text-white" id="viewAssetType">
                            -
                        </div>
                    </div>
                    <div class="rounded-xl bg-gray-50 p-4 dark:bg-gray-900">
                        <div class="text-xs font-medium uppercase tracking-wide text-gray-500">
                            Serial Number
                        </div>
                        <div class="mt-1 text-gray-900 dark:text-white" id="viewAssetSerial">
                            -
                        </div>
                    </div>
                    <div class="rounded-xl bg-gray-50 p-4 dark:bg-gray-900">
                        <div class="text-xs font-medium uppercase tracking-wide text-gray-500">
                            Status
                        </div>
                        <div class="mt-1 text-gray-900 dark:text-white" id="viewAssetStatus">
                            -
                        </div>
                    </div>
                    <div class="rounded-xl bg-gray-50 p-4 dark:bg-gray-900">
                        <div class="text-xs font-medium uppercase tracking-wide text-gray-500">
                            Issue Date
                        </div>
                        <div class="mt-1 text-gray-900 dark:text-white" id="viewAssetIssueDate">
                            -
                        </div>
                    </div>
                    <div class="rounded-xl bg-gray-50 p-4 dark:bg-gray-900">
                        <div class="text-xs font-medium uppercase tracking-wide text-gray-500">
                            Return Date
                        </div>
                        <div class="mt-1 text-gray-900 dark:text-white" id="viewAssetReturnDate">
                            -
                        </div>
                    </div>
                </div>
                <div class="mt-4 rounded-xl bg-gray-50 p-4 dark:bg-gray-900">
                    <div class="text-xs font-medium uppercase tracking-wide text-gray-500">
                        Notes
                    </div>
                    <div class="mt-2 whitespace-pre-wrap text-gray-900 dark:text-white" id="viewAssetNotes">
                        -
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        window.openAssetModal = function(mode = 'create', data = {}) {
            const form = document.getElementById('assetForm');
            const methodInput = document.getElementById('assetFormMethod');

            // TomSelect instances helper
            const setSelectValue = (id, val) => {
                const el = document.getElementById(id);
                if (el && el.tomselect) {
                    el.tomselect.setValue(val);
                } else if (el) {
                    el.value = val;
                }
            };

            if (mode === 'edit') {
                document.getElementById('assetModalTitle').textContent = 'Edit Asset Assignment';
                document.getElementById('assetSubmitButton').textContent = 'Update Assignment';
                form.action = data.action;
                methodInput.value = 'PUT';
                document.getElementById('assetName').value = data.assetName || '';
                setSelectValue('assetType', data.assetType || '');
                document.getElementById('assetSerialNumber').value = data.serialNumber || '';
                setSelectValue('assetStatus', data.status || 'issued');
                document.getElementById('assetIssueDate').value = data.issueDate || '';
                document.getElementById('assetReturnDate').value = data.returnDate || '';
                document.getElementById('assetNotes').value = data.notes || '';
            } else {
                document.getElementById('assetModalTitle').textContent = 'Assign Asset';
                document.getElementById('assetSubmitButton').textContent = 'Assign Asset';
                form.action = "{{ route('employees.assets.store', $user->id) }}";
                methodInput.value = 'POST';
                document.getElementById('assetName').value = '';
                setSelectValue('assetType', '');
                document.getElementById('assetSerialNumber').value = '';
                setSelectValue('assetStatus', 'issued');
                document.getElementById('assetIssueDate').value = '';
                document.getElementById('assetReturnDate').value = '';
                document.getElementById('assetNotes').value = '';
            }
            modalHelper.open('assetFormModal');
        };

        window.openAssetViewModal = function(data) {
            document.getElementById('viewAssetName').textContent = data.assetName || '-';
            document.getElementById('viewAssetType').textContent = data.assetType || '-';
            document.getElementById('viewAssetSerial').textContent = data.serialNumber || '-';
            document.getElementById('viewAssetStatus').textContent = data.status || '-';
            document.getElementById('viewAssetIssueDate').textContent = data.issueDate || '-';
            document.getElementById('viewAssetReturnDate').textContent = data.returnDate || '-';
            document.getElementById('viewAssetNotes').textContent = data.notes || '-';
            modalHelper.open('assetViewModal');
        };

        document.addEventListener('DOMContentLoaded', function() {

            const form = document.getElementById('assetForm');

            form.addEventListener('submit', async function(e) {
                e.preventDefault();
                const button = document.getElementById('assetSubmitButton');
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

                    document.querySelectorAll('#assetForm .err-msg').forEach(error => {
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

                    modalHelper.close('assetFormModal');

                    form.reset();

                    document.getElementById('assetFormMethod').value = 'POST';
                    document.getElementById('assetModalTitle').textContent = 'Assign Asset';
                    document.getElementById('assetSubmitButton').textContent = 'Assign Asset';

                    // Reset TomSelect instances
                    const setSelectValue = (id, val) => {
                        const el = document.getElementById(id);
                        if (el && el.tomselect) {
                            el.tomselect.setValue(val);
                        } else if (el) {
                            el.value = val;
                        }
                    };
                    setSelectValue('assetType', '');
                    setSelectValue('assetStatus', 'issued');

                    Livewire.dispatch('refresh-table');
                    showToast(
                        result.message || 'Asset saved successfully',
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
