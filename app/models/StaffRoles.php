<?php
/** Data model for accessing and manipulating staff roles assigned for the contacts 
* list on website
* @category 	Pas
* @package 		Pas_Db_Table
* @subpackage 	Abstract
* @author 		Daniel Pett dpett @ britishmuseum.org
* @copyright 	2010 - DEJ Pett
* @license 		GNU General Public License
* @version 		1
* @since 		20 August 2010, 12:23:46
* @todo 		add edit and delete functions
* @todo 		add caching
*/

class StaffRoles extends Pas_Db_Table_Abstract {
	
	protected $_name = 'staffroles';
	
	protected $_primary = 'id';

	/** get key value pairs to populate dropdown lists
	* @return array
	* @todo add caching
	*/
	public function getOptions(){
		$select = $this->select()
			->from($this->_name, array('ID', 'role'))
			->order('ID ASC');
		$options = $this->getAdapter()->fetchPairs($select);
	return $options;
    }
    
    /** Retrieve valid roles for system with extended data for updates etc
	* @return array
	* @todo add caching
	*/
	public function getValidRoles() {
		$roles = $this->getAdapter();
		$select = $roles->select()
			->from($this->_name)
			->joinLeft('users','users.id = '.$this->_name.'.createdBy', array('fullname'))
			->joinLeft('users','users_2.id = '.$this->_name.'.updatedBy', array('fn' => 'fullname'));
	return $roles->fetchAll($select);
    }
    
    /** Retrieve valid roles for system with extended data for updates etc
    * @param integer $role The role id number
	* @return array
	* @todo add caching
	*/
	public function getRole($role) {
		$roles = $this->getAdapter();
		$select = $roles->select()
			->from($this->_name)
			->joinLeft('users','users.id = ' . $this->_name.'.createdBy', array('fullname'))
			->joinLeft('users','users_2.id = ' . $this->_name.'.updatedBy', array('fn' => 'fullname'))
			->where($this->_name.'.id = ?',(int)$role);
	return $roles->fetchAll($select);
    }
    
     /** Retrieve members assigned to a specific role
    * @param integer $role The role id number
	* @return array
	* @todo add caching
	*/
	public function getMembers($role) {
		$roles = $this->getAdapter();
		$select = $roles->select()
			->from($this->_name,array())
			->joinLeft('staff',$this->_name.'.id = staff.role', array('firstname', 'lastname', 'id',
			'alumni', 'updated', 'updatedBy'))
			->joinLeft('users','users.id = ' . $this->_name.'.createdBy', array('fullname'))
			->joinLeft('users','users_2.id = ' . $this->_name.'.updatedBy', array('fn' => 'fullname'))
		->where($this->_name.'.id = ?',(int)$role);
	return $roles->fetchAll($select);
    }

}
