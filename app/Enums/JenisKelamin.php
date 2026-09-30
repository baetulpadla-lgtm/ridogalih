<?php

namespace App\Enums;

use App\Traits\EnumOptions;
enum JenisKelamin: string {
    use EnumOptions; // Cukup panggil Trait ini, fungsi values() otomatis tersedia
    case LAKI_LAKI = 'Laki-laki';
    case PEREMPUAN = 'Perempuan';

}
