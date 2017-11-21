<?php

/**
 * This class is to help display date of creation etc in words.
 * @category   Pas
 * @package    View
 * @subpackage Helper
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @uses Zend_View_Helper_Abstract
 * @author Daniel Pett <dpett at britishmuseum.org>
 * Examples are: 23 seconds ago, 1 minute ago, 12 hours ago, 2 weeks ago,
 *  and if longer than a month the actual date is returned. This is based
 * upon the class found in cakephp's helpers which is distributed, used and
 *  modified under an MIT licence.
 */
class Pas_View_Helper_TimeAgoInWords extends Zend_View_Helper_Abstract
{

    // defines the number of seconds per "unit"
    private $secondsPerMinute = 60;
    private $secondsPerHour = 3600;
    private $secondsPerDay = 86400;
    private $secondsPerMonth = 2592000;
    private $secondsPerYear = 31536000; // 31622400 seconds on leap years though...
    private $timezone;
    private $previousTimezone;


    /**
     * Fetches the different between $past and $now in a spoken format.
     * NOTE: both past and now should be parseable by strtotime
     * @param string $past the past date to use
     * @param string $now the current time, defaults to now (can be an other time though)
     * @return string the difference in spoken format, e.g. 1 day ago
     */
    public function timeAgoInWords($past, $now = "now")
    {
        // finds the past in datetime
        $past = strtotime($past);
        // finds the current datetime
        $now = strtotime($now);
        // finds the time difference
        $timeDifference = $now - $past;
        $timeAgo = $this->getTimeDifference($past, $timeDifference);
        return $timeAgo;
    }
    /**
     * Fetches the date difference between the two given dates.
     * NOTE: both past and now should be parseable by strtotime
     *
     * @param string $past the "past" time to parse
     * @param string $now the "now" time to parse
     * @return array the difference in dates, using the two dates
     */
    public function dateDifference($past, $now = "now")
    {
        // initializes the placeholders for the different "times"
        $seconds = 0;
        $minutes = 0;
        $hours = 0;
        $days = 0;
        $months = 0;
        $years = 0;
        // finds the past in datetime
        $past = strtotime($past);
        // finds the current datetime
        $now = strtotime($now);
        // calculates the difference
        $timeDifference = $now - $past;
        // starts determining the time difference
        if ($timeDifference >= 0) {
            switch ($timeDifference) {
                // finds the number of years
                case ($timeDifference >= $this->secondsPerYear):
                    // uses floor to remove decimals
                    $years = floor($timeDifference / $this->secondsPerYear);
                    // saves the amount of seconds left
                    $timeDifference = $timeDifference - ($years * $this->secondsPerYear);
                    break;
                // finds the number of months
                case ($timeDifference >= $this->secondsPerMonth && $timeDifference <= ($this->secondsPerYear - 1)):
                    // uses floor to remove decimals
                    $months = floor($timeDifference / $this->secondsPerMonth);
                    // saves the amount of seconds left
                    $timeDifference = $timeDifference - ($months * $this->secondsPerMonth);
                    break;
                // finds the number of days
                case ($timeDifference >= $this->secondsPerDay && $timeDifference <= ($this->secondsPerYear - 1)):
                    // uses floor to remove decimals
                    $days = floor($timeDifference / $this->secondsPerDay);
                    // saves the amount of seconds left
                    $timeDifference = $timeDifference - ($days * $this->secondsPerDay);
                    break;
                // finds the number of hours
                case ($timeDifference >= $this->secondsPerHour && $timeDifference <= ($this->secondsPerDay - 1)):
                    // uses floor to remove decimals
                    $hours = floor($timeDifference / $this->secondsPerHour);
                    // saves the amount of seconds left
                    $timeDifference = $timeDifference - ($hours * $this->secondsPerHour);
                    break;
                // finds the number of minutes
                case ($timeDifference >= $this->secondsPerMinute && $timeDifference <= ($this->secondsPerHour - 1)):
                    // uses floor to remove decimals
                    $minutes = floor($timeDifference / $this->secondsPerMinute);
                    // saves the amount of seconds left
                    $timeDifference = $timeDifference - ($minutes * $this->secondsPerMinute);
                    break;
                // finds the number of seconds
                case ($timeDifference <= ($this->secondsPerMinute - 1)):
                    // seconds is just what there is in the timeDifference variable
                    $seconds = $timeDifference;
                    break;
            }
        }
        $this->restoreTimezone();
        $difference = [
            "years" => $years,
            "months" => $months,
            "days" => $days,
            "hours" => $hours,
            "minutes" => $minutes,
            "seconds" => $seconds,
        ];
        return $difference;
    }



    /**
     * Applies rules to find the time difference as a string
     * @param int|false $past
     * @param $timeDifference
     * @return string
     */
    private function getTimeDifference($past, $timeDifference)
    {
        // rule 0
        // $past is null or empty or ''
        if ($this->isPastEmpty($past)) {
            return 'Never';
        }
        // rule 1
        // less than 29secs
        if ($this->isLessThan29Seconds($timeDifference)) {
            return 'Less than a minute ago';
        }
        // rule 2
        // more than 29secs and less than 1min29secss
        if ($this->isLessThan1Min29Seconds($timeDifference)) {
            return 'One minute ago';
        }
        // rule 3
        // between 1min30secs and 44mins29secs
        if ($this->isLessThan44Min29Secs($timeDifference)) {
            $minutes = round($timeDifference / $this->secondsPerMinute);
            return 'Less than one hour ago';
        }
        // rule 4
        // between 44mins30secs and 1hour29mins59secs
        if ($this->isLessThan1Hour29Mins59Seconds($timeDifference)) {
            return 'About one hour ago';
        }
        // rule 5
        // between 1hour29mins59secs and 23hours59mins29secs
        if ($this->isLessThan23Hours59Mins29Seconds($timeDifference)) {
            $hours = round($timeDifference / $this->secondsPerHour);
            return $hours . ' hours ago';
        }
        // rule 6
        // between 23hours59mins30secs and 47hours59mins29secs
        if ($this->isLessThan47Hours59Mins29Seconds($timeDifference)) {
            return 'About one day ago';
        }
        // rule 7
        // between 47hours59mins30secs and 29days23hours59mins29secs
        if ($this->isLessThan29Days23Hours59Mins29Seconds($timeDifference)) {
            $days = round($timeDifference / $this->secondsPerDay);
            return $days . ' days ago';
        }
        // rule 8
        // between 29days23hours59mins30secs and 59days23hours59mins29secs
        if ($this->isLessThan59Days23Hours59Mins29Secs($timeDifference)) {
            return 'About one month ago';
        }
        // rule 9
        // between 59days23hours59mins30secs and 1year (minus 1sec)
        if ($this->isLessThan1Year($timeDifference)) {
            $months = $this->roundMonthsAboveOneMonth($timeDifference);
            return $months . ' months ago';
        }
        // rule 10
        // between 1year and 2years (minus 1sec)
        if ($this->isLessThan2Years($timeDifference)) {
            return 'About one year ago';
        }
        // rule 11
        // 2years or more
        $years = floor($timeDifference / $this->secondsPerYear);
        return $years . ' years ago';
    }
    /**
     * Checks if the given past is empty
     * @param string $past the "past" to check
     * @return bool true if empty, else false
     */
    private function isPastEmpty($past)
    {
        return $past === '' || is_null($past) || empty($past);
    }
    /**
     * Checks if the time difference is less than 29seconds
     * @param int $timeDifference the time difference in seconds
     * @return bool
     */
    private function isLessThan29Seconds($timeDifference)
    {
        return $timeDifference <= 29;
    }
    /**
     * Checks if the time difference is less than 1min 29seconds
     * @param int $timeDifference the time difference in seconds
     * @return bool
     */
    private function isLessThan1Min29Seconds($timeDifference)
    {
        return $timeDifference >= 30 && $timeDifference <= 89;
    }
    /**
     * Checks if the time difference is less than 44mins 29seconds
     * @param int $timeDifference the time difference in seconds
     * @return bool
     */
    private function isLessThan44Min29Secs($timeDifference)
    {
        return $timeDifference >= 90 &&
        $timeDifference <= (($this->secondsPerMinute * 44) + 29);
    }
    /**
     * Checks if the time difference is less than 1hour 29mins 59seconds
     * @param int $timeDifference the time difference in seconds
     * @return bool
     */
    private function isLessThan1Hour29Mins59Seconds($timeDifference)
    {
        return $timeDifference >= (($this->secondsPerMinute * 44) + 30)
        &&
        $timeDifference <= ($this->secondsPerHour + ($this->secondsPerMinute * 29) + 59);
    }
    /**
     * Checks if the time difference is less than 23hours 59mins 29seconds
     * @param int $timeDifference the time difference in seconds
     * @return bool
     */
    private function isLessThan23Hours59Mins29Seconds($timeDifference)
    {
        return $timeDifference >= (
            $this->secondsPerHour +
            ($this->secondsPerMinute * 30)
        )
        &&
        $timeDifference <= (
            ($this->secondsPerHour * 23) +
            ($this->secondsPerMinute * 59) +
            29
        );
    }
    /**
     * Checks if the time difference is less than 27hours 59mins 29seconds
     * @param int $timeDifference the time difference in seconds
     * @return bool
     */
    private function isLessThan47Hours59Mins29Seconds($timeDifference)
    {
        return $timeDifference >= (
            ($this->secondsPerHour * 23) +
            ($this->secondsPerMinute * 59) +
            30
        )
        &&
        $timeDifference <= (
            ($this->secondsPerHour * 47) +
            ($this->secondsPerMinute * 59) +
            29
        );
    }
    /**
     * Checks if the time difference is less than 29days 23hours 59mins 29seconds
     * @param int $timeDifference the time difference in seconds
     * @return bool
     */
    private function isLessThan29Days23Hours59Mins29Seconds($timeDifference)
    {
        return $timeDifference >= (
            ($this->secondsPerHour * 47) +
            ($this->secondsPerMinute * 59) +
            30
        )
        &&
        $timeDifference <= (
            ($this->secondsPerDay * 29) +
            ($this->secondsPerHour * 23) +
            ($this->secondsPerMinute * 59) +
            29
        );
    }
    /**
     * Checks if the time difference is less than 59days 23hours 59mins 29seconds
     * @param int $timeDifference the time difference in seconds
     * @return bool
     */
    private function isLessThan59Days23Hours59Mins29Secs($timeDifference)
    {
        return $timeDifference >= (
            ($this->secondsPerDay * 29) +
            ($this->secondsPerHour * 23) +
            ($this->secondsPerMinute * 59) +
            30
        )
        &&
        $timeDifference <= (
            ($this->secondsPerDay * 59) +
            ($this->secondsPerHour * 23) +
            ($this->secondsPerMinute * 59) +
            29
        );
    }
    /**
     * Checks if the time difference is less than 1 year
     * @param int $timeDifference the time difference in seconds
     * @return bool
     */
    private function isLessThan1Year($timeDifference)
    {
        return $timeDifference >= (
            ($this->secondsPerDay * 59) +
            ($this->secondsPerHour * 23) +
            ($this->secondsPerMinute * 59) +
            30
        )
        &&
        $timeDifference < $this->secondsPerYear;
    }
    /**
     * Checks if the time difference is less than 2 years
     * @param int $timeDifference the time difference in seconds
     * @return bool
     */
    private function isLessThan2Years($timeDifference)
    {
        return $timeDifference >= $this->secondsPerYear
        &&
        $timeDifference < ($this->secondsPerYear * 2);
    }
    /**
     * Rounds of the months, and checks if months is 1, then it's increased to 2, since this should be taken
     * from a different rule
     * @param int $timeDifference the time difference in seconds
     * @return int the number of months the difference is un
     */
    private function roundMonthsAboveOneMonth($timeDifference)
    {
        $months = round($timeDifference / $this->secondsPerMonth);
        // if months is 1, then set it to 2, because we are "past" 1 month
        if ($months == 1) {
            $months = 2;
        }
        return $months;
    }
}