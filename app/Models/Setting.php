<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use App\Observers\SettingObserver;
use Illuminate\Support\Facades\Cache;

#[ObservedBy([SettingObserver::class])]
class Setting extends Model
{
    protected $table = 'settings';
    protected $guarded = ['id'];

    /**
     * Helper caching untuk mengambil nilai setting dengan sangat cepat.
     */
    public static function get(string $key, $default = null)
    {
        return Cache::rememberForever('setting_' . $key, function () use ($key, $default) {
            $setting = self::where('key', $key)->first();
            return $setting ? $setting->value : $default;
        });
    }
}
