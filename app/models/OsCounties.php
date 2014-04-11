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
class OsCounties extends Pas_Db_Table_Abstract {

	protected $_name = 'osCounties';

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
		->from($this->_name, array('osID', 'CONCAT(label," (",type,")")'))
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
	public function getCountyToRegion( $county ) {
	$key = md5('countyRegion' . $county);
	if (!$data = $this->_cache->load( $key )) {
	$table = $this->getAdapter();
	$select = $table->select()
		->from($this->_name, array())
		->joinLeft('osRegions', 'osRegions.osID = osCounties.regionID',array('id' => 'osID', 'term' => 'CONCAT(osRegions.label," (",osRegions.type,")")'))
//		->order('osRegions.label')
		->where('osCounties.osID =?', (int) $county);
	$data = $table->fetchAll($select);
	$this->_cache->save($data, $key);
	}

	return $data;
	}

/** retrieve county list again as key pairs.
	* @return array
	* @todo not sure why duplicate of first function. Fix it!(doofus Dan).
	*/
	public function getCountyToRegionList( $county ) {
	$key = md5('countyRegionList' . $county);
	if (!$data = $this->_cache->load( $key )) {
	$table = $this->getAdapter();
	$select = $table->select()
		->from($this->_name, array())
		->joinLeft('osRegions', 'osRegions.osID = osCounties.regionID',array('osID', 'CONCAT(osRegions.label," (",osRegions.type,")")'))
//		->order('osRegions.label')
		->where('osCounties.osID =?', (int) $county);
	$data = $table->fetchPairs($select);
	$this->_cache->save($data, $key);
	}

	return $data;
	}
	
}
