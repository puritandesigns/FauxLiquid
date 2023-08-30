<?php

namespace FauxLiquid\Support;

class Filters
{
    public static function dollarsToCents($money): int
    {
        if (self::hasDecimal($money)) {
            return $money * 100;
        }

        return $money;
    }

    public static function centsToDollars($money)
    {
        if (! self::hasDecimal($money)) {
            return number_format($money / 100, 2);
        }

        return $money;
    }

    public static function hasDecimal($money)
    {
        return str_contains($money, '.') || (floor($money) != $money);
    }
}
