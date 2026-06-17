<div>
    <livewire:employees.employee-education-table :employee="$user->employee" />
    <!-- Form Modal -->
    <div class="modal fixed inset-0 z-[9998] hidden items-center justify-center bg-black/0 p-4 backdrop-blur-sm transition-all duration-300"
        id="educationFormModal">
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
                <h3 class="section-title" id="educationModalTitle">Add Education Record</h3>
            </div>

            <form action="{{ route('employees.education.store', $user->id) }}" id="educationForm" method="POST">
                @csrf
                <input id="educationFormMethod" name="_method" type="hidden" value="POST">

                <div class="p-6 pt-2">
                    <div class="grid grid-cols-1 gap-5">
                        <div>
                            <label class="field-label">Qualification <span class="text-red-400">*</span></label>
                            <div class="field-wrap relative">
                                <input class="field-input" id="educationQualification" name="qualification"
                                    placeholder="e.g. Bachelor of Science, MBA" required type="text">
                            </div>
                            <p class="err-msg hidden"></p>
                        </div>

                        <div>
                            <label class="field-label">Institute Name <span class="text-red-400">*</span></label>
                            <div class="field-wrap relative">
                                <input class="field-input" id="educationInstitute" name="institute_name"
                                    placeholder="Enter school / college / university name" required type="text">
                            </div>
                            <p class="err-msg hidden"></p>
                        </div>

                        <div>
                            <label class="field-label">Board / University</label>
                            <div class="field-wrap relative">
                                <input class="field-input" id="educationBoard" name="board_university"
                                    placeholder="e.g. CBSE, State Board, Stanford University" type="text">
                            </div>
                            <p class="err-msg hidden"></p>
                        </div>

                        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                            <div>
                                <label class="field-label">Passing Year</label>
                                <div class="field-wrap relative">
                                    <input class="field-input" id="educationPassingYear" max="{{ date('Y') + 10 }}"
                                        min="1900" name="passing_year" placeholder="e.g. 2020" type="number">
                                </div>
                                <p class="err-msg hidden"></p>
                            </div>

                            <div>
                                <label class="field-label">Percentage / Grade</label>
                                <div class="field-wrap relative">
                                    <input class="field-input" id="educationGrade" name="percentage_grade"
                                        placeholder="e.g. 85%, GPA 3.8" type="text">
                                </div>
                                <p class="err-msg hidden"></p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 bg-gray-50 p-4 pt-0 dark:bg-gray-950">
                    <button class="btn-ghost modal-close-btn" type="button">
                        Cancel
                    </button>
                    <button class="btn-primary" id="educationSubmitButton" type="submit">
                        Add Record
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        window.openEducationModal = function(mode = 'create', data = {}) {
            const form = document.getElementById('educationForm');
            const methodInput = document.getElementById('educationFormMethod');

            if (mode === 'edit') {
                document.getElementById('educationModalTitle').textContent = 'Edit Education Record';
                document.getElementById('educationSubmitButton').textContent = 'Update Record';
                form.action = data.action;
                methodInput.value = 'PUT';
                document.getElementById('educationQualification').value = data.qualification || '';
                document.getElementById('educationInstitute').value = data.instituteName || '';
                document.getElementById('educationBoard').value = data.boardUniversity || '';
                document.getElementById('educationPassingYear').value = data.passingYear || '';
                document.getElementById('educationGrade').value = data.percentageGrade || '';
            } else {
                document.getElementById('educationModalTitle').textContent = 'Add Education Record';
                document.getElementById('educationSubmitButton').textContent = 'Add Record';
                form.action = "{{ route('employees.education.store', $user->id) }}";
                methodInput.value = 'POST';
                document.getElementById('educationQualification').value = '';
                document.getElementById('educationInstitute').value = '';
                document.getElementById('educationBoard').value = '';
                document.getElementById('educationPassingYear').value = '';
                document.getElementById('educationGrade').value = '';
            }
            modalHelper.open('educationFormModal');
        };

        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('educationForm');

            form.addEventListener('submit', async function(e) {
                e.preventDefault();

                const button = document.getElementById('educationSubmitButton');
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

                    document.querySelectorAll('#educationForm .err-msg').forEach(error => {
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

                    modalHelper.close('educationFormModal');

                    form.reset();

                    document.getElementById('educationFormMethod').value = 'POST';

                    document.getElementById('educationModalTitle').textContent =
                        'Add Education Record';

                    document.getElementById('educationSubmitButton').textContent =
                        'Add Record';

                    Livewire.dispatch('refresh-table');

                    showToast(
                        result.message || 'Education record saved successfully',
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
