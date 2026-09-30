<?php

use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GeneralSettingController;
use App\Http\Controllers\JenisSuratController;
use App\Http\Controllers\LaporanSuratController;
use App\Http\Controllers\MfaSecurityController;
use App\Http\Controllers\PendudukController;
use App\Http\Controllers\PrivateFileController;
use App\Http\Controllers\ProfilDesaController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleMenuController;
use App\Http\Controllers\SuratKeluarController;
use App\Http\Controllers\SuratMasukController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - E-Office Desa Ridogalih
|--------------------------------------------------------------------------
| [SECURITY LAYER]: Semua parameter yang berinteraksi dengan data publik
| atau arsip sensitif WAJIB menggunakan UUID.
*/

// ==========================================
// 1. HALAMAN PUBLIK (WELCOME & VALIDASI SURAT)
// ==========================================
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// FIXED: Rute Validasi dikeluarkan dari Auth agar bisa di-scan QR Code oleh publik
Route::get('/validasi/surat/{uuid}', [SuratKeluarController::class, 'validasi'])->name('validasi.surat');

// ==========================================
// 2. AUTENTIKASI (GUEST) & SECURITY THROTTLE
// ==========================================
// POST autentikasi memakai limiter terpisah; halaman GET tidak menghabiskan kuota percobaan.
Route::middleware('guest')->controller(AuthController::class)->group(function () {
    Route::get('/login', 'showLoginForm')->name('login');
    Route::post('/login', 'login')->middleware('throttle:login')->name('login.post');
    Route::get('/login/mfa', 'showMfaChallenge')->middleware('throttle:mfa')->name('login.mfa');
    Route::post('/login/mfa', 'verifyMfaChallenge')->middleware('throttle:mfa')->name('login.mfa.verify');

    Route::get('/register', 'showRegistrationForm')->name('register');
    Route::post('/register', 'register')->middleware('throttle:register')->name('register.post');
    Route::post('/register/verify-otp', 'verifyOtp')->middleware('throttle:otp')->name('register.verify-otp');
});

// ==========================================
// 3. ROUTE UTAMA (AUTH & PRIVILEGE OTOMATIS)
// ==========================================
// Route Logout berdiri sendiri agar tidak terhalang middleware privilege
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// Terapkan perlindungan Auth dan Hak Akses Ketat pada grup ini
Route::middleware(['auth', 'privilege', 'mfa.enrollment'])->group(function () {
    Route::get('/penduduk/{penduduk}/photo', [PrivateFileController::class, 'pendudukPhoto'])
        ->middleware('throttle:file-download')->name('penduduk.photo');
    Route::get('/surat-masuk/{suratMasuk}/file', [PrivateFileController::class, 'suratMasukFile'])
        ->middleware('throttle:file-download')->name('surat-masuk.file');

    // ------------------------------------------
    // DASHBOARD
    // ------------------------------------------
    Route::controller(DashboardController::class)->group(function () {
        Route::get('/dashboard', 'index')->name('dashboard');
        Route::get('/home', 'index')->name('home');
    });

    // ------------------------------------------
    // MODUL MANAJEMEN PENGGUNA (USERS)
    // ------------------------------------------
    Route::controller(UserController::class)->prefix('users')->name('users.')->group(function () {
        Route::get('export', 'export')->middleware('throttle:sensitive-action')->name('export');
        // Catatan: Jika User sudah pakai UUID, parameter otomatis menggunakan UUID di resource
        Route::get('{user}/print', 'print')->name('print');
    });
    Route::resource('users', UserController::class)
        ->middlewareFor(['store', 'update', 'destroy'], 'throttle:sensitive-action');

    // ------------------------------------------
    // MODUL DATA PENDUDUK
    // ------------------------------------------
    Route::controller(PendudukController::class)->prefix('penduduk')->name('penduduk.')->group(function () {
        Route::get('exports', 'exports')->middleware('throttle:file-download')->name('exports');
        Route::get('exports/{exportedFile}/download', 'downloadExport')->middleware('throttle:file-download')->name('exports.download');
        Route::post('import', 'importExcel')->middleware('throttle:sensitive-action')->name('import');
        Route::get('export/excel', 'exportExcel')->middleware('throttle:sensitive-action')->name('export.excel');
        Route::get('export/csv', 'exportCsv')->middleware('throttle:sensitive-action')->name('export.csv');
        Route::get('export/pdf', 'exportPdf')->middleware('throttle:sensitive-action')->name('export.pdf');

        Route::get('template', 'downloadTemplate')->name('download.template');
        // [DEBT SECURITY]: Nantinya ubah {id} ke {uuid} jika tabel Penduduk sudah di-upgrade
        Route::get('print', 'print')->name('print');
    });
    Route::resource('penduduk', PendudukController::class)
        ->middlewareFor(['store', 'update', 'destroy'], 'throttle:sensitive-action');

    // ------------------------------------------
    // MODUL E-SURAT (MASUK, KELUAR, JENIS)
    // ------------------------------------------
    // [SECURITY FIXED]: Parameter {id} diganti menjadi {uuid} untuk cetak surat
    Route::get('/surat-keluar/{uuid}/print', [SuratKeluarController::class, 'print'])->name('surat-keluar.print');

    Route::resource('jenis-surat', JenisSuratController::class)->parameters([
        'jenis-surat' => 'uuid',
    ]);

    // [SECURITY FIXED]: Memaksa Laravel Resource untuk menggunakan {uuid} sebagai kunci parameter
    Route::resource('surat-keluar', SuratKeluarController::class)->parameters([
        'surat-keluar' => 'uuid',
    ])->except(['edit'])->middlewareFor(['store', 'update', 'destroy'], 'throttle:sensitive-action');
    Route::resource('surat-masuk', SuratMasukController::class)->parameters([
        'surat-masuk' => 'uuid',
    ])->middlewareFor(['store', 'update', 'destroy'], 'throttle:sensitive-action');

    // ------------------------------------------
    // MODUL LAPORAN & BUKU AGENDA SURAT
    // ------------------------------------------
    Route::controller(LaporanSuratController::class)->prefix('laporan-surat')->name('laporan-surat.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/print', 'print')->name('print');
    });

    // ------------------------------------------
    // 4. Pengaturan keamanan dan role yang dilindungi permission server-side
    // ------------------------------------------
    // MANAJEMEN ROLE & MENUS
    Route::prefix('admin/role-menus')->name('admin.role-menus.')->controller(RoleMenuController::class)->middleware('admin.ip')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/{role}/edit', 'edit')->name('edit');
        Route::put('/{role}', 'update')->middleware('throttle:sensitive-action')->name('update');
        Route::post('/permissions', 'storePermission')->middleware('throttle:sensitive-action')->name('permissions.store');

        // CRUD MASTER MENU / RUTE AKSI
        // [DEBT SECURITY]: Ini ranah admin, penggunaan {id} masih bisa ditolerir sementara waktu
        Route::post('/store-menu', 'storeMenu')->middleware('throttle:sensitive-action')->name('storeMenu');
        Route::put('/menu/{menu}', 'updateMenu')->middleware('throttle:sensitive-action')->name('updateMenu');
        Route::delete('/menu/{menu}', 'destroyMenu')->middleware('throttle:sensitive-action')->name('destroyMenu');
    });

    // PENGATURAN PROFIL DESA
    Route::prefix('admin/profil-desa')->name('admin.profil-desa.')->controller(ProfilDesaController::class)->middleware('admin.ip')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/show', 'show')->name('show');
        Route::get('/print', 'print')->name('print');
        Route::put('/', 'update')->name('update');
    });

    // ==========================================
    // 5. MODUL AUDIT LOG (HANYA UNTUK SUPER ADMIN /

    Route::prefix('admin/audit-logs')->name('admin.audit-logs.')->controller(AuditLogController::class)->middleware('admin.ip')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/{id}', 'show')->name('show');
    });

    Route::prefix('/admin.settings')->name('admin.settings.')->controller(GeneralSettingController::class)->middleware('admin.ip')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'update')->middleware('throttle:sensitive-action')->name('update');
        Route::get('/backup', 'backupSql')->middleware('throttle:sensitive-action')->name('backup');
    });

    // ==========================================
    // 6. MODUL PROFIL PENGGUNA (USER PROFILE)
    // ==========================================
    Route::prefix('profile')->name('profile.')->controller(ProfileController::class)->group(function () {
        Route::get('/security/mfa', [MfaSecurityController::class, 'setup'])->middleware('admin.ip')->name('security-mfa.setup');
        Route::post('/security/mfa', [MfaSecurityController::class, 'enable'])->middleware(['admin.ip', 'throttle:mfa'])->name('security-mfa.enable');
        Route::get('/security/mfa/recovery-codes', [MfaSecurityController::class, 'showRecoveryCodes'])->middleware('admin.ip')->name('security-mfa.recovery');
        Route::get('/edit', 'edit')->name('edit');
        Route::get('/photo', [ProfileController::class, 'photo'])->middleware('throttle:file-download')->name('photo');
        Route::post('/update', 'update')->middleware('throttle:sensitive-action')->name('update');
        Route::post('/update-password', 'updatePassword')->middleware('throttle:sensitive-action')->name('update-password');
    });
});
