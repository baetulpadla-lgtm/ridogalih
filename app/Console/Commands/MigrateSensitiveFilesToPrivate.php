<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

class MigrateSensitiveFilesToPrivate extends Command
{
    protected $signature = 'security:migrate-sensitive-files';

    protected $description = 'Move sensitive legacy uploads and exports from public to private storage';

    public function handle(): int
    {
        $public = Storage::disk('public');
        $private = Storage::disk('private');
        $directories = ['foto-penduduk', 'penduduk/foto', 'arsip-surat-masuk', 'qr-surat', 'exports'];
        $moved = 0;

        foreach ($directories as $directory) {
            foreach ($public->allFiles($directory) as $path) {
                if ($private->exists($path)) {
                    throw new RuntimeException("Refusing to overwrite an existing private file: {$path}");
                }

                $stream = $public->readStream($path);
                if (! is_resource($stream)) {
                    throw new RuntimeException("Unable to read legacy public file: {$path}");
                }

                try {
                    $stored = $private->put($path, $stream);
                } finally {
                    fclose($stream);
                }

                if (! $stored || ! $private->exists($path) || $public->size($path) !== $private->size($path)) {
                    throw new RuntimeException("Unable to verify private copy of legacy public file: {$path}");
                }

                $public->delete($path);
                $moved++;
            }
        }

        $this->info("Moved {$moved} sensitive files to private storage.");

        return self::SUCCESS;
    }
}
