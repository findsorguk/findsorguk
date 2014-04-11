<?php
/**
 * This class is to display a menu
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
class Pas_View_Helper_Menu
	extends Zend_View_Helper_Abstract {

	/** Display the menu
	* @access public
	* @return string $html
	*/
	public function menu($section, $controller, $action, $route) {
	$content = new Content();
	$menus = $content->buildMenu($controller);
	$html = '';
	foreach($menus as $m) {
	$html .= '<li class="menu collapsible nav nav-stacked nav-pills"><a href="';
	$html .= $this->view->url(array(
		'module' => $section,
		'controller' => $controller,
		'action' => $action,'slug' => $m['slug']),
		$route, true);
	$html .= '" title="Read more">';
	$html .= $m['menuTitle'];
	$html .= '</a></li>';
	}
	return $html;
	}
}