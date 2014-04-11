<?php
/**
 * This class is to display conservation menu
 * Load of rubbish, needs a rewrite
 * @category   Pas
 * @package    Pas_View_Helper
 * @subpackage Abstract
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @uses Zend_View_Helper_Abstract
 * @author Daniel Pett
 * @since September 13 2011
 * @todo change the class to use zend_navigation
*/
class Pas_View_Helper_Conservationmenu
	extends Zend_View_Helper_Abstract {


	/** Display the menu
	* @access public
	* @return string $html
	*/		
	public function conservationmenu() {
	$conservation = new Content();
	$cons = $conservation->getConservationNotes();
	$html = '';
	foreach($cons as $c) {
	$html .= '<li><a href="';
	$html .= $this->view->url(array('module' => 'conservation','controller' => 'advice','action' => 'note','slug' => $c['slug']),'c',true);
	$html .= '" title="Read this note">';
	$html .=$c['menuTitle'];
	$html .= '</a></li>';
	}
	return $html;
	}



}