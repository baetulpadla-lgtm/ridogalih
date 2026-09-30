<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Device extends Model
{
    use SoftDeletes, HasUuids; // Otomatisasi UUID

    protected $table = 'devices';
    protected $guarded = ['id'];

    public function uniqueIds(): array { return ['uuid']; }
    public function getRouteKeyName(): string { return 'uuid'; }

    // [SECURITY]: Jangan pernah tampilkan hash token dan Internal ID di API Response
    protected $hidden = [
        'api_token_hash', 'id'
    ];

    protected function casts(): array
    {
        return [
            'last_ping' => 'datetime',
        ];
    }

    public function logs(): HasMany
    {
        return $this->hasMany(DeviceLog::class, 'device_id');
    }
}
