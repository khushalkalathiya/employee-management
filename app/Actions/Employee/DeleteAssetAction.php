<?php

namespace App\Actions\Employee;

use App\Models\Asset;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class DeleteAssetAction
{
    use AsAction;

    public function handle(Asset $asset): bool
    {
        return DB::transaction(function () use ($asset) {
            return (bool) $asset->delete();
        });
    }
}
