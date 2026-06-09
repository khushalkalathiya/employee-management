<x-app-layout>
    <livewire:leave.leave-request-table />

    <div class="modal fixed inset-0 z-[9998] hidden items-center justify-center bg-black/0 p-4 backdrop-blur-sm transition-all duration-300"
        id="leaveViewModal">
        <div
            class="modal-content relative w-full max-w-3xl scale-95 overflow-hidden rounded-2xl border border-gray-200 bg-white opacity-0 shadow-2xl transition-all duration-300 dark:border-gray-800 dark:bg-gray-950">

            <div class="flex items-center justify-between px-6 py-4">

                <div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                        Leave Details
                    </h3>
                </div>

                <button class="modal-close-btn text-gray-400 hover:text-gray-700 dark:hover:text-white" type="button">
                    <svg class="h-5 w-5" fill="none" stroke-width="2" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M6 18L18 6M6 6l12 12" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </button>
            </div>

            <!-- Body -->
            <div class="max-h-[75vh] overflow-y-auto p-6 pt-4">

                <div
                    class="grid grid-cols-1 gap-5 rounded-xl border border-gray-200 p-5 md:grid-cols-2 dark:border-gray-800">

                    <div class="col-span-2 flex items-center gap-4">
                        <div class="flex hidden h-12 w-12 items-center justify-center rounded-full bg-gradient-to-br from-blue-500 to-blue-700 text-sm font-semibold text-white"
                            id="viewEmployeeInitials">
                        </div>
                        <img alt="Employee" class="h-16 w-16 rounded-full border object-cover" id="viewEmployeeAvatar"
                            src="">
                        <div>
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-white" id="viewEmployee"></h4>
                            <p class="text-sm text-gray-500" id="viewEmployeeEmail"></p>
                        </div>
                    </div>

                    <div>
                        <p class="text-xs uppercase text-gray-500">
                            Leave Type
                        </p>
                        <p class="mt-1 font-semibold" id="viewLeaveType"></p>
                    </div>

                    <div>
                        <p class="text-xs uppercase text-gray-500">
                            Leave Mode
                        </p>
                        <p class="mt-1 font-semibold" id="viewLeaveMode"></p>
                    </div>

                    <div>
                        <p class="text-xs uppercase text-gray-500">
                            Start Date
                        </p>
                        <p class="mt-1 font-semibold" id="viewStartDate"></p>
                    </div>

                    <div>
                        <p class="text-xs uppercase text-gray-500">
                            End Date
                        </p>
                        <p class="mt-1 font-semibold" id="viewEndDate"></p>
                    </div>

                    <div>
                        <p class="text-xs uppercase text-gray-500">
                            Status
                        </p>

                        <div class="mt-2" id="viewStatus">

                        </div>
                    </div>

                    <div class="col-span-2">
                        <h4 class="mb-2 text-sm font-semibold text-gray-700 dark:text-gray-300">
                            Reason
                        </h4>

                        <p class="whitespace-pre-wrap text-sm text-gray-600 dark:text-gray-400" id="viewReason">
                        </p>
                    </div>
                </div>

                <!-- Reason -->

                <!-- Approval Information -->
                <div class="mt-6 hidden rounded-xl border border-gray-200 p-4 dark:border-gray-800"
                    id="viewApprovedSection">

                    <h4 class="mb-4 text-sm font-semibold text-gray-700 dark:text-gray-300">
                        Approval Information
                    </h4>

                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">

                        <div>
                            <p class="text-xs uppercase text-gray-500">
                                Approved By
                            </p>

                            <p class="mt-1 font-medium" id="viewApprovedBy"></p>
                        </div>

                        <div>
                            <p class="text-xs uppercase text-gray-500">
                                Approved At
                            </p>

                            <p class="mt-1 font-medium" id="viewApprovedAt"></p>
                        </div>

                    </div>
                </div>

                <!-- Rejection Reason -->
                <div class="mt-6 hidden" id="viewRejectionSection">

                    <div class="rounded-xl border border-red-200 bg-red-50 p-4 dark:border-red-900 dark:bg-red-950/20">

                        <h4 class="mb-2 text-sm font-semibold text-red-600">
                            Rejection Reason
                        </h4>

                        <p class="text-sm text-red-500" id="viewRejectionReason"></p>

                    </div>

                </div>

            </div>

        </div>

    </div>

    @canany(['leave.create', 'leave.edit'])
        <div class="modal fixed inset-0 z-[9998] hidden items-center justify-center bg-black/0 p-4 backdrop-blur-sm transition-all duration-300"
            id="leaveFormModal">
            <div
                class="modal-content relative w-full max-w-3xl scale-95 overflow-hidden rounded-2xl border border-gray-200 bg-white opacity-0 shadow-2xl transition-all duration-300 dark:border-gray-800 dark:bg-gray-950">
                <button
                    class="modal-close-btn absolute right-4 top-4 cursor-pointer text-gray-400 transition hover:text-gray-700 dark:hover:text-white"
                    type="button">
                    <svg class="h-5 w-5" fill="none" stroke-width="2" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M6 18L18 6M6 6l12 12" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </button>

                <div class="p-6">
                    <h3 class="section-title" id="leaveModalTitle">Create Leave</h3>
                </div>

                <form data-create-action="{{ route('leaves.store') }}" id="leaveForm" method="POST">
                    @csrf
                    <input class="hidden" id="leaveFormIsEdit" name="is_edit" type="hidden" value="0" />
                    <div class="p-6 pt-2">
                        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                            @if (!has_permission('leave.own'))
                                <div class="col-span-2">
                                    <label class="field-label">Employee <span class="text-red-400">*</span></label>
                                    <div class="field-wrap relative">
                                        <select class="field-input tom-select" id="leaveEmployeeId" name="user_id" required>
                                            <option value="">Select employee</option>
                                            @foreach ($users as $userId => $userName)
                                                <option value="{{ $userId }}">
                                                    {{ $userName }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <p class="err-msg hidden"></p>
                                </div>
                            @endif

                            <div class="col-span-2">
                                <div class="flex flex-wrap gap-3">
                                    @foreach ($leaveTypes as $leaveTypeId => $leaveTypeName)
                                        <div>
                                            <input class="peer hidden" id="leave_type_{{ $leaveTypeId }}"
                                                name="leave_type_id" type="radio" value="{{ $leaveTypeId }}">

                                            <label
                                                class="flex cursor-pointer items-center gap-2 rounded-lg border border-[var(--border)] px-4 py-2 text-sm font-medium transition-all hover:border-blue-500 peer-checked:border-blue-600 peer-checked:bg-blue-500 peer-checked:text-white"
                                                for="leave_type_{{ $leaveTypeId }}">
                                                {{ $leaveTypeName }}
                                            </label>
                                        </div>
                                    @endforeach

                                </div>
                                <p class="err-msg hidden"></p>
                            </div>


                            <div class="col-span-2">
                                <div class="flex flex-wrap justify-start gap-3 sm:flex-nowrap">
                                    @foreach (['full_day' => 'Full Day', 'multiple_days' => 'Multiple Days', 'half_day' => 'Half Day'] as $leaveModeKey => $leaveModeLabel)
                                        <div class="w-full">
                                            <input class="peer hidden" id="leave_mode_{{ $leaveModeKey }}"
                                                name="leave_mode" type="radio" value="{{ $leaveModeKey }}">

                                            <label
                                                class="flex w-full cursor-pointer items-center justify-center rounded-lg border border-[var(--border)] px-4 py-2 text-center text-sm font-medium transition-all hover:border-blue-500 peer-checked:border-blue-600 peer-checked:bg-blue-500 peer-checked:text-white"
                                                for="leave_mode_{{ $leaveModeKey }}">
                                                {{ $leaveModeLabel }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>

                                <p class="err-msg hidden"></p>
                            </div>

                            <div class="col-span-2" id="leaveSingleDateWrap">
                                <label class="field-label">Single Date <span class="text-red-400">*</span></label>
                                <div class="field-wrap relative">
                                    <input autocomplete="off" class="field-input" id="leaveDate" name="leave_date"
                                        placeholder="Select leave date" type="text">
                                </div>
                                <p class="err-msg hidden"></p>
                            </div>

                            <div class="hidden md:col-span-2" id="leaveDateRangeWrap">
                                <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                                    <div>
                                        <label class="field-label">Start Date</label>
                                        <div class="field-wrap relative">
                                            <input autocomplete="off" class="field-input" id="leaveStartDate"
                                                name="start_datetime" placeholder="Select start date" type="text">
                                        </div>
                                        <p class="err-msg hidden"></p>
                                    </div>

                                    <div>
                                        <label class="field-label">End Date</label>
                                        <div class="field-wrap relative">
                                            <input autocomplete="off" class="field-input" id="leaveEndDate"
                                                name="end_datetime" placeholder="Select end date" type="text">
                                        </div>
                                        <p class="err-msg hidden"></p>
                                    </div>
                                </div>
                            </div>

                            <div class="hidden md:col-span-2" id="leaveTimeRangeWrap">
                                <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                                    <div>
                                        <label class="field-label">Start Time</label>
                                        <div class="field-wrap relative">
                                            <input autocomplete="off" class="field-input" id="leaveStartTime"
                                                name="start_time" placeholder="Select start time" type="text">
                                        </div>
                                        <p class="err-msg hidden"></p>
                                    </div>

                                    <div>
                                        <label class="field-label">End Time</label>
                                        <div class="field-wrap relative">
                                            <input autocomplete="off" class="field-input" id="leaveEndTime"
                                                name="end_time" placeholder="Select end time" type="text">
                                        </div>
                                        <p class="err-msg hidden"></p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-span-2">
                                <label class="field-label">Reason <span class="text-red-400">*</span></label>
                                <div class="field-wrap relative">
                                    <textarea class="field-input min-h-[90px]" id="leaveReason" name="reason"
                                        placeholder="Describe the reason for this leave request" required></textarea>
                                </div>
                                <p class="err-msg hidden"></p>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 bg-gray-50 p-4 pt-0 dark:bg-gray-950">
                        <button class="btn-ghost modal-close-btn" type="button">Cancel</button>
                        <button class="btn-primary" id="leaveSubmitButton" type="submit">Create Leave</button>
                    </div>
                </form>
            </div>
        </div>
    @endcanany

    @can('leave.edit')
        <div class="modal fixed inset-0 z-[9998] hidden items-center justify-center bg-black/0 p-4 backdrop-blur-sm transition-all duration-300"
            id="leaveApproveModal">

            <div
                class="modal-content relative w-full max-w-md scale-95 overflow-hidden rounded-2xl border border-gray-200 bg-white opacity-0 shadow-2xl transition-all duration-300 dark:border-gray-800 dark:bg-gray-950">

                <button
                    class="modal-close-btn absolute right-4 top-4 cursor-pointer text-gray-400 transition hover:text-gray-700 dark:hover:text-white"
                    type="button">

                    <svg class="h-5 w-5" fill="none" stroke-width="2" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M6 18L18 6M6 6l12 12" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>

                </button>

                <div class="p-6">

                    <div
                        class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-green-100 text-green-600 dark:bg-green-500/10 dark:text-green-400">

                        <svg class="h-7 w-7" fill="none" stroke-width="2.5" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>

                    </div>

                    <h3 class="mt-4 text-center text-lg font-semibold text-gray-900 dark:text-white">
                        Approve Leave Request
                    </h3>

                    <p class="mt-2 text-center text-sm text-gray-500 dark:text-gray-400">
                        Are you sure you want to approve this leave request?
                    </p>

                </div>

                <div class="grid grid-cols-2 gap-3 bg-gray-50 p-4 pt-0 dark:bg-gray-950">

                    <button
                        class="modal-close-btn w-full cursor-pointer rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium transition hover:bg-gray-100 dark:border-gray-700 dark:hover:bg-gray-800"
                        type="button">
                        Cancel
                    </button>

                    <button
                        class="w-full cursor-pointer rounded-lg bg-green-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-green-700"
                        data-action="" id="approveLeaveSubmitButton" type="button">
                        Approve Leave
                    </button>

                </div>

            </div>
        </div>

        <div class="modal fixed inset-0 z-[9998] hidden items-center justify-center bg-black/0 p-4 backdrop-blur-sm transition-all duration-300"
            id="leaveRejectModal">
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

                    <div
                        class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-red-100 text-red-600 dark:bg-red-500/10 dark:text-red-400">

                        <svg class="h-7 w-7" fill="none" stroke-width="2.5" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path d="M18 6L6 18" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M6 6L18 18" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>

                    </div>

                    <h3 class="mt-4 text-center text-lg font-semibold text-gray-900 dark:text-white">
                        Reject Leave Request
                    </h3>

                    <p class="mt-2 text-center text-sm text-gray-500 dark:text-gray-400">
                        Provide a reason for rejecting this leave request.
                    </p>
                </div>

                <div class="p-5 py-3 pt-0">
                    <div class="field-wrap relative">
                        <textarea class="field-input min-h-[100px]" id="leaveRejectionReason" name="rejection_reason"
                            placeholder="Enter the rejection reason" required></textarea>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3 bg-gray-50 p-4 pt-0 dark:bg-gray-950">

                    <button
                        class="modal-close-btn w-full cursor-pointer rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium transition hover:bg-gray-100 dark:border-gray-700 dark:hover:bg-gray-800"
                        type="button">
                        Cancel
                    </button>

                    <button
                        class="w-full cursor-pointer rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-red-700"
                        data-action="" id="rejectLeaveSubmitButton" type="button">
                        Reject Leave
                    </button>

                </div>

            </div>
        </div>

        <div class="modal fixed inset-0 z-[9998] hidden items-center justify-center bg-black/0 p-4 backdrop-blur-sm transition-all duration-300"
            id="leaveCancelModal">
            <div
                class="modal-content relative w-full max-w-md scale-95 overflow-hidden rounded-2xl border border-gray-200 bg-white opacity-0 shadow-2xl transition-all duration-300 dark:border-gray-800 dark:bg-gray-950">
                <button
                    class="modal-close-btn absolute right-4 top-4 cursor-pointer text-gray-400 transition hover:text-gray-700 dark:hover:text-white"
                    type="button">
                    <svg class="h-5 w-5" fill="none" stroke-width="2" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M6 18L18 6M6 6l12 12" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </button>

                <div class="p-6 text-center">
                    <div
                        class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-red-500/10 text-red-500 dark:bg-red-500/10">
                        <svg class="h-6 w-6" fill="none" stroke-width="2" stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"
                                stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </div>
                    <h3 class="section-title">Cancel Leave Request</h3>
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Are you sure you want to cancel this leave
                        request?</p>
                </div>

                <form id="leaveCancelForm" method="POST">
                    @csrf
                    <div class="flex items-center justify-end gap-3 bg-gray-50 p-4 pt-0 dark:bg-gray-950">
                        <button class="btn-ghost modal-close-btn" type="button">No</button>
                        <button class="btn-danger" id="cancelLeaveSubmitButton" type="submit">Cancel Leave</button>
                    </div>
                </form>
            </div>
        </div>
    @endcan
</x-app-layout>
