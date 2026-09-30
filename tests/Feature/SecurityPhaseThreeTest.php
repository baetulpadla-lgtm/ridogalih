<?php

namespace Tests\Feature;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use App\Services\Security\AuditDataSanitizer;
use App\Services\Security\TotpService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tests\TestCase;

class SecurityPhaseThreeTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        Carbon::setTestNow();

        parent::tearDown();
    }

    public function test_security_headers_and_compatible_csp_are_applied(): void
    {
        config([
            'security.csp.enabled' => true,
            'security.csp.report_only' => true,
        ]);

        $response = $this->get(route('login'));

        $response->assertOk()
            ->assertHeader('X-Content-Type-Options', 'nosniff')
            ->assertHeader('X-Frame-Options', 'DENY')
            ->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin')
            ->assertHeader('Permissions-Policy', 'camera=(), microphone=(), geolocation=()')
            ->assertHeader('Content-Security-Policy-Report-Only');

        $policy = $response->headers->get('Content-Security-Policy-Report-Only');
        $this->assertStringContainsString("default-src 'self'", $policy);
        $this->assertStringContainsString("object-src 'none'", $policy);
        $this->assertStringContainsString('https://cdn.jsdelivr.net', $policy);
        $this->assertArrayNotHasKey('Content-Security-Policy', $response->headers->all());

        config(['security.csp.report_only' => false]);
        $this->get(route('login'))->assertHeader('Content-Security-Policy');
    }

    public function test_ip_restriction_checks_single_ips_and_cidr_ranges_and_fails_closed_when_misconfigured(): void
    {
        $role = Role::create(['name' => 'Settings Reader']);
        $role->permissions()->attach(Permission::create([
            'key' => 'security.settings.update',
            'name' => 'Update security settings',
        ]));
        $user = $this->createUser($role);
        config([
            'security.ip_restriction.enabled' => true,
            'security.ip_restriction.allowed' => ['192.0.2.0/24', '198.51.100.7'],
        ]);

        $this->actingAs($user)
            ->withServerVariables(['REMOTE_ADDR' => '203.0.113.8'])
            ->get(route('admin.settings.index'))
            ->assertForbidden();

        $this->withServerVariables(['REMOTE_ADDR' => '192.0.2.42'])
            ->get(route('admin.settings.index'))
            ->assertOk();

        config(['security.ip_restriction.allowed' => []]);
        $this->withServerVariables(['REMOTE_ADDR' => '192.0.2.42'])
            ->get(route('admin.settings.index'))
            ->assertStatus(503);
    }

    public function test_totp_service_matches_rfc6238_six_digit_vector_and_rejects_invalid_codes(): void
    {
        $totp = app(TotpService::class);
        $secret = 'GEZDGNBVGY3TQOJQGEZDGNBVGY3TQOJQ';

        $this->assertSame('287082', $totp->currentCode($secret, 59));
        $this->assertSame(1, $totp->matchingStep($secret, '287082', 59));
        $this->assertNull($totp->matchingStep($secret, '000000', 59));
        $this->assertNull($totp->matchingStep($secret, '12ab56', 59));
    }

    public function test_system_admin_must_enroll_mfa_then_uses_totp_for_next_login(): void
    {
        $role = Role::create(['name' => 'Administrator']);
        $role->permissions()->attach(Permission::create([
            'key' => 'system.admin',
            'name' => 'System administrator',
            'is_protected' => true,
        ]));
        $user = $this->createUser($role, true);

        $this->post(route('login.post'), [
            'nik' => $user->penduduk->nik,
            'password' => 'Password123!',
        ])->assertRedirect(route('profile.security-mfa.setup'));

        $this->get(route('dashboard'))->assertRedirect(route('profile.security-mfa.setup'));
        $setupResponse = $this->get(route('profile.security-mfa.setup'));
        $setupResponse->assertOk();
        $this->assertStringContainsString('private', $setupResponse->headers->get('Cache-Control'));
        $this->assertStringContainsString('no-store', $setupResponse->headers->get('Cache-Control'));
        $secret = $user->fresh()->mfa_secret;
        $this->assertNotEmpty($secret);

        $this->post(route('profile.security-mfa.enable'), [
            'code' => app(TotpService::class)->currentCode($secret),
        ])->assertRedirect(route('profile.security-mfa.recovery'));

        $this->assertNotNull($user->fresh()->mfa_enabled_at);
        $recoveryResponse = $this->get(route('profile.security-mfa.recovery'));
        $recoveryResponse->assertOk();
        $recoveryCodes = $recoveryResponse->viewData('codes');
        $this->assertCount(8, $recoveryCodes);
        $this->get(route('profile.security-mfa.recovery'))->assertNotFound();
        $dashboardResponse = $this->get(route('dashboard'));
        $this->assertSame(200, $dashboardResponse->status(), (string) $dashboardResponse->headers->get('Location'));

        Carbon::setTestNow(now()->addSeconds(31));
        $this->post(route('logout'));
        $this->post(route('login.post'), [
            'nik' => $user->penduduk->nik,
            'password' => 'Password123!',
        ])->assertRedirect(route('login.mfa'));

        $this->get(route('dashboard'))->assertRedirect(route('login'));
        $code = app(TotpService::class)->currentCode($secret);
        $this->post(route('login.mfa.verify'), ['code' => $code])
            ->assertRedirect(route('dashboard'))
            ->assertSessionHasNoErrors();
        $this->assertAuthenticatedAs($user);

        $this->post(route('logout'));
        $this->post(route('login.post'), [
            'nik' => $user->penduduk->nik,
            'password' => 'Password123!',
        ])->assertRedirect(route('login.mfa'));
        $this->from(route('login.mfa'))
            ->post(route('login.mfa.verify'), ['code' => $code])
            ->assertRedirect(route('login.mfa'));
        $this->assertGuest();

        $this->post(route('login.post'), [
            'nik' => $user->penduduk->nik,
            'password' => 'Password123!',
        ])->assertRedirect(route('login.mfa'));
        $this->post(route('login.mfa.verify'), ['code' => $recoveryCodes[0]])
            ->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($user);
        $remainingCodes = json_decode((string) $user->fresh()->mfa_recovery_codes, true, 512, JSON_THROW_ON_ERROR);
        $this->assertCount(7, $remainingCodes);

        $this->post(route('logout'));
        $this->post(route('login.post'), [
            'nik' => $user->penduduk->nik,
            'password' => 'Password123!',
        ])->assertRedirect(route('login.mfa'));
        $this->from(route('login.mfa'))
            ->post(route('login.mfa.verify'), ['code' => $recoveryCodes[0]])
            ->assertRedirect(route('login.mfa'));
        $this->assertGuest();
        Carbon::setTestNow();
    }

    public function test_mfa_challenge_locks_pending_login_after_five_invalid_codes(): void
    {
        $role = Role::create(['name' => 'Administrator']);
        $role->permissions()->attach(Permission::create([
            'key' => 'system.admin',
            'name' => 'System administrator',
            'is_protected' => true,
        ]));
        $user = $this->createUser($role, true);
        $user->update([
            'mfa_secret' => 'GEZDGNBVGY3TQOJQGEZDGNBVGY3TQOJQ',
            'mfa_enabled_at' => now(),
            'mfa_last_used_step' => 0,
            'mfa_recovery_codes' => json_encode([Hash::make('A1B2C3D4E5F60718293A4B5C')]),
        ]);
        Carbon::setTestNow(Carbon::createFromTimestamp(59));

        $this->post(route('login.post'), [
            'nik' => $user->penduduk->nik,
            'password' => 'Password123!',
        ])->assertRedirect(route('login.mfa'));

        for ($attempt = 0; $attempt < 5; $attempt++) {
            $response = $this->from(route('login.mfa'))
                ->post(route('login.mfa.verify'), ['code' => '000000']);
            $this->assertTrue($response->isRedirect());
            if ($attempt < 4) {
                $this->assertSame(route('login.mfa'), $response->headers->get('Location'));
            } else {
                $this->assertSame(route('login'), $response->headers->get('Location'));
            }
        }

        $this->assertGuest();
        $this->get(route('login.mfa'))->assertRedirect(route('login'));
    }

    public function test_audit_sanitizer_redacts_mfa_material_and_recovery_codes(): void
    {
        $safe = AuditDataSanitizer::sanitize([
            'mfa_secret' => 'plain-totp-secret',
            'mfa_recovery_codes' => ['plain-recovery-code'],
            'name' => 'Administrator',
        ]);

        $this->assertSame('********', $safe['mfa_secret']);
        $this->assertSame('********', $safe['mfa_recovery_codes']);
        $this->assertSame('Administrator', $safe['name']);
    }

    private function createUser(Role $role, bool $withPenduduk = false): User
    {
        $pendudukId = null;
        if ($withPenduduk) {
            $pendudukId = DB::table('penduduk')->insertGetId([
                'uuid' => (string) Str::uuid(),
                'nik' => (string) random_int(1000000000000000, 9999999999999999),
                'no_kk' => '1234567890123456',
                'nama_lengkap' => 'MFA Administrator',
                'tempat_lahir' => 'Bekasi',
                'tanggal_lahir' => '1980-01-01',
                'jenis_kelamin' => 'Laki-laki',
                'agama' => 'Islam',
                'pendidikan' => 'SMA',
                'pekerjaan' => 'Administrator',
                'status_perkawinan' => 'Kawin',
                'status_hubungan_keluarga' => 'Kepala Keluarga',
                'nama_ayah' => 'Administrator Father',
                'nama_ibu' => 'Administrator Mother',
                'alamat_lengkap' => 'Test address',
                'rt' => '001',
                'rw' => '001',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $id = DB::table('users')->insertGetId([
            'uuid' => (string) Str::uuid(),
            'penduduk_id' => $pendudukId,
            'name' => 'Test Security User',
            'email' => Str::uuid().'@example.test',
            'password' => Hash::make('Password123!'),
            'role_id' => $role->id,
            'is_active' => true,
            'status_akun' => 'Aktif',
            'theme' => 'light',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return User::findOrFail($id);
    }
}
