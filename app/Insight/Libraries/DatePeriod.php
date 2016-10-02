<?php

namespace Insight\Libraries;

use Carbon\Carbon;

class DatePeriod
{

    public static function today()
    {
        return Carbon::today(getenv('APP_TIMEZONE'))->format('Y-m-d');
    }

    public static function yesterday()
    {
        return Carbon::yesterday(getenv('APP_TIMEZONE'))->format('Y-m-d');
    }
}
 