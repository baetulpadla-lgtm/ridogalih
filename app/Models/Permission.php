<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Permission extends Model
{
    protected $guarded = ['id'];

    protected function casts(): array
    {
        return ['is_protected' => 'boolean'];
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_permissions');
    }

    public function menus()
    {
        return $this->hasMany(Menu::class);
    }
}
