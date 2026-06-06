<x-app-layout>

    <div style="padding:20px;border-bottom:1px solid var(--border)">
        <div class="section-title">Create Role</div>
        <div class="section-sub">Add a new system role and assign permissions</div>
    </div>

    <form action="{{ route('roles.store') }}" method="POST">
        @csrf

        @include('roles.form', [
            'role' => null,
            'permissions' => $permissions,
            'selectedPermissions' => old('permissions', []),
        ])

        <div class="mt-5 flex items-center justify-end gap-3">
            <a class="btn-ghost no-underline" href="{{ route('roles.index') }}">
                Cancel
            </a>
            <button class="btn-primary" type="submit">
                Create Role
            </button>
        </div>
    </form>

</x-app-layout>
