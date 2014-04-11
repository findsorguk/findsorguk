<?php
/** Retrieve treasure valuations from the database
* @category 	Pas
* @package 		Pas_Db_Table
* @subpackage 	Abstract
* @author 		Daniel Pett dpett @ britishmuseum.org
* @copyright 	2010 - DEJ Pett
* @license 		GNU General Public License
* @version 		1
* @since 		22 September 2011
 */
class AgreedTreasureValuations 
	extends Pas_Db_Table_Abstract {
	
	protected $_treasureID;
	protected $_primary = 'id';
	protected $_name = 'agreedTreasureValuations';
	
	/** Initialise the request and get treasure ID from the request
	 */
	public function init() {
	$this->_request = Zend_Controller_Front::getInstance()->getRequest();
	$this->_treasureID = $this->_request->getParam('treasureID');
	}
	
	/** Add  a valuation
	* @param array $data 
	* @return boolean
	*/
	public function add($data){
		if (!isset($data['created']) || ($data['created'] instanceof Zend_Db_Expr)) {
		$data['created'] = $this->timeCreation();
	  	}
	  	$data['createdBy'] = $this->userNumber();
		$data['treasureID'] = $this->_treasureID;
		return parent::insert($data);
	}
	
	/** Update a valuation
	* @access public
	* @param array $data 
	* @return boolean
	*/
	public function updateTreasure($data){
		if (!isset($data['updated']) || ($data['updated'] instanceof Zend_Db_Expr)) {
		$data['updated'] = $this->timeCreation();
	  	}
	  	$where = parent::getAdapter()->quoteInto('treasureID = ?', $this->_treasureID);
	  	$data['updatedBy'] = $this->_auth->getIdentity()->id;
	  	return parent::update($data,$where);	
	}
	
	/** Delete a valuation
	* @access public
	* @return boolean
	*/
	public function delete($data){
		
	}
	
	/** List valuations
	* @access public
	* @param integer $treasureID 
	* @return array
	*/
	public function listvaluations($treasureID){
	$values = $this->getAdapter();
	$select = $values->select()
		->from($this->_name)
		->joinLeft('users','users.id = ' . $this->_name . '.createdBy', array('enteredBy' => 'fullname'))
		->where('treasureID = ?',(string)$treasureID)
		->order($this->_name . '.created');
	return $values->fetchAll($select);
	}
	
	/** Get individual valuation
	* @access public
	* @param integer $treasureID 
	* @return array
	*/
	public function getValuation($treasureID){
	$values = $this->getAdapter();
	$select = $values->select()
		->from($this->_name)
		->joinLeft('users','users.id = ' . $this->_name . '.createdBy', array('enteredBy' => 'fullname'))
		->where('treasureID = ?',(string)$treasureID)
		->order($this->_name . '.created');
	return $values->fetchAll($select);
	}
}

