<?php
/** Retrieve and manipulate data from the project type table
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
class ProjectTypes extends Pas_Db_Table_Abstract {
	
	protected $_name = 'projecttypes';
	
	protected $_primarykey = 'id';

	/** Get all valid types
	* @return array
	*/
	public function getTypes() {
	$select = $this->select()
		->from($this->_name, array('id', 'title'));
	$options = $this->getAdapter()->fetchPairs($select);
	return $options;
	}
	/** Get all valid degrees
	* @return array
	*/
	public function getDegrees(){
	$select = $this->select()
		->from($this->_name, array('id', 'title'))
		->where($this->_name . '.id IN ( 1, 2, 3 )');
        $options = $this->getAdapter()->fetchPairs($select);
        return $options;
	}
}