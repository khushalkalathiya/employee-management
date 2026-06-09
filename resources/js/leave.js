let leaveDatePicker;
let leaveStartDatePicker;
let leaveEndDatePicker;
let leaveStartTimePicker;
let leaveEndTimePicker;

window.leaveFormReset = function() {
    const form = document.getElementById('leaveForm');

    if (!form) {
        return;
    }

    form.reset();
    leaveDatePicker?.clear();
    leaveStartDatePicker?.clear();
    leaveEndDatePicker?.clear();
    leaveStartTimePicker?.clear();
    leaveEndTimePicker?.clear();

    const firstLeaveType = document.querySelector('input[name="leave_type_id"]');

    if (firstLeaveType) {
        firstLeaveType.checked = true;
    }

    const fullDayMode = document.querySelector('input[name="leave_mode"][value="full_day"]');

    if (fullDayMode) {
        fullDayMode.checked = true;
    }

    toggleLeaveFields();
};

window.toggleLeaveFields = function() {
    const mode = document.querySelector('input[name="leave_mode"]:checked')?.value || 'full_day';
    const singleDateWrap = document.getElementById('leaveSingleDateWrap');
    const dateRangeWrap = document.getElementById('leaveDateRangeWrap');
    const timeRangeWrap = document.getElementById('leaveTimeRangeWrap');

    if (!singleDateWrap || !dateRangeWrap || !timeRangeWrap) {
        return;
    }

    const isMultipleDays = mode === 'multiple_days';
    const isHalfDay = mode === 'half_day';

    singleDateWrap.classList.toggle('hidden', isMultipleDays);
    dateRangeWrap.classList.toggle('hidden', !isMultipleDays);
    timeRangeWrap.classList.toggle('hidden', !(isHalfDay));
};

window.openLeaveModal = function(mode = 'create', leave = {}) {
    const form = document.getElementById('leaveForm');
    if (!form) {
        return;
    }

    const createAction = form.dataset.createAction;
    const isEdit = mode === 'edit';
    document.getElementById('leaveModalTitle').textContent = isEdit ? 'Edit Leave Request' : 'Create Leave Request';
    document.getElementById('leaveSubmitButton').textContent = isEdit ? 'Update Leave' : 'Create Leave';

    form.action = isEdit ? leave.action : createAction;
    document.getElementById('leaveFormIsEdit').value = isEdit ? 1 : 0;
    leaveFormReset();

    if (isEdit) {
        const employeeSelect = document.getElementById('leaveEmployeeId');
        if (employeeSelect && employeeSelect.tomselect) {
            employeeSelect.tomselect.setValue(leave.userId, true);
        }

        document.querySelector(`input[name="leave_type_id"][value="${leave.leaveTypeId}"]`)?.click();
        document.querySelector(`input[name="leave_mode"][value="${leave.leaveMode}"]`)?.click();

        const start = leave.start ? new Date(leave.start) : null;
        const end = leave.end ? new Date(leave.end) : null;

        if (leave.leaveMode === 'multiple_days') {
            leaveStartDatePicker?.setDate(start, true);
            leaveEndDatePicker?.setDate(end, true);
        } else {
            leaveDatePicker?.setDate(start ? start.toISOString().slice(0, 10) : '', true);
            if (leave.leaveMode === 'half_day') {
                leaveStartTimePicker?.setDate(start ? start.toLocaleTimeString('en-GB', { hour: '2-digit', minute: '2-digit' }) : '', true);
                leaveEndTimePicker?.setDate(end ? end.toLocaleTimeString('en-GB', { hour: '2-digit', minute: '2-digit' }) : '', true);
            }
        }

        document.getElementById('leaveReason').value = leave.reason || '';
    }

    toggleLeaveFields();
    modalHelper.open('leaveFormModal');
};

document.addEventListener('click', function(event) {
    const viewButton = event.target.closest('.js-view-leave');
    if (viewButton) {
        fetch(viewButton.dataset.action, {
            headers: {
                Accept: 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            }
        })
        .then(response => response.json())
        .then(result => {
            if (!result.success) {
                showToast('Unable to load leave details', 'error');
                return;
            }
            const leave = result.data;
            document.getElementById('viewRejectionSection').classList.add('hidden');
            document.getElementById('viewEmployeeInitials').classList.add('hidden');
            document.getElementById('viewEmployeeAvatar').classList.add('hidden');
            document.getElementById('viewApprovedSection').classList.add('hidden');

            if(leave.employee_avatar){
                document.getElementById('viewEmployeeAvatar').classList.remove('hidden');
                document.getElementById('viewEmployeeAvatar').src = leave.employee_avatar;
            } else {
                document.getElementById('viewEmployeeInitials').classList.remove('hidden');
                document.getElementById('viewEmployeeInitials').textContent = leave.employee_initials ?? '-';
            }
            document.getElementById('viewEmployee').textContent = leave.employee_name ?? '-';
            document.getElementById('viewEmployeeEmail').textContent = leave.employee_email ?? '-';
            document.getElementById('viewLeaveType').textContent = leave.leave_type ?? '-';
            document.getElementById('viewLeaveMode').textContent = leave.leave_mode ?? '-';
            document.getElementById('viewStartDate').textContent = leave.start_datetime ?? '-';
            document.getElementById('viewEndDate').textContent = leave.end_datetime ?? '-';
            document.getElementById('viewStatus').textContent = leave.status ?? '-';
            document.getElementById('viewReason').textContent = leave.reason ?? '-';
            if(leave.approved_by){
                document.getElementById('viewApprovedSection').classList.remove('hidden');
                document.getElementById('viewApprovedBy').textContent = leave.approved_by ?? '-';
                document.getElementById('viewApprovedAt').textContent = leave.approved_at ?? '-';
            }
            if (leave.rejection_reason) {
                document.getElementById('viewRejectionSection').classList.remove('hidden');
                document.getElementById('viewRejectionReason').textContent = leave.rejection_reason ?? '-';
            }
            modalHelper.open('leaveViewModal');
        }).catch(error => {
            console.error(error);
            showToast('Something went wrong', 'error');
        });
        return;
    }

    const editButton = event.target.closest('.js-edit-leave');
    if (editButton) {
        fetch(editButton.dataset.viewAction, {
            headers: {
                Accept: 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            }
        })
        .then(response => response.json())
        .then(result => {
            if (!result.success) {
                showToast('Unable to load leave details', 'error');
                return;
            }
            const leave = result.data;
            openLeaveModal('edit', {
                action: editButton.dataset.action,
                userId: leave.user_id,
                leaveTypeId: leave.leave_type_id,
                leaveMode: leave.leave_mode,
                start: leave.start_datetime,
                end: leave.end_datetime,
                reason: leave.reason,
            });
        })
        .catch(error => {
            console.error(error);
            showToast('Something went wrong', 'error');
        });
        return;
    }
});

document.addEventListener('DOMContentLoaded', () => {
    leaveDatePicker = flatpickr('#leaveDate', {
        dateFormat: 'Y-m-d',
        monthSelectorType: 'static',
        allowInput: true,
    });

    leaveStartDatePicker = flatpickr('#leaveStartDate', {
        enableTime: true,
        dateFormat: 'Y-m-d H:i K',
        time_24hr: false,
        monthSelectorType: 'static',
        allowInput: true,
    });

    leaveEndDatePicker = flatpickr('#leaveEndDate', {
        enableTime: true,
        dateFormat: 'Y-m-d H:i K',
        time_24hr: false,
        monthSelectorType: 'static',
        allowInput: true,
    });

    leaveStartTimePicker = flatpickr('#leaveStartTime', {
        enableTime: true,
        noCalendar: true,
        dateFormat: 'h:i K',
        time_24hr: false,
        allowInput: true,
    });

    leaveEndTimePicker = flatpickr('#leaveEndTime', {
        enableTime: true,
        noCalendar: true,
        dateFormat: 'h:i K',
        time_24hr: false,
        allowInput: true,
    });

    document.querySelectorAll('input[name="leave_mode"]').forEach(radio => {
        radio.addEventListener('change', toggleLeaveFields);
    });
    toggleLeaveFields();

    const form = document.getElementById('leaveForm');
    if (form) {
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            const button = document.getElementById('leaveSubmitButton');
            const originalText = button.innerHTML;
            button.disabled = true;
            button.innerHTML = 'Saving...';

            try {
                const formData = new FormData(form);
                const csrfToken = formData.get('_token');
                if (formData.get('is_edit') == '1') {
                    formData.append('_method', 'PUT');
                }

                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: formData,
                });

                const result = await response.json();
                document.querySelectorAll('#leaveForm .err-msg').forEach(error => {
                    error.textContent = '';
                    error.classList.add('hidden');
                });

                if (!result.success) {
                    if (result.errors) {
                        Object.entries(result.errors).forEach(([field, messages]) => {
                            const input = form.querySelector(`[name="${field}"]`);
                            if (!input) {
                                return;
                            }
                            const errorElement = input.closest('div')?.parentElement?.querySelector('.err-msg');
                            if (errorElement) {
                                errorElement.textContent = messages[0];
                                errorElement.classList.remove('hidden');
                            }
                        });
                        return;
                    }
                    showToast(result.message || 'Something went wrong', 'error');
                    return;
                }

                modalHelper.close('leaveFormModal');
                leaveFormReset();
                Livewire.dispatch('refresh-table');
                showToast(result.message || 'Leave request saved successfully', 'success');
            } catch (error) {
                console.error(error);
                showToast('Something went wrong', 'error');
            } finally {
                button.disabled = false;
                button.innerHTML = originalText;
            }
        });
    }
    
    
    const rejectLeaveSubmitButton = document.getElementById('rejectLeaveSubmitButton');
    if (rejectLeaveSubmitButton) {
        rejectLeaveSubmitButton.addEventListener('click', async function () {
            const action = this.dataset.action;
            const reasonInput = document.getElementById('leaveRejectionReason');
            const rejectionReason = reasonInput.value.trim();

            if (!rejectionReason) {
                showToast('Rejection reason is required.', 'error');
                return;
            }

            this.disabled = true;
            const originalText = this.innerHTML;
            this.innerHTML = 'Rejecting...';

            try {
                const response = await fetch(action, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: JSON.stringify({
                        rejection_reason: rejectionReason,
                        status: 2
                    })
                });

                const result = await response.json();

                if (!result.success) {
                    showToast(result.message || 'Unable to reject leave request', 'error');
                    return;
                }

                modalHelper.close('leaveRejectModal');
                Livewire.dispatch('refresh-table');
                showToast(result.message || 'Leave request rejected successfully', 'success');

            } catch (error) {
                console.error(error);
                showToast('Something went wrong', 'error');
            } finally {
                this.disabled = false;
                this.innerHTML = originalText;
            }
        });
    }

    const approveLeaveSubmitButton = document.getElementById('approveLeaveSubmitButton');
    if (approveLeaveSubmitButton) {
        approveLeaveSubmitButton.addEventListener('click', async function () {
            const action = this.dataset.action;
            this.disabled = true;
            const originalText = this.innerHTML;
            this.innerHTML = 'Approving...';
            try {
                const response = await fetch(action, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: JSON.stringify({
                        status: 1
                    })
                });

                const result = await response.json();

                if (!result.success) {
                    showToast(result.message || 'Unable to approve leave request', 'error');
                    return;
                }

                modalHelper.close('leaveApproveModal');
                Livewire.dispatch('refresh-table');
                showToast(result.message || 'Leave request approved successfully', 'success');

            } catch (error) {
                console.error(error);
                showToast('Something went wrong', 'error');
            } finally {
                this.disabled = false;
                this.innerHTML = originalText;
            }
        });
    }
});

document.addEventListener('change', function(e) {
    const select = e.target.closest('.js-update-leave-status');
    if (!select) {
        return;
    }
    const previousValue = select.getAttribute('data-current-value');
    const statusAction = select.dataset.action;
    const status = select.value;
    if (status === '1') {
        document.getElementById('approveLeaveSubmitButton').dataset.action = statusAction;
        modalHelper.open('leaveApproveModal');
    } else if (status === '2') {
        document.getElementById('rejectLeaveSubmitButton').dataset.action = statusAction;
        modalHelper.open('leaveRejectModal');
    }
    select.value = previousValue;
});