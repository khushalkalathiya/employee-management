<form action="{{ route('employees.bank-account.update', $employee->id) }}" method="POST">
    @csrf
    @method('put')

    <div class="card" style="padding:20px">
        <div class="mb-5 grid grid-cols-1 gap-5 md:grid-cols-2">
            <div>
                <label class="field-label">Bank Name <span class="text-red-400">*</span></label>
                <div class="field-wrap relative">
                    <input class="field-input" name="bank_name" placeholder="Enter bank name" required type="text"
                        value="{{ old('bank_name', $bankAccount->bank_name ?? '') }}" />
                </div>
                @error('bank_name')
                    <p class="err-msg">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="field-label">Account Holder Name <span class="text-red-400">*</span></label>
                <div class="field-wrap relative">
                    <input class="field-input" name="account_holder_name" placeholder="Enter account holder name" required type="text"
                        value="{{ old('account_holder_name', $bankAccount->account_holder_name ?? $employee->full_name) }}" />
                </div>
                @error('account_holder_name')
                    <p class="err-msg">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="mb-5 grid grid-cols-1 gap-5 md:grid-cols-2">
            <div>
                <label class="field-label">Account Number <span class="text-red-400">*</span></label>
                <div class="field-wrap relative">
                    <input class="field-input" name="account_number" placeholder="Enter account number" required type="text"
                        value="{{ old('account_number', $bankAccount->account_number ?? '') }}" />
                </div>
                @error('account_number')
                    <p class="err-msg">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="field-label">IFSC Code <span class="text-red-400">*</span></label>
                <div class="field-wrap relative">
                    <input class="field-input" name="ifsc_code" placeholder="Enter IFSC code" required type="text"
                        value="{{ old('ifsc_code', $bankAccount->ifsc_code ?? '') }}" />
                </div>
                @error('ifsc_code')
                    <p class="err-msg">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
            <div>
                <label class="field-label">Branch Name</label>
                <div class="field-wrap relative">
                    <input class="field-input" name="branch_name" placeholder="Enter branch name" type="text"
                        value="{{ old('branch_name', $bankAccount->branch_name ?? '') }}" />
                </div>
                @error('branch_name')
                    <p class="err-msg">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="field-label">UPI ID</label>
                <div class="field-wrap relative">
                    <input class="field-input" name="upi_id" placeholder="e.g. username@upi" type="text"
                        value="{{ old('upi_id', $bankAccount->upi_id ?? '') }}" />
                </div>
                @error('upi_id')
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
