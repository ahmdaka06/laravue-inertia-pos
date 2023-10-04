<?php

if (! function_exists('formatPrice')) {
    /**
     * formatPrice
     *
     * @param  mixed $string
     * @return string
     */
    function formatPrice(mixed $string): string {
        return 'Rp. ' . number_format($string, '0', '', '.');
    }
}
