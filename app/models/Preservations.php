<?php

/** Retrieve and manipulate data from the preservation states
* @category 	Pas
* @package 		Pas_Db_Table
* @subpackage 	Abstract
* @author Daniel Pett dpett @ britishmuseum.org
* @copyright 2010 - DEJ Pett
* @license 		GNU General Public License
* @version 		1
* @since 		22 September 2011
* @todo add caching
*/
class Preservations extends Pas_Db_Table_Abstract {
	
	protected $_name = 'preservations';
	
	protected $_primary = 'id';
	
	/** Get all valid preservation states as a key value list
	* @return array
	*/
	public function getPreserves() {
	$select = $this->select()
		->from($this->_name, array('id', 'term'))
		->order('term ASC')
		->where('valid = ?',(int)1);
	$options = $this->getAdapter()->fetchPairs($select);
	return $options;
    }
    
    /** Get all valid preservation states
	* @return array
	*/
	public function getPreservationTerms(){
	$preserves = $this->getAdapter();
	$select = $preserves->select()
		->from($this->_name)
		->where('valid = ?',(int)1);
	return  $preserves->fetchAll($select);
	}

	/** Get all preservation details by ID
	* @param integer $id
	* @return array
	*/
	public function getPreservationDetails($id){
	$preserves = $this->getAdapter();
	$select = $preserves->select()
		->from($this->_name)
		->where('id = ?',(int)$id)
		->where('valid = ?',(int)1);
	return  $preserves->fetchAll($select);
	}

	/** Get all preservation types for admin
	* @return array
	*/
	public function getPreservationTermsAdmin() {
	$preserves = $this->getAdapter();
	$select = $preserves->select()
		->from($this->_name)
		->joinLeft('users','users.id = ' . $this->_name . '.createdBy', array('fullname'))
		->joinLeft('users','users_2.id = ' . $this->_name .'.updatedBy', array('fn' => 'fullname'));
     return  $preserves->fetchAll($select);
}

}
