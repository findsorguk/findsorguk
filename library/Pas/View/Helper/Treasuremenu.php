<?php
/**
 * This class is to display the treasure menu
 * Load of rubbish, needs a rewrite
 * @category   Pas
 * @package    View
 * @subpackage Helper
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @uses Zend_View_Helper_Abstract
 * @author Daniel Pett
 * @since September 13 2011
 * @todo change the class to use zend_navigation
*/
class Pas_View_Helper_Treasuremenu
    extends Zend_View_Helper_Abstract {

    protected $_front, $_param;

    public function __construct()
    {
        $this->_front = Zend_Controller_Front::getInstance()->getRequest();
        $this->_param = $this->_front->getParam('slug');
    }

    /** Display the treasure menu
    * @access public
    * @return string $html
    */
    public function treasureMenu()
    {
        return $this;
    }

    public function menu()
    {
        $treasure = new Content();
        $treasure = $treasure->getTreasureContent();
        $html = '';
        foreach ($treasure as $t) {
        $html .= '<li ';
        if ($t['slug'] == $this->_param) {
        $html .= 'class="active"';
        }
        $html .= '>';
        $html .= '<a href="';
        $html .= $this->view->url(array(
        'module' => 'treasure',
        'controller' => 'advice',
        'action' => 'legal',
        'slug' => $t['slug']), 'treasure', true);
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
