<?php

namespace App\Actions\Payroll;

use App\Models\Salary;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class DeletePayrollAction
{
    use AsAction;

    public function handle(Salary $salary): void
    {
        DB::transaction(fn () => $salary->delete());
    }
}
