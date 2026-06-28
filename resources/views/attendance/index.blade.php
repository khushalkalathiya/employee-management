<x-app-layout>
    @if(auth()->user()?->can('attendance.own'))
        <livewire:attendance.employee-attendance />
    @else
        <livewire:attendance.admin-attendance />
    @endif
</x-app-layout>
