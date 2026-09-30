<?php

namespace App\Traits;

/**
 * @method static array cases()
 */
trait EnumOptions
{
    /**
     * Mengambil semua nilai (value) dari Enum menjadi array flat.
     * Sangat berguna untuk dropdown di Blade View.
     */
    public static function values(): array
    {
        // Fitur asli PHP 8.4: array_column bisa langsung membaca properties dari object Enum
        return array_column(self::cases(), 'value');
    }
}
