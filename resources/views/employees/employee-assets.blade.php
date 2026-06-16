<div class="card" style="padding:20px">
    <div class="mb-4 flex items-center justify-between">
        <div>
            <h3 class="section-title">Assets</h3>
            <p class="section-sub">Manage company property allocated to employee</p>
        </div>
        <button class="btn-primary" onclick="openAssetModal('create')" type="button">
            Assign Asset
        </button>
    </div>

    <div style="overflow-x:auto">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Asset Name</th>
                    <th>Asset Type</th>
                    <th>Serial Number</th>
                    <th>Issue Date</th>
                    <th>Return Date</th>
                    <th>Status</th>
                    <th>Notes</th>
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
                        <td>{{ $asset->return_date ? dateFormat($asset->return_date) : '-' }}</td>
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
                        <td class="max-w-[150px] truncate text-xs" title="{{ $asset->notes }}">{{ $asset->notes ?? '-' }}</td>
                        <td>
                            <div class="flex items-center gap-2">
                                <button type="button" class="btn-ghost" style="padding:6px" title="Edit"
                                    data-id="{{ $asset->id }}"
                                    data-asset-name="{{ $asset->asset_name }}"
                                    data-asset-type="{{ $asset->asset_type }}"
                                    data-serial-number="{{ $asset->serial_number }}"
                                    data-issue-date="{{ $asset->issue_date }}"
                                    data-return-date="{{ $asset->return_date }}"
                                    data-status="{{ $asset->status }}"
                                    data-notes="{{ $asset->notes }}"
                                    data-action="{{ route('employees.assets.update', [$employee->id, $asset->id]) }}"
                                    onclick="openAssetModal('edit', this.dataset)">
                                    <svg fill="none" height="16" stroke-width="2" stroke="currentColor" viewBox="0 0 24 24" width="16">
                                        <path d="M11 5H6a2 2 0 0 0-2 2v11a2 2 0 0 0 2 2h11a2 2 0 0 0 2-2v-5" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </button>

                                <button type="button" class="btn-ghost js-delete-confirm" style="padding:6px" title="Delete"
                                    data-title="Delete Asset Assignment"
                                    data-description="Are you sure you want to delete this asset allocation?"
                                    data-url="{{ route('employees.assets.destroy', [$employee->id, $asset->id]) }}">
                                    <svg fill="none" height="16" stroke-width="2" stroke="currentColor" viewBox="0 0 24 24" width="16">
                                        <path d="M3 6h18" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M8 6V4a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M10 11v6M14 11v6" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-8 text-gray-500">No assets allocated yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Form Modal -->
<div class="modal fixed inset-0 z-[9998] hidden items-center justify-center bg-black/0 p-4 backdrop-blur-sm transition-all duration-300"
    id="assetFormModal">
    <div class="modal-content relative w-full max-w-xl scale-95 overflow-hidden rounded-2xl border border-gray-200 bg-white opacity-0 shadow-2xl transition-all duration-300 dark:border-gray-800 dark:bg-gray-950">
        <button class="modal-close-btn close-icon absolute right-4 top-4 cursor-pointer text-gray-400 transition hover:text-gray-700 dark:hover:text-white" type="button">
            <svg class="h-5 w-5" fill="none" stroke-width="2" stroke="currentColor" viewBox="0 0 24 24">
                <path d="M6 18L18 6M6 6l12 12" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </button>

        <div class="p-6">
            <h3 class="section-title" id="assetModalTitle">Assign Asset</h3>
        </div>

        <form action="{{ route('employees.assets.store', $employee->id) }}" id="assetForm" method="POST">
            @csrf
            <input type="hidden" name="_method" id="assetFormMethod" value="POST">

            <div class="p-6 pt-2">
                <div class="grid grid-cols-1 gap-5">
                    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                        <div>
                            <label class="field-label">Asset Name <span class="text-red-400">*</span></label>
                            <div class="field-wrap relative">
                                <input class="field-input" id="assetName" name="asset_name"
                                    placeholder="e.g. MacBook Pro, Monitor" required type="text">
                            </div>
                            @error('asset_name')
                                <p class="err-msg">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="field-label">Asset Type <span class="text-red-400">*</span></label>
                            <div class="field-wrap relative">
                                <select class="field-input tom-select" id="assetType" name="asset_type" required data-placeholder="Select Type">
                                    <option value="">Select Type</option>
                                    <option value="laptop">Laptop</option>
                                    <option value="mobile">Mobile Phone</option>
                                    <option value="accessory">Accessory</option>
                                    <option value="furniture">Furniture</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                            @error('asset_type')
                                <p class="err-msg">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                        <div>
                            <label class="field-label">Serial Number</label>
                            <div class="field-wrap relative">
                                <input class="field-input" id="assetSerialNumber" name="serial_number"
                                    placeholder="Enter serial number" type="text">
                            </div>
                            @error('serial_number')
                                <p class="err-msg">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="field-label">Status <span class="text-red-400">*</span></label>
                            <div class="field-wrap relative">
                                <select class="field-input tom-select" id="assetStatus" name="status" required data-placeholder="Select Status">
                                    <option value="issued">Issued</option>
                                    <option value="returned">Returned</option>
                                    <option value="damaged">Damaged</option>
                                </select>
                            </div>
                            @error('status')
                                <p class="err-msg">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                        <div>
                            <label class="field-label">Issue Date</label>
                            <div class="field-wrap relative">
                                <input class="field-input" id="assetIssueDate" name="issue_date" type="date">
                            </div>
                            @error('issue_date')
                                <p class="err-msg">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="field-label">Return Date</label>
                            <div class="field-wrap relative">
                                <input class="field-input" id="assetReturnDate" name="return_date" type="date">
                            </div>
                            @error('return_date')
                                <p class="err-msg">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label class="field-label">Notes</label>
                        <div class="field-wrap relative">
                            <textarea class="field-input min-h-[80px]" id="assetNotes" name="notes"
                                placeholder="Enter any notes or remarks"></textarea>
                        </div>
                        @error('notes')
                            <p class="err-msg">{{ $message }}</p>
                        @enderror
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
            form.action = "{{ route('employees.assets.store', $employee->id) }}";
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

    document.addEventListener('DOMContentLoaded', () => {
        window.addEventListener('refresh-table', () => {
            location.reload();
        });
    });
</script>
