<?php
/**
 * This class is to help display links for edit or delete image functions
 * @category   Pas
 * @package    Pas_View_Helper
 * @subpackage Abstract
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @uses Zend_View_Helper_Abstract
 * @author Daniel Pett
 * @since September 13 2011
*/
class Pas_View_Helper_ImageLink extends Zend_View_Helper_Abstract {
/** Set up the user groups with no access
	 *
	 * @var array $noaccess
	 */
	protected $noaccess = array('public');

	/** Set up the user groups with limited access
	 *
	 * @var array $restricted
	 */
	protected $restricted = array('member','research','hero');

	/** Set up the user groups with recorder access
	 *
	 * @var array $recorders
	 */
	protected $recorders = array('flos');

	/** Set up the user groups with higher level access
	 *
	 * @var array $higherLevel
	 */
	protected $higherLevel = array('admin','fa','treasure');

	/** Set up the exception error if missing a group from user profile
	 *
	 * @var string $_missingGroup
	 */
	protected $_missingGroup = 'User is not assigned to a group';

	/** Set up the error message
	 *
	 * @var string $_message
	 */
	protected $_message = 'You are not allowed edit rights to this record';


	protected $_auth;

	public function __construct(){
	$auth = Zend_Auth::getInstance();
	$this->_auth = $auth;
	}

    /** Get the user's role
     * @access public
     * @return string $role
     */
	public function getRole() {
	if($this->_auth->hasIdentity()) {
	$user = $this->_auth->getIdentity();
	$role = $user->role;
	} else {
	$role = 'public';
	}
	return $role;
	}

	/** Get user's ID number
	 * @access public
	 * @return integer $id
	 */
	public function getIdentityForForms(){
	if($this->_auth->hasIdentity()){
	$user = $this->_auth->getIdentity();
	$id = $user->id;
	} else {
	$id = '3';
	}
	return $id;
	}

	/** Check access by institution ID
	 * @access public
	 * @param string $oldfindID
	 * @return boolean
	 */
	public function checkAccessbyInstitution($oldfindID) {
	$find = explode('-', $oldfindID);
	$id = $find['0'];
	$inst = $this->getInst();
	if((in_array($this->getRole(),$this->recorders) && ($id === 'PUBLIC'))) {
	return TRUE;
	} else if($id == $inst) {
	return TRUE;
	}
	}

	/** Check access to record by userID number and creation
	 * @access public
	 * @param integer $userID
	 * @param integer $createdBy
	 * @return boolean
	 */
	public function checkAccessbyUserID($userID, $createdBy) {
	if($userID === $createdBy) {
	return TRUE;
	} else {
	return FALSE;
	}
	}

	/** get the user institution
	 * @access public
	 * @return string $inst
	 * @throws Pas_Exception_Group
	 */
	public function getInst() {
	if($this->_auth->hasIdentity())	{
	$user = $this->_auth->getIdentity();
	$inst = $user->institution;
	if(is_null($inst)){
	throw new Pas_Exception_Group($this->_missingGroup);
	}
	return $inst;
	} else {
	return FALSE;
	}
	}

	/** Create the links for images
	 * @access public
	 * @param string $oldfindID
	 * @param string $findID
	 * @param string $secuid
	 * @param integer $createdBy
	 * @return string
	 */
	public function ImageLink($oldfindID, $findID, $secuid, $createdBy) {
	$byID = $this->checkAccessbyUserID($this->getIdentityForForms(),$createdBy);
	$instID = $this->checkAccessbyInstitution($oldfindID);
	if(in_array($this->getRole(),$this->restricted)) {
	if((($byID == TRUE) && ($instID == TRUE)) || (($byID == TRUE) && ($instID == FALSE))) {
	return $this->buildHtml($findID,$secuid);
	}
	} elseif (in_array($this->getRole(),$this->recorders)) {
	if((($byID == true) && ($instID === false)) || (($byID === FALSE) && ($instID === TRUE))
	|| ($byID === true && $instID === true)) {
	return $this->buildHtml($findID,$secuid);
	}
	} else if(in_array($this->getRole(),$this->higherLevel)) {
	return $this->buildHtml($findID,$secuid);
	} else {
	return FALSE;
	}
	}

	/** Build the html
	 * @access public
	 * @param string $findID
	 * @param string $secuid
	 * @return string $string
	 */
	public function buildHtml($findID, $secuid) {
	$url = $this->view->url(array('module' => 'database','controller' => 'images','action' => 'add',
	'id' => $findID, 'findID' => $secuid),null,TRUE);
        $class = 'btn btn-small btn-success';
	$string = '<div class="noprint"><a href="' . $url
	. '" title="Add an image to this find" accesskey="i" class="' . $class .'">Add an image</a></div>';
	return $string;
	}

}
