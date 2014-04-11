<?php
/** Data model for accessing treasure valuation dates and cases from link table
* @category 	Pas
* @package 		Pas_Db_Table
* @subpackage 	Abstract
* @author 		Daniel Pett dpett @ britishmuseum.org
* @copyright 	2010 - DEJ Pett
* @license 		GNU General Public License
* @version		1
* @since 		22 October 2010, 17:12:34
* @todo 		implement edit and delete function methods
*/
class TvcDatesToCases extends Pas_Db_Table_Abstract {
	
	protected $_primary = 'id';
	
	protected $_name = 'tvcDatesToCases';
	
	protected  $_time, $_treasureID, $_request;
	
	/** Construct the auth and cache objects
	* @return object
	*/
	public function init() {
		$this->_request = Zend_Controller_Front::getInstance()->getRequest();
		$this->_treasureID = Zend_Controller_Front::getInstance()->getRequest()->getParam('treasureID');
		$this->_time = Zend_Date::now()->toString('yyyy-MM-dd HH:mm:ss');
	}
	
	/** Add new TVC date/case link to database
	* @param array $data
	* @return boolean
	*/
	public function add($data){
	if (!isset($data['created']) || ($data['created'] instanceof Zend_Db_Expr)) {
    $data['created'] = $this->_time;
  	}
  	$data['treasureID'] = $this->_treasureID;
  	$data['createdBy'] = $this->_auth->getIdentity()->id;
	return parent::insert($data);
	}
	
	/** List cases for a TVC
	* @param integer $tvcID the tvc id number
	* @return array
	*/	
	public function listCases($tvcID){
	$tvcs = $this->getAdapter();
	$select = $tvcs->select()
		->from($this->_name,array('treasureID'))
		->joinLeft('tvcDates',$this->_name . '.tvcID = tvcDates.secuid', array())
		->where('tvcDates.id = ?',$tvcID)
		->order($this->_name . '.' . $this->_primary);
	$data =  $tvcs->fetchAll($select);
	return $data;
	}
	
	/** List dates for a TVC case
	* @param integer $treasureID the tvc id number
	* @return array
	*/	
	public function listDates($treasureID){
	$tvcs = $this->getAdapter();
	$select = $tvcs->select()
			->from($this->_name,array())
			->joinLeft('tvcDates',$this->_name . '.tvcID = tvcDates.secuid', array('id','date','location'))
			->where($this->_name . '.treasureID = ?',$treasureID)
			->order($this->_name . '.' . $this->_primary);
	$data =  $tvcs->fetchAll($select);
	return $data;
	}
	
}

