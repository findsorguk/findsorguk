<?php
/**
* Data model for accessing surface treatment table
* @category 	Pas
* @package 		Pas_Db_Table
* @subpackage 	Abstract
* @author 		Daniel Pett dpett @ britishmuseum.org
* @copyright 	2010 - DEJ Pett
* @license 		GNU General Public License
* @version 		1
* @since 		22 October 2010, 17:12:34
*/
class Surftreatments extends Pas_Db_Table_Abstract {

	protected $_primaryKey = 'id';

	protected $_name = 'surftreatments';

	/** Get surface treatment dropdowns
	* @return array
	*/
	public function getSurfaces() {
	if (!$options = $this->_cache->load('surftreatdd')) {
	$select = $this->select()
		->from($this->_name, array('id', 'term'))
		->where('valid = ?',(int)1)
		->order('term');
	$options = $this->getAdapter()->fetchPairs($select);
	$this->_cache->save($options, 'surftreatdd');
	}
	return $options;
    }
	/** Get surface treatment details
	* @param integer $surfaceterm
	* @return array
	*/
	public function getSurfaceTerm($surfaceterm = NULL) {
	$surfaces = $this->getAdapter();
	$select = $surfaces->select()
		->from($this->_name, array('id','term'))
		->where('valid = ?',(int)1)
		->order('id')
		->limit('1')
		->where('id = ?', (int)$surfaceterm);
	return $surfaces->fetchAll($select);
	}

	/** Get surface treatment list
	* @return array
	*/
	public function getSurfaceTreatments() {
	$surfs = $this->getAdapter();
	$select = $surfs->select()
		->from($this->_name, array('id','term'))
		->where('valid = ?',(int)1);
	return  $surfs->fetchAll($select);
	}

	/** Get surface treatment list for admin
	* @return array
	*/
	public function getSurfaceTreatmentsAdmin() {
	$surfs = $this->getAdapter();
	$select = $surfs->select()
		->from($this->_name)
		->joinLeft('users','users.id = ' . $this->_name . '.createdBy', array('fullname'))
		->joinLeft('users','users_2.id = ' . $this->_name . '.updatedBy', array('fn' => 'fullname'));
	return  $surfs->fetchAll($select);
	}

	/** Get surface treatment details
	* @param integer $id
	* @return array
	*/
	public function getSurfaceTreatmentDetails($id) {
	$surfs = $this->getAdapter();
	$select = $surfs->select()
		->from($this->_name, array('id','term'))
		->where($this->_name . '.id = ?', (int)$id)
		->where('valid = ?', (int)1);
	return  $surfs->fetchAll($select);
	}
	
	/** Get surface treatment counts
	* @param integer $id
	* @return array
	*/
	public function getSurfaceCounts($id) {
	$surfs = $this->getAdapter();
	$select = $surfs->select()
		->from($this->_name, array('id','term'))
		->joinLeft('finds',$this->_name . '.id = finds.surftreat', array('c' => 'count(*)'))
		->where($this->_name.'.id = ?', (int)$id)
		->where('valid = ?',(int)1)
		->group($this->_name . '.id');
     return  $surfs->fetchAll($select);
	 }
}
