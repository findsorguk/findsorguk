<?php

/** view helper for editing reference link
 * @category   Pas
 * @package    Pas_View_Helper
 * @subpackage Abstract
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @see Zend_View_Helper_Abstract
 */
class Pas_View_Helper_EditReference extends Zend_View_Helper_Abstract
{

    /** The no access group
     * @var array
     * @access protected
     */
    protected $noaccess = array('public');

    /** The restricted access group
     * @var array
     * @access protected
     */
    protected $restricted = array('member', 'research', 'hero');

    /** The recorders group
     * @var array
     * @access protected
     */
    protected $recorders = array('flos');

    /** The higher level array
     * @var array
     * @access protected
     */
    protected $higherLevel = array('admin', 'fa', 'treasure', 'hoard');

    /** Missing group message
     * @var string
     * @access protected
     */
    protected $_missingGroup = 'User is not assigned to a group';

    /** The auth object
     * @var null
     * @access protected
     */
    protected $_auth = null;

    /** Get the auth object
     * @access public
     * @return \Zend_Auth
     */
    public function getAuth()
    {
        $this->_auth = Zend_Auth::getInstance();
        return $this->_auth;
    }

    /** Get the role of the user
     * @access public
     * @return string
     */
    public function getRole()
    {
        if ($this->getAuth()->hasIdentity()) {
            $user = $this->getAuth()->getIdentity();
            $role = $user->role;
        } else {
            $role = 'public';
        }

        return $role;
    }

    /** Get a user id
     * @access public
     * @return integer
     */
    public function getUserID()
    {
        if ($this->getAuth()->hasIdentity()) {
            $user = $this->getAuth()->getIdentity();
            $id = $user->id;
        } else {
            $id = 3;
        }
        return $id;
    }

    /** Get the controller
     * @access public
     * @return object
     */
    public function getController()
    {
        $this->_controller = Zend_Controller_Front::getInstance()->getRequest()->getControllerName();
        return $this->_controller;
    }

    /** Check access by user id
     * @access public
     * @return boolean
     */
    public function checkAccessbyUserID($createdBy)
    {
        if ($createdBy == $this->getUserID()) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /** Edit the reference
     * @access public
     * @return string|boolean
     */
    public function editReference($i, $fID)
    {
        if ($this->checkAccess()){
            return $this->buildHtml($i, $fID);
        }
    }

    /** Build the html
     * @access public
     * @return string
     */
    public function buildHtml($i, $fID)
    {
        $html = '';
        $html .= ' <a href="' . $this->view->url(array(
                'module' => 'database',
                'controller' => 'references',
                'action' => 'edit',
                'id' => $i,
                'findID' => $fID,
                'recordtype' => $this->getController()
            ), NULL, TRUE) . '" title="Edit this reference">Edit</a> | <a href="'
            . $this->view->url(array(
                'module' => 'database',
                'controller' => 'references',
                'action' => 'delete',
                'id' => $i,
                'findID' => $fID,
                'recordtype' => $this->getController()
            ), NULL, TRUE)
            . '" title="Delete this reference">Delete</a>';
        $html .= '.</li>' . "\n";

        return $html;
    }

    public function checkAccess()
    {
        // If role = public return false
        if (in_array($this->getRole(), $this->_noaccess)) {
            return false;
        }
        //If role in restricted and created = created by return true
        else if (in_array($this->getRole(), $this->_restricted) && $this->getCreatedBy() == $this->getUserID()) {
            return true;
        }
        //If role in recorders and institution = inst or created by = created return true
        else if (in_array($this->getRole(), $this->_recorders) && $this->getInst() == $this->getInstitution()
            || $this->getCreatedBy() == $this->getUserID()
            || in_array($this->getRole(), $this->_recorders) && $this->getInstitution() == 'PUBLIC') {
            return true;
        }
        //If role in higher level return true
        else if (in_array($this->getRole(), $this->_higherLevel)) {
            return true;
        } else {
            return false;
        }
    }

}