<?php

namespace App\Models;

use App\Observers\AuditLogObserver;
use App\Observers\UserObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Cache;

// Mendaftarkan Observer menggunakan PHP 8 Attributes
#[ObservedBy([AuditLogObserver::class, UserObserver::class])]
class User extends Authenticatable
{
    use HasFactory, HasUuids, Notifiable, SoftDeletes;

    protected $guarded = ['id']; // Zero-Maintenance Mass Assignment Protection

    public function uniqueIds(): array
    {
        return ['uuid'];
    }

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    protected $hidden = [
        'password',
        'remember_token',
        'mfa_secret',
        'mfa_recovery_codes',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'mfa_secret' => 'encrypted',
            'mfa_recovery_codes' => 'encrypted',
            'mfa_enabled_at' => 'datetime',
        ];
    }

    public function penduduk(): BelongsTo
    {
        return $this->belongsTo(Penduduk::class, 'penduduk_id');
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class, 'group_id');
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function isSuperAdmin(): bool
    {
        return $this->hasPermission('system.admin');
    }

    public function hasPermission(string $permission): bool
    {
        if (! $this->role_id) {
            return false;
        }

        return $this->role()
            ->whereHas('permissions', fn ($query) => $query->where('key', $permission))
            ->exists();
    }

    public function hasRole(string|array $roles): bool
    {
        if (! $this->role) {
            return false;
        }

        $currentRoleName = strtolower($this->role->name);

        if (is_array($roles)) {
            $lowerRoles = array_map('strtolower', $roles);

            return in_array($currentRoleName, $lowerRoles);
        }

        return $currentRoleName === strtolower($roles);
    }

    public function hasAnyRole(string|array $roles): bool
    {
        return $this->hasRole($roles);
    }

    public function suratKeluars(): HasMany
    {
        return $this->hasMany(SuratKeluar::class, 'user_id');
    }

    public static function getNamaKepalaDesa(): string
    {
        return Cache::rememberForever('nama_kepala_desa_aktif', function () {
            $kades = self::whereHas('role', fn ($q) => $q->where('name', 'LIKE', '%Kepala Desa%'))
                ->where('status_akun', 'Aktif')
                ->first();

            return $kades ? strtoupper($kades->name) : 'KEPALA DESA RIDOGALIH';
        });
    }

    // public static function boot() dihapus karena HasUuids sudah mengurus generate UUID otomatis.
}
