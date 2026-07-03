<?php

namespace App\Actions\Setting;

use App\Models\Setting;
use Illuminate\Http\UploadedFile;

class UpdateGeneralSettingAction
{
    /**
     * Update general settings and handle media uploads via Spatie.
     *
     * For app_logo and app_favicon:
     *  - The Setting row for each key is used as the Spatie media owner.
     *  - The media URL is written back into setting.value so the rest of
     *    the app can read it with a simple Setting::getValue('app_logo').
     *
     * @param array{
     *   app_name: string,
     *   app_email: string,
     *   app_logo?: UploadedFile|null,
     *   app_favicon?: UploadedFile|null,
     * } $data
     */
    public function handle(array $data): void
    {
        // ── Plain text settings ─────────────────────────────────────────
        Setting::upsert(
            [
                ['key' => 'app_name',  'value' => $data['app_name']],
                ['key' => 'app_email', 'value' => $data['app_email']],
            ],
            ['key'],
            ['value'],
        );

        // ── Media uploads ───────────────────────────────────────────────
        foreach (['app_logo', 'app_favicon'] as $key) {
            if (empty($data[$key])) {
                continue;
            }

            /** @var UploadedFile $file */
            $file = $data[$key];

            // Retrieve (or create) the Setting record that owns this media
            $setting = Setting::firstOrCreate(['key' => $key], ['value' => null]);

            // Replace any previous upload (singleFile collection)
            $media = $setting
                ->addMedia($file)
                ->usingFileName($key . '.' . $file->getClientOriginalExtension())
                ->toMediaCollection($key);

            // Persist the public URL back into the value column
            $setting->value = $media->getUrl();
            $setting->save();
        }
    }
}
