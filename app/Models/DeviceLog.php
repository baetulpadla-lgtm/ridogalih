<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class DeviceLog extends Model
{
    use HasUuids;

    protected $table = 'device_logs';
    protected $guarded = ['id'];

    public function uniqueIds(): array { return ['uuid']; }
    public function getRouteKeyName(): string { return 'uuid'; }

    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class, 'device_id');
    }

    public function penduduk(): BelongsTo
    {
        return $this->belongsTo(Penduduk::class, 'penduduk_id');
    }

    protected static function booted(): void
    {
        // [SECURITY LEVEL 10]: Sabuk Pengaman Immutable
        static::updating(function ($log) {
            throw new \Exception('Integritas Sistem: Data Log Akses Fisik IoT tidak boleh dimodifikasi.');
        });

        static::deleting(function ($log) {
            throw new \Exception('Integritas Sistem: Penghapusan Data Log Akses Fisik IoT dilarang keras secara sistem.');
        });
    }
}
