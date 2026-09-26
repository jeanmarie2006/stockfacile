<?php

namespace App\Support;

use Carbon\Carbon;

class Fmt
{
    public static function fcfa($n): string
    {
        return number_format((float) $n, 0, ',', "\u{202F}")."\u{00A0}FCFA";
    }

    public static function date($d, string $format = 'd M Y'): string
    {
        return $d ? Carbon::parse($d)->locale('fr')->translatedFormat($format) : '';
    }
}
