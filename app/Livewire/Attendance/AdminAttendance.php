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
