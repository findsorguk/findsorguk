<?php
/** Data model for accessing wear types for coins
* @category 	Pas
* @package 		Pas_Db_Table
* @subpackage 	Abstract
* @author 		Daniel Pett dpett @ britishmuseum.org
* @copyright 	2010 - DEJ Pett
* @license 		GNU General Public License
* @version 		1
* @since 		22 October 2010, 17:12:34
*/

class Weartypes extends Pas_Db_Table_Abstract {

	protected $_name = 'weartypes';
	
	protected $_primary = 'id';

	/** Get list of wear types for coins as key value pair array
	* @return array
	*/
	public function getWears() {
	$select = $this->select()
		->from($this->_name, array('id', 'term'))
		->order('term');
	$options = $this->getAdapter()->fetchPairs($select);
	return $options;
    }
	
    /** Get list of wear types for coins 
	* @param integer $wear the wear type ID
	* @return array
	*/
    public function getWearType($wear) {
	$select = $this->select()
		->from($this->_name, array('id', 'term'))
		->where('id = ?', (int)$wear);
	$options = $this->getAdapter()->fetchAll($select);
	return $options;
    }

     /** Get list of wear types for coins admin interface 
	* @return array
	*/
	public function getWearTypesAdmin() {	
	$wears = $this->getAdapter();
	$select = $wears->select()
		->from($this->_name)
		->joinLeft('users','users.id = ' . $this->_name . '.createdBy',array('fullname'))
		->joinLeft('users','users_2.id = ' . $this->_name . '.updatedBy',array('fn' => 'fullname'));
	return $wears->fetchAll($select);
    }
}
