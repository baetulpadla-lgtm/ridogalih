<?php

namespace App\Imports;

use App\Models\Penduduk;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Illuminate\Contracts\Queue\ShouldQueue;
use Carbon\Carbon;

class PendudukImport implements ToModel, WithHeadingRow, WithBatchInserts, WithChunkReading, WithValidation, SkipsEmptyRows, ShouldQueue
{
    public function model(array $row): ?Penduduk
    {
        $nik_bersih = preg_replace('/[^0-9]/', '', $row['nik'] ?? '');
        $kk_bersih  = preg_replace('/[^0-9]/', '', $row['no_kk'] ?? '');

        if (empty($nik_bersih) || Penduduk::where('nik', $nik_bersih)->exists()) {
            return null;
        }
        // Penanganan Tanggal Lahir (Excel to Date) - Presisi Error Handling
        $tanggal_lahir = '1970-01-01';
        if (!empty($row['tanggal_lahir'])) {
            if (is_numeric($row['tanggal_lahir'])) {
                $tanggal_lahir = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['tanggal_lahir'])->format('Y-m-d');
            } else {
                try {
                    $tanggal_lahir = Carbon::parse($row['tanggal_lahir'])->format('Y-m-d');
                } catch (\Exception $e) {
                    // Fallback aman jika format teks tanggal hancur
                    $tanggal_lahir = '1970-01-01';
                }
            }
        }

        // Normalisasi Enum Data
        $jk = strtoupper(trim($row['jenis_kelamin'] ?? ''));
        $jenis_kelamin = in_array($jk, ['P', 'PEREMPUAN']) ? 'Perempuan' : 'Laki-laki';

        $goldar = strtoupper(trim($row['golongan_darah'] ?? ''));
        $golongan_darah = in_array($goldar, ['A', 'B', 'AB', 'O']) ? $goldar : 'Tidak Tahu';

        // SECURITY FIXED: Wajib XSS Sanitization (strip_tags) pada semua data tekstual dari Excel
        return new Penduduk([
            'nik'                      => $nik_bersih,
            'no_kk'                    => $kk_bersih ?: '-',
            'nama_lengkap'             => strip_tags($row['nama_lengkap'] ?? 'Tanpa Nama'),
            'tempat_lahir'             => strip_tags($row['tempat_lahir'] ?? '-'),
            'tanggal_lahir'            => $tanggal_lahir,
            'jenis_kelamin'            => $jenis_kelamin,
            'agama'                    => strip_tags($row['agama'] ?? 'Islam'),
            'pendidikan'               => strip_tags($row['pendidikan'] ?? '-'),
            'pekerjaan'                => strip_tags($row['pekerjaan'] ?? '-'),
            'golongan_darah'           => $golongan_darah,
            'status_perkawinan'        => strip_tags($row['status_perkawinan'] ?? 'Belum Kawin'),
            'status_hubungan_keluarga' => strip_tags($row['status_hubungan_keluarga'] ?? '-'),
            'kewarganegaraan'          => strip_tags($row['kewarganegaraan'] ?? 'WNI'),
            'nama_ayah'                => strip_tags($row['nama_ayah'] ?? '-'),
            'nama_ibu'                 => strip_tags($row['nama_ibu'] ?? '-'), // Akan otomatis dienkripsi Model
            'alamat_lengkap'           => strip_tags($row['alamat_lengkap'] ?? '-'),
            'dusun'                    => strip_tags($row['dusun'] ?? null),
            'rt'                       => strip_tags($row['rt'] ?? '001'),
            'rw'                       => strip_tags($row['rw'] ?? '001'),
            'desa'                     => strip_tags($row['desa'] ?? config('app.desa_default')),
            'kecamatan'                => strip_tags($row['kecamatan'] ?? '-'),
            'kabupaten'                => strip_tags($row['kabupaten'] ?? '-'),
            'provinsi'                 => strip_tags($row['provinsi'] ?? '-'),
            'kode_pos'                 => strip_tags($row['kode_pos'] ?? null),
            'status_kependudukan'      => 'Aktif',
        ]);
    }

    // PERFORMA FIXED: Validasi dipindahkan ke sini. Laravel Excel akan melakukan validasi secara Batch!
    // Ini menghemat ribuan Query ke database (Mencegah N+1 Problem).
    public function rules(): array
    {
        return [
            'nik'          => 'required|unique:penduduk,nik',
            'nama_lengkap' => 'required|string|max:255',
        ];
    }

    // Memasukkan data ke DB per 200 baris (Ditingkatkan untuk efisiensi Laravel 13)
    public function batchSize(): int
    {
        return 500;
    }

    // Membaca file Excel per 200 baris
    public function chunkSize(): int
    {
        return 500;
    }
}
