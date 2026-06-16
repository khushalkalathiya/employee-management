<div class="card" style="padding:20px">
    <div class="mb-4 flex items-center justify-between">
        <div>
            <h3 class="section-title">Experience</h3>
            <p class="section-sub">Manage employee past employment and work experience</p>
        </div>
        <button class="btn-primary" onclick="openExperienceModal('create')" type="button">
            Add Experience
        </button>
    </div>

    <div style="overflow-x:auto">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Company Name</th>
                    <th>Designation</th>
                    <th>Location</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Description</th>
                    <th width="120">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($experiences as $exp)
                    <tr>
                        <td class="font-semibold text-[var(--text)]">{{ $exp->company_name }}</td>
                        <td>{{ $exp->designation }}</td>
                        <td>{{ $exp->location ?? '-' }}</td>
                        <td>{{ dateFormat($exp->start_date) }}</td>
                        <td>{{ $exp->end_date ? dateFormat($exp->end_date) : 'Present' }}</td>
                        <td class="max-w-[200px] truncate text-xs" title="{{ $exp->description }}">{{ $exp->description ?? '-' }}</td>
                        <td>
                            <div class="flex items-center gap-2">
                                <button type="button" class="btn-ghost" style="padding:6px" title="Edit"
                                    data-id="{{ $exp->id }}"
                                    data-company-name="{{ $exp->company_name }}"
                                    data-designation="{{ $exp->designation }}"
                                    data-location="{{ $exp->location }}"
                                    data-start-date="{{ $exp->start_date }}"
                                    data-end-date="{{ $exp->end_date }}"
                                    data-description="{{ $exp->description }}"
                                    data-action="{{ route('employees.experience.update', [$employee->id, $exp->id]) }}"
                                    onclick="openExperienceModal('edit', this.dataset)">
                                    <svg fill="none" height="16" stroke-width="2" stroke="currentColor" viewBox="0 0 24 24" width="16">
                                        <path d="M11 5H6a2 2 0 0 0-2 2v11a2 2 0 0 0 2 2h11a2 2 0 0 0 2-2v-5" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </button>

                                <button type="button" class="btn-ghost js-delete-confirm" style="padding:6px" title="Delete"
                                    data-title="Delete Experience Record"
                                    data-description="Are you sure you want to delete this experience record?"
                                    data-url="{{ route('employees.experience.destroy', [$employee->id, $exp->id]) }}">
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
                        <td colspan="7" class="text-center py-8 text-gray-500">No experience details recorded yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Form Modal -->
<div class="modal fixed inset-0 z-[9998] hidden items-center justify-center bg-black/0 p-4 backdrop-blur-sm transition-all duration-300"
    id="experienceFormModal">
    <div class="modal-content relative w-full max-w-xl scale-95 overflow-hidden rounded-2xl border border-gray-200 bg-white opacity-0 shadow-2xl transition-all duration-300 dark:border-gray-800 dark:bg-gray-950">
        <button class="modal-close-btn close-icon absolute right-4 top-4 cursor-pointer text-gray-400 transition hover:text-gray-700 dark:hover:text-white" type="button">
            <svg class="h-5 w-5" fill="none" stroke-width="2" stroke="currentColor" viewBox="0 0 24 24">
                <path d="M6 18L18 6M6 6l12 12" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </button>

        <div class="p-6">
            <h3 class="section-title" id="experienceModalTitle">Add Experience</h3>
        </div>

        <form action="{{ route('employees.experience.store', $employee->id) }}" id="experienceForm" method="POST">
            @csrf
            <input type="hidden" name="_method" id="experienceFormMethod" value="POST">

            <div class="p-6 pt-2">
                <div class="grid grid-cols-1 gap-5">
                    <div>
                        <label class="field-label">Company Name <span class="text-red-400">*</span></label>
                        <div class="field-wrap relative">
                            <input class="field-input" id="experienceCompany" name="company_name"
                                placeholder="Enter company name" required type="text">
                        </div>
                        @error('company_name')
                            <p class="err-msg">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="field-label">Designation <span class="text-red-400">*</span></label>
                        <div class="field-wrap relative">
                            <input class="field-input" id="experienceDesignation" name="designation"
                                placeholder="Enter job title / designation" required type="text">
                        </div>
                        @error('designation')
                            <p class="err-msg">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="field-label">Location</label>
                        <div class="field-wrap relative">
                            <input class="field-input" id="experienceLocation" name="location"
                                placeholder="e.g. New York, USA or Remote" type="text">
                        </div>
                        @error('location')
                            <p class="err-msg">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                        <div>
                            <label class="field-label">Start Date <span class="text-red-400">*</span></label>
                            <div class="field-wrap relative">
                                <input class="field-input" id="experienceStartDate" name="start_date" required type="date">
                            </div>
                            @error('start_date')
                                <p class="err-msg">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="field-label">End Date (Leave blank if present)</label>
                            <div class="field-wrap relative">
                                <input class="field-input" id="experienceEndDate" name="end_date" type="date">
                            </div>
                            @error('end_date')
                                <p class="err-msg">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label class="field-label">Description</label>
                        <div class="field-wrap relative">
                            <textarea class="field-input min-h-[100px]" id="experienceDescription" name="description"
                                placeholder="Enter job description and responsibilities"></textarea>
                        </div>
                        @error('description')
                            <p class="err-msg">{{ $message }}</p>
                        @enderror
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
            form.action = "{{ route('employees.experience.store', $employee->id) }}";
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

    document.addEventListener('DOMContentLoaded', () => {
        window.addEventListener('refresh-table', () => {
            location.reload();
        });
    });
</script>
