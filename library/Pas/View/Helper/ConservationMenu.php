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
 * @uses Zend_Controller_Front
 * @uses Content
 * @author Daniel Pett
 * @since September 13 2011
 * @todo change the class to use zend_navigation
*/
class Pas_View_Helper_ConservationMenu extends Zend_View_Helper_Abstract {

    protected $_front;
    
    protected $_param;
    
    public function getFront() {
        $this->_front = Zend_Controller_Front::getInstance()->getRequest();
        return $this->_front;
    }

    public function getParam() {
        $this->_param = $this->_front->getParam('slug');
        return $this->_param;
    }

   
    
    /** Display the menu
    * @access public
    * @return string $html
    */		
    public function conservationMenu(){
        return $this;
    }


    /** Build the html for the menu
     * @access public
     * @return string
     */
    public function menu() {
        $conservation = new Content();
        $cons = $conservation->getConservationNotes();
        $html = '';
        foreach($cons as $c) {
            $html .= '<li ';
            if($c['slug'] == $this->getParam()) {
                $html .= 'class="active"';
            }
            $html .= '>';
            $html .= '<a href="';
            $html .= $this->view->url(array(
                'module' => 'conservation',
                'controller' => 'advice',
                'action' => 'note',
                'slug' => $c['slug']
                    ),'c',true);
            $html .= '" title="Read this note">';
            $html .=$c['menuTitle'];
            $html .= '</a></li>';
        }
        return $html;		
    }

    /** The to string method
     * @access public
     * @return string
     */
    public function __toString() {
        return $this->menu();
    }
}