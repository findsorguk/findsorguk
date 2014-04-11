<?php
/**
 * This class is to display the treasure menu
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
class Pas_View_Helper_Treasuremenu
	extends Zend_View_Helper_Abstract {

	/** Display the treasure menu
	* @access public
	* @return string $html
	*/
	public function treasuremenu() {
	$treasure = new Content();
	$treasure = $treasure->getTreasureContent();
	$html = '';
	foreach($treasure as $t) {
	$html .= '<li ><a href="';
	$html .= $this->view->url(array(
	'module' => 'treasure',
	'controller' => 'advice',
	'action' => 'legal',
	'slug' => $t['slug']), 't', true);
	$html .= '" title="Read more">';
	$html .= $t['menuTitle'];
	$html .= '</a></li>';
	}
	return $html;
	}
}