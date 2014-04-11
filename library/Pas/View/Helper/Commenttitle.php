<?php
/**
 * A view helper for displaying the correct comments title from parameters posted
 * 
 * @category   Pas
 * @package    Pas_View_Helper
 * @subpackage Abstract
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @see Zend_View_Helper_Abstract
 */

class Pas_View_Helper_Commenttitle extends Zend_View_Helper_Abstract {

	/** Build the h2 title for the comments page
	 * 
	 * @param array $params
	 */
	public function commenttitle($params) {
	if(isset($params['approval'])){
	switch($params['approval']) {
	case 'approved':
	$string =  '<h2>All approved comments</h2>';
	break;
	case 'spam':
	$string = '<h2>All spam comments</h2>';
	break;
	case 'moderation':
	$string = '<h2>All comments awaiting approval</h2>';
	break;
	}
	return $string;
	} else {
	$string = '<h2>All comments</h2>';
	return $string;
	}
	}
}
