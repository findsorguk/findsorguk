<?php
/** A view helper for creating coin reference edit and delete links
 * @category Pas
 * @package Pas_View
 * @subpackage Helper
 * @version 1
 * @since September 30 2011
 * @copyright DEJ Pett
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @todo DRY the code
 * @author Daniel Pett
 */
class Pas_View_Helper_CoinRefEditDeleteLink extends Zend_View_Helper_Abstract {

    /** The array of roles with no access 
     * @access protected
     * @var array
     */
    protected $_noaccess = array('public', null);
    
    /** The restricted access array
     * @access protected
     * @var array
     */
    protected $_restricted = array('member','research','hero');
    
    /** The recorders array
     * @access protected
     * @var array
     */
    protected $_recorders = array('flos');
    
    /** The higher level array of roles
     * @access protected
     * @var array
     */
    protected $_higherLevel = array('admin','fa','treasure');
    
    /** The user details
     * @access protected
     * @var \Pas_User_Details
     */
    protected $_user;
    
    /** Get the user details from the model
     * @access public
     * @return \Pas_User_Details
     */
    public function getUser() {
        $this->_user = new Pas_User_Details();
        return $this->_user;
    }

    /** The default role
     * @access protected
     * @var string
     */
    protected $_role = 'public';
    
    /** The id of the user default to public
     * @access protected
     * @var integer
     */
    protected $_id = 3;
    
    /** The default institution
     * @access protected
     * @var string
     */
    protected $_institution = 'PUBLIC';
    
    /** The creator of the record
     * @access protected
     * @var integer
     */
    protected $_creator;
    
    /** Get the creator for a record
     * @access public
     * @return integer
     */
    public function getCreator() {
        return $this->_creator;
    }

    /** Set the creator for a record
     * @access public
     * @param integer $creator
     * @return \Pas_View_Helper_CoinRefEditDeleteLink
     */
    public function setCreator($creator) {
        $this->_creator = $creator;
        return $this;
    }

    /** Get the role of the user
     * @access public
     * @return string
     */
    public function getRole() {
        $this->_role = $this->getUser()->getPerson()->role;
        return $this->_role;
    }

    /** Get the id of the current user
     * @access public
     * @return integer
     */
    public function getId() {
        $this->_id = $this->getUser()->getIdentityForForms();
        return $this->_id;
    }

    /** Get the institution of the user
     * @access public
     * @return string
     */
    public function getInstitution(){
        return $this->_institution;
    }
    
    /** Set the institution for the record
     * @access public
     * @param string $institution
     * @return \Pas_View_Helper_CoinRefEditDeleteLink
     */
    public function setInstitution($institution) {
        $this->_institution = $institution;
        return $this;
    }

    /** The record ID number
     * @access protected
     * @var integer
     */
    protected $_recordID;
    
    /** The coin ID
     * @access protected
     * @var integer
     */
    protected $_coinID;
    
    /** Get the record ID
     * @access public
     * @return integer
     */
    public function getRecordID() {
        return $this->_recordID;
    }

    /** Get the coin ID for the record
     * @access public
     * @return integer
     */
    public function getCoinID() {
        return $this->_coinID;
    }

    /** Set the record ID
     * @access public
     * @param integer $recordID
     * @return \Pas_View_Helper_CoinRefEditDeleteLink
     */
    public function setRecordID($recordID) {
        $this->_recordID = $recordID;
        return $this;
    }

    /** Set the coin id for the record
     * @access public
     * @param integer $coinID
     * @return \Pas_View_Helper_CoinRefEditDeleteLink
     */
    public function setCoinID($coinID) {
        $this->_coinID = $coinID;
        return $this;
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


    /** The function to return
     * @access public
     * @return \Pas_View_Helper_CoinRefEditDeleteLink
     */
    public function coinRefEditDeleteCheck() {
        return $this;
    }

    /** Return the HTML
     * @access public
     * @param int $id
     * @param int $returnID
     * @return string
     */
    public function buildHtml() {
        $allowed = $this->performChecks();
        $html = '';
        if($allowed) {
            $html .= '<a href="';
            $html .= $this->view->url(array(
                'module' => 'database',
                'controller' => 'coins',
                'action' => 'editcoinref',
                'id' => $this->getCoinID(),
                'returnID' => $this->getRecordID()),null,true);
            $html .= '" title="Edit this coin reference">Edit</a> | <a href="';
            $html .= $this->view->url(array(
                'module' => 'database',
                'controller' => 'coins',
                'action' => 'deletecoinref',
                'id' => $this->getCoinID(),
                'returnID' => $this->getRecordID()),null,true);
            $html .= '" title="Delete this reference">Delete</a>';
        }
        return $html;
    }

    /** To string function
     * @access public
     * @return string
     */
    public function __toString() {
        return $this->buildHtml();
    }
}