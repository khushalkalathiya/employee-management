<?php

namespace App\Actions\Employee;

use App\Models\Asset;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateAssetAction
{
    use AsAction;

    public function handle(Asset $asset, array $data): Asset
    {
        return DB::transaction(function () use ($asset, $data) {
            $asset->update($data);
            return $asset;
        });
    }
}
