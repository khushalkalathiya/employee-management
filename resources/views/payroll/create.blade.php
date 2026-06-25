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
            <h1 class="section-title text-2xl">Generate Payroll</h1>
            <p class="section-sub mt-1">Calculate and create a new payroll record for an employee</p>
        </div>
        <a class="btn-ghost" href="{{ route('payroll.index') }}">
            <svg fill="none" height="16" stroke-width="2" stroke="currentColor" viewBox="0 0 24 24" width="16">
                <path d="M19 12H5M12 5l-7 7 7 7" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            Back to Payroll
        </a>
    </div>

    <div class="two-col animate-fu d2" style="align-items:start">

        {{-- ── Left: Form ── --}}
        <div>
            <form action="{{ route('payroll.store') }}" id="payrollForm" method="POST">
                @csrf

                {{-- Employee selection --}}
                <div class="card card-pad mb-5">
                    <h2 class="mb-4 text-base font-bold text-[var(--text)]">Employee & Period</h2>

                    <div class="space-y-4">
                        {{-- Employee --}}
                        <div>
                            <label class="field-label">Employee <span class="text-red-400">*</span></label>
                            <div class="field-wrap">
                                <select class="field-input js-tom-select" id="employeeSelect" name="employee_id"
                                    required>
                                    <option value="">— Select employee —</option>
                                    @foreach ($employees as $emp)
                                        <option
                                            {{ old('employee_id') == optional($emp->employee)->id ? 'selected' : '' }}
                                            data-is-hourly="{{ optional($emp->employee)->is_hourly ? '1' : '0' }}"
                                            data-salary="{{ optional($emp->employee)->current_salary }}"
                                            value="{{ optional($emp->employee)->id }}">
                                            {{ $emp->full_name }}
                                            ({{ optional($emp->employee)->is_hourly ? 'Hourly' : 'Monthly' }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('employee_id')
                                <p class="err-msg">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Date range --}}
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="field-label">Start Date <span class="text-red-400">*</span></label>
                                <div class="field-wrap">
                                    <input class="field-input" id="startDate" name="start_date" required type="date"
                                        value="{{ old('start_date') }}">
                                </div>
                                @error('start_date')
                                    <p class="err-msg">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="field-label">End Date <span class="text-red-400">*</span></label>
                                <div class="field-wrap">
                                    <input class="field-input" id="endDate" name="end_date" required type="date"
                                        value="{{ old('end_date') }}">
                                </div>
                                @error('end_date')
                                    <p class="err-msg">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Quick month selector --}}
                        <div>
                            <label class="field-label">Quick Month Select</label>
                            <div class="field-wrap">
                                <input class="field-input" id="monthPicker" type="month"
                                    value="{{ now()->format('Y-m') }}">
                            </div>
                            <p class="mt-1 text-xs text-[var(--muted)]">Selecting a month auto-fills start & end dates
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Deductions --}}
                <div class="card card-pad mb-5">
                    <h2 class="mb-4 text-base font-bold text-[var(--text)]">Deductions & Adjustments</h2>

                    <div class="space-y-4">
                        <div class="grid grid-cols-3 gap-3">
                            <div>
                                <label class="field-label">PF / EPF Amount</label>
                                <div class="field-wrap">
                                    <input class="field-input" id="pfAmount" min="0" name="pf_amount"
                                        step="0.01" type="number" value="{{ old('pf_amount', 0) }}">
                                </div>
                                @error('pf_amount')
                                    <p class="err-msg">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="field-label">Other Deductions</label>
                                <div class="field-wrap">
                                    <input class="field-input" id="otherDeductions" min="0"
                                        name="other_deductions" step="0.01" type="number"
                                        value="{{ old('other_deductions', 0) }}">
                                </div>
                                @error('other_deductions')
                                    <p class="err-msg">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="field-label">Hold Amount</label>
                                <div class="field-wrap">
                                    <input class="field-input" id="holdAmount" min="0" name="hold_amount"
                                        step="0.01" type="number" value="{{ old('hold_amount', 0) }}">
                                </div>
                                @error('hold_amount')
                                    <p class="err-msg">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label class="field-label">Notes</label>
                            <div class="field-wrap">
                                <textarea class="field-input" name="notes" placeholder="Any remarks for this payroll run…" rows="3">{{ old('notes') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex gap-3">
                    <button class="btn-ghost flex items-center gap-2" id="calculateBtn" type="button">
                        <svg fill="none" height="16" stroke-width="2" stroke="currentColor"
                            viewBox="0 0 24 24" width="16">
                            <path d="M9 7H6a2 2 0 00-2 2v9a2 2 0 002 2h9a2 2 0 002-2v-3" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path d="M9 15L20 4" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M15 4h5v5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        Calculate Preview
                    </button>

                    <button class="btn-primary flex items-center gap-2" id="saveBtn" type="submit">
                        <svg fill="none" height="16" stroke-width="2.5" stroke="currentColor"
                            viewBox="0 0 24 24" width="16">
                            <path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        Save Payroll
                    </button>
                </div>
            </form>
        </div>

        {{-- ── Right: Live Preview ── --}}
        <div>

            {{-- Empty state --}}
            <div class="card card-pad flex flex-col items-center justify-center py-16 text-center" id="previewEmpty">
                <div
                    class="flex h-16 w-16 items-center justify-center rounded-full bg-blue-50 text-blue-400 dark:bg-blue-500/10">
                    <svg fill="currentColor" height="30" viewBox="0 0 24 24" width="30">
                        <path
                            d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z" />
                    </svg>
                </div>
                <h3 class="mt-4 text-sm font-bold text-[var(--text)]">No Preview Yet</h3>
                <p class="mt-1 text-xs text-[var(--muted)]">Select an employee and date range, then click<br>"Calculate
                    Preview" to see the breakdown.</p>
            </div>

            {{-- Loading --}}
            <div class="card card-pad hidden" id="previewLoading">
                <div class="animate-pulse space-y-4">
                    <div class="h-6 w-1/2 rounded-lg bg-gray-200 dark:bg-gray-800"></div>
                    <div class="h-4 w-3/4 rounded-lg bg-gray-100 dark:bg-gray-800/60"></div>
                    <div class="mt-4 grid grid-cols-2 gap-3">
                        <div class="h-20 rounded-2xl bg-gray-100 dark:bg-gray-800/60"></div>
                        <div class="h-20 rounded-2xl bg-gray-100 dark:bg-gray-800/60"></div>
                        <div class="h-20 rounded-2xl bg-gray-100 dark:bg-gray-800/60"></div>
                        <div class="h-20 rounded-2xl bg-gray-100 dark:bg-gray-800/60"></div>
                    </div>
                    <div class="h-10 rounded-xl bg-gray-100 dark:bg-gray-800/60"></div>
                </div>
            </div>

            {{-- Actual preview card --}}
            <div class="hidden" id="previewCard">

                {{-- Header --}}
                <div class="card card-pad mb-4 bg-gradient-to-br from-blue-600 to-indigo-700 dark:from-blue-700 dark:to-indigo-800"
                    style="border:none">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-widest text-blue-200">Payroll Preview</p>
                            <h2 class="mt-1 text-xl font-bold text-white" id="prevEmpName">—</h2>
                            <p class="mt-0.5 text-sm text-blue-200" id="prevPeriod">—</p>
                        </div>
                        <div class="text-right">
                            <p class="text-xs font-semibold uppercase tracking-wider text-blue-200">Salary Type</p>
                            <span
                                class="mt-1 inline-block rounded-full bg-white/20 px-3 py-1 text-xs font-bold text-white"
                                id="prevType">—</span>
                        </div>
                    </div>
                </div>

                {{-- Stats grid --}}
                <div class="mb-4 grid grid-cols-2 gap-3" id="prevStats">
                    {{-- Filled by JS --}}
                </div>

                {{-- Breakdown table --}}
                <div class="card card-pad mb-4">
                    <h3 class="mb-3 text-sm font-bold text-[var(--text)]">Earnings Breakdown</h3>
                    <div class="space-y-2.5" id="prevBreakdown"></div>
                </div>

                {{-- Deductions live total --}}
                <div class="card card-pad" style="border-color:rgba(239,68,68,0.2)">
                    <h3 class="mb-3 text-sm font-bold text-[var(--text)]">Final Calculation</h3>
                    <div class="space-y-2" id="prevFinal"></div>
                    <div class="mt-3 flex items-center justify-between border-t border-[var(--border)] pt-3">
                        <span class="text-sm font-bold text-[var(--text)]">Estimated Net Pay</span>
                        <span class="text-lg font-extrabold text-emerald-600 dark:text-emerald-400"
                            id="prevNetPay">—</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        (function() {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
            const previewUrl = "{{ route('payroll.preview') }}";

            const empSelect = document.getElementById('employeeSelect');
            const startDate = document.getElementById('startDate');
            const endDate = document.getElementById('endDate');
            const monthPicker = document.getElementById('monthPicker');
            const pfAmount = document.getElementById('pfAmount');
            const otherDeductions = document.getElementById('otherDeductions');
            const holdAmount = document.getElementById('holdAmount');
            const calcBtn = document.getElementById('calculateBtn');

            const previewEmpty = document.getElementById('previewEmpty');
            const previewLoading = document.getElementById('previewLoading');
            const previewCard = document.getElementById('previewCard');

            // Quick month → fill start/end dates
            monthPicker.addEventListener('change', () => {
                const [y, m] = monthPicker.value.split('-');
                const last = new Date(y, m, 0).getDate();
                startDate.value = `${y}-${m}-01`;
                endDate.value = `${y}-${m}-${String(last).padStart(2, '0')}`;
            });

            // Recalculate final pay dynamically as deductions change
            [pfAmount, otherDeductions, holdAmount].forEach(el => {
                el.addEventListener('input', updateNetPay);
            });

            function updateNetPay() {
                const grossEl = document.getElementById('prevGrossStored');
                if (!grossEl) return;
                const gross = parseFloat(grossEl.value) || 0;
                const ded = (parseFloat(pfAmount.value) || 0) +
                    (parseFloat(otherDeductions.value) || 0) +
                    (parseFloat(holdAmount.value) || 0);
                const net = Math.max(0, gross - ded);
                const netEl = document.getElementById('prevNetPay');
                if (netEl) netEl.textContent = '$' + net.toFixed(2);
            }

            function fmt(val) {
                return '$' + parseFloat(val || 0).toFixed(2);
            }

            function makeStatCard(label, value, color) {
                const colors = {
                    blue: 'bg-blue-50 dark:bg-blue-500/10 text-blue-700 dark:text-blue-300',
                    green: 'bg-emerald-50 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-300',
                    amber: 'bg-amber-50 dark:bg-amber-500/10 text-amber-700 dark:text-amber-300',
                    violet: 'bg-violet-50 dark:bg-violet-500/10 text-violet-700 dark:text-violet-300',
                };
                return `
                <div class="rounded-2xl border border-[var(--border)] p-4 ${colors[color] || ''}">
                    <p class="text-xs font-bold uppercase tracking-wider opacity-70">${label}</p>
                    <p class="mt-1 text-xl font-extrabold">${value}</p>
                </div>`;
            }

            function makeRow(label, value, highlight) {
                const cls = highlight ? 'font-semibold text-[var(--text)]' : 'text-[var(--muted)]';
                return `
                <div class="flex items-center justify-between text-sm">
                    <span class="${cls}">${label}</span>
                    <span class="${highlight ? 'font-bold text-[var(--text)]' : 'text-[var(--muted)]'}">${value}</span>
                </div>`;
            }

            calcBtn.addEventListener('click', async () => {
                const empId = empSelect.value;
                const sd = startDate.value;
                const ed = endDate.value;

                if (!empId || !sd || !ed) {
                    alert('Please select an employee and date range first.');
                    return;
                }

                // Show loader
                previewEmpty.classList.add('hidden');
                previewCard.classList.add('hidden');
                previewLoading.classList.remove('hidden');

                const originalHtml = calcBtn.innerHTML;
                calcBtn.disabled = true;
                calcBtn.innerHTML =
                    `<svg class="animate-spin" width="16" height="16" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg> Calculating…`;

                try {
                    const res = await fetch(previewUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                        },
                        body: JSON.stringify({
                            employee_id: empId,
                            start_date: sd,
                            end_date: ed
                        }),
                    });
                    const json = await res.json();

                    if (!json.success) {
                        alert(json.message || 'Calculation failed.');
                        previewLoading.classList.add('hidden');
                        previewEmpty.classList.remove('hidden');
                        return;
                    }

                    const d = json.data;
                    const b = d.breakdown;

                    // Header
                    document.getElementById('prevEmpName').textContent = d.employee_name;
                    document.getElementById('prevPeriod').textContent = `${sd} → ${ed}`;
                    document.getElementById('prevType').textContent = d.salary_type;

                    // Stats grid
                    const statsDiv = document.getElementById('prevStats');
                    if (d.is_hourly) {
                        statsDiv.innerHTML =
                            makeStatCard('Total Hours', parseFloat(d.total_hours).toFixed(2) + ' hrs',
                                'blue') +
                            makeStatCard('Hourly Rate', fmt(d.hourly_rate), 'violet') +
                            makeStatCard('Sessions', b.attendance_count, 'amber') +
                            makeStatCard('Gross Earned', fmt(d.earned_salary), 'green');
                    } else {
                        statsDiv.innerHTML =
                            makeStatCard('Sched. Days', d.working_days, 'blue') +
                            makeStatCard('Present Days', parseFloat(d.present_days).toFixed(1), 'green') +
                            makeStatCard('Leave Days', d.leave_days, 'amber') +
                            makeStatCard('Per Day Rate', fmt(d.per_day_salary), 'violet');
                    }

                    // Breakdown
                    const brkDiv = document.getElementById('prevBreakdown');
                    if (d.is_hourly) {
                        brkDiv.innerHTML =
                            makeRow('Base Hourly Rate', fmt(d.hourly_rate)) +
                            makeRow('Total Hours Worked', parseFloat(d.total_hours).toFixed(2) + ' hrs') +
                            makeRow('Gross Earned', fmt(d.earned_salary), true);
                    } else {
                        brkDiv.innerHTML =
                            makeRow('Base Monthly Salary', fmt(b.base_salary)) +
                            makeRow('Scheduled Working Days', b.scheduled_days) +
                            makeRow('Per Day Rate', fmt(b.per_day_salary)) +
                            makeRow('Present Days (full)', b.present_days) +
                            makeRow('Half-Days (×0.5)', b.half_day_days) +
                            makeRow('Leave Days', b.leave_days) +
                            makeRow('Absent Days (unpaid)', b.absent_days) +
                            makeRow('Paid Days Total', parseFloat(b.paid_days).toFixed(1), true) +
                            makeRow('Gross Earned', fmt(b.gross), true);
                    }

                    // Final
                    const finalDiv = document.getElementById('prevFinal');
                    const ded = (parseFloat(pfAmount.value) || 0) + (parseFloat(otherDeductions.value) ||
                        0) + (parseFloat(holdAmount.value) || 0);
                    const net = Math.max(0, parseFloat(d.earned_salary) - ded);
                    finalDiv.innerHTML =
                        makeRow('Gross Earned', fmt(d.earned_salary)) +
                        makeRow('PF / EPF', '- ' + fmt(pfAmount.value || 0)) +
                        makeRow('Other Deductions', '- ' + fmt(otherDeductions.value || 0)) +
                        makeRow('Hold Amount', '- ' + fmt(holdAmount.value || 0));
                    document.getElementById('prevNetPay').textContent = fmt(net);

                    // Store gross for live deduction updates
                    let grossStored = document.getElementById('prevGrossStored');
                    if (!grossStored) {
                        grossStored = document.createElement('input');
                        grossStored.type = 'hidden';
                        grossStored.id = 'prevGrossStored';
                        document.body.appendChild(grossStored);
                    }
                    grossStored.value = d.earned_salary;

                    previewLoading.classList.add('hidden');
                    previewCard.classList.remove('hidden');

                } catch (err) {
                    alert('Server error: ' + err.message);
                    previewLoading.classList.add('hidden');
                    previewEmpty.classList.remove('hidden');
                } finally {
                    calcBtn.disabled = false;
                    calcBtn.innerHTML = originalHtml;
                }
            });
        })();
    </script>
</x-app-layout>
