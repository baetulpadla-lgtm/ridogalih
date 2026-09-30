<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use App\Observers\ProfilDesaObserver;

// Mendaftarkan Observer secara langsung menggunakan PHP Attributes
#[ObservedBy([ProfilDesaObserver::class])]
class ProfilDesa extends Model
{
    use HasUuids;

    protected $table = 'profil_desas';
    protected $guarded = ['id'];

    public function uniqueIds(): array
    {
        return ['uuid'];
    }

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    protected function casts(): array
    {
        return [];
    }
}
