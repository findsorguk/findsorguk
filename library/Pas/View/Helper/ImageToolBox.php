<?php
/**
 * A view helper for image link toolbox generation
 *
 * An example of use:
 * <code>
 * <?pho
 * $this->imageToolBox()
 * ->setID($this->id)
 * ->setCreatedBy($this->createdBy)
 * ->setInstitution($this->institution);
 * ?>
 * </code>
 *
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @copyright (c) 2014, Daniel Pett
 * @version 1
 * @example /app/views/scripts/partials/database/image.phtml Image view
 * @category Pas
 * @package View_Helper
 *
 *
 *
 */
class Pas_View_Helper_ImageToolBox extends Zend_View_Helper_Abstract {

    /** array of roles with no access to toolbox
     * @access protected
     * @var array
     */
    protected $_noaccess = array('public', NULL);

    /** Array of roles with restricted access
     * @access protected
     * @var array
     */
    protected $_restricted = array('member','research','hero');

    /** The recording officer role
     * @access protected
     * @var array
     */
    protected $_recorders = array('flos');

    /** Array of higher level roles
     * @access protected
     * @var array
     */
    protected $_higherLevel = array('admin','fa','treasure');

    /** An override institution
     * @access protected
     * @var string
     */
    protected $_overRide = 'PUBLIC';

    /** Id of record
     * @access protected
     * @var int
     */
    protected $_id;

    /** The institution for the record
     * @access protected
     * @var string
     */
    protected $_institution;

    /** The creator
     * @access protected
     * @var int
     */
    protected $_createdBy;

    /** Boolean can create
     * @access protected
     * @var boolean
     */
    protected $_canCreate;

    /** Get the user from the model
     * @access public
     * @return object
     */
    public function _getUser() {
        $person = new Pas_User_Details();
        return $person->getPerson();
    }

    /** Check their institution
     * @access protected
     * @return boolean
     */
    protected function _checkInstitution() {
        if ($this->getInstitution() === $this->_getUser()->institution) {
            return true;
        } else {
            return false;
        }
    }

    /** Check the creator
     * @access public
     * @return boolean
     */
    protected function _checkCreator() {
        if ($this->getCreatedBy() === $this->_getUser()->id) {
            return true;
        } else {
            return false;
        }
    }

    /** Set the id
     * @access public
     * @param int $id
     * @return \Pas_View_Helper_ImageToolBox
     */
    public function setID($id) {
        $this->_id = $id;
        return $this;
    }

    /** Set the institution
     * @access public
     * @param string $institution
     * @return \Pas_View_Helper_ImageToolBox
     */
    public function setInstitution($institution) {
        $this->_institution = $institution;
        return $this;
    }

    /** set created by
     * @access public
     * @param int $createdBy
     * @return \Pas_View_Helper_ImageToolBox
     */
    public function setCreatedBy($createdBy) {
        $this->_createdBy = $createdBy;
        return $this;
    }


    /** Build the html
     * @access public
     * @return string
     */
    public function _buildHtml() {
        $html = '';
        $this->_checkParameters();
        $this->_performChecks();
        if ($this->_canCreate) {
            $paramsEdit = array(
                'module' => 'database',
                'controller' => 'images',
                'action' => 'edit',
                'id' => $this->getId()
            );
            $paramsDelete = array(
                'module' => 'database',
                'controller' => 'images',
                'action' => 'delete',
                'id' => $this->getId()
            );
            $editurl = $this->view->url($paramsEdit, 'default' ,TRUE);
            $deleteurl = $this->view->url($paramsDelete, 'default', TRUE);
            $html .= ' <a class="btn btn-success" href="' . $editurl;
            $html .= '" title="Edit image">Edit</a> <a class="btn btn-warning" href="';
            $html .= $deleteurl . '" title="Delete this image">Delete</a>';
        }
        return $html;
    }

    /** The function to return
     * @access public
     * @return \Pas_View_Helper_ImageToolBox
     */
    public function imageToolBox() {
        return $this;
    }

    /** Perform checks for access
     * @access public
     * @return boolean
     */
    public function _performChecks() {
        if ($this->_getUser()) {
            $role = $this->_getUser()->role;
        } else {
            $role = NULL;
        }
        //If user's role is in the no access array, return false for creation
        if (in_array($role, $this->_noaccess)) {
            $this->_canCreate = false;
        }
        //If user's role is in the higher level array, return true for creation
        else if (in_array($role,$this->_higherLevel)) {
            $this->_canCreate = true;
        }
        //If user's role is in recorders group check for
        // a) user ID = creator of image
        // b) institution is a public record
        // c) institution is theirs
        else if (in_array($role,$this->_recorders)) {
            if($this->_checkCreator() ||
            $this->getInstitution() === $this->_overRide ||
            $this->_checkInstitution()) {
                $this->_canCreate = true;
            }
        }
        //If user's role is in restricted groups
        // a) check if the user's institution is theirs and they are the creator
        else if (in_array($role,$this->_restricted)) {
        if (($this->_checkCreator() && $this->_checkInstitution())) {
            $this->_canCreate = true;
        }
        } else {
            $this->_canCreate = false;
        }
    }

    /** Check all parameters exist
     * @access public
     * @return boolean
     * @throws Zend_Exception
     */
    public function _checkParameters() {
        $parameters = array(
            $this->getCreatedBy(),
            $this->getInstitution(),
            $this->getId()
        );
        foreach ($parameters as $parameter) {
            if (is_null($parameter)) {
                throw new Zend_Exception('A parameter is missing');
            }
        }
        return true;
    }

    /** To string
     * @access public
     * @return string
     */
    public function __toString() {
        return $this->_buildHtml();
    }

    /** Get the id number
     * @access public
     * @return int
     */
    public function getId() {
        return $this->_id;
    }

    /** Get the institution
     * @access public
     * @return string
     */
    public function getInstitution() {
        return $this->_institution;
    }

    /** Get the creator
     * @access public
     * @return int
     */
    public function getCreatedBy() {
        return $this->_createdBy;
    }
}