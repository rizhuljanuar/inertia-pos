<?php

if (! function_exists('formatPrice')) {
    /**
     * Undocumented function
     *
     * @param [int] $str
     * @return void
     */
    function formatPrice($str) {
        return 'Rp. ' . number_format($str, '0', '', '.');
    }
}