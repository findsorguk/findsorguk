<?php 
/**
 * A trivial view helper to work out who has visited more times than you
 * @category   Pas
 * @package    Pas_View_Helper
 * @subpackage Abstract
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @see Zend_View_Helper_Abstract
 */

class Pas_View_Helper_MoreVisitsThanMe 
	extends Zend_View_Helper_Abstract {
	
	/** Find out how many more people than you have visited the site
	 * 
	 * @param integer $visits
	 */
	public function morevisitsthanme($visits) {
	$users = new Users();
	$visits = $users->getMoreTotals($visits);
	foreach($visits as $v) {
	$t = $v['morethan'];
	}
	return $t;	
	}
}