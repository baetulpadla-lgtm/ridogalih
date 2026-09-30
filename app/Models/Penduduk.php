<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use App\Observers\AuditLogObserver;
use App\Observers\PendudukObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

#[ObservedBy([AuditLogObserver::class, PendudukObserver::class])]
class Penduduk extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    protected $table = 'penduduk';
    protected $guarded = ['id'];

    /**
     * [SECURITY] Memberitahu trait HasUuids untuk generate UUID di kolom 'uuid',
     * membiarkan primary key 'id' tetap menggunakan Integer Auto-Increment.
     */
    public function uniqueIds(): array
    {
        return ['uuid'];
    }

    /**
     * [SECURITY] Memaksa Route Model Binding menggunakan UUID, mencegah IDOR.
     */
    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    protected function casts(): array
    {
        return [
            'tanggal_lahir'            => 'date',
            'nama_ibu'                 => 'encrypted', // Lapisan Enkripsi Database
            'agama'                    => \App\Enums\Agama::class,
            'jenis_kelamin'            => \App\Enums\JenisKelamin::class,
            'golongan_darah'           => \App\Enums\GolonganDarah::class,
            'status_perkawinan'        => \App\Enums\StatusPerkawinan::class,
            'status_hubungan_keluarga' => \App\Enums\StatusHubunganKeluarga::class,
            'dusun'                    => \App\Enums\Dusun::class,
            'status_kependudukan'      => \App\Enums\StatusKependudukan::class,
            'pendidikan'               => \App\Enums\Pendidikan::class,
            'pekerjaan'                => \App\Enums\Pekerjaan::class,
            'rt'                       => \App\Enums\Rt::class,
            'rw'                       => \App\Enums\Rw::class,
        ];
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'penduduk_id');
    }

    public function suratKeluars(): HasMany
    {
        return $this->hasMany(SuratKeluar::class, 'penduduk_id');
    }

    protected function umur(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->tanggal_lahir ? $this->tanggal_lahir->age : 0,
        );
    }

    protected function domisili(): Attribute
    {
        return Attribute::make(
            get: fn () => "RT " . str_pad((string)($this->rt?->value ?? 0), 3, '0', STR_PAD_LEFT) .
                          " / RW " . str_pad((string)($this->rw?->value ?? 0), 3, '0', STR_PAD_LEFT) .
                          " - Dusun " . ($this->dusun?->value ?? '-'),
        );
    }

    public function scopeSearch(Builder $query, ?string $search): Builder
    {
        if ($search) {
            $safeSearch = str_replace(['%', '_'], ['\%', '\_'], $search);
            return $query->where(function (Builder $q) use ($safeSearch) {
                $q->where('nama_lengkap', 'like', "%{$safeSearch}%")
                  ->orWhere('nik', 'like', "%{$safeSearch}%")
                  ->orWhere('no_kk', 'like', "%{$safeSearch}%");
            });
        }
        return $query;
    }

    public function scopeFilterWilayah(Builder $query, ?string $filter): Builder
    {
        if ($filter) {
            $safeFilter = str_replace(['%', '_'], ['\%', '\_'], $filter);
            return $query->where(function (Builder $q) use ($safeFilter) {
                $q->where('dusun', 'like', "%{$safeFilter}%")
                  ->orWhere('alamat_lengkap', 'like', "%{$safeFilter}%");
            });
        }
        return $query;
    }

    protected static function booted()
    {
        // UUID generation dihapus karena sudah di-handle otomatis oleh HasUuids & uniqueIds()

        static::deleting(function ($penduduk) {
            if ($penduduk->user()->exists()) {
                throw new \Exception('Data penduduk tidak dapat dihapus karena masih terikat dengan Akun Pengguna E-Office aktif. Harap cabut akun terlebih dahulu.');
            }
        });
    }
}
