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
            <h2 class="settings-page-title">
                Work Schedule
            </h2>
        </div>

        <aside class="sub-side-menu">
            @include('setting.sidebar')
        </aside>

        <!-- Content -->
        <div class="settings-content">
            <div class="mx-auto max-w-7xl">
                <form @can('settings.work_schedule.edit') action="{{ route('settings.work-schedule.update') }}" @endcan
                    method="POST">
                    @csrf
                    <!-- Header -->
                    <h1 class="mb-5 text-2xl font-bold text-gray-900 dark:text-white">
                        Work Schedule
                    </h1>

                    <!-- Attendance Settings -->
                    <div
                        class="mb-6 rounded-3xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">

                        <h2 class="mb-5 text-lg font-semibold text-gray-900 dark:text-white">
                            Attendance Rules
                        </h2>

                        <div class="mb-6 grid gap-5 md:grid-cols-2">
                            <!-- Fixed Timing Mode Toggle -->
                            <div
                                class="flex items-center justify-between rounded-2xl border border-gray-200 bg-gray-50/50 p-4 dark:border-gray-800 dark:bg-gray-950/20">
                                <div class="flex flex-col">
                                    <span class="text-sm font-semibold text-gray-900 dark:text-white">Fixed Timing
                                        Mode</span>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">Enable to enforce daily
                                        start/end
                                        shift hours, late check-ins, and early clock-in limits. Disable for flexible
                                        timing.</span>
                                </div>
                                <label class="inline-flex cursor-pointer items-center">
                                    <input @checked((old('timing_mode') ?? ($settings['timing_mode'] ?? 'fixed')) === 'fixed') class="peer sr-only" id="timingModeToggle"
                                        onchange="toggleTimingMode(this.checked)" type="checkbox" value="1">
                                    <div
                                        class="peer relative h-6 w-11 rounded-full bg-gray-300 transition-all after:absolute after:start-[2px] after:top-[2px] after:h-5 after:w-5 after:rounded-full after:bg-white after:transition-all after:content-[''] peer-checked:bg-blue-600 peer-checked:after:translate-x-full dark:bg-gray-700 dark:peer-checked:bg-blue-500">
                                    </div>
                                </label>
                                <!-- Hidden input to submit timing_mode value -->
                                <input id="timingModeValue" name="timing_mode" type="hidden"
                                    value="{{ old('timing_mode') ?? ($settings['timing_mode'] ?? 'fixed') }}">
                            </div>

                            <!-- Fixed Break Mode Toggle -->
                            <div
                                class="flex items-center justify-between rounded-2xl border border-gray-200 bg-gray-50/50 p-4 dark:border-gray-800 dark:bg-gray-950/20">
                                <div class="flex flex-col">
                                    <span class="text-sm font-semibold text-gray-900 dark:text-white">Fixed Break
                                        Mode</span>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">Enable to enforce daily
                                        scheduled
                                        break start/end times and notification warnings. Disable for flexible
                                        breaks.</span>
                                </div>
                                <label class="inline-flex cursor-pointer items-center">
                                    <input @checked((old('break_mode') ?? ($settings['break_mode'] ?? 'fixed')) === 'fixed') class="peer sr-only" id="breakModeToggle"
                                        onchange="toggleBreakMode(this.checked)" type="checkbox" value="1">
                                    <div
                                        class="peer relative h-6 w-11 rounded-full bg-gray-300 transition-all after:absolute after:start-[2px] after:top-[2px] after:h-5 after:w-5 after:rounded-full after:bg-white after:transition-all after:content-[''] peer-checked:bg-blue-600 peer-checked:after:translate-x-full dark:bg-gray-700 dark:peer-checked:bg-blue-500">
                                    </div>
                                </label>
                                <!-- Hidden input to submit break_mode value -->
                                <input id="breakModeValue" name="break_mode" type="hidden"
                                    value="{{ old('break_mode') ?? ($settings['break_mode'] ?? 'fixed') }}">
                            </div>
                        </div>


                        <div class="mb-6 grid gap-5 md:grid-cols-2">

                            <!-- Late Allowance (Always Displayed) -->
                            <div>
                                <label class="field-label">
                                    Late Allowance in Min
                                    <span class="text-red-400">*</span>
                                </label>
                                <div class="field-wrap relative">
                                    <input class="field-input" name="late_allowance_minutes" type="number"
                                        value="{{ old('late_allowance_minutes') ?? ($settings['late_allowance_minutes'] ?? 10) }}">
                                    <span class="absolute right-10 top-1/2 -translate-y-1/2 text-xs text-gray-400">
                                        Min
                                    </span>
                                </div>
                                <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">
                                    If you add 0, the system will not ask for a late reason. If greater than 0, a late
                                    reason modal is required on clock-in on a working day.
                                </p>
                                @error('late_allowance_minutes')
                                    <p class="err-msg">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Early Clock In Min (Conditional: Fixed Timing Only) -->
                            <div class="timing-rule-fixed">
                                <label class="field-label">
                                    Early Clock In Min
                                    <span class="text-red-400">*</span>
                                </label>
                                <div class="field-wrap relative">
                                    <input class="field-input" min="0" name="early_clock_in_minutes"
                                        type="number"
                                        value="{{ old('early_clock_in_minutes') ?? ($settings['early_clock_in_minutes'] ?? 15) }}">
                                    <span class="absolute right-10 top-1/2 -translate-y-1/2 text-xs text-gray-400">
                                        Min
                                    </span>
                                </div>
                                <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">
                                    How many minutes before shift start an employee can clock in.
                                </p>
                                @error('early_clock_in_minutes')
                                    <p class="err-msg">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Early Clock Out Min (Conditional: Fixed Timing Only) -->
                            <div class="timing-rule-fixed">
                                <label class="field-label">
                                    Early Clock Out Min
                                    <span class="text-red-400">*</span>
                                </label>
                                <div class="field-wrap relative">
                                    <input class="field-input" min="0" name="early_clock_out_minutes"
                                        type="number"
                                        value="{{ old('early_clock_out_minutes') ?? ($settings['early_clock_out_minutes'] ?? 15) }}">
                                    <span class="absolute right-10 top-1/2 -translate-y-1/2 text-xs text-gray-400">
                                        Min
                                    </span>
                                </div>
                                <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">
                                    How many minutes before shift end an employee can clock out.
                                </p>
                                @error('early_clock_out_minutes')
                                    <p class="err-msg">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Break Notification Before (sec) (Conditional: Fixed Break Mode Only) -->
                            <div class="break-rule-fixed">
                                <label class="field-label">
                                    Break Notification Before (sec)
                                    <span class="text-red-400">*</span>
                                </label>
                                <div class="field-wrap relative">
                                    <input class="field-input" min="0"
                                        name="break_notification_before_seconds" type="number"
                                        value="{{ old('break_notification_before_seconds') ?? ($settings['break_notification_before_seconds'] ?? 60) }}">
                                    <span class="absolute right-10 top-1/2 -translate-y-1/2 text-xs text-gray-400">
                                        Sec
                                    </span>
                                </div>
                                <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">
                                    Seconds before break start time to trigger the countdown notification.
                                </p>
                                @error('break_notification_before_seconds')
                                    <p class="err-msg">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Early Break Out (min) (Conditional: Fixed Break Mode Only) -->
                            <div class="break-rule-fixed">
                                <label class="field-label">
                                    Early Break Out (min)
                                    <span class="text-red-400">*</span>
                                </label>
                                <div class="field-wrap relative">
                                    <input class="field-input" min="0" name="early_break_out_minutes"
                                        type="number"
                                        value="{{ old('early_break_out_minutes') ?? ($settings['early_break_out_minutes'] ?? 0) }}">
                                    <span class="absolute right-10 top-1/2 -translate-y-1/2 text-xs text-gray-400">
                                        Min
                                    </span>
                                </div>
                                <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">
                                    How many minutes before break end an employee can clock back in from break early.
                                </p>
                                @error('early_break_out_minutes')
                                    <p class="err-msg">{{ $message }}</p>
                                @enderror
                            </div>


                            <!-- Global Break Time (min) (Conditional: Flexible Break Mode Only) -->
                            <div class="break-rule-flexible">
                                <label class="field-label">
                                    Break Time (min)
                                    <span class="text-red-400">*</span>
                                </label>
                                <div class="field-wrap relative">
                                    <input class="field-input" min="0" name="break_time_minutes"
                                        type="number"
                                        value="{{ old('break_time_minutes') ?? ($settings['break_time_minutes'] ?? 0) }}">
                                    <span class="absolute right-10 top-1/2 -translate-y-1/2 text-xs text-gray-400">
                                        Min
                                    </span>
                                </div>
                                <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">
                                    Global break duration in minutes (applies to all days when break mode is flexible).
                                </p>
                                @error('break_time_minutes')
                                    <p class="err-msg">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Allow Off Day Attendance Toggle -->
                        <div
                            class="flex items-center justify-between rounded-2xl border border-gray-200 bg-gray-50/50 p-4 dark:border-gray-800 dark:bg-gray-950/20">
                            <div class="flex flex-col">
                                <span class="text-sm font-semibold text-gray-900 dark:text-white">Allow Off-Day
                                    Attendance</span>
                                <span class="text-xs text-gray-500 dark:text-gray-400">Controls whether employees can
                                    clock in on weekends/holidays/non-scheduled days.</span>
                            </div>
                            <label class="inline-flex cursor-pointer items-center">
                                <input @checked(old('allow_off_day_attendance', $settings['allow_off_day_attendance'] ?? false)) class="peer sr-only"
                                    name="allow_off_day_attendance" type="checkbox" value="1">
                                <div
                                    class="peer relative h-6 w-11 rounded-full bg-gray-300 transition-all after:absolute after:start-[2px] after:top-[2px] after:h-5 after:w-5 after:rounded-full after:bg-white after:transition-all after:content-[''] peer-checked:bg-blue-600 peer-checked:after:translate-x-full dark:bg-gray-700 dark:peer-checked:bg-blue-500">
                                </div>
                            </label>
                        </div>
                    </div>

                    <script>
                        function toggleTimingMode(isFixed) {
                            const fixedFields = document.querySelectorAll('.timing-rule-fixed');
                            const hiddenInput = document.getElementById('timingModeValue');
                            hiddenInput.value = isFixed ? 'fixed' : 'flexible';
                            fixedFields.forEach(f => {
                                if (isFixed) {
                                    f.classList.remove('hidden');
                                } else {
                                    f.classList.add('hidden');
                                }
                            });
                        }

                        function toggleBreakMode(isFixed) {
                            const fixedFields = document.querySelectorAll('.break-rule-fixed');
                            const flexibleFields = document.querySelectorAll('.break-rule-flexible');
                            const hiddenInput = document.getElementById('breakModeValue');
                            hiddenInput.value = isFixed ? 'fixed' : 'flexible';
                            fixedFields.forEach(f => {
                                if (isFixed) {
                                    f.classList.remove('hidden');
                                } else {
                                    f.classList.add('hidden');
                                }
                            });
                            flexibleFields.forEach(f => {
                                if (isFixed) {
                                    f.classList.add('hidden');
                                } else {
                                    f.classList.remove('hidden');
                                }
                            });

                            const dayBreakFixed = document.querySelectorAll('.day-break-fixed');
                            dayBreakFixed.forEach(f => {
                                if (isFixed) {
                                    f.classList.remove('hidden');
                                } else {
                                    f.classList.add('hidden');
                                }
                            });

                            // Recalculate heights of open break sections
                            document.querySelectorAll('.js-break-toggle').forEach(toggle => {
                                if (toggle.checked) {
                                    const breakSection = document.getElementById(toggle.dataset.day + 'Schedule')?.querySelector(
                                        '.js-break-section');
                                    if (breakSection) {
                                        breakSection.style.maxHeight = 'none';
                                        breakSection.style.maxHeight = breakSection.scrollHeight + 'px';
                                    }
                                }
                            });

                            // Trigger summary update for all days
                            ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'].forEach(day => {
                                if (window.updateSummary) {
                                    window.updateSummary(day);
                                }
                            });
                        }

                        document.addEventListener('DOMContentLoaded', function() {
                            const timingToggle = document.getElementById('timingModeToggle');
                            const breakToggle = document.getElementById('breakModeToggle');
                            toggleTimingMode(timingToggle.checked);
                            toggleBreakMode(breakToggle.checked);
                        });
                    </script>

                    <!-- Work Schedule -->
                    <div
                        class="rounded-3xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900">
                        <div class="space-y-4 p-1 md:p-6">
                            @foreach (['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'] as $day)
                                <div
                                    class="rounded-2xl border border-gray-200 bg-gray-50 p-5 dark:border-gray-800 dark:bg-gray-950/50">

                                    <div class="flex items-center justify-between gap-4">

                                        <div class="flex items-center gap-4">

                                            <div
                                                class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-100 text-sm font-semibold capitalize text-blue-600 dark:bg-blue-500/10 dark:text-blue-400">
                                                {{ substr($day, 0, 1) }}
                                            </div>

                                            <div class="flex flex-col gap-0">
                                                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                                                    {{ $day }}
                                                </h3>
                                                <p class="js-summary text-sm text-gray-500"
                                                    data-day="{{ $day }}">
                                                    {{--  --}}
                                                </p>
                                            </div>
                                        </div>

                                        <label class="inline-flex cursor-pointer items-center">
                                            <input @checked(old($day . '_working', $settings[$day . '_working'] ?? false)) class="js-day-toggle peer sr-only"
                                                data-target="{{ $day }}Schedule"
                                                name="{{ $day }}_working" type="checkbox" value="1">
                                            <div
                                                class="peer relative h-5 w-9 rounded-full bg-gray-300 transition-all after:absolute after:start-[2px] after:top-[2px] after:h-4 after:w-4 after:rounded-full after:bg-white after:transition-all after:content-[''] peer-checked:bg-blue-600 peer-checked:after:translate-x-full dark:bg-gray-700 dark:peer-checked:bg-blue-500">
                                            </div>
                                        </label>
                                    </div>
                                    <div class="schedule-content overflow-hidden transition-all duration-300"
                                        id="{{ $day }}Schedule"
                                        style="{{ old($day . '_working', $settings[$day . '_working'] ?? false) ? 'max-height:auto;opacity:1;' : 'max-height:0px;opacity:0;' }}">
                                        <div class="mt-4 grid gap-4 md:grid-cols-2">
                                            <div>
                                                <label class="field-label">Start Time</label>
                                                <div class="field-wrap relative">
                                                    <input class="field-input js-time-picker"
                                                        name="{{ $day }}_start_time"
                                                        placeholder="Start Time" type="time"
                                                        value="{{ convertTimeTo24HourFormat(old($day . '_start_time', $settings[$day . '_start_time'] ?? '')) }}">
                                                </div>
                                                @error($day . '_start_time')
                                                    <p class="err-msg">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div>
                                                <label class="field-label">End Time</label>
                                                <div class="field-wrap relative">
                                                    <input class="field-input js-time-picker"
                                                        name="{{ $day }}_end_time" placeholder="End Time"
                                                        type="time"
                                                        value="{{ convertTimeTo24HourFormat(old($day . '_end_time', $settings[$day . '_end_time'] ?? '')) }}">
                                                </div>
                                                @error($day . '_end_time')
                                                    <p class="err-msg">{{ $message }}</p>
                                                @enderror
                                            </div>

                                            <div class="flex w-full items-end justify-between">
                                                <label class="inline-flex cursor-pointer items-center gap-3">
                                                    <input @checked(old($day . '_break_enabled', $settings[$day . '_break_enabled'] ?? false))
                                                        class="module-checkbox js-break-toggle peer sr-only"
                                                        data-day="{{ $day }}"
                                                        name="{{ $day }}_break_enabled" type="checkbox"
                                                        value="1">
                                                    <div
                                                        class="flex h-5 w-5 items-center justify-center rounded-md border border-gray-300 bg-white transition-all duration-200 peer-checked:border-blue-600 peer-checked:bg-blue-600 dark:border-gray-600 dark:bg-gray-800">
                                                        <svg class="h-3.5 w-3.5 text-white opacity-0 transition-all duration-200"
                                                            fill="none" stroke-width="3" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path d="M5 13l4 4L19 7" stroke-linecap="round"
                                                                stroke-linejoin="round" />
                                                        </svg>
                                                    </div>
                                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                                        Enable Break
                                                    </span>
                                                </label>
                                            </div>
                                        </div>

                                        <div class="js-break-section grid gap-4 md:grid-cols-2"
                                            style="{{ old($day . '_break_enabled', $settings[$day . '_break_enabled'] ?? false) ? 'max-height:auto;opacity:1;' : 'max-height:0px;opacity:0;' }}">
                                            <div class="day-break-fixed mt-4">
                                                <label class="field-label">Break Start Time</label>
                                                <div class="field-wrap relative">
                                                    <input class="field-input js-time-picker"
                                                        name="{{ $day }}_break_start"
                                                        placeholder="Break Start Time" type="time"
                                                        value="{{ convertTimeTo24HourFormat(old($day . '_break_start', $settings[$day . '_break_start'] ?? '')) }}">
                                                </div>
                                                @error($day . '_break_start')
                                                    <p class="err-msg">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div class="day-break-fixed md:mt-4">
                                                <label class="field-label">Break End Time</label>
                                                <div class="field-wrap relative">
                                                    <input class="field-input js-time-picker"
                                                        name="{{ $day }}_break_end"
                                                        placeholder="Break End Time" type="time"
                                                        value="{{ convertTimeTo24HourFormat(old($day . '_break_end', $settings[$day . '_break_end'] ?? '')) }}">
                                                </div>
                                                @error($day . '_break_end')
                                                    <p class="err-msg">{{ $message }}</p>
                                                @enderror
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @can('settings.work_schedule.edit')
                        <div class="mt-5 flex items-center justify-end gap-3">
                            <button class="btn-primary" type="submit">
                                Save Schedule
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
            <button class="settings-mobile-close" onclick="closeSubSidebarMenu()" type="button">
                ✕
            </button>
            <div class="settings-mobile-scroll">
                @include('setting.sidebar')
            </div>
        </div>
    </div>
</x-app-layout>
