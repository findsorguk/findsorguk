<?php
/** view helper for editing reference link
 * @category   Pas
 * @package    Pas_View_Helper
 * @subpackage Abstract
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @see Zend_View_Helper_Abstract
 */
class Pas_View_Helper_EditReference
	extends Zend_View_Helper_Abstract {
		
	protected $noaccess = array('public');
	protected $restricted = array('member','research','hero');
	protected $recorders = array('flos');
	protected $higherLevel = array('admin','fa','treasure');
	protected $_missingGroup = 'User is not assigned to a group';
	
	protected $_auth;
	
	public function __construct() { 
    $auth = Zend_Auth::getInstance();
    $this->_auth = $auth; 
    }
	
		
	public function getRole() {
	if($this->_auth->hasIdentity()) {
	$user = $this->_auth->getIdentity();
	$role = $user->role;
	} else {
	$role = 'public';
	}	
	return $role;
	}
	
	public function getUserID() {
	if($this->_auth->hasIdentity()){
	$user = $this->_auth->getIdentity();
	$id = $user->id;
	return $id;
	}
	}	
	
	public function checkAccessbyUserID($createdBy) {
	if($createdBy == $this->getUserID()) {
	return TRUE;
	} else {
	return FALSE;
	}
	}
	
	public function EditReference($i,$fID,$createdBy) {
	$byID = $this->checkAccessbyUserID($createdBy);	
	if(in_array($this->getRole(),$this->noaccess)){
	return FALSE;	
	} else if(in_array($this->getRole(),$this->restricted) && $byID == TRUE){
	return $this->buildHtml($i,$fID);	
	} else if(in_array($this->getRole(),$this->recorders)){
	return $this->buildHtml($i,$fID);	
	} elseif(in_array($this->getRole(),$this->higherLevel)) {
	return	$this->buildHtml($i,$fID);
	} else {
	return FALSE;
	}
	}
	
	public function buildHtml($i,$fID) {
	$html = '';
	$html .= ' <a href="' . $this->view->url(array(
	'module' => 'database',
	'controller' => 'references', 
	'action' => 'edit',
	'id' => $i,
	'findID' => $fID),NULL,TRUE) . '" title="Edit this reference">Edit</a> | <a href="'	
	. $this->view->url(array(
	'module' => 'database',
	'controller' => 'references', 
	'action' => 'delete',
	'id' => $i,
	'findID' => $fID),NULL,TRUE)
	. '" title="Delete this reference">Delete</a>';
	$html .= '.</li>'."\n";
	return $html;
	}
}