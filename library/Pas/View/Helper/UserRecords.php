<?php 
/**
 * A view helper for displaying a user's record counts
 * @category   Pas
 * @package    Pas_View_Helper
 * @subpackage Abstract
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @see Zend_View_Helper_Abstract
 */
class Pas_View_Helper_UserRecords extends Zend_View_Helper_Abstract {
	
	/** Display and render a user's record numbers
	 * 
	 * @param string $username
	 */
	public function UserRecords($username) {
	$users = new Users();
	$ids = $users->getUserID($username);
	$totals = $users->getCountFinds($ids['0']['id']);
	if($totals['0']['finds'] > 0) {
	$html =  '<div class="object">';
	$html .= $totals['0']['finds'];
	$html .= ' finds within ';
	$html .= $totals['0']['records'];
	$html .= ' records.</div>';
	return $html;
	}
	}

}