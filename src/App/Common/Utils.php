<?php
/**
 * Copyright Serhii Borodai (c) 2017-2018.
 */

/**
 * Created by Serhii Borodai <clarifying@gmail.com>
 */

namespace App\Common;


use DateInterval;
use DatePeriod;
use DateTime;

class Utils
{
    static function generateDateRangeAsString(DateTime $start, DateTime $end, $separator = ',')
    {
        $period = new DatePeriod(
            $start,
            new DateInterval('P1D'),
            $end
        );
        $tmp = [];
        /** @var DateTime $date */
        foreach ($period as $date) {
            $tmp[] = $date->format(Constants::DATE_FORMAT_PHP);
        }
        return implode($separator, $tmp);
    }

}