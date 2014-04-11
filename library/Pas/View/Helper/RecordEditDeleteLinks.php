<?php
/**
 * A view helper for printing links on image page
 * @category   Pas
 * @package    Pas_View_Helper
 * @subpackage Abstract
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @see Zend_View_Helper_Abstract
 * @uses Zend_View_Helper_Url
 * @author Daniel Pett
 */
class Pas_View_Helper_RecordEditDeleteLinks extends Zend_View_Helper_Abstract {

	protected $noaccess = array('public');
	protected $restricted = array('member','research','hero');
	protected $recorders = array('flos');
	protected $higherLevel = array('admin','fa','treasure');
	protected $_auth = NULL;
	protected $_missingGroup = 'User is not assigned to a group';
	protected $_message = 'You are not allowed edit rights to this record';

	/** Constructor for authorisation
	* @access private
	*/
	public function __construct()  {
	$auth = Zend_Registry::get('auth');
	$this->_auth = $auth;
	}

	/** Get the user's role from identity
	 * @access private
	 * @return string $role The user's role
	 */
	private function getRole() {
	if($this->_auth->hasIdentity()) {
	$user = $this->_auth->getIdentity();
	$role = $user->role;
	return $role;
	}
	}

	/** get the user's identity number
	 * @access private
	 * @return integer $id The user's id number
	 */
	private function getUserID() {
	if($this->_auth->hasIdentity()) {
	$user = $this->_auth->getIdentity();
	$id = $user->id;
	return $id;
	}
	}

	/** Get the user's institution
	 * @access private
	 * @return string $inst The institution name
	 * @throws Pas_Exception_Group
	 */
	private function getInst() {
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

	/** Check the user's access by ID number and created by
	 * @access private
	 * @param integer $createdBy the created by number for the find
	 * @return boolean
	 */
	private function checkAccessbyUserID($createdBy) {
	if($createdBy === $this->getUserID()) {
	return TRUE;
	} else {
	return FALSE;
	}
	}

	/** Check access by the institutional ID
	 * @access private
	 * @param string $oldfindID The record ID
	 * @return boolean
	 */
	private function checkAccessbyInstitution($oldfindID) {
	$find = explode('-', $oldfindID);
	$id = $find['0'];
	$inst = $this->getInst();
	if((in_array($this->getRole(),$this->recorders) && ($id == 'PUBLIC'))) {
	return TRUE;
	} else if($id === $inst) {
	return TRUE;
	}
	}

	/** Return the HTML links
	 * @access private
	 * @param integer $findID The find number from primary key of finds table
	 * @param string $oldfindID The old find ID number
	 * @return string $html The code for the strings of links
	 */
	private function buildHtml($findID, $oldfindID) {
        $class = 'btn btn-small btn-warning';
        $classDanger = 'btn btn-small btn-danger';
	$editurl = $this->view->url(array('module' => 'database', 'controller' => 'artefacts', 'action' => 'edit',
	'id' => $findID),null,true);
	$deleteurl = $this->view->url(array('module' => 'database', 'controller' => 'artefacts', 'action' => 'delete',
	'id' => $findID),null,true);
	$html = ' <a class="' . $class . '" href="';
	$html .= $editurl;
	$html .= '" title="Edit details for ';
	$html .= $oldfindID;
	$html .= '" accesskey="e">Edit <i class="icon-white icon-edit"></i></a> <a class="' . $classDanger . '" href="';
	$html .= $deleteurl;
	$html .= '" title="Delete record ';
	$html .= $oldfindID;
	$html .= '" accesskey="d">';
	$html .= 'Delete <i class="icon-white icon-trash"></i></a>';
	return $html;
	}

	/** Create the links
	 * @access public
	 * @param integer $findID The find number
	 * @param string $oldfindID The find string
	 * @param integer $createdBy Created by number
	 * @return string
	 */
	public function RecordEditDeleteLinks($findID, $oldfindID ,$createdBy) {
	$byID = $this->checkAccessbyUserID($createdBy);
	$instID = $this->checkAccessbyInstitution($oldfindID);
	if(in_array($this->getRole(),$this->noaccess)){
	return FALSE;
	} else if(in_array($this->getRole(),$this->restricted)) {
	if(($byID == TRUE && $instID == TRUE) || ($byID == TRUE && $instID == FALSE)) {
	return $this->buildHtml($findID,$oldfindID);
	}
	} else if(in_array($this->getRole(),$this->recorders)){
	if(($byID == true && $instID == true) || ($byID == true && $instID == FALSE)
	|| ($byID == FALSE && $instID == true)){
	return $this->buildHtml($findID,$oldfindID);
	} else {
	return FALSE;
	}
	} else if(in_array($this->getRole(),$this->higherLevel)) {
	return $this->buildHtml($findID,$oldfindID);
	}
	}
}