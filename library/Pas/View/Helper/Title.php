<?php 
/**
 * A view helper for displaying page title without the <title></title> tags
 * @category   Pas
 * @package    Pas_View_Helper
 * @subpackage Abstract
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @see Zend_View_Helper_Abstract
 * @uses Zend_View_Helper_HeadTitle
 */
class Pas_View_Helper_Title extends Zend_View_Helper_Abstract {
	/** Strip tags from the headtitle and return clean
	 * @return string 
	 */
	public function title() {
	$headTitle = $this->view->headTitle();
	return strip_tags($headTitle->toString());
    }
}