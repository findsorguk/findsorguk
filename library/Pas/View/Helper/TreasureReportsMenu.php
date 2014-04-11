<?php
/**
 * This class is to display the treasure reports menu
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
class Pas_View_Helper_TreasureReportsMenu
	extends Zend_View_Helper_Abstract {

	/** Display the treasure reports menu
	* @access public
	* @return string $html
	*/
	public function treasurereportsmenu(){
	$treasure = new Content();
	$treasure = $treasure->buildTMenu();
	$html = '';
	foreach($treasure as $t) {
	$html .= '<li class="menu collapsible nav nav-stacked nav-pills"><a href="';
	$html .= $this->view->url(array(
		'module' => 'treasure',
		'controller' => 'reports',
		'action' => 'index',
		'slug' => $t['slug']),
		'treps', true);
	$html .= '" title="Read more">';
	$html .= $t['menuTitle'];
	$html .= '</a></li>';
	}
	return $html;
	}
}