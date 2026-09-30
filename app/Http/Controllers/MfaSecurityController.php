<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\Security\TotpService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class MfaSecurityController extends Controller
{
    public function setup(TotpService $totp): Response|RedirectResponse
    {
        $user = $this->privilegedUser();

        if ($user->mfa_enabled_at) {
            return redirect()->route('profile.edit')->with('success', 'MFA TOTP sudah aktif untuk akun Anda.');
        }

        if (! $user->mfa_secret) {
            $user->update(['mfa_secret' => $totp->generateSecret()]);
            $user->refresh();
        }

        $issuer = (string) config('app.name', 'E-Office');
        $provisioningUri = $totp->provisioningUri($user->mfa_secret, (string) $user->email, $issuer);

        return response()
            ->view('profile.security-mfa', compact('user', 'provisioningUri'))
            ->header('Cache-Control', 'private, no-store');
    }

    public function enable(Request $request, TotpService $totp): RedirectResponse
    {
        $user = $this->privilegedUser();
        $validated = $request->validate(['code' => ['required', 'digits:6']]);

        abort_unless($user->mfa_secret, 409, 'Buat sesi enrollment MFA terlebih dahulu.');

        $step = $totp->matchingStep($user->mfa_secret, $validated['code']);
        if ($step === null || $step <= (int) ($user->mfa_last_used_step ?? -1)) {
            return back()->withErrors(['code' => 'Kode MFA salah atau sudah pernah digunakan.']);
        }

        $recoveryCodes = $totp->generateRecoveryCodes();
        $enabled = DB::transaction(function () use ($user, $step, $recoveryCodes): bool {
            $updated = User::query()
                ->whereKey($user->getKey())
                ->whereNull('mfa_enabled_at')
                ->whereNull('mfa_last_used_step')
                ->update([
                    'mfa_enabled_at' => now(),
                    'mfa_last_used_step' => $step,
                ]);

            if ($updated !== 1) {
                return false;
            }

            $user = User::findOrFail($user->getKey());
            $user->mfa_recovery_codes = json_encode(array_map(
                static fn (string $code): string => Hash::make($code),
                $recoveryCodes
            ), JSON_THROW_ON_ERROR);
            $user->save();

            return true;
        });

        if (! $enabled) {
            return back()->withErrors(['code' => 'MFA sudah aktif atau kode setup telah digunakan.']);
        }
        Auth::setUser($user->fresh());
        $request->session()->forget('mfa_enrollment_required');
        $request->session()->regenerate();

        return redirect()->route('profile.security-mfa.recovery')
            ->with('mfa_recovery_codes', $recoveryCodes);
    }

    public function showRecoveryCodes(Request $request): Response
    {
        $codes = $request->session()->pull('mfa_recovery_codes');
        abort_unless(is_array($codes) && count($codes) === 8, 404);

        return response()
            ->view('profile.security-mfa-recovery', compact('codes'))
            ->header('Cache-Control', 'private, no-store');
    }

    private function privilegedUser(): User
    {
        /** @var User $user */
        $user = Auth::user();
        abort_unless($user->isSuperAdmin(), 403);

        return $user;
    }
}
