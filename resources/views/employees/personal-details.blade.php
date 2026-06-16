@php
    $employeeDetails = $employee->employee;
@endphp

<form action="{{ route('employees.personal-details.update', $employee->id) }}" method="POST">
    @csrf
    @method('put')

    <div class="card" style="padding:20px">
        <div class="mb-5 grid grid-cols-1 gap-5 md:grid-cols-3">
            <div>
                <label class="field-label">Employee Code <span class="text-red-400">*</span></label>
                <div class="field-wrap relative">
                    <input class="field-input" name="employee_code" placeholder="Enter employee code" required
                        type="text" value="{{ old('employee_code', $employeeDetails->employee_code ?? '') }}" />
                </div>
                @error('employee_code')
                    <p class="err-msg">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="field-label">Department</label>
                <div class="field-wrap relative">
                    <select class="field-input tom-select" data-placeholder="Select Department" name="department_id">
                        <option value="">Select Department</option>
                        @foreach ($departments as $id => $name)
                            <option @selected(old('department_id', $employeeDetails->department_id ?? '') == $id) value="{{ $id }}">
                                {{ $name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @error('department_id')
                    <p class="err-msg">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="field-label">Designation</label>
                <div class="field-wrap relative">
                    <select class="field-input tom-select" data-placeholder="Select Designation" name="designation_id">
                        <option value="">Select Designation</option>
                        @foreach ($designations as $id => $name)
                            <option @selected(old('designation_id', $employeeDetails->designation_id ?? '') == $id) value="{{ $id }}">
                                {{ $name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @error('designation_id')
                    <p class="err-msg">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="mb-5 grid grid-cols-1 gap-5 md:grid-cols-3">
            <div>
                <label class="field-label">Gender</label>
                <div class="field-wrap relative">
                    <select class="field-input tom-select" data-placeholder="Select Gender" name="gender">
                        <option value="">Select Gender</option>
                        <option @selected(old('gender', $employeeDetails->gender ?? '') === 'male') value="male">Male</option>
                        <option @selected(old('gender', $employeeDetails->gender ?? '') === 'female') value="female">Female</option>
                        <option @selected(old('gender', $employeeDetails->gender ?? '') === 'other') value="other">Other</option>
                    </select>
                </div>
                @error('gender')
                    <p class="err-msg">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="field-label">Date of Birth</label>
                <div class="field-wrap relative">
                    <input class="field-input" name="date_of_birth" type="date"
                        value="{{ old('date_of_birth', $employeeDetails->date_of_birth ?? '') }}" />
                </div>
                @error('date_of_birth')
                    <p class="err-msg">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="field-label">Marital Status</label>
                <div class="field-wrap relative">
                    <select class="field-input tom-select" data-placeholder="Select Marital Status"
                        name="marital_status">
                        <option value="">Select Status</option>
                        <option @selected(old('marital_status', $employeeDetails->marital_status ?? '') === 'single') value="single">Single</option>
                        <option @selected(old('marital_status', $employeeDetails->marital_status ?? '') === 'married') value="married">Married</option>
                        <option @selected(old('marital_status', $employeeDetails->marital_status ?? '') === 'divorced') value="divorced">Divorced</option>
                        <option @selected(old('marital_status', $employeeDetails->marital_status ?? '') === 'widowed') value="widowed">Widowed</option>
                    </select>
                </div>
                @error('marital_status')
                    <p class="err-msg">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="mb-5 grid grid-cols-1 gap-5 md:grid-cols-3">
            <div>
                <label class="field-label">Alternate Phone</label>
                <div class="field-wrap relative">
                    <input class="field-input" name="alternate_phone" placeholder="Enter alternate phone" type="text"
                        value="{{ old('alternate_phone', $employeeDetails->alternate_phone ?? '') }}" />
                </div>
                @error('alternate_phone')
                    <p class="err-msg">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="field-label">Joining Date</label>
                <div class="field-wrap relative">
                    <input class="field-input" name="joining_date" type="date"
                        value="{{ old('joining_date', $employeeDetails->joining_date ?? '') }}" />
                </div>
                @error('joining_date')
                    <p class="err-msg">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="field-label">Probation End Date</label>
                <div class="field-wrap relative">
                    <input class="field-input" name="probation_end_date" type="date"
                        value="{{ old('probation_end_date', $employeeDetails->probation_end_date ?? '') }}" />
                </div>
                @error('probation_end_date')
                    <p class="err-msg">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="mb-5 grid grid-cols-1 gap-5 md:grid-cols-3">
            <div>
                <label class="field-label">Employment Type</label>
                <div class="field-wrap relative">
                    <select class="field-input tom-select" data-placeholder="Select Employment Type"
                        name="employment_type">
                        <option value="">Select Type</option>
                        <option @selected(old('employment_type', $employeeDetails->employment_type ?? '') === 'permanent') value="permanent">Permanent</option>
                        <option @selected(old('employment_type', $employeeDetails->employment_type ?? '') === 'contract') value="contract">Contract</option>
                        <option @selected(old('employment_type', $employeeDetails->employment_type ?? '') === 'intern') value="intern">Intern</option>
                        <option @selected(old('employment_type', $employeeDetails->employment_type ?? '') === 'freelancer') value="freelancer">Freelancer</option>
                    </select>
                </div>
                @error('employment_type')
                    <p class="err-msg">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="field-label">Reporting Manager</label>
                <div class="field-wrap relative">
                    <select class="field-input tom-select" data-placeholder="Select Manager"
                        name="reporting_manager_id">
                        <option value="">Select Manager</option>
                        @foreach ($managers as $mgr)
                            <option @selected(old('reporting_manager_id', $employeeDetails->reporting_manager_id ?? '') == $mgr->id) value="{{ $mgr->id }}">
                                {{ $mgr->full_name }} ({{ $mgr->email }})
                            </option>
                        @endforeach
                    </select>
                </div>
                @error('reporting_manager_id')
                    <p class="err-msg">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="field-label">Status <span class="text-red-400">*</span></label>
                <div class="field-wrap relative">
                    <select class="field-input tom-select" data-placeholder="Select Status" name="status" required>
                        <option value="">Select Status</option>
                        <option @selected(old('status', $employeeDetails->status ?? '') === 'active') value="active">Active</option>
                        <option @selected(old('status', $employeeDetails->status ?? '') === 'probation') value="probation">Probation</option>
                        <option @selected(old('status', $employeeDetails->status ?? '') === 'on-notice') value="on-notice">On Notice</option>
                        <option @selected(old('status', $employeeDetails->status ?? '') === 'resigned') value="resigned">Resigned</option>
                        <option @selected(old('status', $employeeDetails->status ?? '') === 'terminated') value="terminated">Terminated</option>
                        <option @selected(old('status', $employeeDetails->status ?? '') === 'inactive') value="inactive">Inactive</option>
                    </select>
                </div>
                @error('status')
                    <p class="err-msg">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="mb-5 grid grid-cols-1 gap-5 md:grid-cols-3">
            <div>
                <label class="field-label">Current Salary</label>
                <div class="field-wrap relative">
                    <span aria-hidden="true" class="field-icon">
                        $
                    </span>
                    <input class="field-input" min="0" name="current_salary" placeholder="0.00"
                        step="0.01" style="padding-left: 33px" type="number"
                        value="{{ old('current_salary', $employeeDetails->current_salary ?? '') }}" />
                </div>
                @error('current_salary')
                    <p class="err-msg">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <hr class="my-6 border-gray-200 dark:border-gray-800" />

        <div class="mb-5">
            <label class="field-label">Address</label>
            <div class="field-wrap relative">
                <textarea class="field-input min-h-[80px]" name="address" placeholder="Enter full address">{{ old('address', $employeeDetails->address ?? '') }}</textarea>
            </div>
            @error('address')
                <p class="err-msg">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid grid-cols-1 gap-5 md:grid-cols-4">
            <div>
                <label class="field-label">City</label>
                <div class="field-wrap relative">
                    <input class="field-input" name="city" placeholder="City" type="text"
                        value="{{ old('city', $employeeDetails->city ?? '') }}" />
                </div>
                @error('city')
                    <p class="err-msg">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="field-label">State</label>
                <div class="field-wrap relative">
                    <input class="field-input" name="state" placeholder="State" type="text"
                        value="{{ old('state', $employeeDetails->state ?? '') }}" />
                </div>
                @error('state')
                    <p class="err-msg">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="field-label">Country</label>
                <div class="field-wrap relative">
                    <input class="field-input" name="country" placeholder="Country" type="text"
                        value="{{ old('country', $employeeDetails->country ?? '') }}" />
                </div>
                @error('country')
                    <p class="err-msg">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="field-label">Postal Code</label>
                <div class="field-wrap relative">
                    <input class="field-input" name="postal_code" placeholder="Postal Code" type="text"
                        value="{{ old('postal_code', $employeeDetails->postal_code ?? '') }}" />
                </div>
                @error('postal_code')
                    <p class="err-msg">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    <div class="mt-5 flex items-center justify-end gap-3">
        <button class="btn-primary" type="submit">
            Save Changes
        </button>
    </div>
</form>
