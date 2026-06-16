<div class="card" style="padding:20px">
    <div class="mb-4 flex items-center justify-between">
        <div>
            <h3 class="section-title">Education</h3>
            <p class="section-sub">Manage academic qualifications and degrees</p>
        </div>
        <button class="btn-primary" onclick="openEducationModal('create')" type="button">
            Add Education
        </button>
    </div>

    <div style="overflow-x:auto">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Qualification</th>
                    <th>Institute Name</th>
                    <th>Board / University</th>
                    <th>Passing Year</th>
                    <th>Percentage / Grade</th>
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
                            <div class="flex items-center gap-2">
                                <button type="button" class="btn-ghost" style="padding:6px" title="Edit"
                                    data-id="{{ $edu->id }}"
                                    data-qualification="{{ $edu->qualification }}"
                                    data-institute-name="{{ $edu->institute_name }}"
                                    data-board-university="{{ $edu->board_university }}"
                                    data-passing-year="{{ $edu->passing_year }}"
                                    data-percentage-grade="{{ $edu->percentage_grade }}"
                                    data-action="{{ route('employees.education.update', [$employee->id, $edu->id]) }}"
                                    onclick="openEducationModal('edit', this.dataset)">
                                    <svg fill="none" height="16" stroke-width="2" stroke="currentColor" viewBox="0 0 24 24" width="16">
                                        <path d="M11 5H6a2 2 0 0 0-2 2v11a2 2 0 0 0 2 2h11a2 2 0 0 0 2-2v-5" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </button>

                                <button type="button" class="btn-ghost js-delete-confirm" style="padding:6px" title="Delete"
                                    data-title="Delete Education Record"
                                    data-description="Are you sure you want to delete this education record?"
                                    data-url="{{ route('employees.education.destroy', [$employee->id, $edu->id]) }}">
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
                        <td colspan="6" class="text-center py-8 text-gray-500">No education details recorded yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Form Modal -->
<div class="modal fixed inset-0 z-[9998] hidden items-center justify-center bg-black/0 p-4 backdrop-blur-sm transition-all duration-300"
    id="educationFormModal">
    <div class="modal-content relative w-full max-w-xl scale-95 overflow-hidden rounded-2xl border border-gray-200 bg-white opacity-0 shadow-2xl transition-all duration-300 dark:border-gray-800 dark:bg-gray-950">
        <button class="modal-close-btn close-icon absolute right-4 top-4 cursor-pointer text-gray-400 transition hover:text-gray-700 dark:hover:text-white" type="button">
            <svg class="h-5 w-5" fill="none" stroke-width="2" stroke="currentColor" viewBox="0 0 24 24">
                <path d="M6 18L18 6M6 6l12 12" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </button>

        <div class="p-6">
            <h3 class="section-title" id="educationModalTitle">Add Education Record</h3>
        </div>

        <form action="{{ route('employees.education.store', $employee->id) }}" id="educationForm" method="POST">
            @csrf
            <input type="hidden" name="_method" id="educationFormMethod" value="POST">

            <div class="p-6 pt-2">
                <div class="grid grid-cols-1 gap-5">
                    <div>
                        <label class="field-label">Qualification <span class="text-red-400">*</span></label>
                        <div class="field-wrap relative">
                            <input class="field-input" id="educationQualification" name="qualification"
                                placeholder="e.g. Bachelor of Science, MBA" required type="text">
                        </div>
                        @error('qualification')
                            <p class="err-msg">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="field-label">Institute Name <span class="text-red-400">*</span></label>
                        <div class="field-wrap relative">
                            <input class="field-input" id="educationInstitute" name="institute_name"
                                placeholder="Enter school / college / university name" required type="text">
                        </div>
                        @error('institute_name')
                            <p class="err-msg">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="field-label">Board / University</label>
                        <div class="field-wrap relative">
                            <input class="field-input" id="educationBoard" name="board_university"
                                placeholder="e.g. CBSE, State Board, Stanford University" type="text">
                        </div>
                        @error('board_university')
                            <p class="err-msg">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                        <div>
                            <label class="field-label">Passing Year</label>
                            <div class="field-wrap relative">
                                <input class="field-input" id="educationPassingYear" name="passing_year"
                                    placeholder="e.g. 2020" type="number" min="1900" max="{{ date('Y') + 10 }}">
                            </div>
                            @error('passing_year')
                                <p class="err-msg">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="field-label">Percentage / Grade</label>
                            <div class="field-wrap relative">
                                <input class="field-input" id="educationGrade" name="percentage_grade"
                                    placeholder="e.g. 85%, GPA 3.8" type="text">
                            </div>
                            @error('percentage_grade')
                                <p class="err-msg">{{ $message }}</p>
                            @enderror
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
            form.action = "{{ route('employees.education.store', $employee->id) }}";
            methodInput.value = 'POST';
            document.getElementById('educationQualification').value = '';
            document.getElementById('educationInstitute').value = '';
            document.getElementById('educationBoard').value = '';
            document.getElementById('educationPassingYear').value = '';
            document.getElementById('educationGrade').value = '';
        }
        modalHelper.open('educationFormModal');
    };

    document.addEventListener('DOMContentLoaded', () => {
        window.addEventListener('refresh-table', () => {
            location.reload();
        });
    });
</script>
