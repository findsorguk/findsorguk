<?php
/**  Model for describing decorative methods for artefacts
* @category Pas
* @package Pas_Db_Table
* @subpackage Abstract
* @author Daniel Pett dpett @ britishmuseum.org
* @copyright 2010 - DEJ Pett
* @license 		GNU General Public License
* @version 		1
* @since 		22 September 2011
* */
class Decmethods extends Pas_Db_Table_Abstract {

	protected $_name = 'decmethods';
	
	protected $_primaryKey = 'id';
	

	/** retrieve a key pair list of decoration methods for dropdown usage as key value pairs
	* @return array
	*/
	public function getDecmethods() {
	if (!$options = $this->_cache->load('decmethoddd')) {
	$select = $this->select()
		->from($this->_name, array('id', 'term'))
		->order('id')
		->where('valid = ?', (int)1);
	$options = $this->getAdapter()->fetchPairs($select);
	$this->_cache->save($options, 'decmethoddd');
	}
	return $options;
    }
	
    /** retrieve a list of decoration methods for dropdown usage
	* @return array
	*/
	public function getDecorationDetailsList(){
	$methods = $this->getAdapter();
	$select = $methods->select()
		->from($this->_name)
		->where('valid = ?', (int)1)
		->order('id');
	return $methods->fetchAll($select);
    }

    /** retrieve a list of decoration methods for dropdown usage as admin
    * @todo merge with above function and add param of valid to achieve same aims 
	* @return array
	*/
	public function getDecorationDetailsListAdmin() {
	$methods = $this->getAdapter();
	$select = $methods->select()
		->from($this->_name)
		->joinLeft('users','users.id = ' . $this->_name . '.createdBy', array('fullname'))
		->joinLeft('users','users_2.id = ' . $this->_name . '.updatedBy', array('fn' => 'fullname'))
		->order('id');
	return $methods->fetchAll($select);
    }
    
    /** retrieve details of decoration method
	* @return array
	* @param integer $id
	* @todo add caching
	* @todo change to fetchrow?
	*/
	public function getDecorationDetails($id){
	$methods = $this->getAdapter();
	$select = $methods->select()
		->from($this->_name)
		->where('valid = ?', (int)1)
		->where($this->_name . '.id = ?', (int)$id);
	return $methods->fetchAll($select);
    }

	/** retrieve a count of objects with a specific decoration method
	* @param integer $id
	* @return array
	*/
	public function getDecCount($id) {
	$methods = $this->getAdapter();
	$select = $methods->select()
		->from($this->_name)
		->joinLeft('finds','finds.decmethod = ' . $this->_name . '.id' ,array('c' => 'count(finds.id)'))
		->where('valid = ?',(int)1)
		->where($this->_name . '.id = ?',(int)$id)
		->group($this->_name . '.id');
	return $methods->fetchAll($select);
    }

}
