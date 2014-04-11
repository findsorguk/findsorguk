<?php 
/**
 * A view helper for displaying workflow icons
 * @category   Pas
 * @package    Pas_View_Helper
 * @subpackage Abstract
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @see Zend_View_Helper_Abstract
 * @uses Zend_View_Helper_Baseurl
 */
class Pas_View_Helper_Workflow extends Zend_View_Helper_Abstract {

	/** Determine the correct image to display from workflow lookup
	 * 
	 * @param integer $secwfstage
	 */
	public function Workflow($secwfstage) {
	switch ($secwfstage) {
		case 1:
			$wf = '<img src="' . $this->view->baseUrl() . '/images/icons/quarantine.png" width="16" height="16" alt="Find in quarantine - not available to the public"/>';
			break;
		case 2:
			$wf = '<img src="'.$this->view->baseUrl() . '/images/icons/flag_red.gif" width="16" height="16" alt="Find on review - not available to the public"/>';
			break;
		case 4:
			$wf = '<img src="'.$this->view->baseUrl() . '/images/icons/flag_orange.gif" width="16" height="16" alt="Find waiting to be validated"/>';
			break; 
		case 3:
			$wf = '<img src="'.$this->view->baseUrl() . '/images/icons/flag_green.gif" width="16" height="16" alt="Find validated and published by finds advisers"/>';
			break; 
		default:
			return false;
			break;
	}		
	return $wf;
	}

}
