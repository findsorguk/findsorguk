<?php
/**
 *
 * @author dpett
 * @version 
 */

/**
 * SecondsToMinutes helper
 *
 * @uses viewHelper Pas_View_Helper
 */
class Pas_View_Helper_SecondsToMinutes {
	
	protected $_seconds;
	/**
	 * 
	 */
	public function secondsToMinutes() {
		return $this;
	}
	
	public function setSeconds( $seconds )
	{
			$this->_seconds = $seconds;
		return $this;
	}
	
	public function convert(){
		if($this->_seconds > 0){
			$time = new Zend_Date($this->_seconds, Zend_Date::SECOND);
			return $time->toString('mm.ss'); 
			} else {
			return 'cannot be computed';
			}
	}
	public function __toString(){
		return $this->convert();
	}
}

