<?php
/** Model for interacting with status for coins table
* @category 	Pas
* @package 		Pas_Db_Table
* @subpackage 	Abstract
* @author 		Daniel Pett dpett @ britishmuseum.org
* @copyright 	2010 - DEJ Pett
* @license 		GNU General Public License
* @todo 		add edit and delete functions
*/
class Statuses extends Pas_Db_Table_Abstract {
	
	protected $_name = 'statuses';
	
	protected $_primary = 'id';
	
	/** Retrieve a key value pair list for coin status dropdown list
	* @return Array
	*/
	public function getCoinStatus() {
        $select = $this->select()
				->from($this->_name, array('id', 'term'))
				->order('id');
        $options = $this->getAdapter()->fetchPairs($select);
        return $options;
    }

}
