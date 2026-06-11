<x-app-layout>

    <div class="p-5">
        <div>
            <h1 class="section-title">
                Edit Role
            </h1>

            <p class="section-sub">
                Update role information and permissions
            </p>
        </div>
    </div>

    <form action="{{ route('roles.update', $role->id) }}" method="POST">
        @csrf
        @method('put')

        @include('roles.form', [
            'role' => $role,
            'crudPermissions' => $crudPermissions,
            'otherPermissions' => $otherPermissions,
            'selectedPermissions' => old('permissions', $role->permissions->pluck('name')->toArray()),
        ])

        <div class="mt-5 flex items-center justify-end gap-3">
            <a class="btn-ghost no-underline" href="{{ route('roles.index') }}">
                Cancel
            </a>
            <button class="btn-primary" type="submit">
                Update Role
            </button>
        </div>
    </form>

</x-app-layout>
