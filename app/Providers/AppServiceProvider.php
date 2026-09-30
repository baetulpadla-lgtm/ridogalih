<?php

namespace App\Providers;

use App\Models\AuditLog;
use App\Models\ExportedFile;
use App\Models\Menu;
use App\Models\Penduduk;
use App\Models\Permission;
use App\Models\ProfilDesa;
use App\Models\Role;
use App\Models\SuratKeluar;
use App\Models\SuratMasuk;
use App\Models\User;
use App\Policies\AuditLogPolicy;
use App\Policies\ExportedFilePolicy;
use App\Policies\MenuPolicy;
use App\Policies\PendudukPolicy;
use App\Policies\PermissionPolicy;
use App\Policies\RolePolicy;
use App\Policies\SuratKeluarPolicy;
use App\Policies\SuratMasukPolicy;
use App\Policies\UserPolicy;
use App\Services\MenuService;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // 1. Mencegah error key too long di database lawas
        Schema::defaultStringLength(191);

        // 2. Keamanan & Performa Tingkat Tinggi (Strict Mode)
        // Mencegah N+1 Query, silent mass assignment, & missing attribute exception
        Model::shouldBeStrict($this->app->environment() !== 'production');

        // 3. Paksa HTTPS di Lingkungan Production untuk mencegah sniffing
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        RateLimiter::for('login', function (Request $request) {
            $nik = preg_replace('/\D/', '', (string) $request->input('nik'));
            $identity = hash('sha256', $request->ip().'|'.Str::lower($nik));

            return [
                Limit::perMinute(5)->by('login:identity:'.$identity),
                Limit::perMinute(10)->by('login:nik:'.hash('sha256', Str::lower($nik))),
                Limit::perMinute(20)->by('login:ip:'.$request->ip()),
            ];
        });
        RateLimiter::for('register', function (Request $request) {
            $nik = preg_replace('/\D/', '', (string) $request->input('nik'));

            return [
                Limit::perHour(3)->by('register:identity:'.hash('sha256', $request->ip().'|'.$nik)),
                Limit::perHour(12)->by('register:ip:'.$request->ip()),
            ];
        });
        RateLimiter::for('otp', function (Request $request) {
            $sessionId = $request->hasSession() ? $request->session()->getId() : '';

            return [
                Limit::perMinute(5)->by('otp:session:'.hash('sha256', $request->ip().'|'.$sessionId)),
                Limit::perMinute(5)->by('otp:identity:'.hash('sha256', Str::lower((string) $request->session()->get('pending_registration.email', '')))),
                Limit::perMinute(15)->by('otp:ip:'.$request->ip()),
            ];
        });
        RateLimiter::for('mfa', function (Request $request) {
            $pendingUser = (string) $request->session()->get('pending_mfa_user_uuid', '');

            return [
                Limit::perMinute(5)->by('mfa:session:'.hash('sha256', $request->ip().'|'.$request->session()->getId())),
                Limit::perMinute(10)->by('mfa:user:'.hash('sha256', $pendingUser)),
                Limit::perMinute(15)->by('mfa:ip:'.$request->ip()),
            ];
        });
        RateLimiter::for('api', fn (Request $request) => Limit::perMinute(60)->by('api:ip:'.$request->ip()));
        RateLimiter::for('sensitive-action', fn (Request $request) => Limit::perMinute(10)->by('sensitive:user:'.($request->user()?->getAuthIdentifier() ?? $request->ip())));
        RateLimiter::for('file-download', fn (Request $request) => Limit::perMinute(60)->by('download:user:'.($request->user()?->getAuthIdentifier() ?? $request->ip())));

        // Pastikan tabel profil_desas sudah ada untuk menghindari error saat pertama kali migrasi
        if (Schema::hasTable('profil_desas')) {
            $profil = ProfilDesa::first();
            // Share variabel $profil ke seluruh file Blade
            View::share('profil', $profil);
        }

        // PENGHAPUSAN BUG:
        // Pendaftaran Observer manual dihapus dari sini.
        // Standar Laravel 13 & PHP 8.4 mewajibkan pemakaian #[ObservedBy] langsung di dalam file Model.

        // 4. Mendaftarkan Gates Keamanan Sentral
        $this->registerSecurityGates();

        View::composer('layouts.app', function ($view): void {
            $menuService = app(MenuService::class);
            $navigation = $menuService->navigationForUser(Auth::user());

            $view->with([
                'menuNavigations' => $navigation,
                'authorizedSearchMenus' => $menuService->searchItems($navigation),
            ]);
        });
    }

    private function registerSecurityGates(): void
    {
        Gate::before(function (User $user, string $ability): ?bool {
            if ($user->isSuperAdmin()) {
                return true;
            }

            if (str_contains($ability, '.') && Permission::where('key', $ability)->exists()) {
                return $user->hasPermission($ability);
            }

            return null;
        });

        Gate::policy(User::class, UserPolicy::class);
        Gate::policy(Role::class, RolePolicy::class);
        Gate::policy(Penduduk::class, PendudukPolicy::class);
        Gate::policy(SuratMasuk::class, SuratMasukPolicy::class);
        Gate::policy(SuratKeluar::class, SuratKeluarPolicy::class);
        Gate::policy(AuditLog::class, AuditLogPolicy::class);
        Gate::policy(Menu::class, MenuPolicy::class);
        Gate::policy(Permission::class, PermissionPolicy::class);
        Gate::policy(ExportedFile::class, ExportedFilePolicy::class);
    }
}
