<?php

namespace App\Helpers;

use Carbon\Carbon;

class ExportHelper
{
    /**
     * Format file name.
     *
     * @param   string  $name
     * @param   string  $format
     *
     * @return  string
     */
    public static function formatFileName(string $name, string $format)
    {
        $now = Carbon::now();
        $date = $now->format('d_m_Y');
        $format = strtolower($format);

        return "{$name}_{$date}.{$format}";
    }
}
