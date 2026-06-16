@php
    $family = $employee->employee ? $employee->employee->familyInformation : null;
@endphp

<form action="{{ route('employees.family-information.update', $employee->id) }}" method="POST">
    @csrf
    @method('put')

    <div class="card" style="padding:20px">
        <h3 class="mb-4 text-sm font-semibold uppercase tracking-wider text-gray-400">Parents Information</h3>
        <div class="mb-6 grid grid-cols-1 gap-5 md:grid-cols-2">
            <div>
                <label class="field-label">Father's Name</label>
                <div class="field-wrap relative">
                    <input class="field-input" name="father_name" placeholder="Enter father's name" type="text"
                        value="{{ old('father_name', $family->father_name ?? '') }}" />
                </div>
                @error('father_name')
                    <p class="err-msg">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="field-label">Father's Phone</label>
                <div class="field-wrap relative">
                    <input class="field-input" name="father_phone" placeholder="Enter father's phone" type="text"
                        value="{{ old('father_phone', $family->father_phone ?? '') }}" />
                </div>
                @error('father_phone')
                    <p class="err-msg">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="field-label">Mother's Name</label>
                <div class="field-wrap relative">
                    <input class="field-input" name="mother_name" placeholder="Enter mother's name" type="text"
                        value="{{ old('mother_name', $family->mother_name ?? '') }}" />
                </div>
                @error('mother_name')
                    <p class="err-msg">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="field-label">Mother's Phone</label>
                <div class="field-wrap relative">
                    <input class="field-input" name="mother_phone" placeholder="Enter mother's phone" type="text"
                        value="{{ old('mother_phone', $family->mother_phone ?? '') }}" />
                </div>
                @error('mother_phone')
                    <p class="err-msg">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <hr class="my-6 border-gray-200 dark:border-gray-800" />

        <h3 class="mb-4 text-sm font-semibold uppercase tracking-wider text-gray-400">Spouse Information</h3>
        <div class="mb-6 grid grid-cols-1 gap-5 md:grid-cols-2">
            <div>
                <label class="field-label">Spouse Name</label>
                <div class="field-wrap relative">
                    <input class="field-input" name="spouse_name" placeholder="Enter spouse's name" type="text"
                        value="{{ old('spouse_name', $family->spouse_name ?? '') }}" />
                </div>
                @error('spouse_name')
                    <p class="err-msg">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="field-label">Spouse Phone</label>
                <div class="field-wrap relative">
                    <input class="field-input" name="spouse_phone" placeholder="Enter spouse's phone" type="text"
                        value="{{ old('spouse_phone', $family->spouse_phone ?? '') }}" />
                </div>
                @error('spouse_phone')
                    <p class="err-msg">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <hr class="my-6 border-gray-200 dark:border-gray-800" />

        <h3 class="mb-4 text-sm font-semibold uppercase tracking-wider text-red-400">Emergency Contact Information</h3>
        <div class="grid grid-cols-1 gap-5 md:grid-cols-3">
            <div>
                <label class="field-label">Contact Name <span class="text-red-400">*</span></label>
                <div class="field-wrap relative">
                    <input class="field-input" name="emergency_contact_name" placeholder="Contact Name" required
                        type="text"
                        value="{{ old('emergency_contact_name', $family->emergency_contact_name ?? '') }}" />
                </div>
                @error('emergency_contact_name')
                    <p class="err-msg">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="field-label">Relation <span class="text-red-400">*</span></label>
                <div class="field-wrap relative">
                    <input class="field-input" name="emergency_contact_relation"
                        placeholder="Relation (e.g. Brother, Wife)" required type="text"
                        value="{{ old('emergency_contact_relation', $family->emergency_contact_relation ?? '') }}" />
                </div>
                @error('emergency_contact_relation')
                    <p class="err-msg">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="field-label">Phone Number <span class="text-red-400">*</span></label>
                <div class="field-wrap relative">
                    <input class="field-input" name="emergency_contact_phone" placeholder="Phone Number" required
                        type="text"
                        value="{{ old('emergency_contact_phone', $family->emergency_contact_phone ?? '') }}" />
                </div>
                @error('emergency_contact_phone')
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
