<div style="margin-bottom:20px">

    {{-- ═══════════════ REAL-TIME CONTROL CARD (Always Visible) ═══════════════ --}}
    <livewire:attendance.attendance-control-card />

    {{-- ═══════════════ ROLE-BASED LOWER PANEL ═══════════════ --}}
    @if (auth()->user()?->can('attendance.own'))
        {{-- Employee View: own attendance data only --}}
        <livewire:attendance.employee-attendance />
    @elseif(auth()->user()?->can('attendance.view'))
        {{-- Admin / Manager View: company-wide overview --}}
        <livewire:attendance.admin-attendance />
    @endif

</div>
