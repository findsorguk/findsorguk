<?php
/**
 * A view helper for determining which findspot partial to display to the user
 *
 * @category   Pas
 * @package    Pas_View_Helper
 * @subpackage Abstract
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @see Zend_View_Helper_Abstract
 * @todo this class can be cut substantially for the user object to come from just one call
 * @uses Zend_Auth
 */

class Pas_View_Helper_FindSpot extends Zend_View_Helper_Abstract {

    /** The no access array
     * @access protected
     * @var array
     */
    protected $_noaccess = array('public');

    /** The restricted array
     * @access protected
     * @var array
     */
    protected $_restricted = array('member');

    /** The recorders array
     * @access protected
     * @var array
     */
    protected $_recorders = array();

    /** The higher level array
     * @access protected
     * @var array
     */
    protected $_higherLevel = array(
        'admin', 'fa', 'treasure',
        'flos', 'research', 'hero',
        'hoard'
    );

    /** Message for missing group exception
     * @access protected
     * @var string
     */
    protected $_missingGroup = 'User is not assigned to a group';

    /** Not allowed to edit message
     * @access protected
     * @var string
     */
    protected $_message = 'You are not allowed edit rights to this record';

    /** The auth object
     * @access protected
     * @var \Zend_Auth
     */
    protected $_auth;

    /** Get the auth
     * @access public
     * @return \Zend_Auth
     */
    public function getAuth()  {
        $this->_auth = Zend_Auth::getInstance();
        return $this->_auth;
    }

    /** The default role
     * @access protected
     * @var string
     */
    protected $_role = 'public';

    /** Get the role of the user
     * @access public
     * @return string
     */
    public function getRole() {
        if($this->getUser() == true) {
            $this->_role = $this->getUser()->role;
        }
        return $this->_role;
    }


    /** Get the user
     * @access public
     * @return \Pas_User_Details
     */
    public function getUser(){
        $user = new Pas_User_Details();
        return $user->getPerson();
    }

    /** Get the user's id
     * @access public
     * @return integer
     */
    public function getId() {
        if($this->getUser() == true) {
            $this->_id = $this->getUser()->id;
        } else {
            $this->_id = 3;
        }
        return $this->_id;
    }

    /** Get the user's institution
     * @access public
     * @return string
     */
    public function getInstitution() {
        if($this->getUser() == true) {
            $this->_inst = $this->getUser()->institution;
        }
        return $this->_inst;
    }

    /** Default institution
     * @access protected
     * @var string
     */
    protected $_inst = 'public';

    /** The record created by
     * @access protected
     * @var string
     */
    protected $_createdBy;

    /** Get the created by
     * @access public
     * @return integer
     */
    public function getCreatedBy() {
        return $this->_createdBy;
    }

    /** Set createdby
     * @access public
     * @param integer $createdBy
     * @return \Pas_View_Helper_Findspot
     */
    public function setCreatedBy($createdBy) {
        $this->_createdBy = $createdBy;
        return $this;
    }

    /** The data to build the view
     * @access protected
     * @var array
     */
    protected $_data;

    /** Get the data to build from
     * @access public
     * @return array
     */
    public function getData() {
        return $this->_data;
    }

    /** Set the data to use
     * @access public
     * @param array $data
     * @return \Pas_View_Helper_Findspot
     */
    public function setData(array $data) {
        $this->_data = $data;
        return $this;
    }

    /** The findspot function
     * @access public
     * @return \Pas_View_Helper_Findspot
     */
    public function findSpot() {
        return $this;
    }

    /** Check record by creator
     * @access public
     * @return boolean
     */
    public function checkByCreator() {
        $id = $this->getCreatedBy();
        $role = $this->getRole();
        //If user is not higher level, but created record
        if(!in_array($role, $this->_higherLevel) && $this->getId() == $id) {
            return true;
        } elseif(in_array($role, $this->_higherLevel) )  {  //If user is higher level
            return true;
        } else { //Default return false
            return false;
        }
    }


    /** Build the html and return the correct partial
     * @access public
     * @param array $findspots
     */
    public function buildHtml() {
        $html = '';
        if ($this->checkByCreator()) {
            $html .= $this->view->partial('partials/database/geodata/findSpot.phtml', $this->getData());
        } else {
            $html .= $this->view->partial('partials/database/geodata/unAuthorisedFindSpot.phtml', $this->getData());
        }
        return $html;
    }

    /** Return html string
     * @access public
     * @return string
     */
    public function __toString() {
        try {
            return $this->buildHtml();
        } catch (Exception $e) {
        }
    }
}