<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use App\Observers\AuditLogObserver;

#[ObservedBy([AuditLogObserver::class])]
class SuratMasuk extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    protected $table = 'surat_masuks';
    protected $guarded = ['id'];

    public function uniqueIds(): array { return ['uuid']; }
    public function getRouteKeyName(): string { return 'uuid'; }

    protected function casts(): array
    {
        return [
            'tanggal_surat'    => 'date',
            'tanggal_diterima' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
