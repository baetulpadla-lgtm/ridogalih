<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Tests\TestCase;

class AuthOtpHardeningTest extends TestCase
{
    use RefreshDatabase;

    public function test_expired_registration_otp_cannot_be_used(): void
    {
        $this->withSession([
            'pending_registration' => [
                'otp_hash' => Hash::make('123456'),
                'otp_expires_at' => now()->subMinute()->toIso8601String(),
                'otp_attempts' => 0,
            ],
        ]);

        $this->post('/register/verify-otp', ['otp_code' => '123456'])
            ->assertRedirect(route('register'))
            ->assertSessionMissing('pending_registration');
    }

    public function test_legacy_plaintext_otp_session_is_invalidated(): void
    {
        $this->withSession([
            'pending_registration' => [
                'otp' => 123456,
                'otp_expires' => now()->addMinutes(10)->toIso8601String(),
            ],
        ]);

        $this->post('/register/verify-otp', ['otp_code' => '123456'])
            ->assertRedirect(route('register'))
            ->assertSessionMissing('pending_registration');
    }

    public function test_registration_otp_is_invalidated_after_five_failed_attempts(): void
    {
        $this->withSession([
            'pending_registration' => [
                'otp_hash' => Hash::make('123456'),
                'otp_expires_at' => now()->addMinutes(10)->toIso8601String(),
                'otp_attempts' => 0,
            ],
        ]);

        for ($attempt = 1; $attempt <= 5; $attempt++) {
            $response = $this->post('/register/verify-otp', ['otp_code' => '000000']);

            if ($attempt < 5) {
                $response->assertSessionHas('pending_registration.otp_attempts', $attempt);
            } else {
                $response
                    ->assertRedirect(route('register'))
                    ->assertSessionMissing('pending_registration');
            }
        }
    }

    public function test_registration_responses_do_not_disclose_nik_or_email_existence(): void
    {
        $this->withSession(['captcha_string' => 'ABC123']);

        $this->post('/register', $this->registrationData('1111111111111111', 'new@example.test'))
            ->assertSessionHasErrors([
                'registration' => 'Data pendaftaran tidak dapat diproses. Periksa data Anda atau coba kembali.',
            ]);

        $pendudukId = $this->createPenduduk('2222222222222222');
        DB::table('users')->insert([
            'uuid' => (string) Str::uuid(),
            'penduduk_id' => $pendudukId,
            'name' => 'Existing User',
            'email' => 'existing@example.test',
            'password' => Hash::make('Existing-password-1'),
        ]);

        $this->post('/register', $this->registrationData('2222222222222222', 'new@example.test'))
            ->assertSessionHasErrors([
                'registration' => 'Data pendaftaran tidak dapat diproses. Periksa data Anda atau coba kembali.',
            ]);

        $this->createPenduduk('3333333333333333');
        $this->post('/register', $this->registrationData('3333333333333333', 'existing@example.test'))
            ->assertSessionHasErrors([
                'registration' => 'Data pendaftaran tidak dapat diproses. Periksa data Anda atau coba kembali.',
            ]);
    }

    public function test_registration_stores_only_a_hash_of_the_otp(): void
    {
        $this->createPenduduk('4444444444444444');
        $this->withSession(['captcha_string' => 'ABC123']);

        $otpCode = null;
        Mail::shouldReceive('raw')
            ->once()
            ->withArgs(function (string $body, callable $callback) use (&$otpCode): bool {
                preg_match('/\b(\d{6})\b/', $body, $matches);
                $otpCode = $matches[1] ?? null;

                return $otpCode !== null;
            });

        $this->post('/register', $this->registrationData('4444444444444444', 'new@example.test'))
            ->assertSessionHas('show_otp_modal', true);

        $pending = session('pending_registration');
        $this->assertNotNull($otpCode);
        $this->assertArrayHasKey('otp_hash', $pending);
        $this->assertArrayNotHasKey('otp', $pending);
        $this->assertTrue(Hash::check($otpCode, $pending['otp_hash']));
        $this->assertSame(0, $pending['otp_attempts']);
    }

    public function test_captcha_failure_does_not_flash_registration_secrets(): void
    {
        $this->withSession(['captcha_string' => 'ABC123']);

        $this->post('/register', $this->registrationData('5555555555555555', 'new@example.test', 'WRONG'))
            ->assertSessionHasErrors('captcha')
            ->assertSessionMissing('_old_input.password')
            ->assertSessionMissing('_old_input.password_confirmation');
    }

    /**
     * @return array<string, string>
     */
    private function registrationData(string $nik, string $email, string $captcha = 'ABC123'): array
    {
        return [
            'nik' => $nik,
            'email' => $email,
            'password' => 'Correct-horse-123',
            'password_confirmation' => 'Correct-horse-123',
            'captcha' => $captcha,
        ];
    }

    private function createPenduduk(string $nik): int
    {
        return DB::table('penduduk')->insertGetId([
            'uuid' => (string) Str::uuid(),
            'nik' => $nik,
            'no_kk' => '1234567890123456',
            'nama_lengkap' => 'Test Resident',
            'tempat_lahir' => 'Bekasi',
            'tanggal_lahir' => '1990-01-01',
            'jenis_kelamin' => 'Laki-laki',
            'agama' => 'Islam',
            'pendidikan' => 'SMA',
            'pekerjaan' => 'Petani',
            'golongan_darah' => 'Tidak Tahu',
            'status_perkawinan' => 'Belum Kawin',
            'status_hubungan_keluarga' => 'Kepala Keluarga',
            'kewarganegaraan' => 'WNI',
            'nama_ayah' => 'Test Father',
            'nama_ibu' => 'Test Mother',
            'alamat_lengkap' => 'Test Address',
            'rt' => '001',
            'rw' => '001',
            'status_kependudukan' => 'Aktif',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
