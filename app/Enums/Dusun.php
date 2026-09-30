<?php

namespace App\Enums;

use App\Traits\EnumOptions;

enum Dusun: string
{
    use EnumOptions; // Cukup panggil Trait ini, fungsi values() otomatis tersedia

    case DUSUN_1 = '1';
    case DUSUN_2 = '2';
    case DUSUN_3 = '3';
    case DUSUN_4 = '4';
    case DUSUN_5 = '5';

 public function label(): string
    {
        return match($this) {
            self::DUSUN_1 => 'Dusun 1',
            self::DUSUN_2 => 'Dusun 2',
            self::DUSUN_3 => 'Dusun 3',
            self::DUSUN_4 => 'Dusun 4',
            self::DUSUN_5 => 'Dusun 5',
        };
    }

}
