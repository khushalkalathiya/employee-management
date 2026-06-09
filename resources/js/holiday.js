let holidayDatePicker;
let holidayStartDatePicker;
let holidayEndDatePicker;
let holidayStartTimePicker;
let holidayEndTimePicker;

window.holidayFormReset = function() {
    const form = document.getElementById('holidayForm');
    form.reset();
    holidayDatePicker?.clear();
    holidayStartDatePicker?.clear();
    holidayEndDatePicker?.clear();
    holidayStartTimePicker?.clear();
    holidayEndTimePicker?.clear();
    document.getElementById('holidayIsMultipleDays').checked = false;
    document.getElementById('holidayIsPartialDay').checked = false;
    toggleHolidayFields();
};

window.toggleHolidayFields = function() {
    if(!document.getElementById('holidayIsMultipleDays')){
        return;
    }
    const isMultiple = document.getElementById('holidayIsMultipleDays').checked;
    const isPartial = document.getElementById('holidayIsPartialDay').checked;

    document.getElementById('holidaySingleDateWrap').classList.toggle('hidden', isMultiple);
    document.getElementById('holidayMultipleDateWrap').classList.toggle('hidden', !isMultiple);
    document.getElementById('holidayPartialWrap').classList.toggle('hidden', isMultiple);
    document.getElementById('holidayTimeWrap').classList.toggle('hidden', isMultiple || !isPartial);
}

window.openHolidayModal = function(mode = 'create', holiday = {}) {
    const form = document.getElementById('holidayForm');
    const createAction = form.dataset.createAction;
    const isEdit = mode === 'edit';
    document.getElementById('holidayModalTitle').textContent = isEdit ? 'Edit Holiday' : 'Create Holiday';
    document.getElementById('holidaySubmitButton').textContent = isEdit ? 'Update Holiday' : 'Create Holiday';
    form.action = isEdit ? holiday.action : createAction;
    document.getElementById('holidayFormIsEdit').value = isEdit ? 1 : 0;
    holidayFormReset();

    if (isEdit) {
        const start = new Date(holiday.start);
        const end = new Date(holiday.end);
        const startDate = holiday.start.substring(0, 10);
        const endDate = holiday.end.substring(0, 10);
        const startTime = holiday.start.substring(11, 16);
        const endTime = holiday.end.substring(11, 16);
        const type = Number(holiday.type);
        const isMultiple = type === 3;
        const isPartial = type === 2;
        document.getElementById('holidayIsMultipleDays').checked = isMultiple;
        document.getElementById('holidayIsPartialDay').checked = isPartial;
        toggleHolidayFields();

        if (isMultiple) {
            holidayStartDatePicker?.setDate(
                holiday.start,
                true
            );
            holidayEndDatePicker?.setDate(
                holiday.end,
                true
            );
        } else {
            holidayDatePicker?.setDate(
                startDate,
                true
            );
            if (isPartial) {
                holidayStartTimePicker?.setDate(
                    startTime,
                    true
                );
                holidayEndTimePicker?.setDate(
                    endTime,
                    true
                );
            }
        }
        document.getElementById('holidayName').value = holiday.name || '';
        document.getElementById('holidayNotes').value = holiday.notes || '';
    }
    modalHelper.open('holidayFormModal');
};

document.addEventListener('click', function(event) {
    const editButton = event.target.closest('.js-edit-holiday');
    if (editButton) {
        openHolidayModal('edit', editButton.dataset);
        return;
    }
});

document.addEventListener('DOMContentLoaded', () => {
    holidayDatePicker = flatpickr("#holidayDate", {
        dateFormat: "Y-m-d",
        monthSelectorType: "static",
        allowInput: true,
    });

    holidayStartDatePicker = flatpickr("#holidayStartDate", {
        enableTime: true,
        dateFormat: "Y-m-d H:i K",
        time_24hr: false,
        monthSelectorType: "static",
        allowInput: true,
    });

    holidayEndDatePicker = flatpickr("#holidayEndDate", {
        enableTime: true,
        dateFormat: "Y-m-d H:i K",
        time_24hr: false,
        monthSelectorType: "static",
        allowInput: true,
    });

    holidayStartTimePicker = flatpickr("#holidayStartTime", {
        enableTime: true,
        noCalendar: true,
        dateFormat: "h:i K",
        time_24hr: false,
        allowInput: true,
    });

    holidayEndTimePicker = flatpickr("#holidayEndTime", {
        enableTime: true,
        noCalendar: true,
        dateFormat: "h:i K",
        time_24hr: false,
        allowInput: true,
    });

    document.getElementById('holidayIsMultipleDays')?.addEventListener('change', toggleHolidayFields);
    document.getElementById('holidayIsPartialDay')?.addEventListener('change', toggleHolidayFields);
    toggleHolidayFields();

    const form = document.getElementById('holidayForm');
    if (!form) {
        return;
    }

    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        const form = this;
        const button = document.getElementById('holidaySubmitButton');
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
                body: formData
            });

            const result = await response.json();
            document.querySelectorAll('.err-msg').forEach(error => {
                error.textContent = '';
                error.classList.add('hidden');
            });

            if (!result.success) {
                if (result.errors) {
                    Object.entries(result.errors).forEach(([field, messages]) => {
                        const input = form.querySelector(
                            `[name="${field}"]`
                        );
                        if (!input) {
                            return;
                        }
                        const errorElement = input.closest('div')?.parentElement?.querySelector(
                            '.err-msg');
                        if (errorElement) {
                            errorElement.textContent = messages[0];
                            errorElement.classList.remove('hidden');
                        }
                    });
                    return;
                }
                showToast(
                    result.message || 'Something went wrong',
                    'error'
                );
                return;
            }

            modalHelper.close('holidayFormModal');
            holidayFormReset();
            toggleHolidayFields();
            Livewire.dispatch('refresh-table');
            showToast(
                result.message || 'Holiday saved successfully',
                'success'
            );
        } catch (error) {
            console.error(error);
            showToast(
                'Something went wrong',
                'error'
            );
        } finally {
            button.disabled = false;
            button.innerHTML = originalText;
        }
    });
});
