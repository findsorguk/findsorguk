<?php
/**
 * A view helper for creating coin reference add link
 *
 * This view helper is used to add a coin reference to the record form if
 * allowed. This function could well be dropped if coin references are
 * integrated into the coin forms.
 *
 * To use this function:
 * <code>
 * <?php
 * echo $this->coinRefAddLink()
 * ->setFindID($findID)
 * ->setSecuID($secuid)
 * ->setCreatedby($createdBy)
 * ->setInstitution($institution);
 * ?>
 * </code>
 *
 * @category Pas
 * @package Pas_View
 * @subpackage Helper
 * @version 1
 * @since September 30 2011
 * @copyright DEJ Pett
  * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @uses Zend_Auth
 *
 */
class Pas_View_Helper_CoinRefAddLink extends Zend_View_Helper_Abstract {

    /** The array of roles with no access
     * @access public
     * @var array
     */
    protected $_noaccess = array('public', null);

    /** The restricted roles
     * @access protected
     * @var array
     */
    protected $_restricted = array('member','research','hero');

    /** The recording array
     * @access protected
     * @var array
     */
    protected $_recorders = array('flos');

    /** The higher level roles
     * @access protected
     * @var array
     */
    protected $_higherLevel = array('admin','fa','treasure');


    /** The auth object
     * @access protected
     * @var object
     */
    protected $_auth;

    /** The role
     * @access protected
     * @var string
     */
    protected $_role = null;

    /** The user ID
     * @access protected
     * @var int
     */
    protected $_userID = null;

    /** The default institution
     * @access protected
     * @var string
     */
    protected $_inst = 'PUBLIC';

    /** The creator
     * @access protected
     * @var int
     */
    protected $_createdBy;

    /** Object secuid for the glue
     * This ties the records together
     * @var string
     * @access protected
     */
    protected $_secuID;

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

    /** The institution for the record
     * @access protected
     * @var string
     */
    protected $_institution;

    /** Get the institution
     * @access public
     * @return string
     */
    public function getInstitution() {
        return $this->_institution;
    }

    /** Set the institution
     * @access public
     * @param string $institution
     * @return \Pas_View_Helper_CoinRefAddLink
     */
    public function setInstitution( $institution) {
        $this->_institution = $institution;
        return $this;
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
    
    protected $_coinID;
    
    public function getCoinID() {
        return $this->_coinID;
    }

    public function setCoinID($coinID) {
        $this->_coinID = $coinID;
        return $this;
    }

    
    /** Get the creator of the record
     * @access public
     * @return int
     */
    public function getCreatedBy() {
        return $this->_createdBy;
    }

    /** Set the secure ID for the glue
     * @access public
     * @return string
     */
    public function getSecuID() {
        return $this->_secuID;
    }

    /** Set created by
     * @access public
     * @param int $createdBy
     * @return \Pas_View_Helper_CoinRefAddLink
     */
    public function setCreatedBy( $createdBy) {
        $this->_createdBy = $createdBy;
        return $this;
    }

    /** Set the secure ID glue string
     * @access public
     * @param string $secuid
     * @return \Pas_View_Helper_CoinRefAddLink
     */
    public function setSecuID( $secuid) {
        $this->_secuID = $secuid;
        return $this;
    }

    /** Get the auth object
     * @access public
     * @return object
     */
    public function getAuth() {
        $this->_auth = Zend_Auth::getInstance();
        return $this->_auth;
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
     * @return \Pas_View_Helper_CoinRefAddLink
     */
    public function coinRefAddLink() {
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
        if( $this->checkAccess( ) ) {
            $html .= $this->buildHtml();
        }
        return $html;
    }

    /** Build just the url
     * @access public
     * @return string
     */
    public function urlBuild() {
        $url = array(
            'module' => 'database',
            'controller' => 'coins',
            'action' => 'coinref',
            'findID' => $this->getSecuID(),
            'returnID' => $this->getFindID()
        );
        return $url;
    }

    /** Build the html
     * @access public
     * @return string
     */
    public function buildHtml() {
        $url = $this->view->url($this->urlBuild(),null,true);
        $html = '';
        $html .= '<div id="coinrefs" class="noprint">';
        $html .= '<a class="btn btn-small btn-primary" href="';
        $html .= $url;
        $html .= '" title="Add a reference for this coin">';
        $html .= 'Add a coin reference</a></div>';
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
            || in_array($this->getRole(), $this->_recorders) && $this->getInst() == 'PUBLIC' ) {
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