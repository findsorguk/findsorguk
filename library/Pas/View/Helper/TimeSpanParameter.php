<?php
/** 
 * A view helper for displaying the time span from parameter entered.
 * @author dpett
 * @version 
 */

/**
 * TimeSpanParameter helper
 *
 * @uses viewHelper Pas_View_Helper
 */
class Pas_View_Helper_TimeSpanParameter extends Zend_View_Helper_Abstract {
	
	protected $_timespan;
	/**
	 * 
	 */
	public function timeSpanParameter() {
		$frontController = Zend_Controller_Front::getInstance();
		$params = $frontController->getRequest()->getParams();
		$ts = $params['timespan'];
		if(array_key_exists('timespan', $params)){

			switch($ts){
				case 'thisweek':
					$time = 'this week';
					break;
				case 'thisyear':
					$time = 'this year';
					break;
				case 'lastyear':
					$time = 'last year';
					break;
				case 'thismonth':
					$time = 'this month';
					break;
				case 'lastmonth':
					$time = 'last month';
					break;
				case 'lastweek':
					$time = 'last week';
					break;
				default:
					$time = $ts;
					break;	
			}
			$this->_timespan = $time;
		} else {
			$this->_timespan = 'this week';
		}
		return $this;
	}
	
	public function __toString(){
		return $this->_timespan;
	}
}

