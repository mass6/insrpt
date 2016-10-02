<?php

namespace Insight\Portal\Orders\Daily\FieldFormatters;

use Illuminate\Support\Facades\App;

class FieldFormatterFactory
{
    public static function make($type)
    {
        switch ($type) {
            case 'number':
                return App::make('Insight\Portal\Orders\Daily\FieldFormatters\NumberFieldFormatter');
            case 'date':
                return App::make('Insight\Portal\Orders\Daily\FieldFormatters\DateFieldFormatter');
        }
    }

}
