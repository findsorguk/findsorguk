<?php
/** 
 * A class to return dates for using with the Google Analytics interface
 * As ever, there's probably some better ways to achieve this!
 * 
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @uses Zend_Date
 * @since 24th January 2013
 * @example /library/Pas/Analytics/Timespan.php 
 */
class Pas_Analytics_Dates {
    /** Set the date format we need for querying Google Analytics
     * @access protected
     * @var string The default date format to return 
     */
    protected $_dateFormat = 'yyyy-MM-dd';
    
    /** The date object
     * @access protected
     * @var \Zend_Date
     */
    protected $_date;
    
    /** Get the date object
     * @access public
     * @return \Zend_Date
     */
    public function getDate() {
        $this->_date = new Zend_Date();
        return $this->_date;
    }
    
    /**Get the start and end date for the current month
     * @access public
     * @return array Dates for start and end 
     */
    public function thisMonth() {
        //Create the start date for the current month
        $start = $this->getDate()->setYear($this->getDate()->get(Zend_Date::YEAR))
                ->setMonth($this->getDate()->get(Zend_Date::MONTH))
                ->setDay(1)
                ->toString($this->_dateFormat);
        //Create the end date for the current month
        $end = $this->getDate()->setYear($this->getDate()->get(Zend_Date::YEAR))
                ->setMonth($this->getDate()->get(Zend_Date::MONTH))
                ->setDay($this->getDate()->get(Zend_Date::MONTH_DAYS))
                ->toString($this->_dateFormat);
        //Return the array of dates
        return array('start' => $start, 'end' => $end);
    }

    /**  Get the start and end date for the previous month
     * @access public
     * @return array Dates for start and end 
     */
    public function lastMonth() {
        //Take one year off the current year
        $year = $this->getDate()->get(Zend_Date::YEAR) - 1;
        $month = $this->getDate()->get(Zend_Date::MONTH) -1;
        //Set the date for the first day of the previous month
        $this->getDate()->setYear($year);
        //take one month off the current month
        $this->getDate()->setMonth($month)->setDayOfYear(01);
        $start = $this->getDate()->toString($this->_dateFormat);
        //Create the end date for the last day of the previous month
        $end = $this->getDate()->setYear($this->getDate()->get(Zend_Date::YEAR))
        //No need to take a month off as we're working with the previous 
        // date set up above
                ->setMonth($this->getDate()->get(Zend_Date::MONTH) )
                ->setDay($this->getDate()->get(Zend_Date::MONTH_DAYS))
                ->toString($this->_dateFormat);
        //Return the array of dates
        return array('start' => $start, 'end' => $end);
    }

    /** Get the start and end date for the current year
     * @access public
     * @return array Dates for start and end 
     */
    public function thisYear() {
       //Set the date for the first day of the current year
        $year = $this->getDate()->get(Zend_Date::YEAR);
        //Set the date for the year to 1st January (this never changes :) )
        $start = $this->getDate()->setYear($year)
                ->setMonth(01)
                ->setDay(01)
                ->toString($this->_dateFormat);
        //Set the date for the end of year to 31st December (this never changes :) )
        $end = $this->getDate()
                ->setYear($year)
                ->setMonth(12)
                ->setDay(31)
                ->toString($this->_dateFormat);
        //Return the array of dates
        return array('start' => $start, 'end' => $end);
    }

    /** Get the start and end date for the previous year
     * @access public
     * @return array Dates for start and end 
     */
    public function lastYear() {
        //Take one year off the current year
        $year = $this->getDate()->get(Zend_Date::YEAR) - 1;
        //Set the start date for the first day of previous year
        $start = $this->getDate()
                ->setYear($year)
                ->setMonth(01)
                ->setDay(01)
                ->toString($this->_dateFormat);
        //Set the end date for the last day of previous year
        $end = $this->getDate()
                ->setYear($year)
                ->setMonth(12)
                ->setDay(31)
                ->toString($this->_dateFormat);
        //Return the array of dates
        return array('start' => $start, 'end' => $end);
    }

    /** Get yesterday's date
     * @access public
     * @return string Date
     */
    public function yesterday() {
        //Instantiate the date class
        $this->getDate() = new Zend_Date();
        //Return date
        $yesterday = $this->getDate()
                ->now()
                ->subDay(1)
                ->toString($this->_dateFormat);
        return array('start' => $yesterday, 'end' => $yesterday); 
    }	

    /** Get today's date
     * @access public
     * @return string Date 
     */
    public function today() {
        $today = $this->getDate()->now()->toString($this->_dateFormat);
        return array('start' => $today, 'end' => $today); 
    }

    /** Get dates for start and end of this week
     * @access public
     * @return array Dates 
     */
    public function thisWeek() {
        //Set the date for current week's first day
        $this->getDate()
                ->setYear($this->getDate()->get(Zend_Date::YEAR))
                ->setWeek($this->getDate()->get(Zend_Date::WEEK))
                ->setWeekDay(1); 


        //create an array for weekdates
        $weekDates = array();
        //Loop through dates
        for ($day = 1; $day <= 7; $day++) {
            if ($day == 1) {
                // we're already at day 1
            } else {
            // get the next day in the week
            $this->getDate()->addDay(1);
            }
        $weekDates[] = $this->getDate()->toString($this->_dateFormat);
        }
        //Return array of dates		
        return array('start' => $weekDates[0], 'end' => $weekDates[6]);
    }

    /** Get the dates for previous week
     * @access public
     * @return array
     */
    public function lastWeek() {
        //Create date for last week's first day 
        $this->getDate()
                ->setYear($this->getDate()->get(Zend_Date::YEAR))
                ->setWeek($this->getDate()->get(Zend_Date::WEEK) - 1)
                ->setWeekDay(1); 
         //create an array for weekdates
        $weekDates = array();
        //Loop through dates
        for ($day = 1; $day <= 7; $day++) {
            if ($day == 1) {
                // we're already at day 1
            } else {
            // get the next day in the week
            $this->getDate()->addDay(1);
            }
        $weekDates[] = $this->getDate()->toString($this->_dateFormat);
        }
        //Return array of dates		
        return array('start' => $weekDates[0], 'end' => $weekDates[6]);
    }
}