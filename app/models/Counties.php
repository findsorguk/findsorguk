<?php
/**
* A model to manipulate data for the Counties of England and Wales. Scotland may be added
* in the future 
* @category Pas
* @package Pas_Db_Table
* @subpackage Abstract
* @author Daniel Pett dpett @ britishmuseum.org
* @copyright 2010 - DEJ Pett
* @license 		GNU General Public License
* @version 		1
* @since 		22 September 2011
*/

class Counties extends Pas_Db_Table_Abstract {
	
	protected $_name = 'counties';
	protected $_primary = 'ID';

	/** retrieve a key pair list of counties in England and Wales for dropdown use
	* @return array
	*/
	public function getCountyname() {
	if (!$data = $this->_cache->load('countynames')) {
	$select = $this->select()
		->from($this->_name, array('ID', 'county'))
		->order('county')
		->where('valid = ?', (int)1);
	$data = $this->getAdapter()->fetchPairs($select);
	$this->_cache->save($data, 'countynames');
	}
	return $data;
    }
	
	/** retrieve region list for county
	* @param string $county 
	* @return array
	* @todo this is a stupid database call, change to fetchrow and into Regions.php (doofus Dan).
	*/
	public function getRegions($county) {
	if (!$data = $this->_cache->load('regions' . str_replace(' ','_',$county))) {
	$regions = $this->getAdapter();
	$select = $regions->select()
		->from($this->_name, array())
		->joinLeft('regions','regions.id = counties.regionID',array('id','term' =>'region'))
		->where('county = ?',$county);
	$data =  $regions->fetchAll($select);
	$this->_cache->save($data, 'regions' . str_replace(' ','_',$county));
	}
	return $data;
	}

	/** retrieve region list key pair valies for county
	* @param string $county 
	* @return array
	* @todo this is a stupid database call, change to fetchrow and into Regions.php (doofus Dan).
	*/
	public function getRegionsList($county) {
	if (!$data = $this->_cache->load('regionlist' . str_replace(' ','_',$county))) {
	$regions = $this->getAdapter();
	$select = $regions->select()
		->from($this->_name, array())
		->joinLeft('regions','regions.id = counties.regionID',array('id','term' =>'region'))
		->where('county = ?',$county);
	$data =  $regions->fetchPairs($select);
	$this->_cache->save($data, 'regionlist' . str_replace(' ','_',$county));
	}
	return $data;
	}
	
	/** retrieve county list again as key pairs. 
	* @return array
	* @todo not sure why duplicate of first function. Fix it!(doofus Dan).
	*/
	public function getCountyname2() {
	if (!$data = $this->_cache->load('countynames2')) {
	$select = $this->select()
		->from($this->_name, array('county', 'county'))
		->order('county')
		->where('valid = ?', (int)1);
	$data = $this->getAdapter()->fetchPairs($select);
	$this->_cache->save($data, 'countynames2');
	}
	return $data;
	}

}
