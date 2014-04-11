<?php
class Pas_User_Details {

	protected $_auth;

	public function __construct(){
	$this->_auth = Zend_Auth::getInstance();
	}

	public function getIdentityForForms() {
	if($this->_auth->hasIdentity()) {
	$user = $this->_auth->getIdentity();
	$id = $user->id;
	} else {
	$id = '3';
	}
	return $id;
	}

	public function getPerson(){
	if($this->_auth->hasIdentity()) {
	return $this->_auth->getIdentity();
	} else {
	return false;
	}
	}
}