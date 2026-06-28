<div>
    <div style="margin-bottom:20px">
        <div style="padding:20px 20px 0;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;margin-bottom:16px">
            <div>
                <div class="section-title">Work Logs Management</div>
                <div class="section-sub">Track and document daily projects and tasks</div>
            </div>

            <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap">
                <div class="search-wrap" style="margin-left:8px">
                    <span class="search-icon">
                        <svg fill="none" height="14" stroke-width="2" stroke="currentColor" viewBox="0 0 24 24" width="14">
                            <circle cx="11" cy="11" r="8" />
                            <line x1="21" x2="16.65" y1="21" y2="16.65" />
                        </svg>
                    </span>
                    <input class="search-inp" placeholder="Search work logs" style="min-width:250px" type="text"
                        wire:model.live.debounce.500ms="search" />
                </div>

                @can('Work Logs Create')
                    <button class="btn-primary inline-flex h-fit items-center" onclick="openWorkLogModal('create')" type="button">
                        Create Work Log
                    </button>
                @elsecan('work_log.create')
                    <button class="btn-primary inline-flex h-fit items-center" onclick="openWorkLogModal('create')" type="button">
                        Create Work Log
                    </button>
                @endcan
            </div>
        </div>

        <div class="card" style="overflow-x:auto">
            <table class="data-table">
                <thead>
                    <tr>
                        @if (!$isOwnOnly)
                            <th style="padding:10px 15px;text-align:left;font-size:11px;font-weight:600;text-transform:uppercase;color:var(--muted);border-bottom:1px solid var(--border)">Employee</th>
                            <th style="padding:10px 15px;text-align:left;font-size:11px;font-weight:600;text-transform:uppercase;color:var(--muted);border-bottom:1px solid var(--border)">Email</th>
                        @endif
                        <th style="padding:10px 15px;text-align:left;font-size:11px;font-weight:600;text-transform:uppercase;color:var(--muted);border-bottom:1px solid var(--border)">Date</th>
                        <th style="padding:10px 15px;text-align:left;font-size:11px;font-weight:600;text-transform:uppercase;color:var(--muted);border-bottom:1px solid var(--border)">Project Title</th>
                        <th style="padding:10px 15px;text-align:left;font-size:11px;font-weight:600;text-transform:uppercase;color:var(--muted);border-bottom:1px solid var(--border)">Description</th>
                        <th style="padding:10px 15px;text-align:left;font-size:11px;font-weight:600;text-transform:uppercase;color:var(--muted);border-bottom:1px solid var(--border)">Images</th>
                        <th width="110" style="padding:10px 15px;text-align:left;font-size:11px;font-weight:600;text-transform:uppercase;color:var(--muted);border-bottom:1px solid var(--border)">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($works as $work)
                        <tr>
                            @if (!$isOwnOnly)
                                <td>
                                    <div class="text-sm font-semibold text-[var(--text)]">
                                        {{ $work->employee->user->full_name }}
                                    </div>
                                </td>
                                <td>
                                    <div class="text-sm text-gray-600 dark:text-gray-400">
                                        {{ $work->employee->user->email }}
                                    </div>
                                </td>
                            @endif
                            <td>
                                <div class="text-sm font-semibold text-[var(--text)]">
                                    {{ $work->date->format('d M Y') }}
                                </div>
                            </td>
                            <td>
                                <div class="text-sm font-semibold text-[var(--text)]">
                                    {{ $work->project_title }}
                                </div>
                            </td>
                            <td class="max-w-md">
                                <div class="text-sm text-gray-600 dark:text-gray-400 line-clamp-2">
                                    {!! $work->description !!}
                                </div>
                            </td>
                            <td>
                                <div class="flex -space-x-2 overflow-hidden">
                                    @foreach ($work->getMedia('work_images')->take(3) as $media)
                                        <img class="inline-block h-8 w-8 rounded-full ring-2 ring-white dark:ring-gray-900 object-cover" src="{{ $media->getUrl() }}" alt="{{ $media->name }}">
                                    @endforeach
                                    @if ($work->getMedia('work_images')->count() > 3)
                                        <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-800 text-xs font-bold text-gray-600 dark:text-gray-400 ring-2 ring-white dark:ring-gray-900">
                                            +{{ $work->getMedia('work_images')->count() - 3 }}
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div style="display:flex;align-items:center;gap:6px">
                                    @if (auth()->user()->can('Work Logs Edit') || auth()->user()->can('work_log.edit'))
                                        <button class="btn-ghost"
                                            onclick="openWorkLogModal('edit', {
                                                id: {{ $work->id }},
                                                date: '{{ $work->date->format('Y-m-d') }}',
                                                employeeId: {{ $work->employee_id }},
                                                projectTitle: '{{ addslashes($work->project_title) }}',
                                                description: '{{ addslashes(str_replace(["\r", "\n"], ["", '\n'], $work->description)) }}',
                                                action: '{{ route('work-logs.update', $work) }}',
                                                images: '{{ json_encode($work->getMedia('work_images')->map(fn($media) => ['id' => $media->id, 'url' => $media->getUrl()])) }}'
                                            })"
                                            style="padding:6px" title="Edit" type="button">
                                            <svg fill="none" height="16" stroke-width="2" stroke="currentColor" viewBox="0 0 24 24" width="16">
                                                <path d="M11 5H6a2 2 0 0 0-2 2v11a2 2 0 0 0 2 2h11a2 2 0 0 0 2-2v-5" stroke-linecap="round" stroke-linejoin="round" />
                                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                        </button>
                                    @endif

                                    @if (auth()->user()->can('Work Logs Delete') || auth()->user()->can('work_log.delete'))
                                        <button class="btn-ghost" onclick="deleteWorkLogAJAX('{{ route('work-logs.destroy', $work) }}')" style="padding:6px" title="Delete" type="button">
                                            <svg fill="none" height="16" stroke-width="2" stroke="currentColor" viewBox="0 0 24 24" width="16">
                                                <path d="M3 6h18" stroke-linecap="round" stroke-linejoin="round" />
                                                <path d="M8 6V4a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2" stroke-linecap="round" stroke-linejoin="round" />
                                                <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6" stroke-linecap="round" stroke-linejoin="round" />
                                                <path d="M10 11v6M14 11v6" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="p-0" colspan="{{ $isOwnOnly ? 5 : 7 }}">
                                <div class="flex flex-col items-center justify-center px-6 py-14">
                                    <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                                        No work logs found
                                    </h3>
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                        There are no work logs matching your criteria.
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="padding:12px 20px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px">
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
                    Showing {{ $works->firstItem() ?? 0 }} to {{ $works->lastItem() ?? 0 }} of
                    {{ $works->total() }} results
                </span>
            </div>
            <div>{{ $works->links() }}</div>
        </div>
    </div>
</div>
