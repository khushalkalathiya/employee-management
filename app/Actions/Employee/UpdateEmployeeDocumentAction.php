<?php

namespace App\Actions\Employee;

use App\Models\Document;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\UploadedFile;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateEmployeeDocumentAction
{
    use AsAction;

    public function handle(Document $document, array $data): Document
    {
        return DB::transaction(function () use ($document, $data) {
            $document->update([
                'document_type' => $data['document_type'],
                'notes' => $data['notes'] ?? null,
            ]);

            if (isset($data['file']) && $data['file'] instanceof UploadedFile) {
                $document->clearMediaCollection('file');
                $document->addMedia($data['file'])->toMediaCollection('file');
            }

            return $document;
        });
    }
}
