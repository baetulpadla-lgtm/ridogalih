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
class SuratKeluar extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    protected $table = 'surat_keluars';
    protected $guarded = ['id'];

    public function uniqueIds(): array { return ['uuid']; }
    public function getRouteKeyName(): string { return 'uuid'; }

    public function jenis_surat(): BelongsTo
    {
        return $this->belongsTo(JenisSurat::class, 'jenis_surat_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function penduduk(): BelongsTo
    {
        return $this->belongsTo(Penduduk::class, 'penduduk_id');
    }
}
