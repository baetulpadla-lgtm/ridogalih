<?php

namespace App\Services\Authorization;

use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GeneralSettingController;
use App\Http\Controllers\JenisSuratController;
use App\Http\Controllers\LaporanSuratController;
use App\Http\Controllers\MfaSecurityController;
use App\Http\Controllers\PendudukController;
use App\Http\Controllers\PrivateFileController;
use App\Http\Controllers\ProfilDesaController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleMenuController;
use App\Http\Controllers\SuratKeluarController;
use App\Http\Controllers\SuratMasukController;
use App\Http\Controllers\UserController;

class PermissionRegistry
{
    /**
     * Permission enforcement is keyed to controller actions, never URL or menu names.
     */
    private const ACTIONS = [
        DashboardController::class.'@index' => 'dashboard.view',
        UserController::class.'@index' => 'users.view',
        UserController::class.'@create' => 'users.create',
        UserController::class.'@store' => 'users.create',
        UserController::class.'@show' => 'users.view',
        UserController::class.'@edit' => 'users.update',
        UserController::class.'@update' => 'users.update',
        UserController::class.'@destroy' => 'users.delete',
        UserController::class.'@print' => 'users.view',
        UserController::class.'@export' => 'users.export',
        PendudukController::class.'@index' => 'penduduk.view',
        PendudukController::class.'@create' => 'penduduk.create',
        PendudukController::class.'@store' => 'penduduk.create',
        PendudukController::class.'@show' => 'penduduk.view',
        PendudukController::class.'@edit' => 'penduduk.update',
        PendudukController::class.'@update' => 'penduduk.update',
        PendudukController::class.'@destroy' => 'penduduk.delete',
        PendudukController::class.'@print' => 'penduduk.view',
        PendudukController::class.'@exportExcel' => 'penduduk.export',
        PendudukController::class.'@exportCsv' => 'penduduk.export',
        PendudukController::class.'@exportPdf' => 'penduduk.export',
        PendudukController::class.'@exports' => 'penduduk.export',
        PendudukController::class.'@downloadExport' => 'penduduk.export',
        PendudukController::class.'@importExcel' => 'penduduk.create',
        PendudukController::class.'@downloadTemplate' => 'penduduk.export',
        PrivateFileController::class.'@pendudukPhoto' => null,
        SuratMasukController::class.'@index' => 'surat_masuk.view',
        SuratMasukController::class.'@create' => 'surat_masuk.create',
        SuratMasukController::class.'@store' => 'surat_masuk.create',
        SuratMasukController::class.'@show' => 'surat_masuk.view',
        SuratMasukController::class.'@edit' => 'surat_masuk.update',
        SuratMasukController::class.'@update' => 'surat_masuk.update',
        SuratMasukController::class.'@destroy' => 'surat_masuk.delete',
        PrivateFileController::class.'@suratMasukFile' => 'surat_masuk.view',
        SuratKeluarController::class.'@index' => 'surat_keluar.view',
        SuratKeluarController::class.'@create' => 'surat_keluar.create',
        SuratKeluarController::class.'@store' => 'surat_keluar.create',
        SuratKeluarController::class.'@show' => 'surat_keluar.view',
        SuratKeluarController::class.'@update' => 'surat_keluar.update',
        SuratKeluarController::class.'@print' => 'surat_keluar.print',
        SuratKeluarController::class.'@destroy' => 'surat_keluar.delete',
        RoleMenuController::class.'@index' => 'roles.view',
        RoleMenuController::class.'@edit' => 'roles.view',
        RoleMenuController::class.'@update' => 'roles.update',
        RoleMenuController::class.'@storeMenu' => 'security.settings.update',
        RoleMenuController::class.'@storePermission' => 'security.permissions.assign',
        RoleMenuController::class.'@updateMenu' => 'security.settings.update',
        RoleMenuController::class.'@destroyMenu' => 'security.settings.update',
        AuditLogController::class.'@index' => 'security.audit.view',
        AuditLogController::class.'@show' => 'security.audit.view',
        GeneralSettingController::class.'@index' => 'security.settings.update',
        GeneralSettingController::class.'@update' => 'security.settings.update',
        GeneralSettingController::class.'@backupSql' => 'security.settings.update',
        ProfilDesaController::class.'@index' => 'security.settings.update',
        ProfilDesaController::class.'@update' => 'security.settings.update',
        ProfilDesaController::class.'@show' => 'security.settings.update',
        ProfilDesaController::class.'@print' => 'security.settings.update',
        JenisSuratController::class.'@index' => 'jenis_surat.view',
        JenisSuratController::class.'@create' => 'jenis_surat.create',
        JenisSuratController::class.'@store' => 'jenis_surat.create',
        JenisSuratController::class.'@show' => 'jenis_surat.view',
        JenisSuratController::class.'@edit' => 'jenis_surat.update',
        JenisSuratController::class.'@update' => 'jenis_surat.update',
        JenisSuratController::class.'@destroy' => 'jenis_surat.delete',
        LaporanSuratController::class.'@index' => 'laporan_surat.view',
        LaporanSuratController::class.'@print' => 'laporan_surat.print',
        // These routes are authenticated personal self-service endpoints.
        ProfileController::class.'@edit' => null,
        ProfileController::class.'@photo' => null,
        ProfileController::class.'@update' => null,
        ProfileController::class.'@updatePassword' => null,
        MfaSecurityController::class.'@setup' => null,
        MfaSecurityController::class.'@enable' => null,
        MfaSecurityController::class.'@showRecoveryCodes' => null,
    ];

    public static function forAction(string $action): ?string
    {
        return self::ACTIONS[$action] ?? null;
    }

    public static function recognizes(string $action): bool
    {
        return array_key_exists($action, self::ACTIONS);
    }

    /**
     * Maps existing menu route metadata to permission keys during migration/seed backfill.
     *
     * @return array<string, string>
     */
    public static function menuRoutePermissions(): array
    {
        return [
            'dashboard' => 'dashboard.view',
            'home' => 'dashboard.view',
            'users.index' => 'users.view',
            'users.create' => 'users.create',
            'users.store' => 'users.create',
            'users.show' => 'users.view',
            'users.edit' => 'users.update',
            'users.update' => 'users.update',
            'users.destroy' => 'users.delete',
            'users.print' => 'users.view',
            'users.export' => 'users.export',
            'penduduk.index' => 'penduduk.view',
            'penduduk.create' => 'penduduk.create',
            'penduduk.store' => 'penduduk.create',
            'penduduk.show' => 'penduduk.view',
            'penduduk.edit' => 'penduduk.update',
            'penduduk.update' => 'penduduk.update',
            'penduduk.destroy' => 'penduduk.delete',
            'penduduk.print' => 'penduduk.view',
            'penduduk.export.excel' => 'penduduk.export',
            'penduduk.export.csv' => 'penduduk.export',
            'penduduk.export.pdf' => 'penduduk.export',
            'penduduk.import' => 'penduduk.create',
            'surat-masuk.index' => 'surat_masuk.view',
            'surat-masuk.create' => 'surat_masuk.create',
            'surat-masuk.store' => 'surat_masuk.create',
            'surat-masuk.show' => 'surat_masuk.view',
            'surat-masuk.edit' => 'surat_masuk.update',
            'surat-masuk.update' => 'surat_masuk.update',
            'surat-masuk.destroy' => 'surat_masuk.delete',
            'surat-keluar.index' => 'surat_keluar.view',
            'surat-keluar.create' => 'surat_keluar.create',
            'surat-keluar.store' => 'surat_keluar.create',
            'surat-keluar.show' => 'surat_keluar.view',
            'surat-keluar.update' => 'surat_keluar.update',
            'surat-keluar.print' => 'surat_keluar.print',
            'surat-keluar.destroy' => 'surat_keluar.delete',
            'jenis-surat.index' => 'jenis_surat.view',
            'jenis-surat.create' => 'jenis_surat.create',
            'jenis-surat.store' => 'jenis_surat.create',
            'jenis-surat.show' => 'jenis_surat.view',
            'jenis-surat.edit' => 'jenis_surat.update',
            'jenis-surat.update' => 'jenis_surat.update',
            'jenis-surat.destroy' => 'jenis_surat.delete',
            'laporan-surat.index' => 'laporan_surat.view',
            'laporan-surat.print' => 'laporan_surat.print',
            'admin.role-menus.index' => 'roles.view',
            'admin.role-menus.edit' => 'roles.view',
            'admin.role-menus.update' => 'roles.update',
            'admin.profil-desa.index' => 'security.settings.update',
            'admin.profil-desa.update' => 'security.settings.update',
            'admin.profil-desa.show' => 'security.settings.update',
            'admin.profil-desa.print' => 'security.settings.update',
            'admin.audit-logs.index' => 'security.audit.view',
            'admin.audit-logs.show' => 'security.audit.view',
            'admin.settings.index' => 'security.settings.update',
            'admin.settings.update' => 'security.settings.update',
            'admin.settings.backup' => 'security.settings.update',
        ];
    }

    /**
     * Legacy role-menu wildcards are converted only to ordinary module permissions.
     * Administrative/security grants must be re-authorized explicitly.
     *
     * @return array<string, list<string>>
     */
    public static function legacyWildcardPermissions(): array
    {
        return [
            'users.*' => ['users.view', 'users.create', 'users.update', 'users.delete', 'users.export'],
            'penduduk.*' => ['penduduk.view', 'penduduk.create', 'penduduk.update', 'penduduk.delete', 'penduduk.export'],
            'surat-masuk.*' => ['surat_masuk.view', 'surat_masuk.create', 'surat_masuk.update', 'surat_masuk.delete'],
            'surat-keluar.*' => ['surat_keluar.view', 'surat_keluar.create', 'surat_keluar.update', 'surat_keluar.delete', 'surat_keluar.print'],
            'jenis-surat.*' => ['jenis_surat.view', 'jenis_surat.create', 'jenis_surat.update', 'jenis_surat.delete'],
            'laporan-surat.*' => ['laporan_surat.view', 'laporan_surat.print'],
        ];
    }
}
