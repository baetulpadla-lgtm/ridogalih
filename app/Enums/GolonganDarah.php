<?php

namespace App\Enums;


use App\Traits\EnumOptions;
enum GolonganDarah: string {
    use EnumOptions; // Cukup panggil Trait ini, fungsi values() otomatis tersedia
    case A = 'A';
    case B = 'B';
    case AB = 'AB';
    case O = 'O';
    case TIDAK_TAHU = 'Tidak Tahu';

}
