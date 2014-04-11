<?php
/** A view helper to print coin edit and delete links
 * @category Pas
 * @package Pas_View
 * @subpackage Helper
 * @version 1
 * @since September 30 2011
 * @copyright Daniel Pett
 * @author Daniel Pett
 * @todo Streamline and DRY the code
 * @license GNU
 */
class Pas_View_Helper_CoinEditDeleteLink
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

    /** Get the user's role
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

	/** Check the user's access to record by institution
	 *
	 * @param string $oldfindID The find ID
	 */
	public function checkAccessbyInstitution($oldfindID) {
	$find = explode('-', $oldfindID);
	$id = $find['0'];
	$inst = $this->getInst();
	if($id == $inst) {
	return true;
	} else if((in_array($this->getRole(),$this->recorders) && ($id == 'PUBLIC'))) {
	return true;
	}
	}

	/** Get institution id
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

	/** Get the user's ID
	 *
	 */
	public function getUserID() {
	if($this->_auth->hasIdentity()){
	$user = $this->_auth->getIdentity();
	$id = $user->id;
	return $id;
	}
	}

	/** Check access by user id
	 *
	 * @param int $createdBy Record created by
	 */
	public function checkAccessbyUserID($createdBy) {
	if($createdBy == $this->getUserID()) {
	return TRUE;
	} else {
	return FALSE;
	}
	}



	/** Call a function to determine whether the user can edit or delete the coin
	 *
	 * @param string $oldfindID The old find id of the record
	 * @param int $id The primary key of the coin entry
	 * @param string $broadperiod The coin's broadperiod
	 * @param string $secuid The find table secuid
	 * @param int $returnID The find primary key to return to record
	 * @param int $createdBy The user id for the creator of record
	 */
	public function CoinEditDeleteLink($oldfindID, $id, $broadperiod, $secuid, $returnID, $createdBy) {
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

	/** Build the html response
	 *
	 * @param int $id The coin table primary key number
	 * @param string $broadperiod The object's broadperiod
	 * @param string $secuid The find table secuid
	 * @param int $returnID The find number to return to
	 */
	public function buildHtml($id, $broadperiod, $secuid, $returnID) {
        $editClass = 'btn btn-small btn-warning';
        $deleteClass = 'btn btn-small btn-danger';
	$editurl = $this->view->url(array('module' => 'database','controller' => 'coins','action' => 'edit',
	'broadperiod' => $broadperiod,'findID' => $secuid,'id' => $id,'returnID' => $returnID),null,TRUE);
	$deleteurl = $this->view->url(array('module' => 'database','controller' => 'coins','action' => 'delete',
	'id' => $id),null,TRUE);
	$string = '<span class="noprint"><p><a class="' . $editClass . '" href="' . $editurl
	. '" title="Edit numismatic data for this record">Edit numismatic data <i class="icon-white icon-edit"></i></a> <a class="' . $deleteClass . '" href="' .
	$deleteurl . '" title="Delete numismatic data">Delete <i class="icon-white icon-trash"></i></a></p></span>';
	return $string;
	}

}
