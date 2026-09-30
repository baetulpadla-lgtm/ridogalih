<?php

namespace App\Enums;

use App\Traits\EnumOptions;

enum StatusKependudukan: string {

    use EnumOptions; // Cukup panggil Trait ini, fungsi values() otomatis tersedia
    case AKTIF = 'Aktif';
    case MENINGGAL = 'Meninggal';
    case PINDAH = 'Pindah';
    case HILANG = 'Hilang';

}
