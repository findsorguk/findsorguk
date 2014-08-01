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
 * ->setReturnID($this->returnID)
 * ->setBroadperiod($this->broadperiod)
 * ->setSecuID($this->secuid)
 * ->setInstitution($this->institution)
 * ->setCreatedBy($this->createdBy)
 * ?>
 * </code>
 *
 * @category Pas
 * @package Pas_View
 * @subpackage Helper
 * @version 1
 * @since September 30 2011
 * @copyright Daniel Pett
 * @author Daniel Pett
 * @todo Streamline and DRY the code
 * @license GNU
 * @uses Zend_Exception
 * @uses Zend_View_Helper_Url
 */
class Pas_View_Helper_CoinEditDeleteLink extends Zend_View_Helper_Abstract {

    /** The coin record ID number
     * @access protected
     * @var int
     */
    protected $_recordID;

    /** Get the recordID
     * @access public
     * @return int
     */
    public function getRecordID() {
        return $this->_recordID;
    }

    /** Set the recordID number
     * @access public
     * @param int $recordID
     * @return \Pas_View_Helper_CoinEditDeleteLink
     */
    public function setRecordID($recordID) {
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
    protected $_higherLevel = array('admin','fa','treasure');

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

    /** The broadperiod string
     * @access public
     * @var string
     */
    protected $_broadperiod;

    /** Get the broadperiod
     * @access public
     * @return string
     */
    public function getBroadperiod() {
        return $this->_broadperiod;
    }

    /** Set the broadperiod to query
     * @access public
     * @param string $broadperiod
     * @return \Pas_View_Helper_CoinDataDisplay
     */
    public function setBroadperiod( $broadperiod) {
        $this->_broadperiod = $broadperiod;
        return $this;
    }
    /** The array of broadperiods available for coins
     * @access public
     * @var array
     */
    protected $_broadperiods = array(
        'IRON AGE', 'ROMAN', 'BYZANTINE',
        'EARLY MEDIEVAL', 'GREEK AND ROMAN PROVINCIAL', 'MEDIEVAL',
        'POST MEDIEVAL', 'MODERN', 'UNKNOWN'
        );

    /** Get the broadperiods allowed
     * @access public
     * @return array
     */
    public function getBroadperiods() {
        return $this->_broadperiods;
    }

    /** Set the array of broadperiods
     * @access public
     * @param type $broadperiods
     * @return \Pas_View_Helper_CoinDataDisplay
     */
    public function setBroadperiods( array $broadperiods) {
        $this->_broadperiods = $broadperiods;
        return $this;
    }

    /** get the creator
     * @access public
     * @return int
     */
    public function getCreatedBy() {
        return $this->_createdBy;
    }

    /** Get the institution
     * @access public
     * @return string
     */
    public function getInstitution() {
        return $this->_institution;
    }

    /** Get the secure ID
     * @access public
     * @return string
     */
    public function getSecuid() {
        return $this->_secuid;
    }

    /** Set createdby
     * @access public
     * @param int $createdBy
     * @return \Pas_View_Helper_ImageLink
     */
    public function setCreatedBy( $createdBy) {
        $this->_createdBy = $createdBy;
        return $this;
    }

    /** Set the institution
     * @access public
     * @param string $institution
     * @return \Pas_View_Helper_ImageLink
     */
    public function setInstitution( $institution) {
        $this->_institution = $institution;
        return $this;
    }

    /** Set the secure ID
     * @access public
     * @param string $secuid
     * @return \Pas_View_Helper_ImageLink
     */
    public function setSecuid( $secuid) {
        $this->_secuid = $secuid;
        return $this;
    }

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

    /** Set the find ID to query
     * @access public
     * @param int $findID
     * @return \Pas_View_Helper_CoinRefAddLink
     */
    public function setFindID( $findID) {
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
    public function getFindID() {
        return $this->_findID;
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
        if(in_array($this->getRole(),$this->_recorders)
                && $this->getInst() == $institution) {
            $allowed = true;
        } elseif (in_array ($this->getRole(), $this->_higherLevel)) {
            $allowed = true;
        } elseif (in_array ($this->getRole(), $this->_restricted)
                && $this->checkAccessbyUserID ($this->getCreatedBy())) {
            $allowed = true;
        } elseif (in_array($this->getRole(),$this->_recorders)
                && $institution == 'PUBLIC') {
            $allowed = true;
        } else {
            $allowed = false;
        }
        return $allowed;
    }

    /** The function to return
     * @access public
     * @return \Pas_View_Helper_CoinEditDeleteLink
     */
    public function coinEditDeleteLink() {
        return $this;
    }
    /** To string function
     * @access public
     * @return string
     */
    public function __toString() {
        return $this->generateLink();
    }

    /** Generate the link
     * @access public
     * @return string
     */
    public function generateLink() {
        $html = '';
        if( $this->checkAccessbyInstitution( $this->getInstitution() ) ) {
            $this->checkBroadperiod();
            $html .= $this->buildHtml();
        }
        return $html;
    }

    /** Build just the url
     * @access public
     * @return string
     */
    public function urlBuildEdit() {
        $url = array(
            'module' => 'database',
            'controller' => 'coins',
            'action' => 'edit',
            'secuid' => $this->getSecuID(),
            'id' => $this->getRecordID(),
            'broadperiod' => $this->getBroadperiod(),
            'returnID' => $this->getFindID(),
            'secuid' => $this->getSecuid()
        );
        return $url;
    }


    /** Check the broadperiod
     * @access public
     * @return \Pas_View_Helper_CoinEditDeleteLink
     * @throws Zend_Exception
     */
    public function checkBroadperiod() {
        if(!in_array($this->getBroadperiod(), $this->getBroadperiods())) {
            throw new Zend_Exception('Broadperiod is incorrect', 500);
        }
        return $this;
    }
    /** Build just the url
     * @access public
     * @return string
     */
    public function urlBuildDelete() {
        $url = array(
            'module' => 'database',
            'controller' => 'coins',
            'action' => 'delete',
            'id' => $this->getRecordID()
        );
        return $url;
    }
    /** Build the html
     * @access public
     * @return string
     */
    public function buildHtml() {
        $editUrl = $this->view->url($this->urlBuildEdit(),null,true);
        $deleteUrl = $this->view->url($this->urlBuildDelete(),null,true);
        $editClass = 'btn btn-small btn-warning';
        $deleteClass = 'btn btn-small btn-danger';
        $html = '';
        $html .= '<span class="noprint"><p><a class="';
        $html .= $editClass;
        $html .= '" href="';
        $html .= $editUrl;
        $html .= '" title="Edit numismatic data for this record">';
        $html .= 'Edit numismatic data <i class="icon-white icon-edit"></i>';
        $html .= '</a> <a class="';
        $html .= $deleteClass;
        $html .= '" href="';
        $html .= $deleteUrl;
        $html .= '" title="Delete numismatic data">Delete';
        $html .= '<i class="icon-white icon-trash"></i></a></p></span>';
        return $html;
    }
}
