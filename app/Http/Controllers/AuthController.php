<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Penduduk;
use App\Models\Role;
use App\Models\User;
use App\Services\Security\TotpService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use Throwable;

class AuthController extends Controller
{
    private const MAX_OTP_ATTEMPTS = 5;

    private const REGISTRATION_ERROR = 'Data pendaftaran tidak dapat diproses. Periksa data Anda atau coba kembali.';

    public function showLoginForm(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $request->validate([
            'nik' => ['required', 'string', 'digits:16'],
            'password' => ['required', 'string'],
        ]);

        $penduduk = Penduduk::where('nik', $request->nik)->first();

        if ($penduduk && $penduduk->user) {
            $user = $penduduk->user;

            if ($user->is_active && $user->status_akun === 'Aktif') {
                if (Hash::check($request->password, $user->password)) {
                    if ($user->isSuperAdmin() && $user->mfa_enabled_at) {
                        $request->session()->regenerate();
                        $request->session()->put([
                            'pending_mfa_user_uuid' => $user->uuid,
                            'pending_mfa_expires_at' => now()->addMinutes(5)->timestamp,
                            'pending_mfa_attempts' => 0,
                        ]);

                        return redirect()->route('login.mfa');
                    }

                    Auth::login($user);
                    $request->session()->regenerate();

                    $user->update([
                        'last_login_at' => now(),
                        'last_login_ip' => $request->ip(),
                    ]);

                    if ($user->isSuperAdmin() && ! $user->mfa_enabled_at) {
                        $request->session()->put('mfa_enrollment_required', true);

                        return redirect()->route('profile.security-mfa.setup');
                    }

                    return redirect()->intended('dashboard');
                }
            }
        }

        return back()->withErrors([
            'nik' => 'NIK atau Kata Sandi salah, atau Akun Anda sedang dinonaktifkan.',
        ])->onlyInput('nik');
    }

    public function showMfaChallenge(Request $request): View|RedirectResponse
    {
        if (! $this->pendingMfaUser($request)) {
            $request->session()->forget([
                'pending_mfa_user_uuid',
                'pending_mfa_expires_at',
                'pending_mfa_attempts',
            ]);

            return redirect()->route('login');
        }

        return view('auth.mfa-challenge');
    }

    public function verifyMfaChallenge(Request $request, TotpService $totp): RedirectResponse
    {
        $validated = $request->validate([
            'code' => ['required', 'regex:/^(?:\d{6}|[A-Fa-f0-9]{24})$/'],
        ]);
        $user = $this->pendingMfaUser($request);

        if (! $user || ! $user->mfa_secret) {
            $request->session()->forget([
                'pending_mfa_user_uuid',
                'pending_mfa_expires_at',
                'pending_mfa_attempts',
            ]);

            return redirect()->route('login')->withErrors(['code' => 'Sesi verifikasi MFA telah berakhir. Silakan masuk kembali.']);
        }

        $step = $totp->matchingStep($user->mfa_secret, $validated['code']);
        $verified = false;

        if ($step !== null && $step > (int) ($user->mfa_last_used_step ?? -1)) {
            $claimedStep = User::query()
                ->whereKey($user->getKey())
                ->where('is_active', true)
                ->where('status_akun', 'Aktif')
                ->whereNotNull('mfa_enabled_at')
                ->where(function ($query) use ($step): void {
                    $query->whereNull('mfa_last_used_step')
                        ->orWhere('mfa_last_used_step', '<', $step);
                })
                ->update([
                    'mfa_last_used_step' => $step,
                    'last_login_at' => now(),
                    'last_login_ip' => $request->ip(),
                ]);
            $verified = $claimedStep === 1;
        } elseif (strlen($validated['code']) === 24) {
            $verified = DB::transaction(function () use ($user, $validated, $request): bool {
                $lockedUser = User::query()->lockForUpdate()->find($user->getKey());
                if (
                    ! $lockedUser
                    || ! $lockedUser->is_active
                    || $lockedUser->status_akun !== 'Aktif'
                    || ! $lockedUser->isSuperAdmin()
                    || ! $lockedUser->mfa_enabled_at
                ) {
                    return false;
                }

                $recoveryCodes = json_decode((string) $lockedUser->mfa_recovery_codes, true, 512, JSON_THROW_ON_ERROR);
                foreach ($recoveryCodes as $index => $recoveryCodeHash) {
                    if (Hash::check($validated['code'], $recoveryCodeHash)) {
                        unset($recoveryCodes[$index]);
                        $lockedUser->mfa_recovery_codes = json_encode(array_values($recoveryCodes), JSON_THROW_ON_ERROR);
                        $lockedUser->last_login_at = now();
                        $lockedUser->last_login_ip = $request->ip();
                        $lockedUser->save();

                        return true;
                    }
                }

                return false;
            });
        }

        if (! $verified) {
            $attempts = $request->session()->increment('pending_mfa_attempts');
            if ($attempts >= 5) {
                $request->session()->forget([
                    'pending_mfa_user_uuid',
                    'pending_mfa_expires_at',
                    'pending_mfa_attempts',
                ]);

                return redirect()->route('login')->withErrors(['code' => 'Batas percobaan MFA tercapai. Silakan masuk kembali.']);
            }

            return back()->withErrors(['code' => 'Kode MFA salah atau sudah pernah digunakan.']);
        }

        $request->session()->forget([
            'pending_mfa_user_uuid',
            'pending_mfa_expires_at',
            'pending_mfa_attempts',
        ]);
        Auth::login($user->fresh());
        $request->session()->regenerate();

        return redirect()->intended('dashboard');
    }

    private function pendingMfaUser(Request $request): ?User
    {
        $uuid = $request->session()->get('pending_mfa_user_uuid');
        $expiresAt = (int) $request->session()->get('pending_mfa_expires_at', 0);

        if (! is_string($uuid) || $expiresAt <= now()->timestamp) {
            return null;
        }

        $user = User::where('uuid', $uuid)->first();

        if (! $user || ! $user->is_active || $user->status_akun !== 'Aktif' || ! $user->isSuperAdmin() || ! $user->mfa_enabled_at) {
            return null;
        }

        return $user;
    }

    public function showRegistrationForm(Request $request): View
    {
        $characters = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789';
        $captchaString = '';
        for ($i = 0; $i < 6; $i++) {
            $captchaString .= $characters[random_int(0, strlen($characters) - 1)];
        }
        session(['captcha_string' => $captchaString]);

        return view('auth.register', compact('captchaString'));
    }

    public function register(Request $request): RedirectResponse
    {
        $userCaptcha = trim((string) $request->input('captcha'));
        $realCaptcha = session('captcha_string');

        if (empty($realCaptcha) || ! hash_equals($realCaptcha, $userCaptcha)) {
            return back()
                ->withErrors(['captcha' => 'Kode keamanan (Captcha) salah.'])
                ->withInput($request->only('nik', 'email'));
        }

        // [BUG FIXED]: Form register sekarang WAJIB menanyakan email untuk mengirim OTP
        $validated = $request->validate([
            'nik' => ['required', 'string', 'digits:16'],
            'email' => ['required', 'email', 'max:100'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $penduduk = Penduduk::where('nik', $validated['nik'])->first();

        if (
            ! $penduduk
            || $penduduk->user()->exists()
            || DB::table('users')->where('email', $validated['email'])->exists()
        ) {
            return back()
                ->withErrors(['registration' => self::REGISTRATION_ERROR])
                ->withInput(['email' => $validated['email']]);
        }

        $otpCode = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        try {
            Mail::raw("Halo {$penduduk->nama_lengkap},\n\nKode Verifikasi (OTP) pendaftaran Layanan Mandiri Desa Ridogalih Anda adalah: {$otpCode}\n\nBerlaku selama 10 menit.", function ($message) use ($validated) {
                $message->to($validated['email'])->subject('Kode OTP E-Office Desa Ridogalih');
            });
        } catch (Throwable $e) {
            report($e);

            return back()
                ->withErrors(['registration' => self::REGISTRATION_ERROR])
                ->withInput(['email' => $validated['email']]);
        }

        session([
            'pending_registration' => [
                'name' => $penduduk->nama_lengkap,
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'penduduk_id' => $penduduk->id,
                'otp_hash' => Hash::make($otpCode),
                'otp_expires_at' => now()->addMinutes(10)->toIso8601String(),
                'otp_attempts' => 0,
            ],
        ]);

        session()->forget('captcha_string');

        return back()->with('show_otp_modal', true)->with('success', 'OTP dikirim ke email Anda.');
    }

    public function verifyOtp(Request $request): RedirectResponse
    {
        $request->validate(['otp_code' => ['required', 'numeric', 'digits:6']]);

        $pending = session('pending_registration');
        if (! $pending) {
            return redirect()->route('register')->withErrors(['otp_code' => 'Sesi verifikasi berakhir. Silakan daftar kembali.']);
        }

        if (! isset($pending['otp_hash'], $pending['otp_expires_at']) || ! is_string($pending['otp_hash']) || ! is_string($pending['otp_expires_at'])) {
            session()->forget('pending_registration');

            return redirect()->route('register')->withErrors(['otp_code' => 'Sesi verifikasi berakhir. Silakan daftar kembali.']);
        }

        if (now()->isAfter($pending['otp_expires_at'])) {
            session()->forget('pending_registration');

            return redirect()->route('register')->withErrors(['otp_code' => 'Sesi verifikasi berakhir. Silakan daftar kembali.']);
        }

        if (! Hash::check((string) $request->otp_code, $pending['otp_hash'])) {
            $pending['otp_attempts'] = ($pending['otp_attempts'] ?? 0) + 1;

            if ($pending['otp_attempts'] >= self::MAX_OTP_ATTEMPTS) {
                session()->forget('pending_registration');

                return redirect()->route('register')->withErrors(['otp_code' => 'Batas percobaan tercapai. Silakan daftar kembali.']);
            }

            session(['pending_registration' => $pending]);

            return back()->with('show_otp_modal', true)->withErrors(['otp_code' => 'OTP salah.']);
        }

        // [BUG FIXED]: Cari ID Role dan Group khusus Warga secara dinamis dari database
        $roleWarga = Role::where('name', 'Warga')->first();
        $groupWarga = Group::where('name', 'Warga')->first();

        // [BUG FIXED]: Masukkan role_id, group_id, dan email agar tidak memicu SQL Constraint
        $user = User::create([
            'name' => $pending['name'],
            'email' => $pending['email'],
            'password' => $pending['password'],
            'penduduk_id' => $pending['penduduk_id'],
            'group_id' => $groupWarga?->id,
            'role_id' => $roleWarga?->id,
            'is_active' => true,
            'status_akun' => 'Aktif',
        ]);

        session()->forget('pending_registration');
        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('dashboard')->with('success', 'Akun berhasil diverifikasi!');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Anda telah keluar dari sistem.');
    }
}
