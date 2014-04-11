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
class OsDistricts extends Pas_Db_Table_Abstract {

	protected $_name = 'osDistricts';

	protected $_primary = 'id';


	/** Get the district by county
	* @param string $county
	* @return array
	* @todo add caching
	*/
 	public function getCounties(){
	$select = $this->select()
		->from($this->_name, array('osID' => 'id','uri', 'type', 'label'))
		->order('label');
	return $this->getAdapter()->fetchAll($select);
	}

	/** retrieve county list again as key pairs.
	* @return array
	* @todo not sure why duplicate of first function. Fix it!(doofus Dan).
	*/
	public function getCountiesID() {
	if (!$data = $this->_cache->load('countyIDs')) {
	$select = $this->select()
		->from($this->_name, array('osID', 'label' => 'CONCAT(label," (",type,")")'))
		->order('label');
	$data = $this->getAdapter()->fetchPairs($select);
	$this->_cache->save($data, 'countyIDs');
	}
	return $data;
	}

	/** retrieve county list again as key pairs.
	* @return array
	* @todo not sure why duplicate of first function. Fix it!(doofus Dan).
	*/
	public function getDistrictsToCounty( $county ) {
	$key = md5('districtsCounty' . $county);
	if (!$data = $this->_cache->load( $key )) {
	$select = $this->select()
		->from($this->_name, array('id' => 'osID', 'term' => 'CONCAT(label," (",type,")")'))
		->order('label')
		->where('countyID =?', (int) $county);
	$data = $this->getAdapter()->fetchAll($select);
	$this->_cache->save($data, $key);
	}
	return $data;
	}
	
	/** retrieve county list again as key pairs.
	* @return array
	* @todo not sure why duplicate of first function. Fix it!(doofus Dan).
	*/
	public function getDistrictsToCountyList( $county ) {
	$key = md5('districtsCountyList' . $county);
	if (!$data = $this->_cache->load( $key )) {
	$select = $this->select()
		->from($this->_name, array('osID', 'CONCAT(label," (",type,")")'))
		->order('label')
		->where('countyID =?', (int) $county);
	$data = $this->getAdapter()->fetchPairs($select);
	$this->_cache->save($data, $key);
	}
	return $data;
	}
}
