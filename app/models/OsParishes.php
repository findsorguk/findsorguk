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
class OsParishes extends Pas_Db_Table_Abstract {

	protected $_name = 'osParishes';

	protected $_primary = 'id';


	/** Get the district by county
	* @param string $county
	* @return array
	* @todo add caching
	*/
 	public function getParishes(){
	$select = $this->select()
		->from($this->_name, array('osID' => 'id','uri', 'type', 'label'))
		->order('label');
	return $this->getAdapter()->fetchAll($select);
	}

	/** retrieve county list again as key pairs.
	* @return array
	* @todo not sure why duplicate of first function. Fix it!(doofus Dan).
	*/
	public function getParishesToDistrict( $district ) {
	$key = md5('parishes' . $district);
	if (!$data = $this->_cache->load( $key )) {
	$select = $this->select()
		->from($this->_name, array('id' => 'osID', 'term' => 'CONCAT(label," (",type,")")'))
		->order('label')
		->where('districtID =?', (int) $district);

	$data = $this->getAdapter()->fetchAll($select);
	$this->_cache->save($data, $key);
	}
	return $data;
	}

/** retrieve county list again as key pairs.
	* @return array
	* @todo not sure why duplicate of first function. Fix it!(doofus Dan).
	*/
	public function getParishesToDistrictList( $district ) {
	$key = md5('parishesList' . $district);
	if (!$data = $this->_cache->load( $key )) {
	$select = $this->select()
		->from($this->_name, array('osID', 'CONCAT(label," (",type,")")'))
		->order('label')
		->where('districtID =?', (int) $district);

	$data = $this->getAdapter()->fetchPairs($select);
	$this->_cache->save($data, $key);
	}
	return $data;
	}
	
	/** retrieve county list again as key pairs.
	* @return array
	* @todo not sure why duplicate of first function. Fix it!(doofus Dan).
	*/
	public function getParishesToCounty( $county ) {
	$key = md5('parishesCounty' . $county);
	if (!$data = $this->_cache->load( $key )) {
	$select = $this->select()
		->from($this->_name, array('osID', 'label' => 'CONCAT(label," (",type,")")'))
		->order('label DESC')
		->where('countyID =?', (int) $county);
	$data = $this->getAdapter()->fetchPairs($select);
	$this->_cache->save($data, $key);
	}
	return $data;
	}

	/** retrieve county list again as key pairs.
	* @return array
	* @todo not sure why duplicate of first function. Fix it!(doofus Dan).
	*/
	public function getParishesToRegion( $region ) {
	$key = md5('parishesRegion' . $region);
	if (!$data = $this->_cache->load( $key )) {
	$select = $this->select()
		->from($this->_name, array('osID', 'label' => 'CONCAT(label," (",type,")")'))
		->order('label')
		->where('regionID =?', (int) $region);
	$data = $this->getAdapter()->fetchPairs($select);
	$this->_cache->save($data, $key);
	}
	return $data;
	}

}
