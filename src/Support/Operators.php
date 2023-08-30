<?php

namespace FauxLiquid\Support;

class Operators
{
    public static function contains($haystack, $needle): bool
    {
        if (is_string($haystack)) {
            return str_contains($haystack, $needle);
        }

        return in_array($needle, $haystack);
    }
}
