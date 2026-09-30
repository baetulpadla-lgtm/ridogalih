<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Gate;
use App\Models\SuratMasuk;
use App\Models\SuratKeluar;
use App\Models\ProfilDesa;
use App\Http\Requests\LaporanSuratRequest;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\View\View;

class LaporanSuratController extends Controller
{
    /**
     * [DRY PRINCIPLE] Memusatkan logika kueri agar index dan print tidak menulis ulang kode yang sama.
     */
    private function getLaporanData(array $validated): array
    {
        $jenisLaporan = $validated['jenis_laporan'] ?? 'masuk';
        $startDate    = $validated['start_date'] ?? Carbon::now()->startOfMonth()->format('Y-m-d');
        $endDate      = $validated['end_date'] ?? Carbon::now()->endOfMonth()->format('Y-m-d');

        if ($jenisLaporan === 'masuk') {
            $dataSurat = SuratMasuk::whereBetween('tanggal_diterima', [$startDate, $endDate])
                                   ->orderBy('tanggal_diterima', 'asc')
                                   ->get();
            $judul = "BUKU AGENDA SURAT MASUK";
        } else {
            $dataSurat = SuratKeluar::with(['jenis_surat', 'penduduk'])
                                    ->whereIn('status', ['Disetujui', 'Selesai'])
                                    ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
                                    ->orderBy('created_at', 'asc')
                                    ->get();
            $judul = "BUKU AGENDA SURAT KELUAR";
        }

        return compact('dataSurat', 'jenisLaporan', 'startDate', 'endDate', 'judul');
    }

    public function index(LaporanSuratRequest $request): View
    {
        Gate::authorize('laporan_surat.view');

        $data = $this->getLaporanData($request->validated());

        return view('admin.laporan_surat.index', $data);
    }

    public function print(LaporanSuratRequest $request)
    {
        Gate::authorize('laporan_surat.print');

        $data = $this->getLaporanData($request->validated());
        $data['profil'] = ProfilDesa::first() ?? new ProfilDesa();

        $pdf = Pdf::loadView('admin.laporan_surat.print', $data)
                  ->setPaper('A4', 'landscape');

        return $pdf->stream('Buku_Agenda_' . ucfirst($data['jenisLaporan']) . '_' . date('F_Y') . '.pdf');
    }
}
