<?php

namespace Tests\Feature;

use App\Exports\PendudukExport;
use App\Jobs\MarkExportReady;
use App\Models\ExportedFile;
use App\Models\Penduduk;
use App\Models\Permission;
use App\Models\Role;
use App\Models\SuratMasuk;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Tests\TestCase;

class SecurityPhaseTwoTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_is_throttled_separately_from_guest_page_views(): void
    {
        for ($attempt = 0; $attempt < 5; $attempt++) {
            $this->post(route('login.post'), [
                'nik' => '1234567890123456',
                'password' => 'wrong-password',
            ])->assertSessionHasErrors('nik');
        }

        $this->get(route('login'))->assertOk();

        $this->post(route('login.post'), [
            'nik' => '1234567890123456',
            'password' => 'wrong-password',
        ])->assertTooManyRequests();
    }

    public function test_device_api_uses_a_per_ip_rate_limit(): void
    {
        $payload = [
            'mac_address' => 'AA:BB:CC:DD:EE:FF',
            'payload' => (string) Str::uuid(),
            'method' => 'QR',
        ];

        for ($attempt = 0; $attempt < 60; $attempt++) {
            $this->postJson('/api/v1/device/verify-access', $payload)->assertUnauthorized();
        }

        $this->postJson('/api/v1/device/verify-access', $payload)->assertTooManyRequests();
    }

    public function test_private_photo_requires_authorization_and_is_not_publicly_served(): void
    {
        Storage::fake('private');
        $role = Role::create(['name' => 'Photo Reader']);
        $permission = Permission::create(['key' => 'penduduk.view', 'name' => 'View residents']);
        $role->permissions()->attach($permission);
        $user = $this->createUser($role);
        $penduduk = $this->createPenduduk('foto-penduduk/private-photo.png');
        $this->get(route('penduduk.photo', $penduduk))->assertRedirect(route('login'));
        Storage::disk('private')->put(
            $penduduk->foto,
            base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+jXioAAAAASUVORK5CYII=')
        );

        $this->actingAs($user)
            ->get(route('penduduk.photo', $penduduk))
            ->assertOk()
            ->assertHeader('X-Content-Type-Options', 'nosniff');

        $photoResponse = $this->get(route('penduduk.photo', $penduduk));
        $this->assertStringContainsString('private', $photoResponse->headers->get('Cache-Control'));
        $this->assertStringContainsString('no-store', $photoResponse->headers->get('Cache-Control'));
        $otherRole = Role::create(['name' => 'Unprivileged User']);
        $otherUser = $this->createUser($otherRole);
        $this->assertFalse(Gate::forUser($otherUser)->allows('photo', $penduduk));
        $this->assertFileDoesNotExist(storage_path('app/public/'.$penduduk->foto));
        $this->assertFileExists(Storage::disk('private')->path($penduduk->foto));
    }

    public function test_incoming_letter_scan_download_requires_permission_and_is_private(): void
    {
        Storage::fake('private');
        $role = Role::create(['name' => 'Incoming Letter Reader']);
        $permission = Permission::create(['key' => 'surat_masuk.view', 'name' => 'View incoming letters']);
        $role->permissions()->attach($permission);
        $user = $this->createUser($role);
        $id = DB::table('surat_masuks')->insertGetId([
            'uuid' => (string) Str::uuid(),
            'user_id' => $user->id,
            'nomor_surat' => '001/TEST',
            'asal_surat' => 'Test Agency',
            'perihal' => 'Test letter',
            'tanggal_surat' => '2026-01-01',
            'tanggal_diterima' => '2026-01-02',
            'file_scan' => 'arsip-surat-masuk/test.pdf',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $suratMasuk = SuratMasuk::findOrFail($id);
        Storage::disk('private')->put($suratMasuk->file_scan, 'private scan bytes');

        $this->actingAs($user)
            ->get(route('surat-masuk.file', $suratMasuk))
            ->assertOk()
            ->assertHeader('X-Content-Type-Options', 'nosniff');
        $this->assertStringContainsString(
            'no-store',
            $this->get(route('surat-masuk.file', $suratMasuk))->headers->get('Cache-Control')
        );

        Storage::disk('public')->assertMissing($suratMasuk->file_scan);
    }

    public function test_legacy_sensitive_files_move_without_moving_public_logos(): void
    {
        Storage::fake('public');
        Storage::fake('private');
        Storage::disk('public')->put('foto-penduduk/person.jpg', 'private-photo');
        Storage::disk('public')->put('arsip-surat-masuk/letter.pdf', 'private-scan');
        Storage::disk('public')->put('exports/residents.csv', 'private-export');
        Storage::disk('public')->put('logo/village.png', 'public-logo');

        $this->artisan('security:migrate-sensitive-files')->assertSuccessful();

        foreach (['foto-penduduk/person.jpg', 'arsip-surat-masuk/letter.pdf', 'exports/residents.csv'] as $path) {
            Storage::disk('private')->assertExists($path);
            Storage::disk('public')->assertMissing($path);
        }
        Storage::disk('public')->assertExists('logo/village.png');
    }

    public function test_encrypted_mothers_name_supports_ciphertext_larger_than_legacy_varchar(): void
    {
        $legacyPlainText = 'Legacy mother name';
        $pendudukId = $this->createPenduduk()->id;
        DB::table('penduduk')->where('id', $pendudukId)->update(['nama_ibu' => $legacyPlainText]);

        $migration = require database_path('migrations/2026_08_01_180000_encrypt_legacy_penduduk_mothers_names.php');
        $migration->up();
        $this->assertSame($legacyPlainText, Penduduk::findOrFail($pendudukId)->nama_ibu);

        $migration->up();
        $this->assertSame($legacyPlainText, Penduduk::findOrFail($pendudukId)->nama_ibu);

        $plainText = str_repeat('Nama Ibu Panjang ', 20);
        DB::table('penduduk')->where('id', $pendudukId)->update([
            'nama_ibu' => Crypt::encryptString($plainText),
        ]);

        $this->assertNotSame($plainText, DB::table('penduduk')->where('id', $pendudukId)->value('nama_ibu'));
        $this->assertSame($plainText, Penduduk::findOrFail($pendudukId)->nama_ibu);
        $this->assertGreaterThan(255, strlen((string) DB::table('penduduk')->where('id', $pendudukId)->value('nama_ibu')));
    }

    public function test_exports_are_queued_to_private_storage_and_only_downloadable_by_the_owner(): void
    {
        Storage::fake('private');
        Excel::fake();
        $role = Role::create(['name' => 'Export Reader']);
        $permission = Permission::create(['key' => 'penduduk.export', 'name' => 'Export residents']);
        $role->permissions()->attach($permission);
        $owner = $this->createUser($role);
        $otherUser = $this->createUser($role);

        $this->actingAs($owner)
            ->get(route('penduduk.export.excel'))
            ->assertRedirect(route('penduduk.exports'));

        $exportedFile = ExportedFile::query()->where('user_id', $owner->id)->firstOrFail();
        $this->assertSame('pending', $exportedFile->status);
        $this->assertStringStartsWith('exports/', $exportedFile->path);
        Excel::assertQueued($exportedFile->path, 'private', fn ($queuedExport) => $queuedExport instanceof PendudukExport);
        Excel::assertQueuedWithChain([MarkExportReady::class]);

        Storage::disk('private')->put($exportedFile->path, 'generated export');
        Storage::disk('public')->assertMissing($exportedFile->path);
        $this->assertFalse(Gate::forUser($otherUser)->allows('download', $exportedFile));
        $this->actingAs($owner)->get(route('penduduk.exports.download', $exportedFile))->assertNotFound();

        (new MarkExportReady($exportedFile->id))->handle();
        $this->assertSame('ready', $exportedFile->fresh()->status);
        $this->assertFalse(Gate::forUser($otherUser)->allows('download', $exportedFile));
        $downloadResponse = $this->actingAs($owner)->get(route('penduduk.exports.download', $exportedFile));
        $downloadResponse->assertOk();
        $this->assertStringContainsString('private', $downloadResponse->headers->get('Cache-Control'));
        $this->assertStringContainsString('no-store', $downloadResponse->headers->get('Cache-Control'));
    }

    public function test_system_admin_cannot_download_another_users_export(): void
    {
        Storage::fake('private');
        $owner = $this->createUser(Role::create(['name' => 'Export Owner']));
        $adminRole = Role::create(['name' => 'System Administrator']);
        $adminRole->permissions()->attach(
            Permission::create(['key' => 'system.admin', 'name' => 'System administrator', 'is_protected' => true])
        );
        $admin = $this->createUser($adminRole);
        $admin->update([
            'mfa_secret' => 'GEZDGNBVGY3TQOJQGEZDGNBVGY3TQOJQ',
            'mfa_enabled_at' => now(),
        ]);
        $exportedFile = ExportedFile::create([
            'user_id' => $owner->id,
            'path' => 'exports/other-user.xlsx',
            'filename' => 'other-user.xlsx',
            'format' => 'xlsx',
            'status' => 'ready',
            'expires_at' => now()->addDay(),
        ]);
        Storage::disk('private')->put($exportedFile->path, 'private export');

        $this->actingAs($admin)
            ->get(route('penduduk.exports.download', $exportedFile))
            ->assertForbidden();
    }

    private function createUser(Role $role): User
    {
        $id = DB::table('users')->insertGetId([
            'uuid' => (string) Str::uuid(),
            'name' => 'Test User',
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

    private function createPenduduk(?string $foto = null): Penduduk
    {
        $id = DB::table('penduduk')->insertGetId([
            'uuid' => (string) Str::uuid(),
            'nik' => (string) random_int(1000000000000000, 9999999999999999),
            'no_kk' => '1234567890123456',
            'nama_lengkap' => 'Test Resident',
            'foto' => $foto,
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
