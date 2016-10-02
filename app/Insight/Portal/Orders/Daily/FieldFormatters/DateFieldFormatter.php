<?php

namespace Insight\Portal\Orders\Daily\FieldFormatters;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class DateFieldFormatter implements FieldFormatter
{

    public function format($value, $format)
    {

        if (! $value) {
            return;
        }

        $timeStamp = Carbon::createFromFormat('Y-m-d H:i:s', $value);
        switch ($format['date_format']) {
            case 'Y-m-d H:i:s': // 1975-12-21 14:15:22
                $dateTime = $timeStamp->toDayDateTimeString();
                break;
            case 'm/d/y H:i:s': // 12/21/75 14:15:22
                $dateTime = $timeStamp->format('m/d/y H:i:s');
                break;
            case 'd-M-y': // 21-Dec-75
                $dateTime = $timeStamp->format('d-M-y');
                break;
            case 'd-m-Y': // 21-12-1975
                $dateTime = $timeStamp->format('d-m-Y');
                break;
            case 'd-M-y H:i:s': // 21-Dec-75 14:15:22
                $dateTime = $timeStamp->format('d-M-y H:i:s');
                break;
            case 'd-m-y h:i:s': // 21-12-75 02:15:22
                $dateTime = $timeStamp->format('d-m-y h:i:s');
                break;
            case 'D, M-d Y \a\t h:i:s a': // Sun, Dec-21 1975 at 02:15:22 pm
                $dateTime = $timeStamp->format('D, M-d Y \a\t h:i:s a');
                break;
            case 'jS \o\f F, Y g:i:s a': // 21st of December, 1975 2:15:22 pm
                $dateTime = $timeStamp->format('jS \o\f F, Y g:i:s a');
                break;
            default:
                $dateTime = $value;
                break;
        }

        return $dateTime;

    }
}
