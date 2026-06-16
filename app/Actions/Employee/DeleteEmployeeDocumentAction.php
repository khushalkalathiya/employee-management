<?php

namespace App\Actions\Employee;

use App\Models\Document;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class DeleteEmployeeDocumentAction
{
    use AsAction;

    public function handle(Document $document): bool
    {
        return DB::transaction(function () use ($document) {
            $document->clearMediaCollection('file');
            return (bool) $document->delete();
        });
    }
}
