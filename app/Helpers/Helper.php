<?php

declare(strict_types=1);

if (! function_exists('formatPrice')) {
    /**
     * formatPrice
     *
     * @param mixed $str
     * @return string
     */
    function formatPrice(mixed $str): string
    {
        return 'Rp. ' . number_format($str, 0, '', '.');
    }
}
