<?php

namespace App\Enums;

use App\Traits\EnumOptions;

enum StatusPerkawinan: string {

    use EnumOptions; // Cukup panggil Trait ini, fungsi values() otomatis tersedia
    case BELUM_KAWIN = 'Belum Kawin';
    case KAWIN = 'Kawin';
    case CERAI_HIDUP = 'Cerai Hidup';
    case CERAI_MATI = 'Cerai Mati';

}
