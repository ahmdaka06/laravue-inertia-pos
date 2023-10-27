<?php

use Illuminate\Support\Facades\DB;

if (! function_exists('formatPrice')) {
    function formatPrice(mixed $string): string {
        return 'Rp. ' . number_format($string, '0', '', '.');
    }
}
if (!function_exists('generateInvoiceNumber')):
    function generateInvoiceNumber(string $code, string $table, string $column): String{
        $lastNumber = DB::table($table)->select(DB::raw('MAX(' .$column. ') AS max'))->first()->max;
        if ($lastNumber) {
            $explodeLastNumber = explode($code, $lastNumber);
            return $code . sprintf("%04s", abs((int) $explodeLastNumber[1] + 1));
        } else {
            return $code . sprintf("%04s", 1);
        }
    }
endif;
