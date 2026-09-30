<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Group extends Model
{
    use HasFactory, HasUuids;

    protected $guarded = ['id'];

    public function uniqueIds(): array { return ['uuid']; }
    public function getRouteKeyName(): string { return 'uuid'; }

    public function roles(): HasMany
    {
        return $this->hasMany(Role::class, 'group_id');
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'group_id');
    }
}
