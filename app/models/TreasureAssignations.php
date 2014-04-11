<?php
/**
* Data model for accessing treasure assignations in the database
* @category 	Pas
* @package 		Pas_Db_Table
* @subpackage 	Abstract
* @author 		Daniel Pett dpett @ britishmuseum.org
* @copyright 	2010 - DEJ Pett
* @license 		GNU General Public License
* @version 		1
* @since 		22 October 2010, 17:12:34
* @todo 		implement edit and delete function methods
*/

class TreasureAssignations extends Pas_Db_Table_Abstract {

	protected  $_treasureID;
	
	protected $_primary = 'id';
	
	protected $_name = 'treasureAssignations';
	
	/** Construct the auth, config, treasureID and other objects
	* @return object
	*/
	public function init() {

		$this->_request = Zend_Controller_Front::getInstance()->getRequest();
		$this->_treasureID = Zend_Controller_Front::getInstance()->getRequest()->getParam('treasureID');
		$this->_time = Zend_Date::now()->toString('yyyy-MM-dd HH:mm:ss');
	}
	
	/** Add data to the system for a treasure action
	* @return boolean
	*/
	public function add($data){
	if (!isset($data['created']) || ($data['created'] instanceof Zend_Db_Expr)) {
    $data['created'] = $this->_time;
  	}
  	$data['createdBy'] = $this->_auth->getIdentity()->id;
	$data['treasureID'] = $this->_treasureID;
	return parent::insert($data);
	}
	
	/** List curators assigned to a case by treasure ID
	* @param integer $treasureID 
	* @return array
	*/
	public function listCurators($treasureID){
		$values = $this->getAdapter();
		$select = $values->select()
			->from($this->_name)
			->joinLeft('users','users.id = ' . $this->_name . '.createdBy',array('enteredBy' => 'fullname'))
			->joinLeft('people','people.secuid = '. $this->_name . '.curatorID',array('fullname','personID' => 'id'))
			->where('treasureID = ?',(string)$treasureID)
			->order($this->_name . '.created');
		return $values->fetchAll($select);
	}
}