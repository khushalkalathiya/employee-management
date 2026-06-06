<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Document extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'employee_id',
        'document_type',
        'notes',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('file')
            ->useDisk(config('media-library.disk_name'))
            ->singleFile();
    }

    public function getFileAttribute()
    {
        return $this->getFirstMediaUrl('file') ?? null;
    }
}
