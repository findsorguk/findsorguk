<?php
/**
 * A view helper for displaying image edit and delete links
 * 
 * @category   Pas
 * @package    View_Helper
 * @subpackage Abstract
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @see Zend_View_Helper_Abstract
 */

class Pas_View_Helper_ImageEditDeleteLink extends Zend_View_Helper_Abstract {

	protected $noaccess = array('public');
	protected $restricted = array('member','research','hero');
	protected $recorders = array('flos');
	protected $higherLevel = array('admin','fa','treasure');
	protected $_missingGroup = 'User is not assigned to a group';
	
	protected $_auth = NULL;

	/** Constructer
	 * 
	 */
	public function __construct() { 
	$auth = Zend_Auth::getInstance();
	$this->_auth = $auth; 
    }
	
	/** Get the role of the user
	 * @access public
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
	
	/** Get the user id
	 * 
	 */
	public function getUserID() {
	if($this->_auth->hasIdentity()){
	$user = $this->_auth->getIdentity();
	$id = $user->id;
	return $id;
	}
	}	
	
	/** Check access by user number
	 * 
	 * @param string $createdBy
	 */
	public function checkAccessbyUserID($createdBy) {
	if($createdBy === $this->getUserID()) {
	return TRUE;
	} else {
	return FALSE;
	}
	}
	
	/** Build html
	 * 
	 * @param integer $id
	 * @param integer $returnID
	 * @param string $secuid
	 */
	public function buildHtml($id, $returnID, $secuid) {
	$unlink = $this->view->url(array('module' => 'database','controller' => 'images',
	'action' => 'unlink',
	'id' => $returnID,'returnID' => $id, 'secuid' => $secuid),null,true);
	$string = ' <a class="btn btn-small" href="' . $unlink . '" title="Unlink this image">Unlink</a>';
	return $string;
	}
	
	/** Build the links for editing and deleting
	 * @access public
	 * @param integer $id
	 * @param string $returnID
	 * @param string $secuid
	 * @param date $createdBy
	 * @return string
	 */
	public function ImageEditDeleteLink($id,$returnID,$secuid,$createdBy){
	$byID = $this->checkAccessbyUserID($createdBy);	
	if(in_array($this->getRole(),$this->noaccess)){
	return FALSE;	
	} else if(in_array($this->getRole(),$this->restricted) && $byID == TRUE){
	return $this->buildHtml($id,$returnID,$secuid);	
	} else if(in_array($this->getRole(),$this->recorders)){
	return $this->buildHtml($id,$returnID,$secuid);	
	} elseif(in_array($this->getRole(),$this->higherLevel)) {
	return	$this->buildHtml($id,$returnID,$secuid);
	} else {
	return FALSE;
	}
	}
}
