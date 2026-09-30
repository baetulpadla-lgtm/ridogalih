<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Penduduk;
use App\Models\SuratMasuk;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class PrivateFileController extends Controller
{
    public function pendudukPhoto(Penduduk $penduduk)
    {
        Gate::authorize('photo', $penduduk);
        $path = $penduduk->foto;

        abort_unless($this->isWithin($path, ['foto-penduduk/', 'penduduk/foto/']), 404);

        $disk = Storage::disk('private');
        abort_unless($disk->exists($path), 404);

        $mimeType = $disk->mimeType($path);
        abort_unless(in_array($mimeType, ['image/jpeg', 'image/png', 'image/webp', 'image/gif'], true), 404);

        $response = response()->file($disk->path($path), [
            'Content-Type' => $mimeType,
            'Cache-Control' => 'private, no-store',
            'X-Content-Type-Options' => 'nosniff',
        ]);
        $response->headers->set('Cache-Control', 'private, no-store');

        return $response;
    }

    public function suratMasukFile(SuratMasuk $suratMasuk)
    {
        Gate::authorize('view', $suratMasuk);
        $path = $suratMasuk->file_scan;

        abort_unless($this->isWithin($path, ['arsip-surat-masuk/']), 404);

        $disk = Storage::disk('private');
        abort_unless($disk->exists($path), 404);

        return $disk->download($path, basename($path), [
            'Cache-Control' => 'private, no-store',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    /**
     * @param  array<int, string>  $allowedPrefixes
     */
    private function isWithin(?string $path, array $allowedPrefixes): bool
    {
        if (! $path || str_contains($path, '..') || str_contains($path, '\\') || str_contains($path, "\0")) {
            return false;
        }

        foreach ($allowedPrefixes as $prefix) {
            if (str_starts_with($path, $prefix)) {
                return true;
            }
        }

        return false;
    }
}
