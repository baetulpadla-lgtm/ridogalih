<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\Storage;
use App\Models\ExportedFile;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Menghapus file ekspor sampah yang sudah lebih dari 3 hari setiap tengah malam
Schedule::call(function () {
    $files = Storage::disk('private')->files('exports');
    $now = now();

    foreach ($files as $file) {
        $lastModified = \Carbon\Carbon::createFromTimestamp(Storage::disk('private')->lastModified($file));
        if ($lastModified->diffInDays($now) >= 3) {
            Storage::disk('private')->delete($file);
        }
    }

    ExportedFile::query()->where('expires_at', '<=', $now)->delete();
})->dailyAt('00:00')->name('cleanup-expired-exports');
