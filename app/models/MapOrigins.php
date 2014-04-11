<?php
/** Model for origins of map grid references 
* @category Pas
* @package Pas_Db_Table
* @subpackage Abstract
* @author Daniel Pett dpett @ britishmuseum.org
* @copyright 2010 - DEJ Pett
* @license 		GNU General Public License
* @version 		1
* @since 		22 September 2011
* @todo add edit and delete functions
*/

class MapOrigins extends Pas_Db_Table_Abstract {

	protected $_name = 'maporigins';
 
	protected $_primary = 'id';
	
	/** Retrieve all map origins 
	* @return array $paginator
	*/
	public function getOrigins() {
		$origins = $this->getAdapter();
		$select = $origins->select()
					->from($this->_name)
					->joinLeft('users','users.id = ' . $this->_name . '.createdBy', array('fullname'))
   					->joinLeft('users','users_2.id = ' . $this->_name . '.updatedBy', array('fn' => 'fullname'));
		 return $origins->fetchAll($select);
	}

	/** Retrieve all map origins as key to value pairs for dropdown listing 
	* @return array $paginator
	*/
	public function getValidOrigins() {
		$origins = $this->getAdapter();
		$select = $origins->select()
					->from($this->_name, array('id','term'));
		 return $origins->fetchPairs($select);
	}

}
