window.openDepartmentModal = function(mode = 'create', department = {}) {
    const form = document.getElementById('departmentForm');
    const createAction = form.dataset.createAction;
    const isEdit = mode === 'edit';
    document.getElementById('departmentModalTitle').textContent = isEdit ? 'Edit Department' :
        'Create Department';
    document.getElementById('departmentSubmitButton').textContent = isEdit ? 'Update Department' :
        'Create Department';
    form.action = isEdit ? department.action : createAction;
    document.getElementById('departmentFormIsEdit').value = isEdit ? 1 : 0;
    document.getElementById('departmentName').value = department.name || '';
    document.getElementById('departmentDescription').value = department.description || '';
    modalHelper.open('departmentFormModal');
};

document.addEventListener('click', function(event) {
    const editButton = event.target.closest('.js-edit-department');
    if (editButton) {
        openDepartmentModal('edit', editButton.dataset);
        return;
    }
});

document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('departmentForm');
    if (!form) {
        return;
    }

    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        const form = this;
        const button = document.getElementById('departmentSubmitButton');
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
                    result.message || 'Department saved successfully',
                    'error'
                );
                return;
            }
    
            modalHelper.close('departmentFormModal');
            form.reset();
            Livewire.dispatch('refresh-table');
            showToast(
                result.message || 'Department saved successfully',
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

    document.addEventListener('change', async function (e) {
        const toggle = e.target.closest(
            '.department-status-toggle'
        );
        if (!toggle) {
            return;
        }
        const originalState = !toggle.checked;
    
        try {
    
            const response = await fetch(
                toggle.dataset.url,
                {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document
                            .querySelector(
                                'meta[name="csrf-token"]'
                            )?.content,
                        'X-Requested-With':
                            'XMLHttpRequest',
                    }
                }
            );
    
            const result = await response.json();
    
            if (!response.ok || !result.success) {
    
                toggle.checked = originalState;
    
                showToast(
                    result.message ||
                    'Failed to update status',
                    'error'
                );
    
                return;
            }
    
            const statusText = toggle
                .closest('td')
                .querySelector('.status-text');
    
            if (statusText) {
                statusText.textContent =
                    toggle.checked
                        ? 'Active'
                        : 'Inactive';
            }
    
            showToast(
                result.message ||
                (
                    toggle.checked
                        ? 'Department activated'
                        : 'Department deactivated'
                ),
                'success'
            );
    
        } catch (error) {
    
            toggle.checked = originalState;
    
            showToast(
                'Something went wrong',
                'error'
            );
        }
    
    });
});
