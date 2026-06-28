<?php

namespace App\Livewire\Attendance;

use App\Models\Attendance;
use Carbon\Carbon;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class AdminAttendance extends Component
{
    use WithPagination;

    public string $dateFrom;
    public string $dateTo;
    public string $search      = '';
    public string $filterStatus = '';
    public int $perPage        = 15;

    public string $sortField     = 'attendance_date';
    public string $sortDirection = 'desc';

    // View Modal
    public ?int   $viewingAttendanceId = null;
    public array  $viewingLogs         = [];
    public ?array $viewingRecord       = null;

    // Delete confirmation
    public ?int   $deletingId = null;

    protected $queryString = [
        'search'       => ['except' => ''],
        'filterStatus' => ['except' => ''],
        'perPage'      => ['except' => 15],
        'dateFrom'     => ['except' => ''],
        'dateTo'       => ['except' => ''],
    ];

    public function mount(): void
    {
        // Security: This component must NOT be accessible by users with attendance.own ONLY.
        // Admin/Manager must have attendance.view but NOT attendance.own.
        abort_if(auth()->user()?->can('attendance.own'), 403);
        abort_unless(auth()->user()?->can('attendance.view'), 403);

        $this->dateFrom = now()->startOfMonth()->format('Y-m-d');
        $this->dateTo   = now()->endOfMonth()->format('Y-m-d');
    }

    public function updatedSearch(): void    { $this->resetPage(); }
    public function updatedFilterStatus(): void { $this->resetPage(); }
    public function updatedDateFrom(): void  { $this->resetPage(); }
    public function updatedDateTo(): void    { $this->resetPage(); }
    public function updatedPerPage(): void   { $this->resetPage(); }

    public function sortBy(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField     = $field;
            $this->sortDirection = 'asc';
        }
        $this->resetPage();
    }

    #[On('refresh-table')]
    public function refresh(): void
    {
        $this->resetPage();
    }

    // ── View Modal ───────────────────────────────────────────────
    public function viewAttendance(int $id): void
    {
        $record = Attendance::with(['logs', 'user'])->findOrFail($id);
        $this->viewingAttendanceId = $id;
        $this->viewingRecord = [
            'date'         => $record->attendance_date,
            'employee_name'=> $record->user?->full_name,
            'check_in'     => $record->check_in,
            'check_out'    => $record->check_out,
            'status'       => $record->status,
            'work_minutes' => $record->total_work_minutes,
            'break_minutes'=> $record->total_break_minutes,
            'overtime'     => $record->overtime_minutes,
            'notes'        => $record->notes,
        ];

        $this->viewingLogs = $record->logs
            ->sortBy('action_time')
            ->values()
            ->map(fn ($log) => [
                'type' => $log->action_type,
                'time' => $log->action_time,
            ])->toArray();

        $this->dispatch('open-view-modal');
    }

    public function closeViewModal(): void
    {
        $this->viewingAttendanceId = null;
        $this->viewingRecord       = null;
        $this->viewingLogs         = [];
    }

    // ── Delete ───────────────────────────────────────────────────
    public function confirmDelete(int $id): void
    {
        abort_unless(auth()->user()?->can('attendance.delete'), 403);
        $this->deletingId = $id;
        $this->dispatch('open-delete-confirm');
    }

    public function deleteAttendance(): void
    {
        abort_unless(auth()->user()?->can('attendance.delete'), 403);

        if ($this->deletingId) {
            $record = Attendance::findOrFail($this->deletingId);
            $record->delete();
            $this->deletingId = null;
            $this->dispatch('attendance-deleted');
            session()->flash('success', 'Attendance record deleted.');
        }
    }

    public function cancelDelete(): void
    {
        $this->deletingId = null;
    }

    public function render()
    {
        // Re-enforce on every render
        abort_if(auth()->user()?->can('attendance.own'), 403);
        abort_unless(auth()->user()?->can('attendance.view'), 403);

        $from = Carbon::parse($this->dateFrom)->startOfDay();
        $to   = Carbon::parse($this->dateTo)->endOfDay();

        $query = Attendance::query()
            ->with(['user', 'user.employee', 'user.employee.designation', 'logs'])
            ->whereBetween('attendance_date', [$from->toDateString(), $to->toDateString()]);

        // Search by user name or email
        if ($this->search !== '') {
            $searchTerm = '%' . $this->search . '%';
            $query->whereHas('user', function ($q) use ($searchTerm) {
                $q->where('first_name', 'like', $searchTerm)
                    ->orWhere('last_name', 'like', $searchTerm)
                    ->orWhere('email', 'like', $searchTerm)
                    ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", [$searchTerm]);
            });
        }

        // Status filter
        if ($this->filterStatus !== '') {
            $query->where('status', $this->filterStatus);
        }

        // Sorting
        $allowedSortFields = ['attendance_date', 'check_in', 'check_out', 'total_work_minutes', 'status'];
        if (in_array($this->sortField, $allowedSortFields)) {
            $query->orderBy($this->sortField, $this->sortDirection);
        } else {
            $query->orderByDesc('attendance_date');
        }

        $attendances = $query->paginate($this->perPage);

        // Summary counts
        $totalPresent = Attendance::query()
            ->whereBetween('attendance_date', [$from->toDateString(), $to->toDateString()])
            ->where('status', 'present')
            ->count();

        $totalAbsent = Attendance::query()
            ->whereBetween('attendance_date', [$from->toDateString(), $to->toDateString()])
            ->where('status', 'absent')
            ->count();

        $totalOnLeave = Attendance::query()
            ->whereBetween('attendance_date', [$from->toDateString(), $to->toDateString()])
            ->where('status', 'leave')
            ->count();

        // Currently active (clocked in today)
        $activeNow = Attendance::query()
            ->where('attendance_date', today())
            ->whereNull('check_out')
            ->count();

        return view('livewire.attendance.admin-attendance', [
            'attendances'  => $attendances,
            'totalPresent' => $totalPresent,
            'totalAbsent'  => $totalAbsent,
            'totalOnLeave' => $totalOnLeave,
            'activeNow'    => $activeNow,
        ]);
    }
}
