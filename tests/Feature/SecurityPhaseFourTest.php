<?php

namespace Tests\Feature;

use App\Models\Penduduk;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\TestCase;

class SecurityPhaseFourTest extends TestCase
{
    use RefreshDatabase;

    public function test_privileged_routes_require_authentication_and_mfa_enrollment_middleware(): void
    {
        $protectedRoutes = 0;

        foreach (Route::getRoutes() as $route) {
            $middleware = $route->gatherMiddleware();

            if (! in_array('privilege', $middleware, true)) {
                continue;
            }

            $protectedRoutes++;
            $this->assertContains('auth', $middleware, $route->getName());
            $this->assertContains('mfa.enrollment', $middleware, $route->getName());
        }

        $this->assertGreaterThan(0, $protectedRoutes);
    }

    public function test_sensitive_administrator_routes_require_the_ip_allowlist_middleware(): void
    {
        $adminRoutePrefixes = [
            'admin.role-menus.',
            'admin.profil-desa.',
            'admin.audit-logs.',
            'admin.settings.',
            'profile.security-mfa.',
        ];
        $checkedRoutes = 0;

        foreach (Route::getRoutes() as $route) {
            $name = $route->getName();
            if (! $name || ! collect($adminRoutePrefixes)->contains(fn (string $prefix): bool => str_starts_with($name, $prefix))) {
                continue;
            }

            $checkedRoutes++;
            $middleware = $route->gatherMiddleware();

            $this->assertContains('auth', $middleware, $name);
            $this->assertContains('privilege', $middleware, $name);
            $this->assertContains('mfa.enrollment', $middleware, $name);
            $this->assertContains('admin.ip', $middleware, $name);
        }

        $this->assertGreaterThan(0, $checkedRoutes);
    }

    public function test_resident_photo_endpoint_allows_only_the_linked_resident_without_broad_permission(): void
    {
        Storage::fake('private');
        $role = Role::create(['name' => 'Self Service']);
        $resident = $this->createPenduduk('foto-penduduk/owner.png');
        $otherResident = $this->createPenduduk('foto-penduduk/other.png');
        $user = $this->createUser($role, $resident->id);
        $image = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+jXioAAAAASUVORK5CYII=');
        Storage::disk('private')->put($resident->foto, $image);
        Storage::disk('private')->put($otherResident->foto, $image);

        $this->actingAs($user)
            ->get(route('penduduk.photo', $resident))
            ->assertOk()
            ->assertHeader('X-Content-Type-Options', 'nosniff');

        $response = $this->get(route('penduduk.photo', $otherResident));
        $response->assertForbidden();
    }

    private function createUser(Role $role, int $pendudukId): User
    {
        $id = DB::table('users')->insertGetId([
            'uuid' => (string) Str::uuid(),
            'penduduk_id' => $pendudukId,
            'name' => 'Self Service User',
            'email' => Str::uuid().'@example.test',
            'password' => Hash::make('password'),
            'role_id' => $role->id,
            'is_active' => true,
            'status_akun' => 'Aktif',
            'theme' => 'light',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return User::findOrFail($id);
    }

    private function createPenduduk(string $photoPath): Penduduk
    {
        $id = DB::table('penduduk')->insertGetId([
            'uuid' => (string) Str::uuid(),
            'nik' => (string) random_int(1000000000000000, 9999999999999999),
            'no_kk' => '1234567890123456',
            'nama_lengkap' => 'Test Resident',
            'foto' => $photoPath,
            'tempat_lahir' => 'Bekasi',
            'tanggal_lahir' => '2000-01-01',
            'jenis_kelamin' => 'Laki-laki',
            'agama' => 'Islam',
            'pendidikan' => 'SMA',
            'pekerjaan' => 'Pegawai',
            'status_perkawinan' => 'Belum Kawin',
            'status_hubungan_keluarga' => 'Kepala Keluarga',
            'nama_ayah' => 'Test Father',
            'nama_ibu' => 'Test Mother',
            'alamat_lengkap' => 'Test address',
            'rt' => '001',
            'rw' => '001',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return Penduduk::findOrFail($id);
    }
}
