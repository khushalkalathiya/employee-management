<form action="{{ route('employees.basic-information.update', $user->id) }}" enctype="multipart/form-data" method="POST">
    @csrf
    @method('put')

    <div class="card" style="padding:20px">

        <div class="mb-5 grid grid-cols-1 gap-5 md:grid-cols-5">

            <div class="image-upload">
                <input accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp" class="image-input hidden"
                    name="avatar" type="file">

                <div
                    class="upload-box relative flex h-40 cursor-pointer flex-col items-center justify-center overflow-hidden rounded-2xl border-2 border-dashed border-gray-300 bg-white transition-all duration-200 hover:border-blue-500 hover:bg-blue-50 dark:border-gray-700 dark:bg-gray-900 dark:hover:border-blue-500 dark:hover:bg-blue-500/10">

                    <!-- Placeholder -->
                    <div
                        class="placeholder {{ $user->avatar ? 'hidden' : '' }} flex flex-col items-center gap-2 text-center">

                        <svg class="h-8 w-8 text-gray-400 dark:text-gray-500" fill="none" stroke-width="1.8"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M12 16V4m0 0l-4 4m4-4l4 4M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2"
                                stroke-linecap="round" stroke-linejoin="round" />
                        </svg>

                        <div>
                            <p class="text-sm font-medium text-gray-700 dark:text-gray-200">
                                Upload Image
                            </p>

                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                PNG, JPG up to 2MB
                            </p>
                        </div>

                    </div>

                    <!-- Preview -->
                    <img alt="Preview"
                        class="preview {{ $user->avatar ? '' : 'hidden' }} absolute inset-0 h-full w-full object-cover"
                        src="{{ $user->avatar ?? '' }}">

                    <!-- Remove -->
                    <button
                        class="remove-btn absolute right-2 top-2 flex hidden h-5 w-5 items-center justify-center rounded-full bg-red-500 text-sm font-bold text-white shadow-lg transition hover:bg-red-600"
                        type="button">
                        ✕
                    </button>

                </div>
                @error('avatar')
                    <p class="err-msg">{{ $message }}</p>
                @enderror
            </div>

            <div class="col-span-4 grid grid-cols-1 gap-5 md:grid-cols-2">
                <div>
                    <label class="field-label">First Name <span class="text-red-400">*</span></label>

                    <div class="field-wrap relative">
                        <input class="field-input" name="first_name" placeholder="Enter first name" required
                            type="text" value="{{ old('first_name') ?? ($user->first_name ?? '') }}" />
                    </div>

                    @error('first_name')
                        <p class="err-msg">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="field-label">Last Name <span class="text-red-400">*</span></label>

                    <div class="field-wrap relative">
                        <input class="field-input" name="last_name" placeholder="Enter last name" required
                            type="text" value="{{ old('last_name') ?? ($user->last_name ?? '') }}" />
                    </div>

                    @error('last_name')
                        <p class="err-msg">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="field-label">Email Address <span class="text-red-400">*</span></label>

                    <div class="field-wrap relative">
                        <input class="field-input" name="email" placeholder="john@example.com" required type="email"
                            value="{{ old('email') ?? ($user->email ?? '') }}" />
                    </div>

                    @error('email')
                        <p class="err-msg">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="field-label">Phone Number <span class="text-red-400">*</span></label>

                    <div class="field-wrap relative">
                        <input class="field-input" name="phone" placeholder="+91 XXXXX XXXXX" required type="text"
                            value="{{ old('phone') ?? ($user->phone ?? '') }}" />
                    </div>

                    @error('phone')
                        <p class="err-msg">{{ $message }}</p>
                    @enderror
                </div>
            </div>

        </div>

        @if (authId() != $user->id)
            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                <div>
                    <label class="field-label">Joining Date <span class="text-red-400">*</span></label>

                    <div class="field-wrap relative">
                        <input class="field-input" name="joining_date" required type="date"
                            value="{{ old('joining_date') ?? ($user->joining_date ?? '') }}" />
                    </div>

                    @error('joining_date')
                        <p class="err-msg">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="field-label">Role <span class="text-red-400">*</span></label>
                    <div class="field-wrap relative">
                        <select
                            class="field-input tom-select appearance-none border border-[var(--border)] bg-[var(--card)] text-[var(--text)]"
                            data-placeholder="Select Role" name="role" required>
                            @foreach ($roles as $name => $displayName)
                                <option @selected(old('role', $user->role) === $name) value="{{ $name }}">
                                    {{ $displayName ?? '-' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @error('role')
                        <p class="err-msg">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        @endif
    </div>

    <div class="mt-5 flex items-center justify-end gap-3">
        <button class="btn-primary" type="submit">
            Save Changes
        </button>
    </div>
</form>
