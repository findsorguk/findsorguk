<?php
/** Data model for accessing treasure actions in the database
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
class TreasureActions extends Pas_Db_Table_Abstract {

	protected $_treasureID,$_time,$_request;
	
	protected $_primary = 'id';
	
	protected $_name = 'treasureActions';
	
	
	/** Construct the auth, config, treasureID and other objects
	* @return object
	*/
	public function init() {
		$this->_request = Zend_Controller_Front::getInstance()->getRequest();
		$this->_treasureID = Zend_Controller_Front::getInstance()->getRequest()->getParam('treasureID');
		$this->_time = Zend_Date::now()->toString('yyyy-MM-dd HH:mm:ss');
	}
	
	/** Add data to the system for a treasure action
	* @return object
	*/
	public function add($data){
	if (!isset($data['created']) || ($data['created'] instanceof Zend_Db_Expr)) {
    $data['created'] = $this->_time;
  	}
  	$data['createdBy'] = $this->_auth->getIdentity()->id;
	$data['treasureID'] = $this->_treasureID;
	return parent::insert($data);
	}
	
	/** Get a list of treasure actions for a specific treasure case
	* @param integer $treasureID the case ID number
	* @return array
	*/
	public function getActionsListed($treasureID){
		$actions = $this->getAdapter();
		$select = $actions->select()
			->from($this->_name)
			->joinLeft('users','users.id = ' . $this->_name . '.createdBy',array('enteredBy' => 'fullname'))
			->joinLeft('treasureActionTypes',$this->_name . '.actionID = treasureActionTypes.id',array('action'))
			->where('treasureID = ?',(string)$treasureID)
			->order($this->_name . '.created');
		return $actions->fetchAll($select);
	}
}