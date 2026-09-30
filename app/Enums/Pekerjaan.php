<?php

namespace App\Enums;

use App\Traits\EnumOptions;
enum Pekerjaan: string
{
    use EnumOptions; // Cukup panggil Trait ini, fungsi values() otomatis tersedia
    case PNS = 'PNS';
    case TNI = 'TNI';
    case POLRI = 'POLRI';
    case SWASTA = 'Swasta';
    case WIRAUSAHA = 'Wirausaha';
    case PETANI = 'Petani';
    case NELAYAN = 'Nelayan';
    case BURUH = 'Buruh';
    case PELAJAR_MAHASISWA = 'Pelajar/Mahasiswa';
    case LAINNYA = 'Lainnya';

}
