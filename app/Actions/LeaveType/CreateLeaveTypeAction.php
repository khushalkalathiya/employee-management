<?php

namespace App\Actions\LeaveType;

use App\Models\LeaveType;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateLeaveTypeAction
{
    use AsAction;

    public function handle(array $data): LeaveType
    {
        return DB::transaction(function () use ($data) {
            return LeaveType::create([
                'name' => $data['name'],
                'monthly_limit' => $data['monthly_limit'] ?? null,
                'description' => $data['description'] ?? null,
            ]);
        });
    }
}
