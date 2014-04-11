<?php
/**
 * A view helper for displaying the correct headtitle from parameters posted
 * 
 * @category   Pas
 * @package    Pas_View_Helper
 * @subpackage Abstract
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @see Zend_View_Helper_Abstract
 */

class Pas_View_Helper_Commentheadtitle extends Zend_View_Helper_Abstract {

	/** Create headtitle for comments section from post parameters
	 * @access public
	 * @param array $params
	 * @return $string
	 */
	public function commentheadtitle($params) {
	if(isset($params['approval'])){
	switch($params['approval']) {
	case 'approved':
	$title = $this->view->headTitle('All approved comments');
	break;
	case 'spam':
	$title = $this->view->headTitle('All spam comments');
	break;
	case 'moderation':
	$title = $this->view->headTitle('All comments awaiting moderation');
	break;
		}
	} else {
	return $title = 'No title set';
	}
	return $title;
	}
}
