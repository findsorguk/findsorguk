<?php
/**
* A model for pulling discovery methods from database 
* @category Pas
* @package Pas_Db_Table
* @subpackage Abstract
* @author Daniel Pett dpett @ britishmuseum.org
* @copyright 2010 - DEJ Pett
* @todo add caching
* @license 		GNU General Public License
* @version 		1
* @since 		22 September 2011
*/

class DiscoMethods extends Pas_Db_Table_Abstract {
	
	protected $_name = 'discmethods';


	/** Get key value pairs and cache the result for use in dropdowns for discovery methods
	* @return array
	*/
	public function getOptions() {
//	if (!$options = $this->_cache->load('discmethoddd')) {
	$select = $this->select()
		->from($this->_name, array('id', 'method'))
		->order('method ASC');
//		->where('valid = ?', (int)1);
	$options = $this->getAdapter()->fetchPairs($select);
//	$this->_cache->save($options, 'discmethoddd');
//	}
	return $options;
    }
    
	/** Get discovery method 
	* @param integer $discmethod
	* @return array
	* @todo add caching
	* @todo change to fetchrow?
	*/
	public function getDiscMethod($discmethod) {
	$select = $this->select()
		->from($this->_name, array('method'))
		->where('id = ?', (int)$discmethod)
		->where('valid = ?', (int)1);
	$options = $this->getAdapter()->fetchAll($select);
	return $options;
    }

    /** Get discovery method information
	* @param integer $discmethod
	* @return array
	* @todo add caching
	*/
	public function getDiscmethodInformation($id) {
	$methods = $this->getAdapter();
	$select = $methods->select()
		->from($this->_name, array('id','method','termdesc'))
		->where('id = ?', (int)$id)
		->where('valid = ?', (int)1);
	return $methods->fetchAll($select);
	}

	/** Get discovery method quantities, expensive query
	* @param integer $discmethod
	* @return array
	*/
	public function getDiscMethodQuantity($id) {
	$methods = $this->getAdapter();
	$select = $methods->select()
		->from($this->_name, array())
		->joinLeft('finds','finds.discmethod = ' . $this->_name . '.id', array('number' => 'SUM(quantity)'))
		->where($this->_name.'.id = ?',$id)
		->where($this->_name.'.valid = ?', (int)1);
	return $methods->fetchAll($select);
	}
	
	/** Get discovery methods as a list where valid
	* @return array
	* @todo add caching
	*/
	public function getDiscMethodsList() {
	$methods = $this->getAdapter();
	$select = $methods->select()
		->from($this->_name)
		->where('valid = ?', (int)1);
	return $methods->fetchAll($select);
	}

	/** Get discovery method information as a list for the administration console
	* @return array
	* @todo add caching
	*/
	public function getDiscMethodsListAdmin() {
	$methods = $this->getAdapter();
	$select = $methods->select()
		->from($this->_name)
		->joinLeft('users','users.id = ' . $this->_name . '.createdBy', array('fullname'))
		->joinLeft('users','users_2.id = ' . $this->_name . '.updatedBy', array('fn' => 'fullname'));
	return $methods->fetchAll($select);
	}

}
