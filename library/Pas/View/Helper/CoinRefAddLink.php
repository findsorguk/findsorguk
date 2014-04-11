<?php
/** A view helper for creating coin reference add link
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
class Pas_View_Helper_CoinRefAddLink
	extends Zend_View_Helper_Abstract {

	protected $__noaccess = array('public');
	protected $_restricted = array('member','research','hero');
	protected $_recorders = array('flos');
	protected $_higherLevel = array('admin','fa','treasure');

	protected $_numismatics = array('COIN');

    protected $_objects = array('JETTON', 'TOKEN');

    protected $_broadperiods = array('IRON AGE', 'ROMAN', 'BYZANTINE',
        'EARLY MEDIEVAL', 'GREEK AND ROMAN PROVINCIAL', 'MEDIEVAL', 'POST MEDIEVAL',
        'MODERN');
	
	
	protected $_auth = NULL;

	/** Construct the auth object
	 */
	public function __construct(){
    $auth = Zend_Auth::getInstance();
    $this->_auth = $auth;
    }
	/** Get the user's role
	*/
	public function getRole() {
	if($this->_auth->hasIdentity()) {
	$user = $this->_auth->getIdentity();
	$role = $user->role;
	return $role;
	}
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

	/** Get the user's institution
	*/
	public function getInst() {
	if($this->_auth->hasIdentity()) {
	$user = $this->_auth->getIdentity();
	$inst = $user->institution;
	return $inst;
	}
	}

	/** Check access by userid
	* @param int $createdBy
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
	/** Check access by institution
	* @param string $oldfindID The find id number
	*/
	public function checkAccessbyInstitution($oldfindID) {
	$find = explode('-', $oldfindID);
	$id = $find['0'];
	$inst = $this->getInst();
	if((in_array($this->getRole(),$this->_recorders) && ($id == 'PUBLIC'))) {
	return TRUE;
	} else if($id == $inst) {
	return TRUE;
	}
	}

	/** Create the links
	 *
	 * @param $oldfindID
	 * @param $id
	 * @param $broadperiod
	 * @param $secuid
	 * @param $returnID
	 * @param $createdBy
	 */
	public function CoinRefAddLink($oldfindID, $id, $broadperiod, $secuid, $returnID, $createdBy) {
	$byID = $this->checkAccessbyUserID($createdBy);
	$instID = $this->checkAccessbyInstitution($oldfindID);
	if(in_array($this->getRole(),$this->_restricted)) {
	if(($byID == TRUE) && ($instID == TRUE) || ($byID == TRUE && $instID == FALSE)) {
	return $this->buildHtml($id,$broadperiod,$secuid,$returnID);
	}
	} else if(in_array($this->getRole(),$this->_higherLevel)) {
	return $this->buildHtml($id,$broadperiod,$secuid,$returnID);
	} else if (in_array($this->getRole(),$this->_recorders)){
	if(($byID == TRUE && $instID == TRUE) || ($byID == FALSE && $instID == TRUE)
	|| ($byID == TRUE && $instID == FALSE)) {
	return $this->buildHtml($id,$broadperiod,$secuid,$returnID);
	}
	} else {
	return FALSE;
	}
	}

	/** Build and return the Html]
	 * @param $id
	 * @param $broadperiod
	 * @param $secuid
	 * @param $returnID
	 */
	public function buildHtml($id,$broadperiod,$secuid,$returnID) {
	$url = $this->view->url(array('module' => 'database','controller' => 'coins',
	'action' => 'coinref','findID' => $secuid,'returnID' => $returnID),null,true);
	$string = '<div id="coinrefs" class="noprint"><a class="btn btn-small btn-primary" href="'. $url
	. '" title="Add a reference for this coin">Add a coin reference</a></div>';
	return $string;
	}

}
