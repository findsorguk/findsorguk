<?php
/** Data model for accessing and manipulating European regions from database
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

class Regions extends Pas_Db_Table_Abstract {
	
	protected $_name = 'regions';
	
	protected $_primary = 'id';

	/** Retrieve regions as key value pairs
	* @return array
	* @todo add caching
	*/
	public function getRegionname() {
        $select = $this->select()
                       ->from($this->_name, array('id', 'region'))
                       ->order($this->_primary)
					   ->where('valid = ?', (int)1);
        $options = $this->getAdapter()->fetchPairs($select);
        return $options;
    }

    /** Retrieve regions as list
	* @return array
	* @todo add caching
	*/
	public function getRegion($region) {
	 	$regions = $this->getAdapter();
		$select = $regions->select()
                       ->from($this->_name, array('region'))
                       ->order($this->_primary)
					   ->limit('1')
					   ->where('id = ?', (int)$region);
        return $regions->fetchAll($select);
    }
}