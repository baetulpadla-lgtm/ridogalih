<?php

namespace App\Enums;

use App\Traits\EnumOptions;

enum Agama: string {
    use EnumOptions; // Cukup panggil Trait ini, fungsi values() otomatis tersedia

    case ISLAM = 'Islam';
    case KRISTEN = 'Kristen';
    case KATOLIK = 'Katolik';
    case HINDU = 'Hindu';
    case BUDDHA = 'Buddha';
    case KONGHUCU = 'Konghucu';
}
