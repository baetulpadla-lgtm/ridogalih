<?php

namespace App\Jobs;

use App\Models\ExportedFile;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

class MarkExportReady implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public int $exportedFileId) {}

    public function handle(): void
    {
        $exportedFile = ExportedFile::findOrFail($this->exportedFileId);

        if (! Storage::disk('private')->exists($exportedFile->path)) {
            throw new RuntimeException('Queued export output was not found on private storage.');
        }

        $exportedFile->update([
            'status' => 'ready',
            'expires_at' => now()->addDays(3),
        ]);
    }
}
