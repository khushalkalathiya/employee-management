<x-app-layout>
    <livewire:work-logs.employee-work-logs />

    <!-- Create/Edit Form Modal (reusing Holiday design + spacing + animations) -->
    <div class="modal fixed inset-0 z-[9998] hidden items-center justify-center bg-black/0 p-4 backdrop-blur-sm transition-all duration-300"
        id="workLogFormModal">
        <div
            class="modal-content relative w-full max-w-xl scale-95 overflow-hidden rounded-2xl border border-gray-200 bg-white opacity-0 shadow-2xl transition-all duration-300 dark:border-gray-800 dark:bg-gray-950">
            <button
                class="modal-close-btn close-icon absolute right-4 top-4 cursor-pointer text-gray-400 transition hover:text-gray-700 dark:hover:text-white"
                type="button" onclick="closeWorkLogModal()">
                <svg class="h-5 w-5" fill="none" stroke-width="2" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M6 18L18 6M6 6l12 12" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </button>

            <div class="p-6">
                <h3 class="section-title" id="workLogModalTitle">Create Work Log</h3>
            </div>

            <form data-create-action="{{ route('work-logs.store') }}" id="workLogForm" method="POST" enctype="multipart/form-data">
                @csrf
                <input class="hidden" id="workLogFormIsEdit" name="is_edit" type="hidden" value="0" />
                
                <div class="p-6 pt-2">
                    <div class="grid grid-cols-1 gap-5">

                        <!-- Employee Dropdown for Admin/Manager -->
                        @if(!empty($employees) && count($employees) > 0)
                            <div>
                                <label class="field-label">Employee <span class="text-red-400">*</span></label>
                                <div class="field-wrap relative">
                                    <select class="field-input text-[var(--text)] dark:bg-gray-950" id="workLogEmployeeId" name="employee_id" required>
                                        <option value="">Select Employee</option>
                                        @foreach($employees as $emp)
                                            <option value="{{ $emp->id }}">{{ $emp->user->full_name }} ({{ $emp->user->email }})</option>
                                        @endforeach
                                    </select>
                                </div>
                                <p class="err-msg hidden text-rose-500 text-xs mt-1"></p>
                            </div>
                        @endif
                        
                        <!-- Date with Flatpickr -->
                        <div>
                            <label class="field-label">Work Date <span class="text-red-400">*</span></label>
                            <div class="field-wrap relative">
                                <input autocomplete="off" class="field-input text-[var(--text)] dark:bg-gray-950" id="workLogDate" name="date" placeholder="Select work date" required type="text">
                            </div>
                            <p class="err-msg hidden text-rose-500 text-xs mt-1"></p>
                        </div>

                        <!-- Project Title -->
                        <div>
                            <label class="field-label">Project Title <span class="text-red-400">*</span></label>
                            <div class="field-wrap relative">
                                <input class="field-input" id="workLogProjectTitle" name="project_title" placeholder="Project Name / Area" required type="text">
                            </div>
                            <p class="err-msg hidden text-rose-500 text-xs mt-1"></p>
                        </div>

                        <!-- Description -->
                        <div>
                            <label class="field-label">Description <span class="text-red-400">*</span></label>
                            <div class="field-wrap relative">
                                <textarea class="field-input min-h-[120px]" id="workLogDescription" name="description" placeholder="Detail your tasks accomplished..." required></textarea>
                            </div>
                            <p class="err-msg hidden text-rose-500 text-xs mt-1"></p>
                        </div>

                        <!-- Upload Images -->
                        <div>
                            <label class="field-label">Attach Work Images</label>
                            <input type="file" multiple accept="image/*" id="workLogImages" name="work_images[]" class="block w-full text-xs text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-gray-800 dark:file:text-gray-200">
                            <p class="err-msg hidden text-rose-500 text-xs mt-1"></p>
                        </div>

                        <!-- Existing Images -->
                        <div id="workLogExistingImagesWrap" class="hidden">
                            <label class="field-label">Existing Images</label>
                            <div id="workLogExistingImagesList" class="flex gap-2 flex-wrap mt-2"></div>
                        </div>

                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 bg-gray-50 p-4 pt-2 dark:bg-gray-950">
                    <button class="btn-ghost" type="button" onclick="closeWorkLogModal()">
                        Cancel
                    </button>
                    <button class="btn-primary" id="workLogSubmitButton" type="submit">
                        Create Work Log
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- AJAX & Modal Management Script -->
    <script>
        let workLogDatePicker;

        window.workLogFormReset = function() {
            const form = document.getElementById('workLogForm');
            form.reset();
            workLogDatePicker?.clear();
            document.getElementById('workLogExistingImagesWrap').classList.add('hidden');
            document.getElementById('workLogExistingImagesList').innerHTML = '';

            const employeeSelect = document.getElementById('workLogEmployeeId');
            if (employeeSelect) {
                employeeSelect.value = '';
            }
        };

        window.openWorkLogModal = function(mode = 'create', data = {}) {
            const form = document.getElementById('workLogForm');
            const createAction = form.dataset.createAction;
            const isEdit = mode === 'edit';
            
            document.getElementById('workLogModalTitle').textContent = isEdit ? 'Edit Work Log' : 'Create Work Log';
            document.getElementById('workLogSubmitButton').textContent = isEdit ? 'Update Work Log' : 'Create Work Log';
            form.action = isEdit ? data.action : createAction;
            document.getElementById('workLogFormIsEdit').value = isEdit ? 1 : 0;
            
            workLogFormReset();

            if (isEdit) {
                document.getElementById('workLogProjectTitle').value = data.projectTitle || '';
                document.getElementById('workLogDescription').value = data.description || '';
                workLogDatePicker?.setDate(data.date, true);

                const employeeSelect = document.getElementById('workLogEmployeeId');
                if (employeeSelect) {
                    employeeSelect.value = data.employeeId || '';
                }

                // Load existing images
                const images = JSON.parse(data.images || '[]');
                if (images.length > 0) {
                    const listContainer = document.getElementById('workLogExistingImagesList');
                    images.forEach(img => {
                        const imgDiv = document.createElement('div');
                        imgDiv.className = 'relative w-16 h-16 rounded-lg overflow-hidden border border-gray-200 dark:border-gray-800';
                        imgDiv.innerHTML = `
                            <img src="${img.url}" class="w-full h-full object-cover">
                            <button type="button" onclick="removeWorkLogImage(${img.id}, this)" class="absolute top-0.5 right-0.5 bg-black/70 hover:bg-black text-white rounded-full w-4.5 h-4.5 flex items-center justify-center text-[8px]">✕</button>
                        `;
                        listContainer.appendChild(imgDiv);
                    });
                    document.getElementById('workLogExistingImagesWrap').classList.remove('hidden');
                }
            } else {
                // Pre-fill today
                const todayStr = new Date().toISOString().substring(0, 10);
                workLogDatePicker?.setDate(todayStr, true);
            }

            modalHelper.open('workLogFormModal');
        };

        window.closeWorkLogModal = function() {
            modalHelper.close('workLogFormModal');
        };

        window.removeWorkLogImage = async function(mediaId, btn) {
            if (!confirm('Are you sure you want to remove this image?')) return;
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
            
            try {
                const response = await fetch(`/work-logs/media/${mediaId}`, {
                    method: 'DELETE',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest',
                    }
                });
                const result = await response.json();
                if (result.success) {
                    btn.parentElement.remove();
                    if (document.getElementById('workLogExistingImagesList').children.length === 0) {
                        document.getElementById('workLogExistingImagesWrap').classList.add('hidden');
                    }
                    if (window.showToast) window.showToast(result.message || 'Image removed', 'success');
                    Livewire.dispatch('refresh-table');
                } else {
                    if (window.showToast) window.showToast(result.message || 'Failed', 'error');
                }
            } catch (e) {
                if (window.showToast) window.showToast('Server error', 'error');
            }
        };

        window.deleteWorkLogAJAX = async function(url) {
            if (!confirm('Are you sure you want to delete this work log?')) return;
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

            try {
                const response = await fetch(url, {
                    method: 'DELETE',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest',
                    }
                });
                const result = await response.json();
                if (result.success) {
                    if (window.showToast) window.showToast(result.message || 'Deleted successfully', 'success');
                    Livewire.dispatch('refresh-table');
                } else {
                    if (window.showToast) window.showToast(result.message || 'Failed', 'error');
                }
            } catch (e) {
                if (window.showToast) window.showToast('Server error', 'error');
            }
        };

        document.addEventListener('DOMContentLoaded', () => {
            workLogDatePicker = flatpickr("#workLogDate", {
                dateFormat: "Y-m-d",
                monthSelectorType: "static",
                allowInput: true,
                maxDate: new Date().toISOString().substring(0, 10),
            });

            const form = document.getElementById('workLogForm');
            form.addEventListener('submit', async function(e) {
                e.preventDefault();
                const submitBtn = document.getElementById('workLogSubmitButton');
                const originalText = submitBtn.innerHTML;
                submitBtn.disabled = true;
                submitBtn.innerHTML = 'Saving...';

                try {
                    const formData = new FormData(form);
                    if (document.getElementById('workLogFormIsEdit').value === '1') {
                        formData.append('_method', 'PUT');
                    }
                    const response = await fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        body: formData
                    });

                    const result = await response.json();
                    document.querySelectorAll('.err-msg').forEach(el => {
                        el.textContent = '';
                        el.classList.add('hidden');
                    });

                    if (!result.success) {
                        if (result.errors) {
                            Object.entries(result.errors).forEach(([field, messages]) => {
                                const input = form.querySelector(`[name="${field}"]`) || form.querySelector(`[name="${field}[]"]`);
                                if (input) {
                                    const errorElement = input.closest('div')?.parentElement?.querySelector('.err-msg');
                                    if (errorElement) {
                                        errorElement.textContent = messages[0];
                                        errorElement.classList.remove('hidden');
                                    }
                                }
                            });
                            return;
                        }
                        if (window.showToast) window.showToast(result.message || 'Operation failed', 'error');
                        return;
                    }

                    closeWorkLogModal();
                    workLogFormReset();
                    Livewire.dispatch('refresh-table');
                    if (window.showToast) window.showToast(result.message || 'Saved successfully', 'success');
                } catch (err) {
                    console.error(err);
                    if (window.showToast) window.showToast('Server error', 'error');
                } finally {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                }
            });
        });
    </script>
</x-app-layout>
