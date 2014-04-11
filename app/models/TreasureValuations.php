<?php
/** Data model for accessing treasure valuations in the database
* @category Pas
* @package Pas_Db_Table
* @subpackage Abstract
* @author Daniel Pett dpett @ britishmuseum.org
* @copyright 2010 - DEJ Pett
* @license GNU General Public License
* @version 1
* @since 22 October 2010, 17:12:34
* @todo sort out cache and cleaning
* @todo add delete method
*/
class TreasureValuations extends Pas_Db_Table_Abstract {
	
	protected $_treasureID, $_time;
	
	protected $_primary = 'id';

	protected $_name = 'agreedTreasureValuations';
	
	/** Construct the treasureID and other objects
	* @return object
	*/
	public function init() {
		$this->_request = Zend_Controller_Front::getInstance()->getRequest();
		$this->_treasureID = Zend_Controller_Front::getInstance()->getRequest()->getParam('treasureID');
		$this->_time = Zend_Date::now()->toString('yyyy-MM-dd HH:mm:ss');
	}
	
	/** Add data to the system for a treasure value
	* @param array $data 
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
	
	/** Update system for a treasure action
	* @param array $data 
	* @return boolean
	*/
	public function updateTreasure($data){
		if (!isset($data['updated']) || ($data['updated'] instanceof Zend_Db_Expr)) {
	    $data['updated'] = $this->_time;
	  	}
	  	$where = parent::getAdapter()->quoteInto('treasureID = ?', $this->_treasureID);
	  	$data['updatedBy'] = $this->_auth->getIdentity()->id;
	  	return parent::update($data,$where);	
	}
	
	/** Get a list of values for a specific treasure ID
	* @param integer $treasureID 
	* @return boolean
	*/
	public function listvaluations($treasureID){
		$values = $this->getAdapter();
		$select = $values->select()
			->from($this->_name)
			->joinLeft('users','users.id = ' . $this->_name . '.createdBy',array('enteredBy' => 'fullname'))
			->joinLeft('people','people.secuid = '. $this->_name . '.valuerID',array('fullname','personID' => 'id'))
			->where('treasureID = ?',(string)$treasureID)
			->order($this->_name . '.created');
		return $values->fetchAll($select);
	}
	
	/** Get a specific list of values for a specific treasure ID
	* @param integer $treasureID 
	* @return boolean
	*/
	public function getValuation($treasureID){
		$values = $this->getAdapter();
		$select = $values->select()
			->from($this->_name)
			->joinLeft('users','users.id = ' . $this->_name . '.createdBy',array('enteredBy' => 'fullname'))
			->joinLeft('people','people.secuid = '. $this->_name . '.valuerID',array('fullname','personID' => 'id'))
			->where('treasureID = ?',(string)$treasureID)
			->order($this->_name . '.created');
		return $values->fetchAll($select);
	}
}