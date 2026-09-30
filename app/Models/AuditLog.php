<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class AuditLog extends Model
{
    use HasUlids; // Otomatisasi ULID

    protected $table = 'audit_logs';
    protected $guarded = []; // Kosongkan guarded karena kita proteksi dari Controller/Observer

    protected function casts(): array
    {
        return [
            'old_values' => 'array',
            'new_values' => 'array',
            'is_robot'   => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
