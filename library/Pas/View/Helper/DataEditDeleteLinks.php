<?php
/**
 * This class is to help display  links for edit or delete on finds view
 * @category   Pas
 * @package    Pas_View_Helper
 * @subpackage Abstract
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @uses Zend_View_Helper_Abstract
 * @author Daniel Pett
 * @since September 13 2011
*/
class Pas_View_Helper_DataEditDeleteLinks extends Zend_View_Helper_Abstract {
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

	/** Get the user's id number
	* @access public
	* @return integer $id
	*/
	public function getUserID() {
	if($this->_auth->hasIdentity()) {
	$user = $this->_auth->getIdentity();
	$id = $user->id;
	return $id;
	}
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

	/** Check access by the user's ID number
	 * @access public
	 * @param integer $createdBy
	 * @return boolean
	 */
	public function checkAccessbyUserID($createdBy) {
	if($createdBy === $this->getUserID()) {
	return TRUE;
	} else {
	return FALSE;
	}
	}

	/** Check access by institution ID
	 * @access public
	 * @param string $oldfindID
	 * @return boolean
	 */
	public function checkAccessbyInstitution($oldfindID) {
	$find = explode('-', $oldfindID);
	$id = $find['0'];
	$inst = $this->getInst();
	if((in_array($this->getRole(),$this->recorders) && ($id == 'PUBLIC'))) {
	return TRUE;
	} else if($id == $inst) {
	return TRUE;
	}
	}

	/** Get a user's institution
	 * @todo this repeats too much
	 * @access public
	 * @return string $inst
	 * @throws Pas_Exception_Group
	 */
	public function getInst() {
	if($this->_auth->hasIdentity())	{
	$user = $this->_auth->getIdentity();
	$inst = $user->institution;
	if(is_null($inst)){
	throw new Pas_Exception_Group($this->_missingGroup);
	}
	return $inst;
	} else {
	return FALSE;
	}
	}

	/** Build html for display
	 * @access public
	 * @param string $findID
	 * @param string $oldfindID
	 * @return string
	 */
	public function buildHtml($findID, $oldfindID) {
	$editurl = $this->view->url(array('module' => 'database','controller' => 'artefacts','action' => 'edit',
	'id' => $findID),null,true);
	$deleteurl = $this->view->url(array('module' => 'database','controller' => 'artefacts',
	'action' => 'delete','id' => $findID),null,true);
        $editClass = 'btn btn-large btn-warning';
        $deleteClass = 'btn btn-large btn-danger';
	$html = '<a class="' . $editClass . '" href="';
	$html .= $editurl;
	$html .= '" title="Edit details for ';
	$html .= $oldfindID;
	$html .= '">Edit</a> | <a class="' . $deleteClass .'" href="';
	$html .= $deleteurl;
	$html .= '" title="Delete record ';
	$html .= $oldfindID;
	$html .= '">';
	$html .= 'Delete</a><br />';
	return $html;
	}

	/** Start the process to determine whether links can be used
	 * @access public
	 * @param string $findID
	 * @param string $oldfindID
	 * @param integer $createdBy
	 */
	public function DataEditDeleteLinks($findID, $oldfindID, $createdBy) {
	$byID = $this->checkAccessbyUserID($createdBy);
	$instID = $this->checkAccessbyInstitution($oldfindID);
	if(in_array($this->getRole(),$this->noaccess)) {
	return FALSE;
	} else if(in_array($this->getRole(),$this->restricted)) {
	if(($byID == TRUE && $instID == TRUE) || ($byID == TRUE && $instID == FALSE)){
	return $this->buildHtml($findID,$oldfindID);
	} else {
	return FALSE;
	}
	} else if(in_array($this->getRole(),$this->recorders)) {
	if(($byID == TRUE && $instID == TRUE) || ($byID == TRUE && $instID == FALSE) ||
	($byID == FALSE && $instID == TRUE)){
	return $this->buildHtml($findID,$oldfindID);
	} else {
	return FALSE;
	}
	} else if (in_array($this->getRole(),$this->higherLevel)){
	return $this->buildHtml($findID,$oldfindID);
	} else {
 	return FALSE;
 	}
	}

}