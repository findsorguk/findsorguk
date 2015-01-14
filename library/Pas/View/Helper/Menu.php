<?php
/**
 * This class is to display a menu
 * Load of rubbish, needs a rewrite
 *
 * An example of use:
 *
 * <code>
 * <?php
 * echo $this->menu()
 * ->setModule('news')
 * ->setController('reviews')
 * ->setSection('index')
 * ->setRoute('r');
 * ?>
 * </code>
 *
 * @category   Pas
 * @package    Pas_View_Helper
 * @subpackage Abstract
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @uses Zend_View_Helper_Abstract
 * @author Daniel Pett
 * @since September 13 2011
 * @todo change the class to use zend_navigation
 * @example /app/views/scripts/structure/newsSidebar.phtml News menu bar
*/
class Pas_View_Helper_Menu extends Zend_View_Helper_Abstract {

    /** The request object
     * @access protected
     * @var \Zend_Controller_Front
     */
    protected $_request;

    /** The parameter
     * @access protected
     * @var string
     */
    protected $_param;

    /** The section
     * @access protected
     * @var string
     */
    protected $_section;

    /** The controller
     * @access protected
     * @var string
     */
    protected $_controller;

    /** The module
     * @access protected
     * @var string
     */
    protected $_module;

    /** The route
     * @access protected
     * @var string
     */
    protected $_route = 'default';

    /** Get the request
     * @access public
     * @return \Zend_Controller_Front
     */
    public function getRequest() {
        $this->_request = Zend_Controller_Front::getInstance()->getRequest();
        return $this->_request;
    }

    /** Get the parameter called
     * @access public
     * @return string
     */
    public function getParam() {
        $this->_param = $this->getRequest()->getParam('slug');
        return $this->_param;
    }

     /** Get the parameter called
     * @access public
     * @return string
     */
    public function getSection() {
        return $this->_section;
    }

    /** Get the controller called
     * @access public
     * @return string
     */
    public function getController() {
        return $this->_controller;
    }
    /** Get the module called
     * @access public
     * @return string
     */
    public function getModule() {
        return $this->_module;
    }
    /** Get the route called
     * @access public
     * @return string
     */
    public function getRoute() {
        return $this->_route;
    }

    /** Set the section
     * @access public
     * @param string $section
     * @return \Pas_View_Helper_Menu
     */
    public function setSection($section) {
        $this->_section = $section;
        return $this;
    }

    /** Set the controller
     * @access public
     * @param string $controller
     * @return \Pas_View_Helper_Menu
     */
    public function setController($controller) {
        $this->_controller = $controller;
        return $this;
    }

    /** Set the module
     * @access public
     * @param string $module
     * @return \Pas_View_Helper_Menu
     */
    public function setModule($module) {
        $this->_module = $module;
        return $this;
    }

    /** Set the route
     * @access public
     * @param string $route
     * @return \Pas_View_Helper_Menu
     */
    public function setRoute($route) {
        $this->_route = $route;
        return $this;
    }

    /** The function to return
     * @access public
     * @return \Pas_View_Helper_Menu
     */
    public function menu() {
        return $this;
    }

    /** To string
     * @access public
     * @return string
     */
    public function __toString() {
        return $this->buildMenu(
                $this->getSection(),
                $this->getController(),
                $this->getSection(),
                $this->getRoute()
                );
    }

    /** Build the html to return to string
     * @access public
     * @param string $section
     * @param string $controller
     * @param string $action
     * @param string $route
     * @return string
     */
    public function buildMenu($section, $controller, $action, $route) {
        $content = new Content();
        $menus = $content->buildMenu($controller);
        $html = '';

        foreach ($menus as $m) {
            $html .= '<li ';
            if ($m['slug'] == $this->getParam()) {
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
