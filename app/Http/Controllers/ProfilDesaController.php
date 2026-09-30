<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Models\ProfilDesa;
use App\Models\User;
use App\Http\Requests\ProfilDesaRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;

class ProfilDesaController extends Controller
{
    private function getProfil(): ProfilDesa
    {
        return ProfilDesa::firstOrCreate(['id' => 1]);
    }

    public function index(): View
    {
        Gate::authorize('security.settings.update');

        $profil = $this->getProfil();
        return view('admin.profil-desa.index', compact('profil'));
    }

    public function update(ProfilDesaRequest $request): RedirectResponse
    {
        Gate::authorize('security.settings.update');

        $validated = $request->validated();
        $profil = $this->getProfil();

        $oldLogoPath = $profil->logo_path;
        $newLogoPath = null;

        try {
            DB::beginTransaction();

            if ($request->hasFile('logo')) {
                $newLogoPath = $request->file('logo')->store('logo', 'public');
                $validated['logo_path'] = $newLogoPath;
            }
            unset($validated['logo']);

            $profil->update($validated);
            DB::commit();

            if ($newLogoPath && $oldLogoPath && Storage::disk('public')->exists($oldLogoPath)) {
                Storage::disk('public')->delete($oldLogoPath);
            }

            return redirect()->route('admin.profil-desa.index')
                             ->with('success', 'Identitas Pemerintahan Desa berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();

            if ($newLogoPath && Storage::disk('public')->exists($newLogoPath)) {
                Storage::disk('public')->delete($newLogoPath);
            }

            report($e);
            return back()->withInput()->with('error', 'Terjadi kesalahan sistem saat memperbarui identitas desa.');
        }
    }

    public function show(): View
    {
        Gate::authorize('security.settings.update');

        $profil = $this->getProfil();
        return view('admin.profil-desa.show', compact('profil'));
    }

    public function print(): View
    {
        Gate::authorize('security.settings.update');

        $profil = $this->getProfil();
        $namaKades = User::getNamaKepalaDesa();

        return view('admin.profil-desa.print', compact('profil', 'namaKades'));
    }
}
