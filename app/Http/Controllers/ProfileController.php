<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Requests\PasswordUpdateRequest;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ProfileController extends Controller
{
    public function edit(): View
    {
        /** @var User $user */
        $user = Auth::user();
        $user->loadMissing('penduduk');

        return view('profile.edit', compact('user'));
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();
        $user->loadMissing('penduduk');
        $validated = $request->validated();

        $oldPhoto = $user->penduduk?->foto;
        $newPhotoPath = null;

        try {
            DB::beginTransaction();

            $user->update([
                'name'  => $validated['name'],
                'email' => $validated['email'],
            ]);

            if ($user->penduduk) {
                $pendudukUpdate = ['nama_lengkap' => $validated['name']];

                if ($request->hasFile('foto')) {
                    $newPhotoPath = $request->file('foto')->store('penduduk/foto', 'private');
                    $pendudukUpdate['foto'] = $newPhotoPath;
                }

                $user->penduduk->update($pendudukUpdate);
            }

            DB::commit();

            // Hapus foto lama HANYA JIKA transaksi database sukses [Anti Orphan File]
            if ($newPhotoPath && $oldPhoto && Storage::disk('private')->exists($oldPhoto)) {
                Storage::disk('private')->delete($oldPhoto);
            }

            return back()->with('success', 'Profil berhasil diperbarui dan tersinkronisasi!');
        } catch (\Exception $e) {
            DB::rollBack();
            if ($newPhotoPath && Storage::disk('private')->exists($newPhotoPath)) {
                Storage::disk('private')->delete($newPhotoPath);
            }
            return back()->with('error', 'Terjadi kesalahan sistem saat memperbarui profil.');
        }
    }

    public function updatePassword(PasswordUpdateRequest $request): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();
        $validated = $request->validated();

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        Auth::logoutOtherDevices($validated['password']);

        return back()->with('success', 'Kata sandi diperbarui. Semua sesi di perangkat lain telah diputus demi keamanan.');
    }

    public function photo()
    {
        /** @var User $user */
        $user = Auth::user();
        $user->loadMissing('penduduk');

        abort_unless($user->penduduk, 404);

        return redirect()->route('penduduk.photo', $user->penduduk);
    }
}
