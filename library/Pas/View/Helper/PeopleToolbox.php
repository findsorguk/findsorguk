<?php
/**
 * A view helper for rendering links for interacting with people
 * 
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category Pas
 * @package Pas_View_Helper
 * @version 1
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @uses Zend_Registry 
 * @uses Pas_User_Details
 */
class Pas_View_Helper_PeopleToolbox extends Zend_View_Helper_Abstract {

    /** The allowed array
     * @access protected
     * @var array
     */
    protected $_allowed = array(
        'fa', 'flos', 'admin',
        'treasure', 'hoard'
    );

    /** The id of the user
     * @access protected
     * @var integer
     */
    protected $_role;
    
    /** Get the user id to query
     * @access public   
     * @return integer
     */
    public function getRole() {
        $user = new Pas_User_Details();
        $this->_role = $user->getRole();
        return $this->_role;
    }
   
    /** The function to return
     * @access public
     * @param type $id
     */
    public function peopleToolbox() {
        return $this;
    }
    
    public function __toString() {
        $html = '';
        if (in_array($this->getRole(), $this->_allowed)) {
            $html .= '<div class="btn-group">';
            $html .= '<a class="btn btn-large btn-primary" href="';
            $html .=  $this->view->url(array(
                'module' => 'database',
                'controller'=>'people',
                'action'=>'add'),
                    'default', true);
            $html .= '">Add new person to database</a>';
            $html .= '</div>';
        }
        return $html;
    }
}