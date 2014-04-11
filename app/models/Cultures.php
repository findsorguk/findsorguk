<?php
/**
* A model for listing all the ascribed cultures in use on the database
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

class Cultures extends Pas_Db_Table_Abstract {

	protected $_name = 'cultures';
	protected $_primary = 'id';

	/** Get a list of all ascribed cultures as key pair values
	* @return array
	*/
	public function getCultures() {
	$select = $this->select()
		->from($this->_name, array('id', 'term'))
		->order('id');
	$options = $this->getAdapter()->fetchPairs($select);
	return $options;
    }
    
	/** Get a list of all ascribed cultures as an array
	* @return array
	*/
	public function getCulturesList() {
	$select = $this->select()
		->from($this->_name, array('id', 'term','termdesc'))
		->where('valid = ?', (int)1)
		->order('id');
	$options = $this->getAdapter()->fetchAll($select);
	return $options;
	}
	
	/** Get an admin list of ascribed cultures
	* @return array
	*/
	public function getCulturesListAdmin() {
	$options = $this->getAdapter();
	$select = $options->select()
		->from($this->_name)
		->joinLeft('users','users.id = ' . $this->_name . '.createdBy', array('fullname'))
		->joinLeft('users','users_2.id = ' . $this->_name . '.updatedBy', array('fn' => 'fullname'))
		->order('id');
	return $options->fetchAll($select);
	}

	/** Get a cultural detail via the id number
	 * 
	 * @param integer $id The id number of the ascribed culture
	 */
	public function getCulture($id) {
	$select = $this->select()
		->from($this->_name, array('id', 'term', 'termdesc'))
		->where('valid = ?', (int)1)
		->where('id = ?', (int)$id)
		->order('id');
	$options = $this->getAdapter()->fetchAll($select);
	return $options;
	}

	/** Get a count of finds for a specific cultural ID
	 * 
	 * @param integer $id
	 */
	public function getCultureCountFinds($id){
	$reasons = $this->getAdapter();
	$select = $reasons->select()
		->from('finds',array('c' => 'COUNT(finds.id)'))
		->where('finds.culture =?', (int)$id);
	return  $reasons->fetchAll($select);
	}
	
}
