<?php
/** A view helper for creating coin reference edit and delete links
 * @category Pas
 * @package Pas_View
 * @subpackage Helper
 * @version 1
 * @since September 30 2011
 * @copyright DEJ Pett
 * @license GNU
 * @todo DRY the code
 * @author Daniel Pett
 */
class Pas_View_Helper_CoinRefEditDeleteLink 
	extends Zend_View_Helper_Abstract {

	protected $_noaccess = array('public');
	protected $_restricted = array('member','research','hero');
	protected $_recorders = array('flos');
	protected $_higherLevel = array('admin','fa','treasure');
	protected $_auth;

	/** Construct the auth object
	 */
	public function __construct() { 
    $auth = Zend_Auth::getInstance();
    $this->_auth = $auth; 
    }
    
    /** Get the user's role
     */
	public function getRole() {
	if($this->_auth->hasIdentity()){
	$user = $this->_auth->getIdentity();
	$role = $user->role;
	} else {
	$role = 'public';
	}	
	return $role;
	}

	/** Get the user's ID
	*/
	public function getUserID() {
	if($this->_auth->hasIdentity()) {
	$user = $this->_auth->getIdentity();
	$id = $user->id;
	return $id;
	}
	}


	/** Check institution by find id
	 * @param string $oldfindID
	 */
	public function checkAccessbyInstitution($oldfindID) {
	$find = explode('-', $oldfindID);
	$id = $find['0'];
	$inst = $this->getUserGroups();
	if(($id == $inst)) {
	return true;
	} else if((in_array($this->getRole(),$this->recorders) && ($id == 'PUBLIC'))) {
	return true;
	}
	}
	
	/** Find out user group for person
	*/	
	public function getUserGroups() {
	if($this->_auth->hasIdentity()) {
	$user = $this->_auth->getIdentity();
	$inst = $user->institution;
	} else {
	throw new Exception('User is not assigned to a group');
	}	
	return $inst;
	}
	
	/** Check access by userID
	* @param int $createdby
	*/
	public function checkAccessbyUserID($createdBy) {
	if(!in_array($this->getRole(),$this->_restricted)) {
	return true;
	} else if(in_array($this->getRole(),$this->_restricted)) {
	if($createdBy == $this->getUserID()) {
	return true;
	}
	} else {
	return false;
	}
	}

	/** Create the links
	*/
	public function CoinRefEditDeleteLink($oldfindID,$id,$returnID,$createdBy) {
	if(in_array($this->getRole(),$this->_noaccess)) {
	return false;
	}
	else if(in_array($this->getRole(),$this->_restricted)) {
	$byID = $this->checkAccessbyUserID($createdBy);
	$instID = $this->checkAccessbyInstitution($oldfindID);
	if(($byID == true)) {
	return $this->buildHtml($id,$returnID);
	}
	}
	else if(in_array($this->getRole(),$this->_higherLevel)) {
	return $this->buildHtml($id,$returnID);
	} else if(in_array($this->getRole(),$this->_recorders)) {
	$find = explode('-', $oldfindID);
	$id = $find['0'];
	$byID = $this->checkAccessbyUserID($createdBy);
	$instID = $this->checkAccessbyInstitution($oldfindID);

	$id = $find['0'];
	if(($id == $instID) || ($byID == true && $instID == true)) {
	return $this->buildHtml($id,$returnID);
	}
	else if($id == 'PUBLIC') {
	return $this->buildHtml($id,$returnID);
	}
	}
	}
	
	/** Return the HTML
	 * @param int $id
	 * @param int $returnID
	 */
	public function buildHtml($id,$returnID) {
	$string = '<a href="'
	.$this->view->url(array('module' => 'database','controller' => 'coins','action' => 'editcoinref','id' => $id,'returnID' => $returnID),null,true)
	.'" title="Edit this coin reference">Edit</a> | <a href="'
	.$this->view->url(array('module' => 'database','controller' => 'coins','action' => 'deletecoinref','id' => $id,'returnID' => $returnID),null,true)
	.'" title="Delete this reference">Delete</a>';
	return $string;
	}

}
