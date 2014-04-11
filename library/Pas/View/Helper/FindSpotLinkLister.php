<?php
/**
 * This class is to help display findspot links for edit or delete
 * @category   Pas
 * @package    Pas_View_Helper
 * @subpackage Abstract
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @uses Zend_View_Helper_Abstract
 * @author Daniel Pett
 * @since September 13 2011
*/
class Pas_View_Helper_FindSpotLinkLister extends Zend_View_Helper_Abstract {
	
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
	* 
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
	
	/** Get the user's identity
	 * @access public
	 * @return integer user id
	 */
	public function getIdentityForForms() {
	if($this->_auth->hasIdentity()) {
	$user = $this->_auth->getIdentity();
	$id = $user->id;
	return $id;
	} else {
	$id = '3';
	return $id;
	}
	}
	
	/** Check for access by institution from find id
	 * @access public
	 * @param string $oldfindID
	 * @return boolean
	 */
	public function checkAccessbyInstitution($oldfindID) {
	$find = explode('-', $oldfindID);
	$id = $find['0'];
	$inst = $this->getInst();
	if((in_array($this->getRole(),$this->recorders) && ($id === 'PUBLIC'))) {
	return true;
	} else if($id == $inst) {
	return true;
	}
	}
	/** Check for access by user's ID number
	 * 
	 * @param integer $userID
	 * @param integer $createdBy
	 * @access public
	 * @return boolean
	 */
	public function checkAccessbyUserID($userID,$createdBy){
	if($userID === $createdBy) {
	return true;
	}
	}

	/** Get a user's institution
	 * @access public
	 * @return string $inst
	 */
	public function getInst() {
	if($this->_auth->hasIdentity()) {
	$user = $this->_auth->getIdentity();
	$inst = $user->institution;
	if(is_null($inst)){
	throw new Exception($this->_missingGroup);	
	}
	return $inst;
	} else {
	return FALSE;
	}	
	}
	
	/** Run the function to create the links
	 * 
	 * @param string $oldfindID
	 * @param string $findID
	 * @param string $secuid
	 * @param integer $createdBy
	 * @access public
	 */
	public function FindSpotLinkLister($oldfindID,$findID,$secuid,$createdBy) {
	$byID = $this->checkAccessbyUserID($this->getIdentityForForms(),$createdBy);
	$instID = $this->checkAccessbyInstitution($oldfindID);
	if(in_array($this->getRole(),$this->restricted)) {
	if((($byID == true) && ($instID == true)) || (($byID == true) && ($instID == false))) {
	return $this->buildHtml($findID,$secuid);	
	} 
	} else if(in_array($this->getRole(),$this->higherLevel)) {
	return $this->buildHtml($findID,$secuid);
	} else if(in_array($this->getRole(),$this->recorders)) {
	if((($byID == true) && ($instID == false)) || (($byID == false) && ($instID == true) 
	|| ($byID == true && $instID == true))) {
	return $this->buildHtml($findID,$secuid);	
	} 
	} else {
	return false;
	}
	}
	
	/** Build the html for displaying the links
	 * @access public
	 * @param string $findID
	 * @param string $secuid
	 * @return string $string
	 * @uses Zend_View_Helper_Url
	 */
	public function buildHtml($findID,$secuid) {
	$url = $this->view->url(array(
	'module' => 'database', 
	'controller' => 'findspots',
	'action' => 'add',
	'id' => $findID, 
	'secuid' => $secuid)
	,null,
	true);
	$string = '<a href="' . $url .'" title="Add spatial details for this find">Add a findspot</a>';
	return $string;
	}

}
