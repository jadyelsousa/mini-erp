<?php

namespace App\Services;

class FreightService
{
    /**
     * Calcula o valor do frete com base no subtotal.
     *
     * @param float $subtotal
     * @return float
     */
    public static function calculateFreight($subtotal)
    {
        if ($subtotal >= 52 && $subtotal <= 166.59) {
            return 15.00;
        } elseif ($subtotal > 200) {
            return 0.00;
        }
        return 20.00;
    }
}
