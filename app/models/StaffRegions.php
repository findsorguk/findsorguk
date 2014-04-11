<?php
/** Model for interacting with staff regions table
* @category 	Pas
* @package 		Pas_Db_Table
* @subpackage 	Abstract
* @author 		Daniel Pett dpett @ britishmuseum.org
* @copyright 	2010 - DEJ Pett
* @license 		GNU General Public License
* @todo 		add edit and delete functions, cache
*/
class StaffRegions extends Pas_Db_Table_Abstract {
	
	protected $_name = 'staffregions';

	protected $_primary = 'id';
	
	/** Get a dropdown key value pair list for staff regions
	* @return array
	*/
	public function getOptions() {
	$select = $this->select()
		->from($this->_name, array('ID', 'description'))
		->order('description ASC');
	$options = $this->getAdapter()->fetchPairs($select);
	return $options;
    }
}
