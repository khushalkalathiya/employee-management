<?php

namespace App\Actions\Employee;

use App\Models\User;
use App\Models\Document;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\UploadedFile;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateEmployeeDocumentAction
{
    use AsAction;

    public function handle(User $user, array $data): Document
    {
        return DB::transaction(function () use ($user, $data) {
            $employee = $user->employee;
            if($employee == null){
                $employee = $user->employee()->create([
                    'employee_code' => 'EMP-' . str_pad($user->id, 5, '0', STR_PAD_LEFT),
                ]);
            }

            $document = $employee->documents()->create([
                'document_type' => $data['document_type'],
                'notes' => $data['notes'] ?? null,
            ]);

            if (isset($data['file']) && $data['file'] instanceof UploadedFile) {
                $document->addMedia($data['file'])->toMediaCollection('file');
            }

            return $document;
        });
    }
}
