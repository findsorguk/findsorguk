<?php
/**
 * A view helper for determining which findspot partial to display to the user
 * 
 * @category   Pas
 * @package    Pas_View_Helper
 * @subpackage Abstract
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @see Zend_View_Helper_Abstract
 * @todo this class can be cut substantially for the user object to come from just one call
 */

class Pas_View_Helper_Findspot extends Zend_View_Helper_Abstract {
	protected $noaccess = array('public');
	protected $restricted = array('member');
	protected $recorders = array('flos','research','hero');
	protected $higherLevel = array('admin','fa','treasure');
	protected $_missingGroup = 'User is not assigned to a group';
	protected $_message = 'You are not allowed edit rights to this record';
	/** Create the variable for the authorisation object 
	 * 
	 * @var object $_auth;
	 */
	protected $_auth = NULL;
	
	/** Construct the authorisation; 
	 * 
	 */
	public function __construct() { 
	$auth = Zend_Auth::getInstance();
	$this->_auth = $auth; 
	}
    
	/** Find out the user's role
     * 
     */
	public function getRole() {
	if($this->_auth->hasIdentity())	{
	$user = $this->_auth->getIdentity();
	$role = $user->role;
	} else {
	$role = 'public';
	}	
	return $role;
	}
	
	/** Find out whether the user has access from their institutional ID
	 * 
	 * @param string $oldfindID
	 */
	public function checkAccessbyInstitution($oldfindID) {
	$find = explode('-', $oldfindID);
	$id = $find['0'];
	$inst = $this->getInst();
	if($id === $inst) {
	return true;
	} else if((in_array($this->getRole(),$this->recorders) && ($id == 'PUBLIC'))) {
	return true;
	}
	}

	/** Retrieve a user's institution
	 * 
	 */
	public function getInst() {
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
	
	/** Find a user's ID
	 * 
	 */
	public function getUserID() {
	if($this->_auth->hasIdentity()){
	$user = $this->_auth->getIdentity();
	$id = $user->id;
	return $id;
	}
	}
	
	/** Check if the user has access by their userid number
	 * 
	 * @param int $createdBy
	 */
	public function checkAccessbyUserID($createdBy) {
	if(!in_array($this->getRole(),$this->restricted)) {
	return TRUE;
	} else if(in_array($this->getRole(),$this->restricted)) {
	if($createdBy === $this->getUserID()) {
	return true;
	}
	} else {
	return false;
	}
	}

	/** Find out what groups a user is in from auth object
	 * 
	 */
	public function getUserGroups()
	{
	if($this->_auth->hasIdentity()) {
	$user = $this->_auth->getIdentity();
	$inst = $user->institution;
	return $inst;
	} else {
	return false;
	}	
	}
	
	/** Build the html and return the correct partial 
	 * 
	 * @param array $findspots
	 */
	public function buildHtml($findspots) {
	$byID = $this->checkAccessbyUserID($findspots['0']['createdBy']);
	if(in_array($this->getRole(),$this->restricted)) {
	if($byID == TRUE) {
	return $this->view->partial('partials/database/findspot.phtml', $findspots[0]);
	} else {
	return $this->view->partial('partials/database/unauthorisedfindspot.phtml', $findspots[0]);	
	}
	} else if(in_array($this->getRole(),$this->higherLevel)) {
	return $this->view->partial('partials/database/findspot.phtml', $findspots[0]);
	} else if (in_array($this->getRole(),$this->recorders)){
	return $this->view->partial('partials/database/findspot.phtml', $findspots[0]);
	} else {
	return $this->view->partial('partials/database/unauthorisedfindspot.phtml', $findspots[0]);
	}	
	}
	
	/** make the call to get the findspots html
	 * 
	 * @param array $findspots
	 */
	public function findspot($findspots) {
	return $this->buildHtml($findspots);	
	}
	
	
	
	
}