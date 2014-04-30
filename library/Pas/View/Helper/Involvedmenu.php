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
class Pas_View_Helper_InvolvedMenu 
	extends Zend_View_Helper_Abstract {

	protected $_front, $_param;
	
	public function __construct()
	{
		$this->_front = Zend_Controller_Front::getInstance()->getRequest();
		$this->_param = $this->_front->getParam('slug');
	}	
		
	/** Build the get involved menu 
	 * @todo convert to Zend Navigation
	 * @return string $html The menu html 
	 */
	public function involvedMenu()
	{
		return $this;
	}
	
	public function menu()
	{
		$treasure = new Content();
		$treasure = $treasure->buildMenu('getinvolved', $front = 0, $publish = 3);
		$html = '';
		foreach($treasure as $t) {
		$html .= '<li ';
		if($t['slug'] == $this->_param) {
		$html .= 'class="active"';
		}
		$html .= '>';
		$html .= '<a href="';
		$html .= $this->view->url(array('module' => 'getinvolved', 'controller' => 'guides', 'action' => 'index',
		'slug' => $t['slug']), 'guides', true);
		$html .= '" title="Read more">';
		$html .= $t['menuTitle'];
		$html .= '</a></li>';
		}
		return $html;
	}
	
	public function __toString()
	{
		return $this->menu();
	}
}