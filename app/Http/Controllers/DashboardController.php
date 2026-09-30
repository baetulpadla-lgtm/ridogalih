<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\View\View;
use App\Models\SuratMasuk;
use App\Models\SuratKeluar;
use App\Models\Penduduk;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function index(): View
    {
        /** @var User $user */
        $user = Auth::user();
        $user->loadMissing(['role', 'group']);

        $groupName = $user->group?->name ?? '';
        $roleName  = $user->role?->name ?? '';
        $isWarga   = (strtolower($roleName) === 'warga' || strtolower($groupName) === 'warga');

        if ($isWarga) {
            return $this->getWargaDashboard($user, $groupName, $roleName, $isWarga);
        }

        return $this->getAdminDashboard($user, $groupName, $roleName, $isWarga);
    }

    private function getWargaDashboard(User $user, string $groupName, string $roleName, bool $isWarga): View
    {
        $scopeTitle = "Layanan Mandiri Warga Desa Ridogalih";
        $scopeBadge = "Portal Warga";

        // Query spesifik untuk warga tidak dibebani cache jangka panjang karena scope kecil dan dinamis
        $wargaTotalSurat = SuratKeluar::where('user_id', $user->id)->count();
        $wargaMenunggu   = SuratKeluar::where('user_id', $user->id)->where('status', 'Menunggu')->count();
        $wargaDiproses   = SuratKeluar::where('user_id', $user->id)->whereIn('status', ['Diproses', 'Disetujui'])->count();
        $wargaSelesai    = SuratKeluar::where('user_id', $user->id)->where('status', 'Selesai')->count();
        $wargaDitolak    = SuratKeluar::where('user_id', $user->id)->where('status', 'Ditolak')->count();

        $riwayatSuratWarga = SuratKeluar::with('jenis_surat')
                                        ->where('user_id', $user->id)
                                        ->latest()
                                        ->take(5)
                                        ->get();

        return view('dashboard.index', compact(
            'user', 'groupName', 'roleName', 'isWarga', 'scopeTitle', 'scopeBadge',
            'wargaTotalSurat', 'wargaMenunggu', 'wargaDiproses', 'wargaSelesai', 'wargaDitolak', 'riwayatSuratWarga'
        ));
    }

    private function getAdminDashboard(User $user, string $groupName, string $roleName, bool $isWarga): View
    {
        $scopeTitle = "Pemerintahan Desa Ridogalih (Akses Global)";
        $scopeBadge = "Administrator / Perangkat Desa";

        // [PERFORMANCE TWEAK]: Memori Cache diaktifkan selama 10 menit (600 detik) untuk statistik berat
        $stats = Cache::remember('admin_dashboard_stats', 600, function () {
            $months = collect(range(5, 0))->map(fn($i) => now()->subMonths($i)->format('M'));
            $suratMasukTrend = [];
            $suratKeluarTrend = [];

            foreach (range(5, 0) as $i) {
                $date = now()->subMonths($i);
                $suratMasukTrend[] = SuratMasuk::whereYear('tanggal_diterima', $date->year)
                                               ->whereMonth('tanggal_diterima', $date->month)->count();

                $suratKeluarTrend[] = SuratKeluar::whereYear('created_at', $date->year)
                                                 ->whereMonth('created_at', $date->month)->count();
            }

            return [
                'totalSuratMasuk'  => SuratMasuk::count(),
                'totalSuratKeluar' => SuratKeluar::count(),
                'totalPenduduk'    => Penduduk::count(),
                'totalUser'        => User::count(),
                'suratMenunggu'    => SuratKeluar::where('status', 'Menunggu')->count(),
                'chartDesaBpd'     => [
                    SuratKeluar::where('status', 'Selesai')->count(),
                    SuratKeluar::where('status', 'Diproses')->count(),
                    SuratKeluar::where('status', 'Menunggu')->count(),
                ],
                'months'           => $months,
                'suratMasukTrend'  => $suratMasukTrend,
                'suratKeluarTrend' => $suratKeluarTrend,
            ];
        });

        // Data yang bersifat sangat dinamis dan perlu real-time tidak dimasukkan ke dalam cache
        $latestSuratMasuk = SuratMasuk::latest()->take(5)->get();
        $latestPengajuan  = SuratKeluar::with(['jenis_surat', 'penduduk'])
                                       ->where('status', 'Menunggu')
                                       ->latest()
                                       ->take(5)
                                       ->get();

        return view('dashboard.index', array_merge(compact(
            'user', 'groupName', 'roleName', 'isWarga', 'scopeTitle', 'scopeBadge',
            'latestSuratMasuk', 'latestPengajuan'
        ), $stats));
    }
}
