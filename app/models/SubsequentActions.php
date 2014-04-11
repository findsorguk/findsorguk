<?php
/** model for interacting with subsequent actions table
* @category 	Pas
* @package 		Pas_Db_Table
* @subpackage 	Abstract
* @author 		Daniel Pett dpett @ britishmuseum.org
* @copyright 	2010 - DEJ Pett
* @license 		GNU General Public License
* @todo 		add edit and delete functions
*/

class SubsequentActions extends Pas_Db_Table_Abstract {

	protected $_name = 'subsequentActions';
	
	protected $_primary = 'id';
	
	
	/** Retrieve a key value pair list for subsequent actions
	* @return Array
	*/
	public function getSubActionsDD() {
	if (!$actions = $this->_cache->load('actions')) {
		 $select = $this->select()
                   ->from($this->_name, array('id', 'action'))
				   ->order(array('action'));
        $actions = $this->getAdapter()->fetchPairs($select);
		$this->_cache->save($actions, 'actions');
		} 
        return $actions;
	}
}