<?php
/** Model for interacting with a link table for rallies to flos
* @category 	Pas
* @package 		Pas_Db_Table
* @subpackage 	Abstract
* @author		Daniel Pett	
* @copyright 	2010 - DEJ Pett
* @license 		GNU General Public License
* @version 		1
* @since 		22 September 2011
* @todo add edit and delete functions and cache
*/
class RallyXFlo extends Pas_Db_Table_Abstract {

	protected $_primaryKey = 'id';

	protected $_name = 'rallyXflo';

	/** Get staff attending a specific rallt
	* @param integer $id 
	* @return array
	*/

	public function getStaff($id) {
		$staff = $this->getAdapter();
		$select = $staff->select()
            ->from($this->_name, array('datefrom', 'dateto', 'rallyID'))
			->joinLeft('users',$this->_name . '.staffID = users.id', array( 'fullname', 'last_name', 'id' ))
			->where($this->_name . '.rallyID = ?',(int)$id)
			->order('last_name');
	   return $staff->fetchAll($select);

}

}