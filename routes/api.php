<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\DeviceController;

/*
|--------------------------------------------------------------------------
| IOT DEVICE INTELLIGENT ROUTES
|--------------------------------------------------------------------------
| Throttle: Maksimal 60 request per menit per IP untuk mencegah serangan DDoS
*/

Route::middleware('throttle:api')->prefix('v1/device')->group(function () {
    // Endpoint yang akan "ditembak" oleh perangkat ESP32 saat ada warga scan kartu/QR
    Route::post('verify-access', [DeviceController::class, 'verifyAccess']);
});
