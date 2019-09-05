<?php

/**
 * A view helper for rendering coin edit delete links data
 *
 * This view helper is used for displaying coin data on a page based on the
 * numismatic or object type.
 *
 * To use this:
 * <code>
 * <?php
 * echo $this->coinEditDeleteLink()
 * ->setFindID($this->id)
 * ->setRecordID($this->returnID)
 * ->setSecuID($this->secuid)
 * ->setInstitution($this->institution)
 * ->setCreatedBy($this->createdBy)
 * ?>
 * </code>
 *
 * @category Pas
 * @package View
 * @subpackage Helper
 * @version 2
 * @since 11/2/2015
 * @copyright Daniel Pett
 * @author Daniel Pett
 * @uses Zend_Exception
 * @uses Zend_View_Helper_Url
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 */
class Pas_View_Helper_ReferencesEditDeleteLink extends Zend_View_Helper_Abstract
{

    /** The coin record ID number
     * @access protected
     * @var int
     */
    protected $_recordID;

    /** Get the recordID
     * @access public
     * @return int
     */
    public function getRecordID()
    {
        return $this->_recordID;
    }

    /** Set the recordID number
     * @access public
     * @param int $recordID
     * @return \Pas_View_Helper_CoinEditDeleteLink
     */
    public function setRecordID($recordID)
    {
        $this->_recordID = $recordID;
        return $this;
    }

    /** Set up the user groups with no access
     * @access protected
     * @var array $noaccess
     */
    protected $_noaccess = array('public', null);

    /** Set up the user groups with limited access
     * @access protected
     * @var array $restricted
     */
    protected $_restricted = array('member', 'research', 'hero');

    /** Set up the user groups with recorder access
     * @access protected
     * @var array $recorders
     */
    protected $_recorders = array('flos');

    /** Set up the user groups with higher level access
     * @access protected
     * @var array $higherLevel
     */
    protected $_higherLevel = array(
        'admin', 'fa', 'treasure',
        'hoard'
    );

    /** The auth object
     * @access protected
     * @var object
     */
    protected $_auth;

    /** The creator
     * @access protected
     * @var int
     */
    protected $_createdBy;

    /** The institution of the recorder creator
     * @access protected
     * @var string
     */
    protected $_institution = 'PUBLIC';

    /** The institution of the user
     * @access protected
     * @var string
     */
    protected $_inst;

    /** The record's secuid
     * @access protected
     * @var string
     */
    protected $_secuid;

    /** The role of the user
     * @access protected
     * @var string
     */
    protected $_role = null;


    /** get the creator
     * @access public
     * @return int
     */
    public function getCreatedBy()
    {
        return $this->_createdBy;
    }

    /** Get the institution
     * @access public
     * @return string
     */
    public function getInstitution()
    {
        return $this->_institution;
    }

    /** Get the secure ID
     * @access public
     * @return string
     */
    public function getSecuid()
    {
        return $this->_secuid;
    }

    /** Set createdby
     * @access public
     * @param int $createdBy
     * @return \Pas_View_Helper_ImageLink
     */
    public function setCreatedBy($createdBy)
    {
        $this->_createdBy = $createdBy;
        return $this;
    }

    /** Set the institution
     * @access public
     * @param string $institution
     * @return \Pas_View_Helper_ImageLink
     */
    public function setInstitution($institution)
    {
        $this->_institution = $institution;
        return $this;
    }

    /** Set the secure ID
     * @access public
     * @param string $secuid
     * @return \Pas_View_Helper_ImageLink
     */
    public function setSecuid($secuid)
    {
        $this->_secuid = $secuid;
        return $this;
    }

    /** Get the user's role
     * @access public
     * @return string
     */
    public function getRole()
    {
        if ($this->getAuth()->hasIdentity()) {
            $user = $this->getAuth()->getIdentity();
            $this->_role = $user->role;
        }
        return $this->_role;
    }

    /** Get the auth object
     * @access public
     * @return object
     */
    public function getAuth()
    {
        $this->_auth = Zend_Registry::get('auth');
        return $this->_auth;
    }

    /** Set the find ID to query
     * @access public
     * @param int $findID
     * @return \Pas_View_Helper_CoinRefAddLink
     */
    public function setFindID($findID)
    {
        $this->_findID = $findID;
        return $this;
    }

    /** The findID
     * @access protected
     * @var int
     */
    protected $_findID;

    /** Get the findID
     * @access public
     * @return int
     */
    public function getFindID()
    {
        return $this->_findID;
    }

    /** The user id
     * @access protected
     * @var integer
     */
    protected $_userID;

    /** Get the user's ID
     * @access public
     * @return int
     */
    public function getUserID()
    {
        if ($this->getAuth()->hasIdentity()) {
            $user = $this->getAuth()->getIdentity();
            $this->_userID = $user->id;
        }
        return $this->_userID;
    }

    /** Get the user's institution
     * @access public
     * @return string
     */
    public function getInst()
    {
        if ($this->getAuth()->hasIdentity()) {
            $user = $this->getAuth()->getIdentity();
            $this->_inst = $user->institution;
        }
        return $this->_inst;
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


    /** The function to return
     * @access public
     * @return \Pas_View_Helper_CoinEditDeleteLink
     */
    public function referencesEditDeleteLink()
    {
        return $this;
    }

    /** To string function
     * @access public
     * @return string
     */
    public function __toString()
    {
        return $this->generateLink();
    }

    /** Generate the link
     * @access public
     * @return string
     */
    public function generateLink()
    {
        $html = '';
        if ($this->checkAccess()) {
            $html .= $this->buildHtml();
        }
        return $html;
    }

    /** Build just the url
     * @access public
     * @return string
     */
    public function urlBuildEdit()
    {
        $url = array(
            'module' => 'database',
            'controller' => 'references',
            'action' => 'edit',
            'id' => $this->getRecordID(),
            'findID' => $this->getFindID(),
            'recordtype' => $this->getController());

        return $url;
    }

    /** Build just the url
     * @access public
     * @return string
     */
    public function urlBuildDelete()
    {
        $url = array(
            'module' => 'database',
            'controller' => 'references',
            'action' => 'delete',
            'id' => $this->getRecordID(),
            'findID' => $this->getFindID(),
            'recordtype' => $this->getController());
        return $url;
    }

    /** Build the html
     * @access public
     * @return string
     */
    public function buildHtml()
    {
        $editUrl = $this->view->url($this->urlBuildEdit(), null, true);
        $deleteUrl = $this->view->url($this->urlBuildDelete(), null, true);
        $editClass = 'btn btn-mini btn-warning';
        $deleteClass = 'btn btn-mini btn-danger';
        $html = '';
        $html .= '<div class="btn-group"><a class="';
        $html .= $editClass;
        $html .= '" href="';
        $html .= $editUrl;
        $html .= '" title="Edit reference data for this record">';
        $html .= 'Edit';
        $html .= '</a><a class="';
        $html .= $deleteClass;
        $html .= '" href="';
        $html .= $deleteUrl;
        $html .= '" title="Delete reference data">';
        $html .= 'Delete';
        $html .= '</a></div>';
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
