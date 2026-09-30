<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Models\DeviceLog;
use App\Models\Penduduk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DeviceController extends Controller
{
    public function verifyAccess(Request $request)
    {
        // 1. SECURITY: Validasi Format Mutlak (Mencegah SQL/Command Injection dari IoT)
        $validated = $request->validate([
            'mac_address' => 'required|string|regex:/^([0-9A-Fa-f]{2}[:-]){5}([0-9A-Fa-f]{2})$/',
            'payload'     => 'required|string|max:255', // Hasil scan QR (UUID Penduduk) atau UID RFID
            'method'      => 'required|in:QR,RFID,FINGERPRINT'
        ]);

        // 2. SECURITY: Otorisasi Perangkat via Bearer Token (Anti-Rogue Device)
        $token = $request->bearerToken();
        if (!$token) {
            return response()->json(['success' => false, 'message' => 'Token Otorisasi Perangkat Hilang'], 401);
        }

        // Cari perangkat berdasarkan MAC dan aktif
        $device = Device::where('mac_address', $validated['mac_address'])
                        ->where('status', 'Aktif')
                        ->first();

        // Verifikasi ketat menggunakan Hash (sama seperti prinsip verifikasi password user)
        if (!$device || !Hash::check($token, $device->api_token_hash)) {
            return response()->json(['success' => false, 'message' => 'Perangkat Tidak Dikenali atau Diblokir'], 403);
        }

        // Update waktu ping terakhir (Heartbeat)
        $device->update(['last_ping' => now()]);

        // 3. LOGIC: Validasi Data Kependudukan
        // Asumsi payload adalah UUID Penduduk dari QR Code TTE yang discan di gerbang
        $penduduk = Penduduk::where('uuid', strip_tags($validated['payload']))
                            ->where('status_kependudukan', 'Aktif')
                            ->first();

        $statusAkses = $penduduk ? 'Granted' : 'Denied';

        // 4. FORENSIK: Catat Histori Akses (Immutable Log)
        DeviceLog::create([
            'device_id'       => $device->id,
            'penduduk_id'     => $penduduk ? $penduduk->id : null,
            'metode_akses'    => $validated['method'],
            'payload_scanned' => Str::limit($validated['payload'], 50), // Limit agar database tidak overflow
            'status_akses'    => $statusAkses
        ]);

        // 5. RESPONS: Format JSON Ringkas & Tegas untuk ESP32
        if ($statusAkses === 'Granted') {
            return response()->json([
                'success'  => true,
                'action'   => 'UNLOCK',
                'duration' => 5, // Relay terbuka 5 detik
                'user'     => $penduduk->nama_lengkap
            ], 200);
        }

        return response()->json([
            'success' => false,
            'action'  => 'LOCK',
            'message' => 'Akses Ditolak: Data Tidak Valid atau Warga Nonaktif'
        ], 403);
    }
}
