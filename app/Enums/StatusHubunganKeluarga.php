<?php

namespace App\Enums;

use App\Traits\EnumOptions;

enum StatusHubunganKeluarga: string
{
    use EnumOptions; // Cukup panggil Trait ini, fungsi values() otomatis tersedia
    case KEPALA_KELUARGA = 'Kepala Keluarga';
    case SUAMI = 'Suami';
    case ISTRI = 'Istri';
    case ANAK = 'Anak';
    case MENANTU = 'Menantu';
    case CUCU = 'Cucu';
    case ORANG_TUA = 'Orang Tua';
    case MERTUA = 'Mertua';
    case KELUARGA_LAIN = 'Keluarga Lain';
    case LAINNYA = 'Lainnya';

}
