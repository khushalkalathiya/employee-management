<div>
    <livewire:employees.employee-experience-table :employee="$user->employee" />

    <!-- Form Modal -->
    <div class="modal fixed inset-0 z-[9998] hidden items-center justify-center bg-black/0 p-4 backdrop-blur-sm transition-all duration-300"
        id="experienceFormModal">
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
                <h3 class="section-title" id="experienceModalTitle">Add Experience</h3>
            </div>

            <form action="{{ route('employees.experience.store', $user->id) }}" id="experienceForm" method="POST">
                @csrf
                <input id="experienceFormMethod" name="_method" type="hidden" value="POST">

                <div class="p-6 pt-2">
                    <div class="grid grid-cols-1 gap-5">
                        <div>
                            <label class="field-label">Company Name <span class="text-red-400">*</span></label>
                            <div class="field-wrap relative">
                                <input class="field-input" id="experienceCompany" name="company_name"
                                    placeholder="Enter company name" required type="text">
                            </div>
                            <p class="err-msg hidden"></p>
                        </div>

                        <div>
                            <label class="field-label">Designation <span class="text-red-400">*</span></label>
                            <div class="field-wrap relative">
                                <input class="field-input" id="experienceDesignation" name="designation"
                                    placeholder="Enter job title / designation" required type="text">
                            </div>
                            <p class="err-msg hidden"></p>
                        </div>

                        <div>
                            <label class="field-label">Location</label>
                            <div class="field-wrap relative">
                                <input class="field-input" id="experienceLocation" name="location"
                                    placeholder="e.g. New York, USA or Remote" type="text">
                            </div>
                            <p class="err-msg hidden"></p>
                        </div>

                        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                            <div>
                                <label class="field-label">Start Date <span class="text-red-400">*</span></label>
                                <div class="field-wrap relative">
                                    <input class="field-input" id="experienceStartDate" name="start_date" required
                                        type="date">
                                </div>
                                <p class="err-msg hidden"></p>
                            </div>

                            <div>
                                <label class="field-label">End Date (Leave blank if present)</label>
                                <div class="field-wrap relative">
                                    <input class="field-input" id="experienceEndDate" name="end_date" type="date">
                                </div>
                                <p class="err-msg hidden"></p>
                            </div>
                        </div>

                        <div>
                            <label class="field-label">Description</label>
                            <div class="field-wrap relative">
                                <textarea class="field-input min-h-[100px]" id="experienceDescription" name="description"
                                    placeholder="Enter job description and responsibilities"></textarea>
                            </div>
                            <p class="err-msg hidden"></p>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 bg-gray-50 p-4 pt-0 dark:bg-gray-950">
                    <button class="btn-ghost modal-close-btn" type="button">
                        Cancel
                    </button>
                    <button class="btn-primary" id="experienceSubmitButton" type="submit">
                        Add Record
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        window.openExperienceModal = function(mode = 'create', data = {}) {
            const form = document.getElementById('experienceForm');
            const methodInput = document.getElementById('experienceFormMethod');

            if (mode === 'edit') {
                document.getElementById('experienceModalTitle').textContent = 'Edit Experience';
                document.getElementById('experienceSubmitButton').textContent = 'Update Record';
                form.action = data.action;
                methodInput.value = 'PUT';
                document.getElementById('experienceCompany').value = data.companyName || '';
                document.getElementById('experienceDesignation').value = data.designation || '';
                document.getElementById('experienceLocation').value = data.location || '';
                document.getElementById('experienceStartDate').value = data.startDate || '';
                document.getElementById('experienceEndDate').value = data.endDate || '';
                document.getElementById('experienceDescription').value = data.description || '';
            } else {
                document.getElementById('experienceModalTitle').textContent = 'Add Experience';
                document.getElementById('experienceSubmitButton').textContent = 'Add Record';
                form.action = "{{ route('employees.experience.store', $user->id) }}";
                methodInput.value = 'POST';
                document.getElementById('experienceCompany').value = '';
                document.getElementById('experienceDesignation').value = '';
                document.getElementById('experienceLocation').value = '';
                document.getElementById('experienceStartDate').value = '';
                document.getElementById('experienceEndDate').value = '';
                document.getElementById('experienceDescription').value = '';
            }
            modalHelper.open('experienceFormModal');
        };

        document.addEventListener('DOMContentLoaded', function() {

            const form = document.getElementById('experienceForm');

            form.addEventListener('submit', async function(e) {
                e.preventDefault();

                const button = document.getElementById('experienceSubmitButton');
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

                    document.querySelectorAll('#experienceForm .err-msg').forEach(error => {
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

                    modalHelper.close('experienceFormModal');

                    form.reset();

                    document.getElementById('experienceFormMethod').value = 'POST';

                    document.getElementById('experienceModalTitle').textContent =
                        'Add Experience';

                    document.getElementById('experienceSubmitButton').textContent =
                        'Add Record';

                    document.getElementById('experienceCompany').value = '';
                    document.getElementById('experienceDesignation').value = '';
                    document.getElementById('experienceLocation').value = '';
                    document.getElementById('experienceStartDate').value = '';
                    document.getElementById('experienceEndDate').value = '';
                    document.getElementById('experienceDescription').value = '';

                    Livewire.dispatch('refresh-table');

                    showToast(
                        result.message || 'Experience record saved successfully',
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
