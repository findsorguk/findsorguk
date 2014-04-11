<?php
/** Retrieve and manipulate data from the Primary activity table
* @category 	Pas
* @package 		Pas_Db_Table
* @subpackage 	Abstract
* @author 		Daniel Pett dpett @ britishmuseum.org
* @copyright 	2010 - DEJ Pett
* @license 		GNU General Public License
* @version 		1
* @since 		22 September 2011
* @todo add caching
*/
class PrimaryActivities extends Pas_Db_Table_Abstract {
	
	protected $_name = 'primaryactivities';

	protected $_primary = 'id';

	/** Get all valid terms
	* @return array
	*/
	public function getTerms() {
	$select = $this->select()
		->from($this->_name, array('id', 'term'))
		->order('term')
		->where('valid = ?',(int)1);
	$options = $this->getAdapter()->fetchPairs($select);
	return $options;
    }

    /** Get all valid activities
	* @return array
	*/
    public function getActivitiesList() {
	$acts = $this->getAdapter();
	$select = $acts->select()
		->from($this->_name)
		->order(array('term'))
		->where('valid = ?',(int)1);
	return $acts->fetchAll($select);
	}

	/** Get all activities for admin console
	* @return array
	*/
	public function getActivitiesListAdmin() {
	$acts = $this->getAdapter();
	$select = $acts->select()
		->from($this->_name)
		->joinLeft('users','users.id = ' . $this->_name . '.createdBy',array('fullname'))
		->joinLeft('users','users_2.id = ' . $this->_name . '.updatedBy',array('fn' => 'fullname'))
		->order(array('term'));
	return $acts->fetchAll($select);
	}


	/** Get  activity details by ID
	* @param integer $id
	* @return array
	*/
	public function getActivityDetails($id) {
	$acts = $this->getAdapter();
	$select = $acts->select()
		->from($this->_name)
		->where('id =?',(int)$id)
		->where('valid = ?',(int)1);
	return $acts->fetchAll($select);
	}

	/** Get all valid activities as a count
	* @param integer $id
	* @return array
	*/
	public function getActivityPersonCounts($id) {
	$acts = $this->getAdapter();
	$select = $acts->select()
		->from($this->_name,array())
		->joinLeft('people','people.primary_activity = ' . $this->_name . '.id',
		array('c' => 'COUNT(' . $this->_name . '.id)'))
		->where($this->_name . '.id =?',(int)$id)
		->where('valid = ?',(int)1)
		->group($this->_name .  '.id');
	return $acts->fetchAll($select);
	}
}
