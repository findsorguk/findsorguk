<?php 

	class Pas_View_Helper_DataEditDeleteCheck extends Zend_View_Helper_Abstract
	{
	protected $noaccess = array('public');
	protected $restricted = array('member','research','hero');
	protected $recorders = array('flos');
	protected $higherLevel = array('admin','fa','treasure');
	protected $_missingGroup = 'User is not assigned to a group';
	protected $_message = 'You are not allowed edit rights to this record';
	protected $_auth = NULL;
	
	public function __construct()
    { 
    	$auth = Zend_Auth::getInstance();
        $this->_auth = $auth; 
    }
	
	public function getRole()
	{
	if($this->_auth->hasIdentity()) {
	$user = $this->_auth->getIdentity();
	$role = $user->role;
	} else {
	$role = 'public';
	}	
	return $role;
	}
	
	public function getIdentityForForms()
	{
	if($this->_auth->hasIdentity()){
	$user = $this->_auth->getIdentity();
	$id = $user->id;
	return $id;
	} else {
	$id = '3';
	return $id;
	}
	}
	
	public function getUserID()
	{
	if($this->_auth->hasIdentity())
	{
	$user = $this->_auth->getIdentity();
	$id = $user->id;
	return $id;
	}
	}
	public function checkAccessbyUserID($createdBy)
	{
	if(!in_array($this->getRole(),$this->restricted)) {
	return true;
	} else if(in_array($this->getRole(),$this->restricted)) {
	if($createdBy == $this->getUserID()) {
	return true;
	}
	} else {
	return false;
	}
	}
	public function checkAccessbyInstitution($oldfindID)
	{
	$find = explode('-', $oldfindID);
	$id = $find['0'];
	$inst = $this->getInst();
	if($id == $inst) {
	return true;
	}
	}
	
	public function getInst()
	{
	if($this->_auth->hasIdentity())	{
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
	
	public function DataEditDeleteCheck($oldfindID,$createdBy)
	{
	$byID = $this->checkAccessbyUserID($createdBy);
	$instID = $this->checkAccessbyInstitution($oldfindID);
	if(in_array($this->getRole(),$this->restricted)) {
	if($createdBy == $this->getIdentityForForms()) {
	if(($byID == true && $instID == true) || ($byID == true  && $instID == FALSE)) {
	return true;
	} else {
	throw new Pas_Exception_NotAuthorised($this->_message);
	}
	} else if(in_array($this->getRole(),$this->higherLevel)) {
	return true;
	} else if (in_array($this->getRole(),$this->recorders)){
	if(($byID == true && $instID == true) || ($byID == false && $instID == true) ||
	($byID == true && $instID == false)) {
	return true;
	} else {
	throw new Pas_Exception_NotAuthorised($this->_message);
	}
	} else {
	throw new Pas_Exception_NotAuthorised($this->_message);
	}
	}
	}
}