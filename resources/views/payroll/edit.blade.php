<x-app-layout>
    @if ($errors->any())
        <div
            class="mb-4 rounded-xl border border-red-200 bg-red-50 p-4 text-red-600 dark:border-red-500/20 dark:bg-red-500/10 dark:text-red-400">
            <ul class="list-disc space-y-1 pl-5">
                @foreach ($errors->all() as $error)
                    <li class="text-sm">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="animate-fu d1 mb-6 flex items-center justify-between">
        <div>
            <h1 class="section-title text-2xl">Edit Payroll</h1>
            <p class="section-sub mt-1">Adjust deductions and status for this payroll record</p>
        </div>
        <div class="flex gap-2">
            <a class="btn-ghost" href="{{ route('payroll.show', $salary) }}">
                <svg fill="none" height="16" stroke-width="2" stroke="currentColor" viewBox="0 0 24 24"
                    width="16">
                    <path d="M1 12C1 12 5 5 12 5s11 7 11 7-4 7-11 7S1 12 1 12z" stroke-linecap="round"
                        stroke-linejoin="round" />
                    <circle cx="12" cy="12" r="3" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                View Payslip
            </a>
            <a class="btn-ghost" href="{{ route('payroll.index') }}">
                <svg fill="none" height="16" stroke-width="2" stroke="currentColor" viewBox="0 0 24 24"
                    width="16">
                    <path d="M19 12H5M12 5l-7 7 7 7" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                Back
            </a>
        </div>
    </div>

    <div class="two-col animate-fu d2" style="align-items:start">

        {{-- ── Left: readonly summary ── --}}
        <div class="card card-pad">
            <h2 class="mb-4 text-base font-bold text-[var(--text)]">Payroll Summary</h2>

            @php
                $emp = $salary->employee;
                $user = optional($emp)->user;
            @endphp

            {{-- Employee chip --}}
            <div class="mb-4 flex items-center gap-3 rounded-xl border border-[var(--border)] bg-[var(--bg)] p-4">
                @if ($user?->avatar)
                    <img alt="{{ $user->full_name }}" class="h-11 w-11 rounded-full object-cover"
                        src="{{ $user->avatar }}">
                @else
                    <div
                        class="emp-avatar flex h-11 w-11 items-center justify-center rounded-full bg-gradient-to-br from-blue-500 to-blue-700 text-sm font-bold text-white">
                        {{ $user?->initials ?? '??' }}
                    </div>
                @endif
                <div>
                    <p class="text-sm font-bold text-[var(--text)]">{{ $user?->full_name ?? '—' }}</p>
                    <p class="text-xs text-[var(--muted)]">{{ optional($emp->department)->name ?? '—' }}</p>
                </div>
                <div class="ml-auto">
                    @if ($salary->is_hourly)
                        <span class="status-pill pill-blue">Hourly</span>
                    @else
                        <span class="status-pill pill-gray">Monthly</span>
                    @endif
                </div>
            </div>

            {{-- Period --}}
            <div class="mb-3 flex items-center justify-between border-b border-[var(--border2)] pb-2 text-sm">
                <span class="text-[var(--muted)]">Period</span>
                <span class="font-semibold text-[var(--text)]">{{ $salary->period_label }}</span>
            </div>

            @if ($salary->is_hourly)
                <div class="mb-3 flex items-center justify-between border-b border-[var(--border2)] pb-2 text-sm">
                    <span class="text-[var(--muted)]">Total Hours</span>
                    <span class="font-semibold text-[var(--text)]">{{ numberFormat($salary->total_hours) }} hrs</span>
                </div>
                <div class="mb-3 flex items-center justify-between border-b border-[var(--border2)] pb-2 text-sm">
                    <span class="text-[var(--muted)]">Hourly Rate</span>
                    <span class="font-semibold text-[var(--text)]">{{ currencyFormat($salary->hourly_rate) }}</span>
                </div>
            @else
                <div class="mb-3 flex items-center justify-between border-b border-[var(--border2)] pb-2 text-sm">
                    <span class="text-[var(--muted)]">Working Days</span>
                    <span class="font-semibold text-[var(--text)]">{{ $salary->present_days }} /
                        {{ $salary->working_days }}</span>
                </div>
                <div class="mb-3 flex items-center justify-between border-b border-[var(--border2)] pb-2 text-sm">
                    <span class="text-[var(--muted)]">Per Day Salary</span>
                    <span class="font-semibold text-[var(--text)]">{{ currencyFormat($salary->per_day_salary) }}</span>
                </div>
            @endif

            <div class="flex items-center justify-between text-sm">
                <span class="font-bold text-[var(--text)]">Gross Earned</span>
                <span
                    class="text-base font-extrabold text-blue-600 dark:text-blue-400">{{ currencyFormat($salary->earned_salary) }}</span>
            </div>
        </div>

        {{-- ── Right: Edit form ── --}}
        <div>
            <form action="{{ route('payroll.update', $salary) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="card card-pad mb-5">
                    <h2 class="mb-4 text-base font-bold text-[var(--text)]">Deductions & Adjustments</h2>
                    <div class="space-y-4">
                        <div class="grid grid-cols-3 gap-3">
                            <div>
                                <label class="field-label">PF / EPF Amount</label>
                                <div class="field-wrap">
                                    <input class="field-input" min="0" name="pf_amount" step="0.01"
                                        type="number" value="{{ old('pf_amount', $salary->pf_amount) }}">
                                </div>
                            </div>
                            <div>
                                <label class="field-label">Other Deductions</label>
                                <div class="field-wrap">
                                    <input class="field-input" min="0" name="other_deductions" step="0.01"
                                        type="number"
                                        value="{{ old('other_deductions', $salary->other_deductions) }}">
                                </div>
                            </div>
                            <div>
                                <label class="field-label">Hold Amount</label>
                                <div class="field-wrap">
                                    <input class="field-input" min="0" name="hold_amount" step="0.01"
                                        type="number" value="{{ old('hold_amount', $salary->hold_amount) }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card card-pad mb-5">
                    <h2 class="mb-4 text-base font-bold text-[var(--text)]">Status & Notes</h2>
                    <div class="space-y-4">
                        <div>
                            <label class="field-label">Payment Status</label>
                            <div class="field-wrap">
                                <select class="field-input" name="status">
                                    <option {{ old('status', $salary->status) === 'pending' ? 'selected' : '' }}
                                        value="pending">Pending</option>
                                    <option {{ old('status', $salary->status) === 'paid' ? 'selected' : '' }}
                                        value="paid">Paid</option>
                                    <option {{ old('status', $salary->status) === 'cancelled' ? 'selected' : '' }}
                                        value="cancelled">Cancelled</option>
                                </select>
                            </div>
                        </div>
                        <div>
                            <label class="field-label">Notes</label>
                            <div class="field-wrap">
                                <textarea class="field-input" name="notes" rows="3">{{ old('notes', $salary->notes) }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex gap-3">
                    <a class="btn-ghost" href="{{ route('payroll.show', $salary) }}">Cancel</a>
                    <button class="btn-primary" type="submit">
                        <svg fill="none" height="16" stroke-width="2.5" stroke="currentColor"
                            viewBox="0 0 24 24" width="16">
                            <path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        Update Payroll
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
