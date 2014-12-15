<?php
/**
 * Created by PhpStorm.
 * User: danielpett
 * Date: 12/10/2014
 * Time: 08:16
 *
 * @todo Add in all the checking logic for who can edit.
 */
class Pas_View_Helper_AddArchaeology extends Zend_View_Helper_Abstract {

    protected $_secUID;

    /** The ID number of the record
     * @access protected
     * @var  $_ID
     */
    protected $_ID;

    /** The hoardID to return to
     * @access protected
     * @var $_hoardID
     */
    protected $_hoardID;

    /** The role variable
     * @var  string */
    protected $_role;

    /** Get the user's role
     * @access public
     * @return string
     */
    public function getRole() {
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
    public function getAuth() {
        $this->_auth = Zend_Registry::get('auth');
        return $this->_auth;
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
    protected $_restricted = array('member','research','hero');

    /** Set up the user groups with recorder access
     * @access protected
     * @var array $recorders
     */
    protected $_recorders = array('flos');

    /** Set up the user groups with higher level access
     * @access protected
     * @var array $higherLevel
     */
    protected $_higherLevel = array('admin','fa','treasure', 'hoard');

    /** The default institution
     * @var string $_institution
     */
    protected $_institution = 'PUBLIC';

    /** The default created by
     * @var string $_createdBy
     */
    protected $_createdBy = '3';

    /** Get the institution for the record
     * @return string
     */
    public function getInstitution()
    {
        return $this->_institution;
    }

    /** Set the institution
     * @param string $institution
     * @return string
     */
    public function setInstitution($institution)
    {
        $this->_institution = $institution;
        return $this;
    }

    /** Get the record created by identifier
     * @return string
     */
    public function getCreatedBy()
    {
        return $this->_createdBy;
    }

    /** Set the record created by from the view
     * @param string $createdBy
     * @return string
     */
    public function setCreatedBy($createdBy)
    {
        $this->_createdBy = $createdBy;
        return $this;
    }

    /** Get the person logged in
     * @return string
     */
    public function getPerson()
    {
        $user = new Pas_User_Details();
        return $user->getPerson();
    }

    /** The user id
     * @access protected
     * @var type
     */
    protected $_userID;

    /** Get the user's ID
     * @access public
     * @return int
     */
    public function getUserID() {
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
    public function getInst() {
        if ($this->getAuth()->hasIdentity()) {
            $user = $this->getAuth()->getIdentity();
            $this->_inst = $user->institution;
        }
        return $this->_inst;
    }


    /** Get the ID of the summary record to work on
     * @access public
     * @return mixed
     */
    public function getID()
    {
        return $this->_ID;
    }

    /** Set the ID of the record
     * @access public
     * @param mixed $ID
     */
    public function setID($ID)
    {
        $this->_ID = $ID;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getHoardID()
    {
        return $this->_secUID;
    }

    /**
     * @param mixed $secUID
     */
    public function setHoardID($hoardID)
    {
        $this->_secUID = $hoardID;
        return $this;
    }



    /** The view helper function
     * @access public
     * @return \Pas_View_Helper_EditDeleteArchaeology
     */
    public function AddArchaeology()
    {
        return $this;
    }

    /** Return the html string
     * @access public
     * @return string
     */
    public function __toString()
    {
        return $this->generateLink();
    }

    /** Build the html to return as a string
     * @access public
     * @return string
     */
    public function buildHtml()
    {
        $html = '';
        $html .= $this->view->partial('partials/hoards/coinArchaeologyAdd.phtml', array(
            'id' => $this->getID(), 'hoardID' =>  $this->getHoardID()
            )
        );
        return $html;
    }


    public function checkParameters()
    {

    }

    /** Check whether access is allowed by userid for that record
     *
     * This function conditionally checks to see if a user is in the restricted
     * group and then checks whether they created the record. If true, they can
     * edit it.
     *
     * @access public
     * @param int $createdBy
     * @return boolean
     */
    public function checkAccessbyUserID($createdBy ) {
        if (in_array( $this->getRole(), $this->_restricted ) ) {
            if ($createdBy == $this->getUserID()) {
                $allowed = true;
            } else {
                $allowed = false;
            }
        }
        return $allowed;
    }

    /** Check institutional access by user's institution
     *
     * This function conditionally checks whether a user's institution allows
     * them editing rights to a record.
     *
     * First condition: if role is in recorders array and their institution is
     * the same, then allow.
     *
     * Second condition: if role is in higher level, then allow
     *
     * Third condition: if role is in restricted (public) and they created,
     * then allow.
     *
     * Fourth condition: if role is in restricted and institution is public,
     * then allow.
     *
     * @access public
     * @param string $institution
     * @return boolean
     *
     */
    public function checkAccessbyInstitution( $institution ) {
        if(in_array($this->getRole(), $this->_recorders)
            && $this->getInst() == $institution) {
            $allowed = true;
        } elseif (in_array ($this->getRole(), $this->_higherLevel)) {
            $allowed = true;
        } elseif (in_array ($this->getRole(), $this->_restricted)
            && $this->checkAccessbyUserID ($this->getCreatedBy())) {
            $allowed = true;
        } elseif (in_array($this->getRole(), $this->_recorders)
            && $institution == 'PUBLIC') {
            $allowed = true;
        } else {
            $allowed = false;
        }
        return $allowed;
    }

    /** Generate the link
     * @access public
     * @return string
     */
    public function generateLink() {
        $html = '';
        if( $this->checkAccessbyInstitution( $this->getInstitution() ) ) {
            $html .= $this->buildHtml();
        }
        return $html;
    }


}