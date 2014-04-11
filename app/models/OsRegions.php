<?php
/** Retrieve and manipulate data from the places listing
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
class OsRegions extends Pas_Db_Table_Abstract {

	protected $_name = 'osRegions';

	protected $_primary = 'id';


	/** Get the district by county
	* @param string $county
	* @return array
	* @todo add caching
	*/
 	public function getRegions(){
	$select = $this->select()
		->from($this->_name, array('osID' => 'id','uri', 'type', 'label'))
		->order('label');
	return $this->getAdapter()->fetchAll($select);
	}

	/** retrieve county list again as key pairs.
	* @return array
	* @todo not sure why duplicate of first function. Fix it!(doofus Dan).
	*/
	public function getRegionsID() {
	if (!$data = $this->_cache->load('regionIDs')) {
	$select = $this->select()
		->from($this->_name, array('osID', 'CONCAT(label," (",type,")")'))
		->order('label');
	$data = $this->getAdapter()->fetchPairs($select);
	$this->_cache->save($data, 'regionIDs');
	}
	return $data;
	}


}
