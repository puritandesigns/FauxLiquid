<?php

namespace FauxLiquid\Support;

class Filters
{
    public static function dollarsToCents($money): int
    {
        if (is_float($money)) {
            return $money * 100;
        }

        return $money;
    }

    public static function centsToDollars($money)
    {
        if (is_int($money)) {
            return number_format($money / 100, 2);
        }

        return $money;
    }
}
