<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Setting extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'key',
        'value',
    ];

    /**
     * Register single-file media collections for logo and favicon.
     * Using singleFile() ensures old uploads are automatically replaced.
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('app_logo')
            ->useDisk(config('media-library.disk_name'))
            ->singleFile();

        $this->addMediaCollection('app_favicon')
            ->useDisk(config('media-library.disk_name'))
            ->singleFile();
    }

    /**
     * Convenience helper: get a setting value by key.
     */
    public static function getValue(string $key, mixed $default = null): mixed
    {
        return static::where('key', $key)->value('value') ?? $default;
    }
}
