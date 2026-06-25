<x-app-layout>
    @php
        $emp = $salary->employee;
        $user = optional($emp)->user;
        $dept = optional($emp)->department;
        $desig = optional($emp)->designation;
        $processor = $salary->processedBy;
        $totalDed = $salary->pf_amount + $salary->other_deductions + $salary->hold_amount;
    @endphp

    {{-- ── Top bar ── --}}
    <div class="animate-fu d1 mb-6 flex flex-wrap items-center justify-between gap-3">
        <div>
            <h1 class="section-title text-2xl">Payslip</h1>
            <p class="section-sub mt-1">{{ $salary->period_label }}</p>
        </div>
        <div class="flex flex-wrap gap-2">
            @can('payroll.edit')
                <a class="btn-ghost" href="{{ route('payroll.edit', $salary) }}">
                    <svg fill="none" height="16" stroke-width="2" stroke="currentColor" viewBox="0 0 24 24"
                        width="16">
                        <path d="M11 5H6a2 2 0 0 0-2 2v11a2 2 0 0 0 2 2h11a2 2 0 0 0 2-2v-5" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                    Edit
                </a>
            @endcan
            <button class="btn-ghost" onclick="window.print()">
                <svg fill="none" height="16" stroke-width="2" stroke="currentColor" viewBox="0 0 24 24"
                    width="16">
                    <path d="M6 9V2h12v7" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2"
                        stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M6 14h12v8H6z" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                Print / PDF
            </button>
            <a class="btn-ghost" href="{{ route('payroll.index') }}">
                <svg fill="none" height="16" stroke-width="2" stroke="currentColor" viewBox="0 0 24 24"
                    width="16">
                    <path d="M19 12H5M12 5l-7 7 7 7" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                Back
            </a>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════════
         PAYSLIP CARD  (this entire block is printed)
    ══════════════════════════════════════════════════════════════ --}}
    <div class="payslip-wrapper animate-fu d2 mx-auto max-w-3xl" id="payslipDocument">
        <div class="card overflow-hidden">

            {{-- ── Header banner ── --}}
            <div
                class="payslip-header relative overflow-hidden bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-800 px-8 py-8">
                {{-- Decorative rings --}}
                <div
                    class="pointer-events-none absolute -right-10 -top-10 h-48 w-48 rounded-full border border-white/10">
                </div>
                <div class="pointer-events-none absolute -right-4 -top-4 h-32 w-32 rounded-full border border-white/10">
                </div>

                <div class="relative flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-center">
                    <div>
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-white/20">
                                <svg class="text-white" fill="currentColor" height="20" viewBox="0 0 24 24"
                                    width="20">
                                    <path
                                        d="M11.8 10.9c-2.27-.59-3-1.2-3-2.15 0-1.09 1.01-1.85 2.7-1.85 1.78 0 2.44.85 2.5 2.1h2.21c-.07-1.72-1.12-3.3-3.21-3.81V3h-3v2.16c-1.94.42-3.5 1.68-3.5 3.61 0 2.31 1.91 3.46 4.7 4.13 2.5.6 3 1.48 3 2.41 0 .69-.49 1.79-2.7 1.79-2.06 0-2.87-.92-2.98-2.1h-2.2c.12 2.19 1.76 3.42 3.68 3.83V21h3v-2.15c1.95-.37 3.5-1.5 3.5-3.55 0-2.84-2.43-3.81-4.7-4.4z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs font-bold uppercase tracking-widest text-blue-200">PeopleCore EMS</p>
                                <h2 class="text-lg font-extrabold text-white">Salary Payslip</h2>
                            </div>
                        </div>
                        <p class="mt-3 text-sm text-blue-200">
                            Period: <span class="font-semibold text-white">{{ $salary->period_label }}</span>
                        </p>
                        <p class="text-sm text-blue-200">
                            Generated on: <span
                                class="font-semibold text-white">{{ dateFormat($salary->created_at) }}</span>
                        </p>
                    </div>

                    <div class="text-right">
                        <p class="text-xs font-bold uppercase tracking-widest text-blue-200">Net Pay</p>
                        <p class="text-4xl font-extrabold text-white">{{ currencyFormat($salary->final_salary) }}</p>
                        <div
                            class="{{ $salary->status === 'paid' ? 'bg-emerald-500/30 text-emerald-100' : ($salary->status === 'cancelled' ? 'bg-red-500/30 text-red-100' : 'bg-amber-500/30 text-amber-100') }} mt-2 inline-flex items-center gap-1.5 rounded-full px-3 py-1">
                            <span
                                class="{{ $salary->status === 'paid' ? 'bg-emerald-300' : ($salary->status === 'cancelled' ? 'bg-red-300' : 'bg-amber-300') }} h-1.5 w-1.5 rounded-full"></span>
                            <span
                                class="text-xs font-bold uppercase tracking-wide">{{ ucfirst($salary->status) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── Body ── --}}
            <div class="p-8">

                {{-- Employee + Company details --}}
                <div class="mb-8 grid gap-6 sm:grid-cols-2">
                    {{-- Employee --}}
                    <div>
                        <p class="mb-3 text-xs font-bold uppercase tracking-widest text-[var(--muted)]">Employee Details
                        </p>
                        <div class="flex items-center gap-3">
                            @if ($user?->avatar)
                                <img alt="{{ $user->full_name }}"
                                    class="h-12 w-12 rounded-full object-cover ring-2 ring-blue-100 dark:ring-blue-500/20"
                                    src="{{ $user->avatar }}">
                            @else
                                <div
                                    class="flex h-12 w-12 items-center justify-center rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 text-base font-bold text-white">
                                    {{ $user?->initials ?? '??' }}
                                </div>
                            @endif
                            <div>
                                <p class="text-base font-bold text-[var(--text)]">{{ $user?->full_name ?? '—' }}</p>
                                <p class="text-xs text-[var(--muted)]">{{ $user?->email ?? '—' }}</p>
                                <p class="text-xs text-[var(--muted)]">{{ $user?->phone ?? '' }}</p>
                            </div>
                        </div>
                        <div class="mt-4 space-y-1 text-sm">
                            <div class="flex gap-2">
                                <span class="w-28 text-[var(--muted)]">Employee ID</span>
                                <span class="font-semibold text-[var(--text)]">{{ $emp?->employee_code ?? '—' }}</span>
                            </div>
                            <div class="flex gap-2">
                                <span class="w-28 text-[var(--muted)]">Department</span>
                                <span class="font-semibold text-[var(--text)]">{{ $dept?->name ?? '—' }}</span>
                            </div>
                            <div class="flex gap-2">
                                <span class="w-28 text-[var(--muted)]">Designation</span>
                                <span class="font-semibold text-[var(--text)]">{{ $desig?->name ?? '—' }}</span>
                            </div>
                            <div class="flex gap-2">
                                <span class="w-28 text-[var(--muted)]">Salary Type</span>
                                <span class="font-semibold text-[var(--text)]">
                                    @if ($salary->is_hourly)
                                        <span class="status-pill pill-blue">Hourly</span>
                                    @else
                                        <span class="status-pill pill-gray">Monthly</span>
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- Pay Period Info --}}
                    <div>
                        <p class="mb-3 text-xs font-bold uppercase tracking-widest text-[var(--muted)]">Pay Period Info
                        </p>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between border-b border-[var(--border2)] pb-2">
                                <span class="text-[var(--muted)]">Pay Period</span>
                                <span class="font-semibold text-[var(--text)]">{{ $salary->period_label }}</span>
                            </div>
                            @if ($salary->start_date && $salary->end_date)
                                <div class="flex justify-between border-b border-[var(--border2)] pb-2">
                                    <span class="text-[var(--muted)]">Start Date</span>
                                    <span
                                        class="font-semibold text-[var(--text)]">{{ dateFormat($salary->start_date) }}</span>
                                </div>
                                <div class="flex justify-between border-b border-[var(--border2)] pb-2">
                                    <span class="text-[var(--muted)]">End Date</span>
                                    <span
                                        class="font-semibold text-[var(--text)]">{{ dateFormat($salary->end_date) }}</span>
                                </div>
                            @endif
                            @if ($salary->paid_at)
                                <div class="flex justify-between border-b border-[var(--border2)] pb-2">
                                    <span class="text-[var(--muted)]">Paid On</span>
                                    <span
                                        class="font-semibold text-emerald-600 dark:text-emerald-400">{{ dateFormat($salary->paid_at) }}</span>
                                </div>
                            @endif
                            @if ($processor)
                                <div class="flex justify-between">
                                    <span class="text-[var(--muted)]">Processed By</span>
                                    <span class="font-semibold text-[var(--text)]">{{ $processor->full_name }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- ── Earnings / Hours ── --}}
                <div class="mb-6 overflow-hidden rounded-2xl border border-[var(--border)]">
                    <div class="border-b border-[var(--border)] bg-[var(--bg2)] px-5 py-3">
                        <p class="text-xs font-bold uppercase tracking-widest text-[var(--muted)]">
                            @if ($salary->is_hourly)
                                Hours & Earnings
                            @else
                                Attendance & Earnings
                            @endif
                        </p>
                    </div>
                    <div class="divide-y divide-[var(--border2)]">
                        @if ($salary->is_hourly)
                            <div class="flex items-center justify-between px-5 py-3.5">
                                <span class="text-sm text-[var(--text2)]">Hourly Rate</span>
                                <span
                                    class="text-sm font-semibold text-[var(--text)]">{{ currencyFormat($salary->hourly_rate) }}
                                    / hr</span>
                            </div>
                            <div class="flex items-center justify-between px-5 py-3.5">
                                <span class="text-sm text-[var(--text2)]">Total Hours Worked</span>
                                <span
                                    class="text-sm font-semibold text-[var(--text)]">{{ numberFormat($salary->total_hours) }}
                                    hrs</span>
                            </div>
                        @else
                            <div class="flex items-center justify-between px-5 py-3.5">
                                <span class="text-sm text-[var(--text2)]">Scheduled Working Days</span>
                                <span
                                    class="text-sm font-semibold text-[var(--text)]">{{ $salary->working_days }}</span>
                            </div>
                            <div class="flex items-center justify-between px-5 py-3.5">
                                <span class="text-sm text-[var(--text2)]">Days Present (Paid Days)</span>
                                <span
                                    class="text-sm font-semibold text-[var(--text)]">{{ numberFormat($salary->present_days) }}</span>
                            </div>
                            <div class="flex items-center justify-between px-5 py-3.5">
                                <span class="text-sm text-[var(--text2)]">Leave Days</span>
                                <span
                                    class="text-sm font-semibold text-[var(--text)]">{{ numberFormat($salary->leave_days) }}</span>
                            </div>
                            <div class="flex items-center justify-between px-5 py-3.5">
                                <span class="text-sm text-[var(--text2)]">Per Day Rate</span>
                                <span
                                    class="text-sm font-semibold text-[var(--text)]">{{ currencyFormat($salary->per_day_salary) }}</span>
                            </div>
                        @endif
                        <div class="flex items-center justify-between bg-blue-50/50 px-5 py-3.5 dark:bg-blue-500/5">
                            <span class="text-sm font-bold text-[var(--text)]">Gross Earned</span>
                            <span
                                class="text-sm font-bold text-blue-600 dark:text-blue-400">{{ currencyFormat($salary->earned_salary) }}</span>
                        </div>
                    </div>
                </div>

                {{-- ── Deductions ── --}}
                @if ($totalDed > 0)
                    <div class="mb-6 overflow-hidden rounded-2xl border border-red-200/60 dark:border-red-500/20">
                        <div
                            class="border-b border-red-200/60 bg-red-50/50 px-5 py-3 dark:border-red-500/20 dark:bg-red-500/5">
                            <p class="text-xs font-bold uppercase tracking-widest text-red-500 dark:text-red-400">
                                Deductions</p>
                        </div>
                        <div class="divide-y divide-[var(--border2)]">
                            @if ($salary->pf_amount > 0)
                                <div class="flex items-center justify-between px-5 py-3.5">
                                    <span class="text-sm text-[var(--text2)]">PF / EPF Amount</span>
                                    <span class="text-sm font-semibold text-red-500">-
                                        {{ currencyFormat($salary->pf_amount) }}</span>
                                </div>
                            @endif
                            @if ($salary->other_deductions > 0)
                                <div class="flex items-center justify-between px-5 py-3.5">
                                    <span class="text-sm text-[var(--text2)]">Other Deductions</span>
                                    <span class="text-sm font-semibold text-red-500">-
                                        {{ currencyFormat($salary->other_deductions) }}</span>
                                </div>
                            @endif
                            @if ($salary->hold_amount > 0)
                                <div class="flex items-center justify-between px-5 py-3.5">
                                    <span class="text-sm text-[var(--text2)]">Hold Amount</span>
                                    <span class="text-sm font-semibold text-red-500">-
                                        {{ currencyFormat($salary->hold_amount) }}</span>
                                </div>
                            @endif
                            <div class="flex items-center justify-between bg-red-50/50 px-5 py-3.5 dark:bg-red-500/5">
                                <span class="text-sm font-bold text-[var(--text)]">Total Deductions</span>
                                <span class="text-sm font-bold text-red-500">- {{ currencyFormat($totalDed) }}</span>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- ── Net Pay row ── --}}
                <div
                    class="rounded-2xl border border-emerald-200/70 bg-gradient-to-r from-emerald-50 to-teal-50 p-5 dark:border-emerald-500/20 dark:from-emerald-500/10 dark:to-teal-500/10">
                    <div class="flex items-center justify-between">
                        <div>
                            <p
                                class="text-xs font-bold uppercase tracking-widest text-emerald-700 dark:text-emerald-400">
                                Net Pay</p>
                            <p class="mt-0.5 text-sm text-emerald-600 dark:text-emerald-500">
                                After all deductions
                            </p>
                        </div>
                        <p class="text-3xl font-extrabold text-emerald-700 dark:text-emerald-300">
                            {{ currencyFormat($salary->final_salary) }}
                        </p>
                    </div>
                </div>

                {{-- ── Notes ── --}}
                @if ($salary->notes)
                    <div class="mt-5 rounded-xl border border-[var(--border)] bg-[var(--bg2)] p-4">
                        <p class="mb-1 text-xs font-bold uppercase tracking-widest text-[var(--muted)]">Notes</p>
                        <p class="text-sm text-[var(--text2)]">{{ $salary->notes }}</p>
                    </div>
                @endif

                {{-- ── Footer ── --}}
                <div class="payslip-footer mt-8 border-t border-[var(--border)] pt-5 text-center">
                    <p class="text-xs text-[var(--muted)]">
                        This is a computer-generated payslip and does not require a physical signature. &nbsp;•&nbsp;
                        PeopleCore EMS
                    </p>
                </div>

            </div>
        </div>
    </div>

    {{-- ── Print Styles ── --}}
    <style>
        @media print {
            body * {
                visibility: hidden;
            }

            #payslipDocument,
            #payslipDocument * {
                visibility: visible;
            }

            #payslipDocument {
                position: absolute;
                inset: 0;
                margin: 0;
                max-width: 100%;
                box-shadow: none;
            }

            .payslip-header {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</x-app-layout>
