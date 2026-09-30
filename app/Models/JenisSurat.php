<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use App\Observers\AuditLogObserver;

#[ObservedBy([AuditLogObserver::class])]
class JenisSurat extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    protected $table = 'jenis_surats';
    protected $guarded = ['id'];

    public function uniqueIds(): array { return ['uuid']; }
    public function getRouteKeyName(): string { return 'uuid'; }

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function suratKeluars(): HasMany
    {
        return $this->hasMany(SuratKeluar::class, 'jenis_surat_id');
    }

    public function scopeAktif(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    protected static function booted(): void
    {
        static::deleting(function ($jenisSurat) {
            if ($jenisSurat->suratKeluars()->exists()) {
                throw new \Exception('Keamanan Terpicu: Jenis Surat ini tidak dapat dihapus karena sudah digunakan dalam arsip Dokumen Warga.');
            }
        });
    }
}
