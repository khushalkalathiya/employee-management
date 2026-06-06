<x-app-layout>

    <div class="space-y-6">

        {{-- Header --}}
        <div class="flex items-center justify-between">

            <div>
                <h1 class="page-title">
                    Employee Details
                </h1>

                <p class="page-subtitle">
                    View employee information and assigned role.
                </p>
            </div>

            <div class="flex items-center gap-3">

                @can('employee.edit')
                    <a class="btn-primary" href="{{ route('employees.edit', $employee) }}">
                        Edit Employee
                    </a>
                @endcan

                <a class="btn-ghost no-underline" href="{{ route('employees.index') }}">
                    Back
                </a>

            </div>

        </div>

        {{-- employee Card --}}
        <div class="card p-6">

            <div class="flex flex-col gap-6 lg:flex-row">

                {{-- Avatar --}}
                <div class="flex justify-center lg:w-64">

                    <div class="text-center">
                        @php
                            $initials = strtoupper(
                                substr($employee->first_name ?? '', 0, 1) . substr($employee->last_name ?? '', 0, 1),
                            );
                        @endphp
                        @if ($employee->avatar)
                            <img alt="{{ $employee->full_name }}"
                                class="h-32 w-32 rounded-full border border-[var(--border)] object-cover shadow-sm"
                                src="{{ $employee->avatar }}">
                        @else
                            <div
                                class="mx-auto flex h-32 w-32 items-center justify-center rounded-full bg-gradient-to-br from-blue-500 to-blue-700 text-3xl font-bold text-white shadow-lg">
                                {{ $initials }}
                            </div>
                        @endif

                        <h2 class="mt-4 text-xl font-semibold">
                            {{ $employee->full_name }}
                        </h2>

                        <p class="text-sm text-[var(--muted)]">
                            {{ $employee->email }}
                        </p>

                    </div>

                </div>

                {{-- Details --}}
                <div class="flex-1">

                    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

                        <div>
                            <div class="field-label">
                                First Name
                            </div>

                            <div class="field-view">
                                {{ $employee->first_name }}
                            </div>
                        </div>

                        <div>
                            <div class="field-label">
                                Last Name
                            </div>

                            <div class="field-view">
                                {{ $employee->last_name }}
                            </div>
                        </div>

                        <div>
                            <div class="field-label">
                                Email Address
                            </div>

                            <div class="field-view">
                                {{ $employee->email }}
                            </div>
                        </div>

                        <div>
                            <div class="field-label">
                                Phone Number
                            </div>

                            <div class="field-view">
                                {{ $employee->phone ?: '—' }}
                            </div>
                        </div>

                        <div>
                            <div class="field-label">
                                Status
                            </div>

                            <div class="field-view">

                                @if ($employee->status)
                                    <span
                                        class="inline-flex rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-700 dark:bg-green-500/10 dark:text-green-400">
                                        Active
                                    </span>
                                @else
                                    <span
                                        class="inline-flex rounded-full bg-red-100 px-3 py-1 text-xs font-medium text-red-700 dark:bg-red-500/10 dark:text-red-400">
                                        Inactive
                                    </span>
                                @endif

                            </div>
                        </div>

                        <div>
                            <div class="field-label">
                                Role
                            </div>

                            <div class="field-view">

                                @foreach ($employee->roles as $role)
                                    <span
                                        class="inline-flex rounded-full bg-blue-100 px-3 py-1 text-xs font-medium text-blue-700 dark:bg-blue-500/10 dark:text-blue-400">
                                        {{ $role->display_name ?? $role->name }}
                                    </span>
                                @endforeach

                            </div>
                        </div>

                        <div>
                            <div class="field-label">
                                Created At
                            </div>

                            <div class="field-view">
                                {{ $employee->created_at->format('d M Y h:i A') }}
                            </div>
                        </div>

                        <div>
                            <div class="field-label">
                                Updated At
                            </div>

                            <div class="field-view">
                                {{ $employee->updated_at->format('d M Y h:i A') }}
                            </div>
                        </div>

                    </div>

                </div>

            </div>

        </div>

        {{-- Permissions --}}
        <div class="card p-6">

            <div class="mb-4">
                <h3 class="section-title">
                    Permissions
                </h3>

                <p class="section-sub">
                    Effective permissions assigned through roles.
                </p>
            </div>

            <div class="grid grid-cols-1 gap-3 md:grid-cols-2 xl:grid-cols-3">

                @forelse ($employee->getAllPermissions() as $permission)
                    <div class="rounded-lg border border-[var(--border)] px-4 py-3">

                        {{ str($permission->name)->headline() }}

                    </div>

                @empty

                    <div class="text-sm text-[var(--muted)]">
                        No permissions assigned.
                    </div>
                @endforelse

            </div>

        </div>

    </div>
</x-app-layout>
