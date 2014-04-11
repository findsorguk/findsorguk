<?php
/**
 * This class is to display the get involved menu
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
class Pas_View_Helper_Involvedmenu 
	extends Zend_View_Helper_Abstract {

	/** Build the get involved menu 
	 * @todo convert to Zend Navigation
	 * @return string $html The menu html 
	 */
	public function involvedmenu(){
	$treasure = new Content();
	$treasure = $treasure->buildMenu('getinvolved', $front = 0, $publish = 3);
	$html = '';
	foreach($treasure as $t) {
	$html .= '<li><a href="';
	$html .= $this->view->url(array('module' => 'getinvolved', 'controller' => 'guides', 'action' => 'index',
	'slug' => $t['slug']), 'guides', true);
	$html .= '" title="Read more">';
	$html .= $t['menuTitle'];
	$html .= '</a></li>';
	}
	return $html;
	}
}