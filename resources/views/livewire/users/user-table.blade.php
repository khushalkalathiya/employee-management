<div>
    <div style="margin-bottom:20px">

        <div
            style="padding:20px 20px 0;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;margin-bottom:16px">

            <div>
                <div class="section-title">Users Management</div>
                <div class="section-sub">Manage system users</div>
            </div>

            <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap">

                <div class="search-wrap" style="margin-left:8px">
                    <span class="search-icon">
                        <svg fill="none" height="14" stroke-width="2" stroke="currentColor" viewBox="0 0 24 24"
                            width="14">
                            <circle cx="11" cy="11" r="8" />
                            <line x1="21" x2="16.65" y1="21" y2="16.65" />
                        </svg>
                    </span>
                    <input class="search-inp" placeholder="Search users" style="min-width:250px" type="text"
                        wire:model.live.debounce.500ms="search" />
                </div>

                @can('user.create')
                    <a class="btn-primary inline-flex h-fit items-center no-underline" href="{{ route('users.create') }}">
                        Create User
                    </a>
                @endcan

            </div>

        </div>

        <div class="card" style="overflow-x:auto">

            <table class="data-table">

                <thead>
                    <tr>
                        <x-table-sort :sort-direction="$sortDirection" :sort-field="$sortField" field="first_name" label="User" />
                        <x-table-sort :sort-direction="$sortDirection" :sort-field="$sortField" field="phone" label="Phone" />
                        <th>Role</th>
                        <x-table-sort :sort-direction="$sortDirection" :sort-field="$sortField" field="joining_date" label="Joining Date" />
                        <x-table-sort :sort-direction="$sortDirection" :sort-field="$sortField" field="is_active" label="Status" />
                        <th width="120">Actions</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($users as $user)
                        <tr>

                            @php
                                $initials = strtoupper(
                                    substr($user->first_name ?? '', 0, 1) . substr($user->last_name ?? '', 0, 1),
                                );
                            @endphp

                            <td>
                                <div class="emp-cell">

                                    @if ($user->avatar)
                                        <img alt="{{ $user->first_name }}"
                                            class="h-10 w-10 rounded-full object-cover ring-2 ring-white dark:ring-gray-800"
                                            src="{{ $user->avatar }}">
                                    @else
                                        <div
                                            class="emp-avatar flex h-10 w-10 items-center justify-center rounded-full bg-gradient-to-br from-blue-500 to-blue-700 p-5 text-sm font-semibold text-white">

                                            {{ $initials }}

                                        </div>
                                    @endif

                                    <div>
                                        <div class="font-display text-sm font-semibold text-[var(--text)]">
                                            {{ $user->first_name }} {{ $user->last_name }}
                                        </div>

                                        <div class="text-xs text-[var(--muted)]">
                                            {{ $user->email }}
                                        </div>
                                    </div>

                                </div>
                            </td>

                            <td>{{ $user->phone ?: '-' }}</td>

                            <td>
                                {{ $user->roles->pluck('name')->implode(', ') ?: '-' }}
                            </td>

                            <td>
                                {{ $user->joining_date ? dateFormat($user->joining_date) : '-' }}
                            </td>

                            <td>
                                @if ($user->is_active)
                                    <span class="status-pill pill-green">
                                        Active
                                    </span>
                                @else
                                    <span class="status-pill pill-red">
                                        Inactive
                                    </span>
                                @endif
                            </td>

                            <td>
                                <div style="display:flex;align-items:center;gap:6px">

                                    @can('user.view')
                                        <a class="btn-ghost" href="{{ route('users.show', $user) }}" style="padding:6px"
                                            title="View">

                                            <svg fill="none" height="16" stroke-width="2" stroke="currentColor"
                                                viewBox="0 0 24 24" width="16" xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5s8.268 2.943 9.542 7c-1.274 4.057-5.065 7-9.542 7S3.732 16.057 2.458 12z"
                                                    stroke-linecap="round" stroke-linejoin="round" />
                                                <circle cx="12" cy="12" r="3" />
                                            </svg>
                                        </a>
                                    @endcan

                                    @can('user.edit')
                                        <a class="btn-ghost" href="{{ route('users.edit', $user) }}" style="padding:6px"
                                            title="Edit">

                                            <svg fill="none" height="16" stroke-width="2" stroke="currentColor"
                                                viewBox="0 0 24 24" width="16" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M11 5H6a2 2 0 0 0-2 2v11a2 2 0 0 0 2 2h11a2 2 0 0 0 2-2v-5"
                                                    stroke-linecap="round" stroke-linejoin="round" />
                                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"
                                                    stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                        </a>
                                    @endcan

                                    @can('user.delete')
                                        <button class="btn-ghost js-delete-confirm"
                                            data-description="Are you sure you want to delete {{ $user->full_name }}?"
                                            data-title="Delete User" data-url="{{ route('users.destroy', $user) }}"
                                            style="padding:6px" title="Delete" type="button">
                                            <svg fill="none" height="16" stroke-width="2" stroke="currentColor"
                                                viewBox="0 0 24 24" width="16">
                                                <path d="M3 6h18" stroke-linecap="round" stroke-linejoin="round" />
                                                <path d="M8 6V4a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                                <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"
                                                    stroke-linecap="round" stroke-linejoin="round" />
                                                <path d="M10 11v6M14 11v6" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                        </button>
                                    @endcan

                                </div>
                            </td>

                        </tr>

                    @empty
                        <tr>
                            <td class="p-0" colspan="6">
                                <div class="flex flex-col items-center justify-center px-6 py-14">

                                    <div
                                        class="mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-gray-100 text-gray-400 dark:bg-gray-800 dark:text-gray-500">

                                        <svg class="h-7 w-7" fill="none" stroke-width="1.8" stroke="currentColor"
                                            viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M9 17h6m-6-4h6m-8 8h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>

                                    </div>

                                    <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                                        No users found
                                    </h3>

                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                        There are no users matching your criteria.
                                    </p>

                                </div>
                            </td>
                        </tr>
                    @endforelse

                </tbody>

            </table>

        </div>

        <div
            style="padding:12px 20px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px">

            <div style="display:flex;align-items:center;gap:12px;flex-wrap:wrap">

                <div style="display:flex;align-items:center;gap:8px">
                    <span style="font-size:13px;color:var(--muted)">Show</span>

                    <select class="input" style="width:75px;height:34px;padding:0 8px" wire:model.live="perPage">

                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>

                    </select>
                </div>

                <span style="font-size:13px;color:var(--muted)">
                    Showing
                    {{ $users->firstItem() ?? 0 }}
                    to
                    {{ $users->lastItem() ?? 0 }}
                    of
                    {{ $users->total() }}
                    results
                </span>

            </div>

            <div>
                {{ $users->links() }}
            </div>

        </div>
    </div>

</div>
