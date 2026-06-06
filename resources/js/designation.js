window.openDesignationModal = function(mode = 'create', designation = {}) {
    const form = document.getElementById('designationForm');
    const createAction = form.dataset.createAction;
    const isEdit = mode === 'edit';
    document.getElementById('designationModalTitle').textContent = isEdit ? 'Edit Designation' :
        'Create Designation';
    document.getElementById('designationSubmitButton').textContent = isEdit ? 'Update Designation' :
        'Create Designation';
    form.action = isEdit ? designation.action : createAction;
    document.getElementById('designationFormIsEdit').value = isEdit ? 1 : 0;
    document.getElementById('designationName').value = designation.name || '';
    const departmentSelect = document.getElementById('designationDepartmentId');
    if (departmentSelect.tomselect) {
        departmentSelect.tomselect.setValue(
            isEdit ? designation.departmentId : '',
            true
        );
    }
    modalHelper.open('designationFormModal');
};

document.addEventListener('click', function(event) {
    const editButton = event.target.closest('.js-edit-designation');
    if (editButton) {
        openDesignationModal('edit', editButton.dataset);
        return;
    }
});

document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('designationForm');
    if (!form) {
        return;
    }

    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        const form = this;
        const button = document.getElementById('designationSubmitButton');
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
                    result.message || 'Designation saved successfully',
                    'error'
                );
                return;
            }
    
            modalHelper.close('designationFormModal');
            form.reset();
            Livewire.dispatch('refresh-table');
            showToast(
                result.message || 'Designation saved successfully',
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
