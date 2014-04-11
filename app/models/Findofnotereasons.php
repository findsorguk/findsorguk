<?php
/** Model for manipulating find of note reasoning 
* @category Pas
* @package Pas_Db_Table
* @subpackage Abstract
* @author Daniel Pett dpett @ britishmuseum.org
* @copyright 2010 - DEJ Pett
* @license 		GNU General Public License
* @version 		1
* @since 		22 September 2011
* @todo add caching throughout model
*/

class Findofnotereasons extends Pas_Db_Table_Abstract {

	protected $_name = 'findofnotereasons';

	protected $_primary = 'id';
	
	/** get all find of note reasons where valid as key value pairs
	* @return array
	*/
	public function getReasons() {
        $select = $this->select()
                       ->from($this->_name, array('id', 'term'))
                       ->order('id')
			   			->where('valid = ?',(int)1);					   
        $options = $this->getAdapter()->fetchPairs($select);
        return $options;
    }
    
    /** get all find of note reasons as a list
	* @return array
	*/
	public function getReasonsList() {
		$reasons = $this->getAdapter();
		$select = $reasons->select()
            ->from($this->_name)
			->where('valid = ?',(int)1)
			->order('id');
     return  $reasons->fetchAll($select);
	}

	/** get all find of note reasons as a list for the admin interface
	* @return array
	*/
	public function getReasonsListAdmin() {
		$reasons = $this->getAdapter();
		$select = $reasons->select()
            ->from($this->_name)
			->joinLeft('users','users.id = ' . $this->_name.'.createdBy', 
			array('fullname'))
   			->joinLeft('users','users_2.id = ' . $this->_name.'.updatedBy', 
   			array('fn' => 'fullname'))			
			->order('id');
     return  $reasons->fetchAll($select);
	}


	/** get find of note reason details
	* @param integer $id  
	* @return array
	*/
	public function getReasonDetails($id) {
		$reasons = $this->getAdapter();
		$select = $reasons->select()
            ->from($this->_name)
			->where('valid = ?',(int)1)
			->where('id = ?',(int)$id);
     return  $reasons->fetchAll($select);
	}
	
	/** get a count of all finds attached to a reason
	* @param integer $id
	* @return array
	*/
	public function getReasonCountFinds($id) {
	$reasons = $this->getAdapter();
	$select = $reasons->select()
					  ->from($this->_name)
					  ->joinLeft('finds',$this->_name . '.id = finds.findofnotereason',
					  array('c' => 'COUNT(finds.id)'))
			->where('valid = ?',(int)1)
			->where($this->_name.'.id = ?',(int)$id)
			->group($this->_name.'.id');
     return  $reasons->fetchAll($select);
	}

}
