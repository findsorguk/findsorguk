<?php
/** A view helper for image link toolbox generation
 *
 * @author dpett
 *
 */
class Pas_View_Helper_ImageToolbox
		extends Zend_View_Helper_Abstract {

	protected $_noaccess = array('public', NULL);

	protected $_restricted = array('member','research','hero');

	protected $_recorders = array('flos');

	protected $_higherLevel = array('admin','fa','treasure');

	protected $_overRide = 'PUBLIC';

	protected $_id;

	protected $_institution;

	protected $_createdBy;

	protected $_canCreate;

	protected function _getUser()
	{
		$person = new Pas_User_Details();
		return $person->getPerson();
	}

	protected function _checkInstitution(){
		if($this->_institution === $this->_getUser()->institution){
			return true;
		} else {
			return false;
		}
	}


	protected function _checkCreator( )
	{
		if($this->_createdBy === $this->_getUser()->id){
			return true;
		} else {
			return false;
		}
	}

	public function setID( $id ){
		$this->_id = $id;
		return $this;
	}

	public function setInstitution( $institution )
	{
		$this->_institution = $institution;
		return $this;
	}

	public function setCreatedBy( $createdBy )
	{
		$this->_createdBy = $createdBy;
		return $this;
	}

	/** Build the html based on the id number of image
	 * @return string $string
	 * @param int $id
	 */
	public function _buildHtml() {
		$this->_checkParameters();
		$this->_performChecks();
		if($this->_canCreate){
			$paramsEdit = array(
				'module' => 'database',
				'controller' => 'images',
				'action' => 'edit',
				'id' => $this->_id
			);
			$paramsDelete = array(
				'module' => 'database',
				'controller' => 'images',
				'action' => 'delete',
				'id' => $this->_id
			);
			$editurl = $this->view->url($paramsEdit, 'default' ,TRUE);
			$deleteurl = $this->view->url($paramsDelete, 'default', TRUE);
			$html = ' <a class="btn btn-success" href="' . $editurl;
			$html .= '" title="Edit image">Edit</a> <a class="btn btn-warning" href="';
			$html .= $deleteurl . '" title="Delete this image">Delete</a>';
			return $html;

		} else {
			return '';
		}
	}

	/** Create the image toolbox
	 *
	 * @param int $id
	 * @param int $createdBy
	 */
	public function imageToolbox() {
	return $this;
	}

	private function _performChecks(){
		if($this->_getUser()){
			$role = $this->_getUser()->role;
		} else {
			$role = NULL;
		}
	//If user's role is in the no access array, return false for creation
	if(in_array($role, $this->_noaccess)) {
		$this->_canCreate = false;
	}
	//If user's role is in the higher level array, return true for creation
	else if(in_array($role,$this->_higherLevel)){
		$this->_canCreate = true;
	}
	//If user's role is in recorders group check for
	// a) user ID = creator of image
	// b) institution is a public record
	// c) institution is theirs
	else if(in_array($role,$this->_recorders)){
	if(
	$this->_checkCreator() ||
	$this->_institution === $this->_overRide ||
	$this->_checkInstitution()
	){
		$this->_canCreate = true;
	}
	}
	//If user's role is in restricted groups
	// a) check if the user's institution is theirs and they are the creator
	else if(in_array($role,$this->_restricted)){
	if(($this->_checkCreator() && $this->_checkInstitution())){
		$this->_canCreate = true;
	}
	}
	//Otherwise do nothing!
	else {
		$this->_canCreate = false;
	}
	}

	private function _checkParameters()
	{
		$parameters = array(
			$this->_createdBy,
			$this->_institution,
			$this->_id
		);
		foreach($parameters as $parameter){
			if(is_null($parameter)){
				throw new Zend_Exception('A parameter is missing');
			}
		}
		return true;
	}

	public function __toString()
	{
		return $this->_buildHtml();
	}
}