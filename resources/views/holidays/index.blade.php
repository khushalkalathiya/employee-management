<x-app-layout>
    <livewire:holidays.holiday-table />

    <div class="modal fixed inset-0 z-[9998] hidden items-center justify-center bg-black/0 p-4 backdrop-blur-sm transition-all duration-300"
        id="holidayFormModal">
        <div
            class="modal-content relative w-full max-w-xl scale-95 overflow-hidden rounded-2xl border border-gray-200 bg-white opacity-0 shadow-2xl transition-all duration-300 dark:border-gray-800 dark:bg-gray-950">
            <button
                class="modal-close-btn absolute right-4 top-4 cursor-pointer text-gray-400 transition hover:text-gray-700 dark:hover:text-white"
                type="button">
                <svg class="h-5 w-5" fill="none" stroke-width="2" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M6 18L18 6M6 6l12 12" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </button>

            <div class="p-6">
                <h3 class="section-title" id="holidayModalTitle">Create Holiday</h3>
            </div>

            <form data-create-action="{{ route('holidays.store') }}" id="holidayForm" method="POST">
                @csrf
                <input class="hidden" id="holidayFormIsEdit" name="is_edit" type="hidden" value="0" />
                <div class="p-6 pt-2">
                    <div class="grid grid-cols-1 gap-5">

                        <div>
                            <label class="field-label">Holiday Name <span class="text-red-400">*</span></label>
                            <div class="field-wrap relative">
                                <input class="field-input" id="holidayName" name="name"
                                    placeholder="e.g. Independence Day" required type="text">
                            </div>
                            <p class="err-msg hidden"></p>
                        </div>

                        <div>
                            <label class="inline-flex cursor-pointer items-center gap-3">

                                <input class="peer sr-only" id="holidayIsMultipleDays" name="is_multiple_days"
                                    type="checkbox" value="1">

                                <div
                                    class="flex h-5 w-5 items-center justify-center rounded-md border border-gray-300 bg-white transition-all duration-200 peer-checked:border-blue-600 peer-checked:bg-blue-600 dark:border-gray-600 dark:bg-gray-800">

                                    <svg class="h-3.5 w-3.5 text-white opacity-0 transition-all duration-200 peer-checked:opacity-100"
                                        fill="none" stroke-width="3" stroke="currentColor" viewBox="0 0 24 24">

                                        <path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round" />

                                    </svg>

                                </div>

                                <span class="text-sm font-medium">
                                    Multiple Days Holiday
                                </span>

                            </label>
                        </div>

                        <div id="holidaySingleDateWrap">
                            <label class="field-label">
                                Holiday Date <span class="text-red-400">*</span>
                            </label>

                            <input autocomplete="off" class="field-input" id="holidayDate" name="holiday_date"
                                placeholder="Select holiday date" type="text">

                            <p class="err-msg hidden"></p>
                        </div>

                        <div class="hidden" id="holidayMultipleDateWrap">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="field-label">Start Date</label>
                                    <input autocomplete="off" class="field-input" id="holidayStartDate"
                                        name="start_date" placeholder="Select start date & time" type="text">
                                    <p class="err-msg hidden"></p>
                                </div>

                                <div>
                                    <label class="field-label">End Date</label>
                                    <input autocomplete="off" class="field-input" id="holidayEndDate" name="end_date"
                                        placeholder="Select end date & time" type="text">
                                    <p class="err-msg hidden"></p>
                                </div>
                            </div>
                        </div>

                        <div id="holidayPartialWrap">
                            <label class="inline-flex cursor-pointer items-center gap-3">

                                <input class="peer sr-only" id="holidayIsPartialDay" name="is_partial_day"
                                    type="checkbox" value="1">

                                <div
                                    class="flex h-5 w-5 items-center justify-center rounded-md border border-gray-300 bg-white transition-all duration-200 peer-checked:border-blue-600 peer-checked:bg-blue-600 dark:border-gray-600 dark:bg-gray-800">

                                    <svg class="h-3.5 w-3.5 text-white opacity-0 transition-all duration-200 peer-checked:opacity-100"
                                        fill="none" stroke-width="3" stroke="currentColor" viewBox="0 0 24 24">

                                        <path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round" />

                                    </svg>

                                </div>

                                <span class="text-sm font-medium">
                                    Partial Day Holiday
                                </span>

                            </label>
                        </div>

                        <div class="hidden" id="holidayTimeWrap">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="field-label">Start Time</label>
                                    <input autocomplete="off" class="field-input" id="holidayStartTime"
                                        name="start_time" placeholder="Select start time" type="text">
                                    <p class="err-msg hidden"></p>
                                </div>

                                <div>
                                    <label class="field-label">End Time</label>
                                    <input autocomplete="off" class="field-input" id="holidayEndTime"
                                        name="end_time" placeholder="Select end time" type="text">
                                    <p class="err-msg hidden"></p>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="field-label">Notes</label>
                            <div class="field-wrap relative">
                                <textarea class="field-input min-h-[70px]" id="holidayNotes" name="notes"
                                    placeholder="Enter any notes about this holiday"></textarea>
                            </div>
                            <p class="err-msg hidden"></p>
                        </div>

                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 bg-gray-50 p-4 pt-0 dark:bg-gray-950">
                    <button class="btn-ghost modal-close-btn" type="button">
                        Cancel
                    </button>
                    <button class="btn-primary" id="holidaySubmitButton" type="submit">
                        Create Holiday
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
