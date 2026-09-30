<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menu;
use App\Models\Permission;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        $this->menu('Dashboard', 'dashboard', 'dashboard.view', 'fas fa-home', null, 1);

        $this->menu('Data Penduduk', 'penduduk.index', 'penduduk.view', 'fas fa-users', null, 2);

        $eSurat = $this->menu('E-Surat', null, null, 'fas fa-envelope', null, 3);

        $this->menu('Surat Masuk (Arsip)', 'surat-masuk.index', 'surat_masuk.view', 'fas fa-inbox', $eSurat->id, 1);
        $this->menu('Surat Keluar (Pengajuan)', 'surat-keluar.index', 'surat_keluar.view', 'fas fa-paper-plane', $eSurat->id, 2);

        $setting = $this->menu('Pengaturan Sistem', null, null, 'fas fa-cogs', null, 4);

        $this->menu('Master Jenis Surat', 'jenis-surat.index', 'jenis_surat.view', 'fas fa-file-text', $setting->id, 1);
        $this->menu('Manajemen User', 'users.index', 'users.view', 'fas fa-user-shield', $setting->id, 2);
        $this->menu('Hak Akses Role', 'admin.role-menus.index', 'roles.view', 'fas fa-key', $setting->id, 3);
        $this->menu('Audit Keamanan', 'admin.audit-logs.index', 'security.audit.view', 'fas fa-clipboard-list', $setting->id, 4);
        $this->menu('Pengaturan Sistem', 'admin.settings.index', 'security.settings.update', 'fas fa-sliders-h', $setting->id, 5);
    }

    private function menu(
        string $label,
        ?string $routeName,
        ?string $permissionKey,
        ?string $icon,
        ?int $parentId,
        int $order
    ): Menu {
        return Menu::updateOrCreate(
            ['nama_menu' => $label, 'parent_id' => $parentId],
            [
                'url_route' => $routeName,
                'permission_id' => $permissionKey ? Permission::where('key', $permissionKey)->value('id') : null,
                'icon' => $icon,
                'is_sidebar' => true,
                'is_active' => true,
                'urutan' => $order,
            ]
        );
    }
}
