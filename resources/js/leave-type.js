window.openLeaveTypeModal = function(mode = 'create', leaveType = {}) {
    const form = document.getElementById('leaveTypeForm');
    const createAction = form.dataset.createAction;
    const isEdit = mode === 'edit';
    document.getElementById('leaveTypeModalTitle').textContent = isEdit ? 'Edit Leave Type' :
        'Create Leave Type';
    document.getElementById('leaveTypeSubmitButton').textContent = isEdit ? 'Update Leave Type' :
        'Create Leave Type';
    form.action = isEdit ? leaveType.action : createAction;
    document.getElementById('leaveTypeFormIsEdit').value = isEdit ? 1 : 0;
    document.getElementById('leaveTypeName').value = leaveType.name || '';
    document.getElementById('leaveTypeMonthlyLimit').value = leaveType.monthlyLimit || '';
    document.getElementById('leaveTypeDescription').value = leaveType.description || '';
    modalHelper.open('leaveTypeFormModal');
};

document.addEventListener('click', function(event) {
    const editButton = event.target.closest('.js-edit-leave-type');
    if (editButton) {
        openLeaveTypeModal('edit', editButton.dataset);
        return;
    }
});

document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('leaveTypeForm');
    if (!form) {
        return;
    }

    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        const form = this;
        const button = document.getElementById('leaveTypeSubmitButton');
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
                    result.message || 'Leave Type saved successfully',
                    'error'
                );
                return;
            }
    
            modalHelper.close('leaveTypeFormModal');
            form.reset();
            Livewire.dispatch('refresh-table');
            showToast(
                result.message || 'Leave Type saved successfully',
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
