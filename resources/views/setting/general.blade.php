<x-app-layout>
    @if ($errors->any())
        <div class="mb-4 rounded-lg border border-red-200 bg-red-50 p-4 text-red-600">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="settings-layout">
        <div class="settings-mobile-header">
            <button class="settings-menu-btn" onclick="openSubSidebarMenu()" type="button">
                <svg fill="none" stroke-width="2" stroke="currentColor" viewBox="0 0 24 24">
                    <line x1="3" x2="21" y1="6" y2="6"></line>
                    <line x1="3" x2="21" y1="12" y2="12"></line>
                    <line x1="3" x2="21" y1="18" y2="18"></line>
                </svg>
            </button>
            <h2 class="settings-page-title">General Settings</h2>
        </div>

        <aside class="sub-side-menu">
            @include('setting.sidebar')
        </aside>

        <!-- Content -->
        <div class="settings-content">
            <div class="mx-auto max-w-3xl">
                <form
                    @can('settings.general.edit')
                    action="{{ route('settings.general.update') }}"
                    @endcan
                    enctype="multipart/form-data" method="POST">
                    @csrf

                    <!-- Page title -->
                    <h1 class="mb-5 text-2xl font-bold text-gray-900 dark:text-white">
                        General Settings
                    </h1>

                    {{-- ── App Identity ───────────────────────────────────────── --}}
                    <div
                        class="mb-6 rounded-3xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">

                        <h2 class="mb-5 text-lg font-semibold text-gray-900 dark:text-white">
                            App Identity
                        </h2>

                        <div class="grid gap-5 md:grid-cols-2">

                            {{-- App Name --}}
                            <div>
                                <label class="field-label" for="app_name">
                                    App Name <span class="text-red-400">*</span>
                                </label>
                                <div class="field-wrap">
                                    <input class="field-input" id="app_name" name="app_name"
                                        placeholder="e.g. PeopleCore" type="text"
                                        value="{{ old('app_name', $settings['app_name'] ?? config('app.name')) }}">
                                </div>
                                @error('app_name')
                                    <p class="err-msg">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- App Email --}}
                            <div>
                                <label class="field-label" for="app_email">
                                    App Email <span class="text-red-400">*</span>
                                </label>
                                <div class="field-wrap">
                                    <input class="field-input" id="app_email" name="app_email"
                                        placeholder="e.g. admin@example.com" type="email"
                                        value="{{ old('app_email', $settings['app_email'] ?? config('mail.from.address')) }}">
                                </div>
                                @error('app_email')
                                    <p class="err-msg">{{ $message }}</p>
                                @enderror
                            </div>

                        </div>
                    </div>

                    {{-- ── App Logo ───────────────────────────────────────────── --}}
                    <div
                        class="mb-6 rounded-3xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">

                        <h2 class="mb-1 text-lg font-semibold text-gray-900 dark:text-white">
                            App Logo
                        </h2>
                        <p class="mb-5 text-sm text-gray-400 dark:text-gray-500">
                            Recommended size: 200 × 60 px. Accepts PNG, JPG, SVG or WebP (max 2 MB).
                        </p>

                        <div class="flex flex-col gap-5 sm:flex-row sm:items-start">

                            {{-- Preview --}}
                            <div class="flex-shrink-0">
                                <div class="flex h-20 w-44 items-center justify-center overflow-hidden rounded-2xl border-2 border-dashed border-gray-200 bg-gray-50 dark:border-gray-700 dark:bg-gray-800"
                                    id="logoPreviewWrap">
                                    @if (!empty($settings['app_logo']))
                                        <img alt="App Logo" class="h-full w-full object-contain p-2" id="logoPreview"
                                            src="{{ $settings['app_logo'] }}">
                                    @else
                                        <svg class="h-8 w-8 text-gray-300 dark:text-gray-600" fill="none"
                                            id="logoPlaceholder" stroke-width="1.5" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path
                                                d="M2.25 15.75l5.16-5.16a2.25 2.25 0 013.18 0l5.16 5.16m-1.5-1.5l1.41-1.41a2.25 2.25 0 013.18 0l2.91 2.91"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                            <path
                                                d="M3.75 19.5h16.5M3.75 3.75h16.5A1.5 1.5 0 0121.75 5.25v13.5a1.5 1.5 0 01-1.5 1.5H3.75a1.5 1.5 0 01-1.5-1.5V5.25a1.5 1.5 0 011.5-1.5z"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <img alt="App Logo" class="hidden h-full w-full object-contain p-2"
                                            id="logoPreview" src="">
                                    @endif
                                </div>
                            </div>

                            {{-- Upload controls --}}
                            <div class="flex flex-1 flex-col gap-3">
                                <label
                                    class="flex cursor-pointer items-center gap-3 rounded-2xl border border-dashed border-gray-300 bg-gray-50 px-5 py-4 transition hover:border-blue-400 hover:bg-blue-50/50 dark:border-gray-700 dark:bg-gray-800/50 dark:hover:border-blue-500 dark:hover:bg-blue-500/5"
                                    for="app_logo">
                                    <span
                                        class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-xl bg-blue-100 text-blue-600 dark:bg-blue-500/10 dark:text-blue-400">
                                        <svg class="h-5 w-5" fill="none" stroke-width="2" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path
                                                d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </span>
                                    <div class="min-w-0">
                                        <p class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                            Click to upload logo
                                        </p>
                                        <p class="mt-0.5 truncate text-xs text-gray-400 dark:text-gray-500"
                                            id="logoFileName">
                                            PNG, JPG, SVG or WebP — max 2 MB
                                        </p>
                                    </div>
                                    <input accept="image/png,image/jpeg,image/svg+xml,image/webp" class="sr-only"
                                        id="app_logo" name="app_logo" type="file">
                                </label>

                                @error('app_logo')
                                    <p class="err-msg">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- ── App Favicon ────────────────────────────────────────── --}}
                    <div
                        class="mb-6 rounded-3xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">

                        <h2 class="mb-1 text-lg font-semibold text-gray-900 dark:text-white">
                            App Favicon
                        </h2>
                        <p class="mb-5 text-sm text-gray-400 dark:text-gray-500">
                            Recommended size: 32 × 32 px. Accepts PNG, ICO, SVG or WebP (max 512 KB).
                        </p>

                        <div class="flex flex-col gap-5 sm:flex-row sm:items-start">

                            {{-- Preview --}}
                            <div class="flex-shrink-0">
                                <div class="flex h-16 w-16 items-center justify-center overflow-hidden rounded-2xl border-2 border-dashed border-gray-200 bg-gray-50 dark:border-gray-700 dark:bg-gray-800"
                                    id="faviconPreviewWrap">
                                    @if (!empty($settings['app_favicon']))
                                        <img alt="Favicon" class="h-full w-full object-contain p-1"
                                            id="faviconPreview" src="{{ $settings['app_favicon'] }}">
                                    @else
                                        <svg class="h-6 w-6 text-gray-300 dark:text-gray-600" fill="none"
                                            id="faviconPlaceholder" stroke-width="1.5" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path
                                                d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <img alt="Favicon" class="hidden h-full w-full object-contain p-1"
                                            id="faviconPreview" src="">
                                    @endif
                                </div>
                            </div>

                            {{-- Upload controls --}}
                            <div class="flex flex-1 flex-col gap-3">
                                <label
                                    class="flex cursor-pointer items-center gap-3 rounded-2xl border border-dashed border-gray-300 bg-gray-50 px-5 py-4 transition hover:border-blue-400 hover:bg-blue-50/50 dark:border-gray-700 dark:bg-gray-800/50 dark:hover:border-blue-500 dark:hover:bg-blue-500/5"
                                    for="app_favicon">
                                    <span
                                        class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-xl bg-blue-100 text-blue-600 dark:bg-blue-500/10 dark:text-blue-400">
                                        <svg class="h-5 w-5" fill="none" stroke-width="2" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path
                                                d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </span>
                                    <div class="min-w-0">
                                        <p class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                            Click to upload favicon
                                        </p>
                                        <p class="mt-0.5 truncate text-xs text-gray-400 dark:text-gray-500"
                                            id="faviconFileName">
                                            PNG, ICO, SVG or WebP — max 512 KB
                                        </p>
                                    </div>
                                    <input accept="image/png,image/jpeg,image/x-icon,image/svg+xml,image/webp"
                                        class="sr-only" id="app_favicon" name="app_favicon" type="file">
                                </label>

                                @error('app_favicon')
                                    <p class="err-msg">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- ── Save button ────────────────────────────────────────── --}}
                    @can('settings.general.edit')
                        <div class="flex items-center justify-end gap-3">
                            <button class="btn-primary" type="submit">
                                Save Settings
                            </button>
                        </div>
                    @endcan

                </form>
            </div>
        </div>
    </div>

    <div class="settings-overlay" id="settingsOverlay" onclick="closeSubSidebarMenu()"></div>
    <div class="settings-mobile-menu" id="settingsMobileMenu">
        <div class="settings-mobile-menu-container">
            <button class="settings-mobile-close" onclick="closeSubSidebarMenu()" type="button">✕</button>
            <div class="settings-mobile-scroll">
                @include('setting.sidebar')
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // ── Live preview helper ───────────────────────────────────────────────
            function setupImagePreview(inputId, previewId, fileNameId, placeholderId) {
                const input = document.getElementById(inputId);
                const preview = document.getElementById(previewId);
                const fileNameEl = document.getElementById(fileNameId);
                const placeholder = document.getElementById(placeholderId);

                if (!input || !preview) return;

                input.addEventListener('change', () => {
                    const file = input.files[0];
                    if (!file) return;

                    // Show selected filename
                    if (fileNameEl) fileNameEl.textContent = file.name;

                    const reader = new FileReader();
                    reader.onload = (e) => {
                        preview.src = e.target.result;
                        preview.classList.remove('hidden');
                        if (placeholder) placeholder.classList.add('hidden');
                    };
                    reader.readAsDataURL(file);
                });
            }

            setupImagePreview('app_logo', 'logoPreview', 'logoFileName', 'logoPlaceholder');
            setupImagePreview('app_favicon', 'faviconPreview', 'faviconFileName', 'faviconPlaceholder');
        </script>
    @endpush

</x-app-layout>
