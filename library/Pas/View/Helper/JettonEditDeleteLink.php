<?php

class Pas_View_Helper_JettonEditDeleteLink extends Zend_View_Helper_Abstract
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
	if($this->_auth->hasIdentity())	{
	$user = $this->_auth->getIdentity();
	$role = $user->role;
	} else {
	$role = 'public';
	}
	return $role;
	}


	public function checkAccessbyInstitution($oldfindID)
	{
	$find = explode('-', $oldfindID);
	$id = $find['0'];
	$inst = $this->getInst();
	if($id == $inst) {
	return true;
	} else if((in_array($this->getRole(),$this->recorders) && ($id == 'PUBLIC'))) {
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

	public function getUserID()
	{
	if($this->_auth->hasIdentity()){
	$user = $this->_auth->getIdentity();
	$id = $user->id;
	return $id;
	}
	}

	public function checkAccessbyUserID($createdBy)
	{
	if($createdBy == $this->getUserID()) {
	return TRUE;
	} else {
	return FALSE;
	}
	}


	public function getUserGroups()
	{
	if($this->_auth->hasIdentity()) {
	$user = $this->_auth->getIdentity();
	$inst = $user->institution;
	return $inst;
	} else {
	return false;
	//throw new Exception($this->_missingGroup);
	}

	}

	public function JettonEditDeleteLink($oldfindID, $id,$broadperiod,$secuid,$returnID,$createdBy)
	{
	$byID = $this->checkAccessbyUserID($createdBy);
	$instID = $this->checkAccessbyInstitution($oldfindID);

	if(in_array($this->getRole(),$this->restricted)) {
	if(($byID == true && $instID == true) || ($byID == TRUE && $instID == FALSE)) {
	return $this->buildHtml($id,$broadperiod,$secuid,$returnID);
	}
	} else if(in_array($this->getRole(),$this->higherLevel)) {
	return $this->buildHtml($id,$broadperiod,$secuid,$returnID);
	} else if (in_array($this->getRole(),$this->recorders)){
	if(($byID == true && $instID == true) || ($byID == false && $instID == true)
	|| ($byID == TRUE && $instID == FALSE)	) {
	return $this->buildHtml($id,$broadperiod,$secuid,$returnID);
	}
	} else {
	return false;
	}
	}

	public function buildHtml($id,$broadperiod,$secuid,$returnID)
	{
        $editClass = 'btn btn-large btn-warning';
        $deleteClass = 'btn btn-large btn-danger';
	$editurl = $this->view->url(array('module' => 'database','controller' => 'jettons','action' => 'edit',
	'broadperiod' => $broadperiod,'findID' => $secuid,'id' => $id,'returnID' => $returnID),null,TRUE);
	$deleteurl = $this->view->url(array('module' => 'database','controller' => 'jettons','action' => 'delete',
	'id' => $id),null,TRUE);
	$string = '<span class="noprint"><p><a class=" ' . $editClass . '" href="' . $editurl
	. '" title="Edit numismatic data for this record">Edit numismatic data</a> | <a href="' .
	$deleteurl . '" title="Delete numismatic data" class=" ' . $deleteClass . '">Delete</a></p></span>';
	return $string;
	}

}
