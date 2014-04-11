<?php
/** Data model for accessing and manipulating system roles from database
* @category 	Pas
* @package 		Pas_Db_Table
* @subpackage 	Abstract
* @author Daniel Pett dpett @ britishmuseum.org
* @copyright 2010 - DEJ Pett
* @license GNU General Public License
* @version 1
* @since 22 October 2010, 17:12:34
* @todo add edit and delete functions
* @todo add caching
*/
class Roles extends Pas_Db_Table_Abstract {

	protected $_name = 'roles';

	protected $_primary = 'id';

	/** Get a key value pair list of roles and descriptions on system 
	* @return array
	*/
	public function getRoles() {
		$roles = $this->getAdapter();
		$select = $roles->select()
				->from($this->_name, array('role','description'));
        return $roles->fetchPairs($select);
	}
	
	/** Get a list of all roles on system 
	* @return array
	*/
	public function getAllRoles() {
		$roles = $this->getAdapter();
		$select = $roles->select()
				->from($this->_name)
				->joinLeft('users','users.id = ' . $this->_name . '.createdBy', array('fullname'))
				->joinLeft('users','users_2.id = ' . $this->_name . '.updatedBy', array('fn' => 'fullname'))
				->joinLeft('users','users_3.role = ' . $this->_name . '.role', array('count' => 'COUNT(users_3.role)'))
				->group($this->_name . '.role');
        return $roles->fetchAll($select);
	}
	
	
	/** Get a specific role on the system
	* @param integer $id 
	* @return array
	*/
	public function getRole($id) {
		$roles = $this->getAdapter();
		$select = $roles->select()
                       ->from($this->_name)
					   ->joinLeft('users','users.id = ' . $this->_name.'.createdBy', array('fullname'))
   					   ->joinLeft('users','users_2.id = ' . $this->_name . '.updatedBy', array('fn' => 'fullname'))
					   ->where($this->_name . '.id = ?',(int)$id);
        return $roles->fetchAll($select);
	}
}