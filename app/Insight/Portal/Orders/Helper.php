<?php
namespace Insight\Portal\Orders;

class Helper
{
    public function omitComma($field)
    {
        if (strpos($field, ',') !== false) {
            $field = str_replace(',', "&#44;", $field);
        }
        return $field;
    }

    public function formatNumber($number, $decimals = 2, $dec_point = '.', $thousands_sep = ',')
    {
        return number_format($number, $decimals, $dec_point, $thousands_sep);
    }

    /**
     * calculate number of working days between two dates, excludes weekend and holidays
     * @param $from
     * @param $to
     * @param mixed $weekend the weekend days, can be a single number or array, in which, 1 for Monday, ..., 7 for Sunday
     * @param array $holidays array of holiday days, day should be in 'd-m-Y' format
     * @return int
     */
    public function numberOfWorkingDays($from, $to, $weekend = null, $holidays = array())
    {
        if (is_null($weekend)) {
            $weekend = [6, 7]; // Saturday and Sunday by default
        }
        if (!is_array($weekend)) {
            $weekend = (array)$weekend;
        }

        $from = new \DateTime($from);
        $from->setTime(0, 0);
        $to = new \DateTime($to);
        $to->modify('+1 day')->setTime(0, 0);
        $interval = new \DateInterval('P1D'); // one day interval
        $periods = new \DatePeriod($from, $interval, $to, \DatePeriod::EXCLUDE_START_DATE);

        $days = 0;
        foreach ($periods as $period) {
            if (in_array($period->format('N'), $weekend)) continue; // is weekend
            if (in_array($period->format('d-m-Y'), $holidays)) continue; // is holiday
            $days++;
        }

        return $days;
    }
}