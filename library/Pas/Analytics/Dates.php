<?php
/** 
 * A class to return dates for using with the Google Analytics interface
 * As ever, there's probably some better ways to achieve this!
 * @author Daniel Pett
 * @uses Zend_Date
 * @since 24th January 2013
 * @copyright Daniel Pett/British Museum dpett @ britishmuseum.org
 */
class Pas_Analytics_Dates {
	
	/** 
	 * Set the date format we need for querying Google Analytics
	 * @var string The default date format to return 
	 */
	protected $_dateFormat = 'yyyy-MM-dd';
	
	/**
	 * Get the start and end date for the current month
	 * @access public
	 * @return array Dates for start and end 
	 */
	public function thisMonth()
	{
		//Instantiate the date class
		$date = new Zend_Date();
		
		//Create the start date for the current month
		$start = $date->setYear($date->get(Zend_Date::YEAR))
		->setMonth($date->get(Zend_Date::MONTH))
		->setDay(1)
		->toString($this->_dateFormat);
		
		//Create the end date for the current month
		$end = $date->setYear($date->get(Zend_Date::YEAR))
		->setMonth($date->get(Zend_Date::MONTH))
		->setDay($date->get(Zend_Date::MONTH_DAYS))
		->toString($this->_dateFormat);
		//Return the array of dates
		return array('start' => $start, 'end' => $end);
	}
	
	/**
	 * Get the start and end date for the previous month
	 * @access public
	 * @return array Dates for start and end 
	 */
	public function lastMonth()
	{
		//Instantiate the date class
		$date = new Zend_Date();
		
		//Take one year off the current year
		$year = $date->get(Zend_Date::YEAR) - 1;
		$month = $date->get(Zend_Date::MONTH) -1;
		//Set the date for the first day of the previous month
		$date->setYear($year);
		//take one month off the current month
		$date->setMonth($month)
		->setDayOfYear(01);
		$start = $date->toString($this->_dateFormat);
		//Create the end date for the last day of the previous month
		$end = $date->setYear($date->get(Zend_Date::YEAR))
		//No need to take a month off as we're working with the previous 
		// date set up above
		->setMonth($date->get(Zend_Date::MONTH) )
		->setDay($date->get(Zend_Date::MONTH_DAYS))
		->toString($this->_dateFormat);
		
		//Return the array of dates
		return array('start' => $start, 'end' => $end);
	}
	
	/**
	 * Get the start and end date for the current year
	 * @access public
	 * @return array Dates for start and end 
	 */
	public function thisYear()
	{
		//Instantiate the date class
		$date = new Zend_Date();
		
		//Set the date for the first day of the current year
		$year = $date->get(Zend_Date::YEAR);
		
		//Set the date for the year to 1st January (this never changes :) )
		$start = $date->setYear($year)
		->setMonth(01)
		->setDay(01)
		->toString($this->_dateFormat);
		
		//Set the date for the end of year to 31st December (this never changes :) )
		$end = $date->setYear($year)
		->setMonth(12)
		->setDay(31)
		->toString($this->_dateFormat);
		
		//Return the array of dates
		return array('start' => $start, 'end' => $end);
	}
	
	/**
	 * Get the start and end date for the previous year
	 * @access public
	 * @return array Dates for start and end 
	 */
	public function lastYear()
	{
		//Instantiate the date class
		$date = new Zend_Date();
		
		//Take one year off the current year
		$year = $date->get(Zend_Date::YEAR) - 1;
		
		//Set the start date for the first day of previous year
		$start = $date->setYear($year)
		->setMonth(01)
		->setDay(01)
		->toString($this->_dateFormat);
		
		//Set the end date for the last day of previous year
		$end = $date->setYear($year)
		->setMonth(12)
		->setDay(31)
		->toString($this->_dateFormat);
		
		//Return the array of dates
		return array('start' => $start, 'end' => $end);
	}
	
	/**
	 * Get yesterday's date
	 * @access public
	 * @return string Date
	 */
	public function yesterday()
	{
		//Instantiate the date class
		$date = new Zend_Date();
		//Return date
		$yesterday = $date->now()->subDay(1)->toString($this->_dateFormat);
		return array('start' => $yesterday, 'end' => $yesterday); 
	}	
	
	/**
	 * Get today's date
	 * @access public
	 * @return string Date 
	 */
	public function today() 
	{
		//Instantiate the date class
		$date = new Zend_Date();
		//Return date
		$today = $date->now()->toString($this->_dateFormat);
		return array('start' => $today, 'end' => $today); 
	}
	
	/**
	 * Get dates for start and end of this week
	 * @access public
	 * @return array Dates 
	 */
	public function thisWeek()
	{
		//Instantiate the date class
		$date = new Zend_Date();
		
		//Set the date for current week's first day
		$date->setYear($date->get(Zend_Date::YEAR))
	     	->setWeek($date->get(Zend_Date::WEEK))
	     	->setWeekDay(1); 
	    
	    //create an array for weekdates
		$weekDates = array();
		
		//Loop through dates
		for ($day = 1; $day <= 7; $day++) {
		    if ($day == 1) {
		    	// we're already at day 1
		    } else {
	    	// get the next day in the week
	    	$date->addDay(1);
	    	}
	    $weekDates[] = $date->toString($this->_dateFormat);
		}
		
		//Return array of dates		
		return array('start' => $weekDates[0], 'end' => $weekDates[6]);
	}
	
	public function lastWeek()
	{
		//Instantiate the date class
		$date = new Zend_Date();
		//Create date for last week's first day 
		$date->setYear($date->get(Zend_Date::YEAR))
	     	->setWeek($date->get(Zend_Date::WEEK) - 1)
	     	->setWeekDay(1); 
		 //create an array for weekdates
		$weekDates = array();
		
		//Loop through dates
		for ($day = 1; $day <= 7; $day++) {
		    if ($day == 1) {
		    	// we're already at day 1
		    } else {
	    	// get the next day in the week
	    	$date->addDay(1);
	    	}
	    $weekDates[] = $date->toString($this->_dateFormat);
		}
		
		//Return array of dates		
		return array('start' => $weekDates[0], 'end' => $weekDates[6]);
	}
}