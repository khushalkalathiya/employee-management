<div>
    <div style="margin-bottom:20px">

        {{-- ── Header & Filters ── --}}
        <div
            style="padding:20px 20px 0;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;margin-bottom:16px">
            <div>
                <div class="section-title">Payroll Management</div>
                <div class="section-sub">Manage and process employee payroll records</div>
            </div>

            <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap">

                {{-- Month filter --}}
                <input class="field-input" style="width:160px;height:36px;padding:0 10px;font-size:13px"
                    title="Filter by month" type="month" wire:model.live="monthFilter">

                {{-- Status filter --}}
                <select class="field-input" style="width:140px;height:36px;padding:0 10px;font-size:13px"
                    wire:model.live="statusFilter">
                    <option value="">All Statuses</option>
                    <option value="pending">Pending</option>
                    <option value="paid">Paid</option>
                    <option value="cancelled">Cancelled</option>
                </select>

                {{-- Search --}}
                <div class="search-wrap">
                    <span class="search-icon">
                        <svg fill="none" height="14" stroke-width="2" stroke="currentColor" viewBox="0 0 24 24"
                            width="14">
                            <circle cx="11" cy="11" r="8" />
                            <line x1="21" x2="16.65" y1="21" y2="16.65" />
                        </svg>
                    </span>
                    <input class="search-inp" placeholder="Search employee…" style="min-width:220px" type="text"
                        wire:model.live.debounce.500ms="search" />
                </div>

                @can('payroll.create')
                    <a class="btn-primary inline-flex h-fit items-center" href="{{ route('payroll.create') }}">
                        <svg fill="none" height="15" stroke-width="2.5" stroke="currentColor" viewBox="0 0 24 24"
                            width="15">
                            <path d="M12 5v14M5 12h14" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        Generate Payroll
                    </a>
                @endcan
            </div>
        </div>

        {{-- ── Table ── --}}
        <div class="card" style="overflow-x:auto">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Employee</th>
                        <th>Period</th>
                        <th>Type</th>
                        <th>Working / Hours</th>
                        <th>Gross Earned</th>
                        <th>Deductions</th>
                        <th>Net Pay</th>
                        <th class="text-center">Status</th>
                        <th width="120">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($payrolls as $payroll)
                        @php
                            $emp = optional($payroll->employee);
                            $user = optional($emp->user);
                            $totalDed = $payroll->pf_amount + $payroll->other_deductions + $payroll->hold_amount;
                        @endphp
                        <tr>
                            {{-- Employee --}}
                            <td>
                                <div class="emp-cell">
                                    @if ($user->avatar)
                                        <img alt="{{ $user->full_name }}"
                                            class="h-9 w-9 rounded-full object-cover ring-2 ring-white dark:ring-gray-800"
                                            src="{{ $user->avatar }}">
                                    @else
                                        <div
                                            class="emp-avatar flex h-9 w-9 items-center justify-center rounded-full bg-gradient-to-br from-blue-500 to-blue-700 text-sm font-semibold text-white">
                                            {{ $user->initials ?? '??' }}
                                        </div>
                                    @endif
                                    <div>
                                        <div class="font-display text-sm font-semibold text-[var(--text)]">
                                            {{ $user->full_name ?? '—' }}
                                        </div>
                                        <div class="text-xs text-[var(--muted)]">
                                            {{ optional($emp->department)->name ?? '—' }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            {{-- Period --}}
                            <td>
                                <div class="text-sm font-semibold text-[var(--text)]">
                                    {{ $payroll->period_label }}
                                </div>
                                <div class="mt-0.5 text-xs text-[var(--muted)]">
                                    Generated {{ dateFormat($payroll->created_at) }}
                                </div>
                            </td>

                            {{-- Type --}}
                            <td>
                                @if ($payroll->is_hourly)
                                    <span class="status-pill pill-blue">Hourly</span>
                                @else
                                    <span class="status-pill pill-gray">Monthly</span>
                                @endif
                            </td>

                            {{-- Working / Hours --}}
                            <td>
                                @if ($payroll->is_hourly)
                                    <span
                                        class="text-sm font-semibold text-[var(--text)]">{{ numberFormat($payroll->total_hours) }}
                                        hrs</span>
                                @else
                                    <span
                                        class="text-sm font-semibold text-[var(--text)]">{{ numberFormat($payroll->present_days) }}
                                        / {{ $payroll->working_days }}</span>
                                    <span class="ml-1 text-xs text-[var(--muted)]">days</span>
                                @endif
                            </td>

                            {{-- Gross --}}
                            <td>
                                <span
                                    class="text-sm font-semibold text-[var(--text)]">{{ currencyFormat($payroll->earned_salary) }}</span>
                            </td>

                            {{-- Deductions --}}
                            <td>
                                @if ($totalDed > 0)
                                    <span class="text-sm font-semibold text-red-500">-
                                        {{ currencyFormat($totalDed) }}</span>
                                @else
                                    <span class="text-sm text-[var(--muted)]">—</span>
                                @endif
                            </td>

                            {{-- Net pay --}}
                            <td>
                                <span
                                    class="text-sm font-bold text-emerald-600 dark:text-emerald-400">{{ currencyFormat($payroll->final_salary) }}</span>
                            </td>

                            {{-- Status --}}
                            <td class="text-center">
                                <span class="status-pill {{ $payroll->status_badge_class }}">
                                    {{ ucfirst($payroll->status) }}
                                </span>
                            </td>

                            {{-- Actions --}}
                            <td>
                                <div style="display:flex;align-items:center;gap:5px">

                                    {{-- View / Payslip --}}
                                    <a class="btn-ghost" href="{{ route('payroll.show', $payroll) }}"
                                        style="padding:6px" title="View Payslip">
                                        <svg fill="none" height="16" stroke-width="2" stroke="currentColor"
                                            viewBox="0 0 24 24" width="16">
                                            <path d="M1 12C1 12 5 5 12 5s11 7 11 7-4 7-11 7S1 12 1 12z"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                            <circle cx="12" cy="12" r="3" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                        </svg>
                                    </a>

                                    @can('payroll.edit')
                                        {{-- Edit --}}
                                        <a class="btn-ghost" href="{{ route('payroll.edit', $payroll) }}"
                                            style="padding:6px" title="Edit">
                                            <svg fill="none" height="16" stroke-width="2" stroke="currentColor"
                                                viewBox="0 0 24 24" width="16">
                                                <path d="M11 5H6a2 2 0 0 0-2 2v11a2 2 0 0 0 2 2h11a2 2 0 0 0 2-2v-5"
                                                    stroke-linecap="round" stroke-linejoin="round" />
                                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"
                                                    stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                        </a>

                                        {{-- Mark Paid --}}
                                        @if ($payroll->status !== 'paid')
                                            <button class="btn-ghost js-mark-paid"
                                                data-url="{{ route('payroll.mark-paid', $payroll) }}" style="padding:6px"
                                                title="Mark as Paid" type="button">
                                                <svg fill="none" height="16" stroke-width="2"
                                                    stroke="currentColor" viewBox="0 0 24 24" width="16">
                                                    <path d="M9 12l2 2 4-4" stroke-linecap="round"
                                                        stroke-linejoin="round" />
                                                    <circle cx="12" cy="12" r="9" stroke-linecap="round"
                                                        stroke-linejoin="round" />
                                                </svg>
                                            </button>
                                        @endif
                                    @endcan

                                    @can('payroll.delete')
                                        <button class="btn-ghost js-delete-confirm"
                                            data-description="Are you sure you want to delete this payroll record for {{ $user->full_name ?? 'this employee' }}?"
                                            data-title="Delete Payroll"
                                            data-url="{{ route('payroll.destroy', $payroll) }}" style="padding:6px"
                                            title="Delete" type="button">
                                            <svg fill="none" height="16" stroke-width="2" stroke="currentColor"
                                                viewBox="0 0 24 24" width="16">
                                                <path d="M3 6h18" stroke-linecap="round" stroke-linejoin="round" />
                                                <path d="M8 6V4a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                                <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"
                                                    stroke-linecap="round" stroke-linejoin="round" />
                                                <path d="M10 11v6M14 11v6" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                            </svg>
                                        </button>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="p-0" colspan="9">
                                <div class="flex flex-col items-center justify-center px-6 py-16">
                                    <div
                                        class="flex h-16 w-16 items-center justify-center rounded-full bg-blue-50 text-blue-400 dark:bg-blue-500/10">
                                        <svg fill="currentColor" height="30" viewBox="0 0 24 24" width="30">
                                            <path
                                                d="M11.8 10.9c-2.27-.59-3-1.2-3-2.15 0-1.09 1.01-1.85 2.7-1.85 1.78 0 2.44.85 2.5 2.1h2.21c-.07-1.72-1.12-3.3-3.21-3.81V3h-3v2.16c-1.94.42-3.5 1.68-3.5 3.61 0 2.31 1.91 3.46 4.7 4.13 2.5.6 3 1.48 3 2.41 0 .69-.49 1.79-2.7 1.79-2.06 0-2.87-.92-2.98-2.1h-2.2c.12 2.19 1.76 3.42 3.68 3.83V21h3v-2.15c1.95-.37 3.5-1.5 3.5-3.55 0-2.84-2.43-3.81-4.7-4.4z" />
                                        </svg>
                                    </div>
                                    <h3 class="mt-4 text-sm font-semibold text-gray-900 dark:text-gray-100">No payroll
                                        records found</h3>
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                        @can('payroll.create')
                                            Get started by generating a payroll record.
                                        @else
                                            No payroll records match your current filters.
                                        @endcan
                                    </p>
                                    @can('payroll.create')
                                        <a class="btn-primary mt-4 inline-flex items-center gap-2"
                                            href="{{ route('payroll.create') }}">
                                            Generate Payroll
                                        </a>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- ── Pagination ── --}}
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
                    Showing {{ $payrolls->firstItem() ?? 0 }} to {{ $payrolls->lastItem() ?? 0 }} of
                    {{ $payrolls->total() }} results
                </span>
            </div>
            <div>{{ $payrolls->links() }}</div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.addEventListener('click', async (e) => {
                const btn = e.target.closest('.js-mark-paid');
                if (!btn) return;

                const url = btn.dataset.url;
                const originalHtml = btn.innerHTML;

                btn.disabled = true;
                btn.innerHTML =
                    '<svg class="animate-spin" width="16" height="16" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>';

                try {
                    const res = await fetch(url, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .content,
                            'Accept': 'application/json',
                        }
                    });
                    const data = await res.json();
                    if (data.success) {
                        if (window.showToast) window.showToast(data.message, 'success');
                        if (typeof Livewire !== 'undefined') Livewire.dispatch('refresh-table');
                    } else {
                        if (window.showToast) window.showToast(data.message || 'Failed', 'error');
                    }
                } catch (err) {
                    if (window.showToast) window.showToast('Server error', 'error');
                } finally {
                    btn.disabled = false;
                    btn.innerHTML = originalHtml;
                }
            });
        });
    </script>
</div>
