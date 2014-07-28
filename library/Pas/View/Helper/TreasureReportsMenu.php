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
 * @uses Content
*/
class Pas_View_Helper_TreasureReportsMenu extends Zend_View_Helper_Abstract {

    /** The request
     * @access protected
     * @var \Zend_Controller_Front
     */
    protected $_front;
    
    /** The slug parameter
     * @access protected
     * @var string
     */
    protected $_param;

    /** Construct the variables
     * @access public
     * @return void
     */
    public function __construct() {
        $this->_front = Zend_Controller_Front::getInstance()->getRequest();
        $this->_param = $this->_front->getParam('slug');
    }

    /** Display the treasure reports menu
    * @access public
    * @return string $html
    */
    public function treasureReportsMenu() {
        return $this;
    }

    /** Generate the menu for display
     * @access public
     * @return string
     */
    public function menu() {
        $content = new Content();
        $treasure = $content->buildTMenu();
        $html = '';
        foreach ($treasure as $t) {
            $html .= '<li class="';
            if ($t['slug'] == $this->_param) {
                $html .= 'active';
            }
            $html .= ' ">';
            $html .= '<a href="';
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

    /** The to string function
     * @access public
     * @return string
     */
    public function __toString() {
        return $this->menu();
    }
}