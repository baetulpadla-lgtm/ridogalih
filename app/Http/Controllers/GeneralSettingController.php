<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Http\Requests\GeneralSettingRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class GeneralSettingController extends Controller
{
    public function index(): View
    {
        Gate::authorize('security.settings.update');

        $settings = Setting::pluck('value', 'key')->toArray();
        return view('admin.settings.index', compact('settings'));
    }

    public function update(GeneralSettingRequest $request): RedirectResponse
    {
        Gate::authorize('security.settings.update');

        $validated = $request->validated();

        DB::transaction(function () use ($validated) {
            foreach ($validated as $key => $value) {
                Setting::updateOrCreate(
                    ['key' => $key],
                    ['value' => is_array($value) ? json_encode($value) : $value]
                );
            }
        });

        return back()->with('success', 'Konfigurasi sistem & kebijakan keamanan berhasil diperbarui.');
    }

    public function backupSql(): BinaryFileResponse|RedirectResponse
    {
        Gate::authorize('security.settings.update');

        $database = (string) config('database.connections.mysql.database');
        $username = (string) config('database.connections.mysql.username');
        $password = (string) config('database.connections.mysql.password');
        $host     = (string) config('database.connections.mysql.host', '127.0.0.1');
        $port     = (string) config('database.connections.mysql.port', '3306');

        $filename = "backup_eoffice_" . date('Y-m-d_H-i-s') . ".sql";
        $backupPath = storage_path("app/private/backups");

        if (!file_exists($backupPath)) {
            mkdir($backupPath, 0700, true);
        }

        $filePath = $backupPath . '/' . $filename;
        $cnfPath = $backupPath . '/.my_' . uniqid('', true) . '.cnf';

        // Write credentials to temporary secure file (readable only by owner) to avoid process table password leaks
        $cnfContent = "[client]\nuser=" . addcslashes($username, "\n\"") . "\npassword=" . addcslashes($password, "\n\"") . "\nhost=" . addcslashes($host, "\n\"") . "\nport=" . (int)$port . "\n";
        file_put_contents($cnfPath, $cnfContent);
        if (function_exists('chmod')) {
            @chmod($cnfPath, 0600);
        }

        $command = sprintf(
            'mysqldump --defaults-extra-file=%s %s > %s',
            escapeshellarg($cnfPath),
            escapeshellarg($database),
            escapeshellarg($filePath)
        );

        exec($command, $output, $resultCode);

        // Always remove the temporary credentials file immediately
        if (file_exists($cnfPath)) {
            @unlink($cnfPath);
        }

        if ($resultCode === 0 && file_exists($filePath) && filesize($filePath) > 0) {
            return response()->download($filePath)->deleteFileAfterSend(true);
        }

        if (file_exists($filePath)) {
            @unlink($filePath);
        }

        return back()->withErrors(['backup' => 'Gagal membuat file backup. Pastikan utilitas "mysqldump" terinstal dan aktif di server.']);
    }
}
