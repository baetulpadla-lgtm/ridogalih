<?php

namespace App\Enums;

use App\Traits\EnumOptions;

enum Rw: string
{
    use EnumOptions; // Cukup panggil Trait ini, fungsi values() otomatis tersedia
    case RW_001 = '001';
    case RW_002 = '002';
    case RW_003 = '003';
    case RW_004 = '004';
    case RW_005 = '005';
    case RW_006 = '006';
    case RW_007 = '007';
    case RW_008 = '008';
    case RW_009 = '009';
    case RW_010 = '010';

}
