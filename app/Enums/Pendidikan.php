<?php

namespace App\Enums;

use App\Traits\EnumOptions;

enum Pendidikan: string
{
    use EnumOptions; // Cukup panggil Trait ini, fungsi values() otomatis tersedia
    case TIDAK_SEKOLAH = 'Tidak Sekolah';
    case PUTUS_SD = 'Putus SD';
    case SD = 'SD';
    case SMP = 'SMP';
    case SMA = 'SMA';
    case D1 = 'D1';
    case D2 = 'D2';
    case D3 = 'D3';
    case S1 = 'S1';
    case S2 = 'S2';
    case S3 = 'S3';

}
