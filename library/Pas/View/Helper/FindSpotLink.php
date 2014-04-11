<?php
/** A view helper for determining whether findspot link should be printed 
 * @category Pas
 * @package Pas_View_Helper
 * @todo streamline code
 * @todo extend the view helper for auth and config objects
 * @copyright DEJ Pett
 * @license GNU
 * @version 1
 * @since 29 September 2011
 * @author dpett
 */

class Pas_View_Helper_FindSpotLink 
	extends Zend_View_Helper_Abstract {
	
	protected $noaccess = array('public');
	protected $restricted = array('member','research','hero');
	protected $recorders = array('flos');
	protected $higherLevel = array('admin','fa','treasure');
	protected $_missingGroup = 'User is not assigned to a group';
	protected $_message = 'You are not allowed edit rights to this record';
	protected $_auth = NULL;

	/** Construct the auth object
	*/
	public function __construct() { 
    $auth = Zend_Auth::getInstance();
    $this->_auth = $auth; 
    }
    
	/** Construct the user role
	*/
	public function getRole(){
	if($this->_auth->hasIdentity()) {
	$user = $this->_auth->getIdentity();
	$role = $user->role;
	} else {
	$role = 'public';
	}	
	return $role;
	}
	
	
	/** Get the user's id
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
	
	/** Check the user's access by institution
	 * 
	 * @param string $oldfindID
	 */
	public function checkAccessbyInstitution($oldfindID) {
	$find = explode('-', $oldfindID);
	$id = $find['0'];
	$inst = $this->getInst();
	if((in_array($this->getRole(),$this->recorders) && ($id == 'PUBLIC'))) {
	return true;
	} else if($id === $inst) {
	return true;
	}
	}

	/** Check access by user id
	 * 
	 * @param int $userID
	 * @param int $createdBy
	 */
	public function checkAccessbyUserID($userID,$createdBy) {
	if($userID === $createdBy) {
	return true;
	}
	}

	/** Check for inst
	 * 
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
	
	/** Determine whether link can be displayed
	 * 
	 * @param string $oldfindID
	 * @param int $findID
	 * @param string $secuid
	 * @param int $createdBy
	 */
	public function FindSpotLink($oldfindID, $findID, $secuid, $createdBy) {
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
	
	/** Build the html
	 * 
	 * @param int $findID
	 * @param string $secuid
	 */
	public function buildHtml($findID, $secuid) {
	$url = $this->view->url(array('module' => 'database', 'controller' => 'findspots','action' => 'add',
	'id' => $findID, 'secuid' => $secuid),null,true);
	$string = '<a class="btn btn-success" href="' . $url
	.'" title="Add spatial details for this find" accesskey="f">Add a findspot</a>';
	return $string;
	}

}
