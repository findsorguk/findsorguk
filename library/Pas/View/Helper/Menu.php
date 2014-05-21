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

    protected $_front, $_param;

    public function __construct()
    {
        $this->_front = Zend_Controller_Front::getInstance()->getRequest();
        $this->_param = $this->_front->getParam('slug');
    }

    /** Display the menu
    * @access public
    * @return string $html
    */
    public function menu($section, $controller, $action, $route)
    {
    $content = new Content();
    $menus = $content->buildMenu($controller);
    $html = '';
    foreach ($menus as $m) {
    $html .= '<li ';
    if ($m['slug'] == $this->_param) {
        $html .= 'class="active"';
        }

    $html .= '><a href="';
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
