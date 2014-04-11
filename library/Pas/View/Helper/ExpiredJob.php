<?php 
/**
 * A view helper for determining the difference between dates for expired jobs
 * @category   Pas
 * @package    Pas_View_Helper
 * @subpackage Abstract
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @see Zend_View_Helper_Abstract
 */

class Pas_View_Helper_ExpiredJob extends Zend_View_Helper_Abstract {

	/** Determine whether a date is earlier or later
	 * 
	 * @param date $date
	 */
	public function expiredJob($date) {
	$today = new Zend_Date(NULL,'YYYY-MM-dd');
	$difference = $today->isLater(new Zend_Date($date,'YYYY-MM-dd'));
	return $difference;
	}

}