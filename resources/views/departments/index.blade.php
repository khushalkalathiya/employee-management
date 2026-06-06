<x-app-layout>
    <livewire:departments.department-table />

    <div class="modal fixed inset-0 z-[9998] hidden items-center justify-center bg-black/0 p-4 backdrop-blur-sm transition-all duration-300"
        id="departmentFormModal">
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
                <h3 class="section-title" id="departmentModalTitle">Create Department</h3>
            </div>

            <form data-create-action="{{ route('departments.store') }}" id="departmentForm" method="POST">
                @csrf
                <input class="hidden" id="departmentFormIsEdit" name="is_edit" type="hidden" value="0" />
                <div class="p-6 pt-2">
                    <div class="grid grid-cols-1 gap-5">
                        <div>
                            <label class="field-label">Name <span class="text-red-400">*</span></label>
                            <div class="field-wrap relative">
                                <input class="field-input" id="departmentName" name="name"
                                    placeholder="Enter department name" required type="text">
                            </div>
                            <p class="err-msg hidden"></p>
                        </div>

                        <div>
                            <label class="field-label">Description</label>
                            <div class="field-wrap relative">
                                <textarea class="field-input min-h-[110px]" id="departmentDescription" name="description"
                                    placeholder="Enter department description"></textarea>
                            </div>
                            <p class="err-msg hidden"></p>
                        </div>

                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 bg-gray-50 p-4 pt-0 dark:bg-gray-950">
                    <button class="btn-ghost modal-close-btn" type="button">
                        Cancel
                    </button>
                    <button class="btn-primary" id="departmentSubmitButton" type="submit">
                        Create Department
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
