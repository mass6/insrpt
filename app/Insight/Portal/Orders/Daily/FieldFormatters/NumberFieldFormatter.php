<?php

namespace Insight\Portal\Orders\Daily\FieldFormatters;

class NumberFieldFormatter implements FieldFormatter
{
    public function format($value, $format)
    {
        $decimals = array_get($format, 'decimal_places', 0);
        $thousands_separator = key_exists('thousands_separator', $format) && $format['thousands_separator'] === false ? false: true;

        if ($thousands_separator) {
            $value = number_format($value,$decimals,'.',',');
        }
        else
        {
            $value = number_format($value,$decimals,'.','');
        }
        if (!$decimals) {
            $value = (int)$value;
        }

        return $value;
    }
}
