@php
    $displayName = old('display_name', $role->display_name ?? '');
    $name = old('name', $role->name ?? '');
@endphp

<div class="card" style="padding:20px">
    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
        <div>
            <label class="field-label">Display Name <span class="text-red-400">*</span></label>

            <div class="field-wrap relative">
                <input class="field-input" id="displayNameInput" name="display_name" placeholder="Enter display name"
                    required type="text" value="{{ $displayName }}" />
            </div>

            @error('display_name')
                <p class="err-msg">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="field-label">Name <span class="text-red-400">*</span></label>

            <div class="field-wrap relative">
                <input class="field-input" id="nameInput" name="name" readonly type="text"
                    value="{{ $name }}" />
            </div>

            @error('name')
                <p class="err-msg">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="mt-6">

        <div class="mb-4 flex items-center justify-between">
            <div>
                <div class="section-title" style="font-size:18px">
                    Permissions
                </div>

                <div class="section-sub">
                    Manage role permissions
                </div>
            </div>


            <label class="inline-flex cursor-pointer items-center gap-2">

                <input class="peer sr-only" id="selectAllPermissions" type="checkbox">

                <div
                    class="flex h-5 w-5 items-center justify-center rounded-md border border-gray-300 bg-white transition-all duration-200 peer-checked:border-blue-600 peer-checked:bg-blue-600 dark:border-gray-600 dark:bg-gray-800">

                    <svg class="h-3.5 w-3.5 text-white opacity-0 transition-all duration-200" fill="none"
                        stroke-width="3" stroke="currentColor" viewBox="0 0 24 24">

                        <path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round" />

                    </svg>

                </div>

                <span class="text-sm font-medium text-[var(--text)]">
                    Select All
                </span>

            </label>
        </div>

        <div class="overflow-hidden rounded-xl border border-[var(--border)]">

            <table class="w-full">

                <thead>
                    <tr>
                        <th class="table-th px-4 py-3 text-left">
                            Permission
                        </th>

                        <th class="table-th px-4 py-3 text-center">
                            All
                        </th>

                        <th class="table-th px-4 py-3 text-center">
                            View
                        </th>

                        <th class="table-th px-4 py-3 text-center">
                            Create
                        </th>

                        <th class="table-th px-4 py-3 text-center">
                            Edit
                        </th>

                        <th class="table-th px-4 py-3 text-center">
                            Delete
                        </th>
                    </tr>
                </thead>

                <tbody>

                    @foreach ($permissions as $module => $actions)
                        @php
                            $moduleSlug = Str::slug($module);
                        @endphp

                        <tr class="border-t border-[var(--border)] hover:bg-[var(--surface-hover)]">

                            <td class="px-4 py-3 font-medium text-[var(--text)]">
                                {{ $module }}
                            </td>

                            <td class="px-4 py-3 text-center">
                                <label class="inline-flex cursor-pointer items-center">

                                    <input class="module-checkbox peer sr-only" data-module="{{ $moduleSlug }}"
                                        type="checkbox">

                                    <div
                                        class="flex h-5 w-5 items-center justify-center rounded-md border border-gray-300 bg-white transition-all duration-200 peer-checked:border-blue-600 peer-checked:bg-blue-600 dark:border-gray-600 dark:bg-gray-800">

                                        <svg class="h-3.5 w-3.5 text-white opacity-0 transition-all duration-200"
                                            fill="none" stroke-width="3" stroke="currentColor" viewBox="0 0 24 24">

                                            <path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round" />

                                        </svg>

                                    </div>

                                </label>
                            </td>

                            @foreach (['view', 'create', 'edit', 'delete'] as $action)
                                <td class="px-4 py-3 text-center">

                                    @if ($actions[$action])
                                        <label class="inline-flex cursor-pointer items-center">

                                            <input @checked(in_array($actions[$action]->name, $selectedPermissions)) class="permission-checkbox peer sr-only"
                                                data-action="{{ $action }}" data-module="{{ $moduleSlug }}"
                                                name="permissions[]" type="checkbox"
                                                value="{{ $actions[$action]->name }}">

                                            <div
                                                class="flex h-5 w-5 items-center justify-center rounded-md border border-gray-300 bg-white transition-all duration-200 peer-checked:border-blue-600 peer-checked:bg-blue-600 dark:border-gray-600 dark:bg-gray-800">

                                                <svg class="h-3.5 w-3.5 text-white opacity-0 transition-all duration-200"
                                                    fill="none" stroke-width="3" stroke="currentColor"
                                                    viewBox="0 0 24 24">

                                                    <path d="M5 13l4 4L19 7" stroke-linecap="round"
                                                        stroke-linejoin="round" />
                                                </svg>

                                            </div>

                                        </label>
                                    @else
                                        <span class="text-gray-400">
                                            —
                                        </span>
                                    @endif

                                </td>
                            @endforeach

                        </tr>
                    @endforeach

                </tbody>

            </table>

        </div>

    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {

        const permissionCheckboxes =
            document.querySelectorAll('.permission-checkbox');

        const moduleCheckboxes =
            document.querySelectorAll('.module-checkbox');

        const selectAll =
            document.getElementById('selectAllPermissions');

        function syncModule(module) {

            const permissions = document.querySelectorAll(
                `.permission-checkbox[data-module="${module}"]`
            );

            const moduleCheckbox = document.querySelector(
                `.module-checkbox[data-module="${module}"]`
            );

            if (!moduleCheckbox) {
                return;
            }

            moduleCheckbox.checked = [...permissions].length > 0 && [...permissions].every(item => item
                .checked);
        }

        function updateModuleRules(module) {

            const view = document.querySelector(
                `.permission-checkbox[data-module="${module}"][data-action="view"]`
            );

            const create = document.querySelector(
                `.permission-checkbox[data-module="${module}"][data-action="create"]`
            );

            const edit = document.querySelector(
                `.permission-checkbox[data-module="${module}"][data-action="edit"]`
            );

            const del = document.querySelector(
                `.permission-checkbox[data-module="${module}"][data-action="delete"]`
            );

            if (!view) {
                return;
            }

            const hasOtherPermissions =
                create?.checked ||
                edit?.checked ||
                del?.checked;

            // Create/Edit/Delete => Auto View
            if (hasOtherPermissions) {
                view.checked = true;
            }

            syncModule(module);
        }

        function syncGlobalCheckbox() {

            if (!selectAll) {
                return;
            }

            selectAll.checked = [...permissionCheckboxes].every(item => item.checked);
        }

        selectAll?.addEventListener('change', function() {

            permissionCheckboxes.forEach(item => {
                item.checked = this.checked;
            });

            moduleCheckboxes.forEach(item => {
                item.checked = this.checked;
            });
        });

        moduleCheckboxes.forEach(item => {

            item.addEventListener('change', function() {

                document
                    .querySelectorAll(
                        `.permission-checkbox[data-module="${this.dataset.module}"]`
                    )
                    .forEach(permission => {
                        permission.checked = this.checked;
                    });

                updateModuleRules(this.dataset.module);

                syncGlobalCheckbox();
            });

        });

        permissionCheckboxes.forEach(item => {

            item.addEventListener('change', function() {

                const module = this.dataset.module;
                const action = this.dataset.action;

                if (
                    action === 'view' &&
                    !this.checked
                ) {

                    document
                        .querySelectorAll(
                            `.permission-checkbox[data-module="${module}"]`
                        )
                        .forEach(permission => {

                            if (
                                permission.dataset.action !== 'view'
                            ) {
                                permission.checked = false;
                            }

                        });
                }

                updateModuleRules(module);

                syncGlobalCheckbox();
            });

        });

        moduleCheckboxes.forEach(item => {
            updateModuleRules(item.dataset.module);
        });

        syncGlobalCheckbox();

        const displayNameInput = document.getElementById('displayNameInput');
        const nameInput = document.getElementById('nameInput');

        function generateRoleName(value) {
            return value.toLowerCase().trim().replace(/[^a-z0-9]+/g, '');
        }

        function syncRoleName() {
            if (!displayNameInput || !nameInput || nameInput.value == 'superadmin') {
                return;
            }
            nameInput.value = generateRoleName(
                displayNameInput.value
            );
        }
        displayNameInput?.addEventListener('input', syncRoleName);
    });
</script>
