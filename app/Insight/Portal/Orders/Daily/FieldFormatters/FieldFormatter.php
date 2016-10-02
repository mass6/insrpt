<?php

namespace Insight\Portal\Orders\Daily\FieldFormatters;

interface FieldFormatter
{
    public function format($value, $format);
}