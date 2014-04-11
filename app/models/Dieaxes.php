<?php
/** A model for pulling data for coin die axes
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

class Dieaxes extends Pas_Db_Table_Abstract {

	protected $_name = 'dieaxes';
	
	protected $_primary = 'id';
	
	/** Retrieve a key value pair list of die axes where valid
	* @return array
	*/
	public function getAxes() {
	$select = $this->select()
		->from($this->_name, array('id', 'die_axis_name'))
		->where('valid = ?', (int)1)
		->order('id');
	$options = $this->getAdapter()->fetchPairs($select);
	return $options;
    }

	/** Retrieve a list of die axes where valid
	* @return array
	*/
	public function getDieList() {
	$dies = $this->getAdapter();
	$select = $dies->select()
		->from($this->_name)
		->where('valid = ?', (int)1);
	return $dies->fetchAll($select);
	}
	
	/** Retrieve a list of die axes for administration console, with update details
	* @return array
	*/
	public function getDieListAdmin() {
	$dies = $this->getAdapter();
	$select = $dies->select()
		->from($this->_name)
		->joinLeft('users','users.id = ' . $this->_name . '.createdBy', array('fullname'))
		->joinLeft('users','users_2.id = ' . $this->_name . '.updatedBy', array('fn' => 'fullname'));
	return $dies->fetchAll($select);
	}

	/** Retrieve a valid die axis detail array
	* @param integer $id
	* @todo change to fetchrow method and return?
	* @return array
	*/
	public function getDieAxesDetails($id) {
	$dies = $this->getAdapter();
	$select = $dies->select()
		->from($this->_name)
		->where('id = ?', (int)$id)
		->where('valid = ?', (int)1);
	return $dies->fetchAll($select);
	}
	
	/** Retrieve a valid die axis count for display
	* @param integer $id
	* @return array
	*/
	public function getDieCounts($id) {
	$dies = $this->getAdapter();
	$select = $dies->select()
		->from($this->_name)
		->joinLeft('coins',$this->_name . '.id = coins.die_axis_measurement')
		->joinLeft('finds','coins.findID = finds.secuid', array('c' => 'count(*)'))
		->where($this->_name . '.id = ?',(int)$id)
		->where('valid = ?',(int)1)
		->group($this->_name . '.id');
	return $dies->fetchAll($select);
	}
}
