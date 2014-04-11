<?php
/**
* A model for getting Roman dynasties from database 
* @category Pas
* @package Pas_Db_Table
* @subpackage Abstract
* @author Daniel Pett dpett @ britishmuseum.org
* @copyright 2010 - DEJ Pett
* @license 		GNU General Public License
* @version 		1
* @since 		22 September 2011
* @todo add caching
*/
class Dynasties extends Pas_Db_Table_Abstract {

	protected $_name = 'dynasties';

	protected $_primary = 'id';

	
	/** Get a key value pair list for use in dropdown list for dynasties
	* @return array
	* @todo add caching
	*/
	public function getOptions() {
	$select = $this->select()
		->from($this->_name, array('id', 'dynasty'))
		->where('valid = ?', (int)1)
		->order($this->_primary);
	$options = $this->getAdapter()->fetchPairs($select);
	return $options;
    }
	
	/** Get a list for dynasties in Rome
	* @return array
	* @todo add caching
	*/
	public function getDynastyList(){
	$dynasties = $this->getAdapter();
	$select = $dynasties->select()
		->from($this->_name)
		->where('valid = ?', (int)1)
		->order($this->_primary);
	return $dynasties->fetchAll($select);
	}

	/** Get dynasty details for Roman period by id
	* @param integer $id 
	* @return array
	* @todo add caching
	*/
	public function getDynasty($id) {
	$dynasties = $this->getAdapter();
	$select = $dynasties->select()
		->from($this->_name)
		->where('id = ?', (int)$id)
		->order($this->_primary);
	return $dynasties->fetchAll($select);
	}
	
	/** Get dynasty list for administation
	* @return array
	* @todo add caching
	*/
	public function getDynastyListAdmin(){
	$dynasties = $this->getAdapter();
	$select = $dynasties->select()
		->from($this->_name)
		->joinLeft('users','users.id = ' . $this->_name . '.createdBy',array('fullname'))
		->joinLeft('users','users_2.id = ' . $this->_name . '.updatedBy',array('fn' => 'fullname'))
		->order($this->_primary);
	return $dynasties->fetchAll($select);
	}

}