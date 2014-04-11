<?php
/** A view helper for adding references for publications
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
class Pas_View_Helper_AddRefLink
	extends Zend_View_Helper_Abstract {

	protected $noaccess = array('public');
	protected $restricted = array('member','research','hero');
	protected $recorders = array('flos');
	protected $higherLevel = array('admin','fa','treasure');
	protected $_auth = NULL;
	protected $_missingGroup = 'User is not assigned to a group';
	protected $_message = 'You are not allowed edit rights to this record';

	/** Construct the auth object
	*/
	public function __construct() {
    $auth = Zend_Auth::getInstance();
    $this->_auth = $auth;
    }

	/** Get the user's role
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

	/** Get the userid
	*/
	public function getUserID() {
	if($this->_auth->hasIdentity()) {
	$user = $this->_auth->getIdentity();
	$id = $user->id;
	return $id;
	}
	}
	/** Get the userid
	*/
	public function getIdentityForForms(){
	if($this->_auth->hasIdentity()) {
	$user = $this->_auth->getIdentity();
	$id = $user->id;
	return $id;
	} else {
	$id = '3';
	return $id;
	}
	}
	/** Check access by institution
	* @return boolean
	* @param string $oldfindID
	*/
	public function checkAccessbyInstitution($oldfindID)
	{
	$find = explode('-', $oldfindID);
	$id = $find['0'];
	$inst = $this->getUserGroups();
	if(($id == $inst)) {
	return TRUE;
	} else if((in_array($this->getRole(),$this->recorders) && ($id == 'PUBLIC'))) {
	return TRUE;
	} else {
	return FALSE;
	}
	}

	/** Get the user's group
	 * @return boolean
	 */
	public function getUserGroups() {
	if($this->_auth->hasIdentity()) {
	$user = $this->_auth->getIdentity();
	$inst = $user->institution;
	return $inst;
	} else {
	return FALSE;
	}
	}

	/** Check access by userid
	 * @return boolean
	 * @param int $createdBy
	 */
	public function checkAccessbyUserID($createdBy) {
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

	/** Add the reference link
	 *
	 * @param $oldfindID
	 * @param $findID
	 * @param $secuid
	 * @param $createdBy
	 */
	public function AddRefLink($oldfindID,$findID, $secuid, $createdBy) {
	$byID = $this->checkAccessbyUserID($createdBy);
	$instID = $this->checkAccessbyInstitution($oldfindID);
	if(in_array($this->getRole(),$this->noaccess)) {
	return false;
	} else if(in_array($this->getRole(),$this->restricted)) {
	if(($byID == TRUE && $instID == TRUE) || ($byID == TRUE && $instID == FALSE)) {
	return $this->buildHtml($findID,$secuid);
	}
	} else if(in_array($this->getRole(),$this->higherLevel)) {
	return $this->buildHtml($findID,$secuid);
	} else if(in_array($this->getRole(),$this->recorders)) {
	if((($byID == TRUE) && ($instID == FALSE)) || (($byID == FALSE) && ($instID == TRUE))
	|| ($byID == TRUE && $instID == TRUE)) {
	return $this->buildHtml($findID,$secuid);
	}
	}
	}

	/** Build the html
	 *
	 * @param string $findID
	 * @param string $secuid
	 */
	public function buildHtml($findID, $secuid) {
	$url = $this->view->url(array('module' => 'database','controller' => 'references','action' => 'add',
	'findID' => $findID,'secID' => $secuid), null, true);
	$string = '<div id="addref" class="noprint"><a class="btn btn-small btn-primary" href="' . $url
	. '" title="Add a reference" accesskey="r">Add a reference</a></div>';

	return $string;
	}

}
