<?php

namespace App\Enums;

use App\Traits\EnumOptions;

enum Rt: string
{
    use EnumOptions; // Cukup panggil Trait ini, fungsi values() otomatis tersedia
    case RT_001 = '001';
    case RT_002 = '002';
    case RT_003 = '003';
    case RT_004 = '004';
    case RT_005 = '005';
    case RT_006 = '006';
    case RT_007 = '007';
    case RT_008 = '008';
    case RT_009 = '009';
    case RT_010 = '010';

}
