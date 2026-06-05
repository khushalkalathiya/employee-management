<x-app-layout>

    <div style="padding:20px;border-bottom:1px solid var(--border)">
        <div class="section-title">Create User</div>
        <div class="section-sub">Add a new system user</div>
    </div>

    <form action="{{ route('users.store') }}" enctype="multipart/form-data" method="POST">
        @csrf
        <div class="card" style="padding:20px">

            <div class="mb-5 grid grid-cols-1 gap-5 md:grid-cols-5">

                <div class="image-upload">
                    <input accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp" class="image-input hidden"
                        name="avatar" type="file">

                    <div
                        class="upload-box relative flex h-40 cursor-pointer flex-col items-center justify-center overflow-hidden rounded-2xl border-2 border-dashed border-gray-300 bg-white transition-all duration-200 hover:border-blue-500 hover:bg-blue-50 dark:border-gray-700 dark:bg-gray-900 dark:hover:border-blue-500 dark:hover:bg-blue-500/10">

                        <!-- Placeholder -->
                        <div class="placeholder flex flex-col items-center gap-2 text-center">

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
                        <img alt="Preview" class="preview absolute inset-0 hidden h-full w-full object-cover">

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
                                type="text" value="{{ old('first_name') }}" />
                        </div>

                        @error('first_name')
                            <p class="err-msg">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="field-label">Last Name <span class="text-red-400">*</span></label>

                        <div class="field-wrap relative">
                            <input class="field-input" name="last_name" placeholder="Enter last name" required
                                type="text" value="{{ old('last_name') }}" />
                        </div>

                        @error('last_name')
                            <p class="err-msg">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="field-label">Email Address <span class="text-red-400">*</span></label>

                        <div class="field-wrap relative">
                            <input class="field-input" name="email" placeholder="john@example.com" required
                                type="email" value="{{ old('email') }}" />
                        </div>

                        @error('email')
                            <p class="err-msg">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="field-label">Phone Number <span class="text-red-400">*</span></label>

                        <div class="field-wrap relative">
                            <input class="field-input" name="phone" placeholder="+91 XXXXX XXXXX" required
                                type="text" value="{{ old('phone') }}" />
                        </div>

                        @error('phone')
                            <p class="err-msg">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

            </div>

            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                <div>
                    <label class="field-label">Joining Date <span class="text-red-400">*</span></label>

                    <div class="field-wrap relative">
                        <input class="field-input" name="joining_date" required type="date"
                            value="{{ old('joining_date') }}" />
                    </div>

                    @error('joining_date')
                        <p class="err-msg">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="field-label">Role <span class="text-red-400">*</span></label>
                    <div class="field-wrap relative">
                        <select
                            class="field-input appearance-none border border-[var(--border)] bg-[var(--card)] text-[var(--text)]"
                            name="role" required>
                            <option value="">Select Role</option>

                            @foreach ($roles as $id => $role)
                                <option @selected(old('role') == $role) value="{{ $role }}">
                                    {{ ucfirst($role) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @error('role')
                        <p class="err-msg">{{ $message }}</p>
                    @enderror
                </div>


                <div class="password-wrapper">

                    <label class="field-label">
                        Password <span class="text-red-400">*</span>
                    </label>

                    <div class="field-wrap relative">

                        <input class="field-input password-input" name="password" placeholder="Enter password" required
                            type="password" />

                        <button class="eye-toggle" onclick="togglePassword(this)" type="button">
                            <svg class="eye-show" fill="none" height="18" id="eyeShow" stroke-width="2"
                                stroke="currentColor" viewBox="0 0 24 24" width="18">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8S1 12 1 12z" />
                                <circle cx="12" cy="12" r="3" />
                            </svg>
                            <svg class="eye-hide" fill="none" height="18" id="eyeHide" stroke-width="2"
                                stroke="currentColor" style="display:none" viewBox="0 0 24 24" width="18">
                                <path
                                    d="M17.94 17.94A10.94 10.94 0 0 1 12 20C5 20 1 12 1 12a21.8 21.8 0 0 1 5.06-6.94" />
                                <path d="M9.9 4.24A10.94 10.94 0 0 1 12 4c7 0 11 8 11 8a21.8 21.8 0 0 1-4.24 5.94" />
                                <path d="M1 1l22 22" />
                            </svg>

                        </button>

                    </div>
                    @error('password')
                        <p class="err-msg">{{ $message }}</p>
                    @enderror

                </div>

                <div class="password-wrapper">

                    <label class="field-label">
                        Confirm Password <span class="text-red-400">*</span>
                    </label>

                    <div class="field-wrap relative">

                        <input class="field-input password-input" name="password_confirmation"
                            placeholder="Confirm password" required type="password" />

                        <button class="eye-toggle" onclick="togglePassword(this)" type="button">
                            <svg class="eye-show" fill="none" height="18" id="eyeShow" stroke-width="2"
                                stroke="currentColor" viewBox="0 0 24 24" width="18">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8S1 12 1 12z" />
                                <circle cx="12" cy="12" r="3" />
                            </svg>
                            <svg class="eye-hide" fill="none" height="18" id="eyeHide" stroke-width="2"
                                stroke="currentColor" style="display:none" viewBox="0 0 24 24" width="18">
                                <path
                                    d="M17.94 17.94A10.94 10.94 0 0 1 12 20C5 20 1 12 1 12a21.8 21.8 0 0 1 5.06-6.94" />
                                <path d="M9.9 4.24A10.94 10.94 0 0 1 12 4c7 0 11 8 11 8a21.8 21.8 0 0 1-4.24 5.94" />
                                <path d="M1 1l22 22" />
                            </svg>
                        </button>

                    </div>

                </div>


                <div>

                    <label class="field-label mb-3 block">
                        Gender
                    </label>

                    <div class="flex flex-wrap gap-3">

                        {{-- Male --}}
                        <div>
                            <input {{ old('gender') === 'male' ? 'checked' : '' }} class="peer hidden"
                                id="gender_male" name="gender" type="radio" value="male">

                            <label
                                class="flex cursor-pointer items-center gap-2 rounded-lg border border-[var(--border)] px-4 py-2 text-sm font-medium transition-all hover:border-blue-500 peer-checked:border-blue-600 peer-checked:bg-blue-500 peer-checked:text-white"
                                for="gender_male">
                                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M15 3h6v6h-2V6.41l-4.29 4.3a6 6 0 1 1-1.41-1.42L17.59 5H15V3z" />
                                </svg>

                                Male
                            </label>
                        </div>

                        {{-- Female --}}
                        <div>
                            <input {{ old('gender') === 'female' ? 'checked' : '' }} class="peer hidden"
                                id="gender_female" name="gender" type="radio" value="female">

                            <label
                                class="flex cursor-pointer items-center gap-2 rounded-lg border border-[var(--border)] px-4 py-2 text-sm font-medium transition-all hover:border-pink-500 peer-checked:border-pink-600 peer-checked:bg-pink-500 peer-checked:text-white"
                                for="gender_female">
                                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2a5 5 0 0 0-1 9.9V14H8v2h3v3h2v-3h3v-2h-3v-2.1A5 5 0 0 0 12 2z" />
                                </svg>

                                Female
                            </label>
                        </div>

                        {{-- Other --}}
                        <div>
                            <input {{ old('gender') === 'other' ? 'checked' : '' }} class="peer hidden"
                                id="gender_other" name="gender" type="radio" value="other">

                            <label
                                class="flex cursor-pointer items-center gap-2 rounded-lg border border-[var(--border)] px-4 py-2 text-sm font-medium transition-all hover:border-purple-500 peer-checked:border-purple-600 peer-checked:bg-purple-500 peer-checked:text-white"
                                for="gender_other">
                                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M12 2a5 5 0 0 0-1 9.9V14H9v2h2v2l-2 2 1.4 1.4L12 20.8l1.6 1.6L15 20l-2-2v-2h2v-2h-2v-2.1A5 5 0 0 0 12 2z" />
                                </svg>

                                Other
                            </label>
                        </div>

                    </div>

                    @error('gender')
                        <p class="err-msg mt-2">{{ $message }}</p>
                    @enderror

                </div>
            </div>
        </div>

        <div class="mt-5 flex items-center justify-end gap-3">
            <a class="btn-ghost no-underline" href="{{ route('users.index') }}">
                Cancel
            </a>
            <button class="btn-primary" type="submit">
                Create User
            </button>
        </div>
    </form>

</x-app-layout>
