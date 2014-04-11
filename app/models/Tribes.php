<?php
/** Data model for accessing tribe listings on the database
* @category 	Pas
* @package 		Pas_Db_Table
* @subpackage 	Abstract
* @author 		Daniel Pett dpett @ britishmuseum.org
* @copyright 	2010 - DEJ Pett
* @license 		GNU General Public License
* @version 		1
* @since 		22 October 2010, 17:12:34
* @todo 		sort out cache and cleaning
*/
class Tribes extends Pas_Db_Table_Abstract {

	protected $_name = 'ironagetribes';

	protected $_primary = 'id';

	/** Get a key value pair list of tribes
	* @return array
	*/
	public function getTribes() {
	$tribes = $this->getAdapter();
	$select = $tribes->select()
		->from($this->_name, array('id','tribe'))
//		->where($this->_name . '.valid = ?',(int)1)
		->order($this->_primary);
	return $tribes->fetchPairs($select);
    }

    /** Get a  list of tribes
	* @return array
	*/
	public function getTribesList()  {
	$tribes = $this->getAdapter();
	$select = $tribes->select()
		->from($this->_name, array('id','tribe'))
		->joinLeft('ironageregionstribes','ironageregionstribes.tribeID =' . $this->_name . '.id', array())
		->joinLeft('geographyironage','geographyironage.id = ironageregionstribes.regionID', array('area','region'))
//		->where($this->_name . '.valid = ?', 1)
		->order('id');
	$options = $this->getAdapter()->fetchAll($select);
	return $options;
    }

    /** Get a paginated list of all tribes for administration
	* @param integer $page the page number
	* @return array
	*/
	public function getTribesListAdmin($page) {
	$tribes = $this->getAdapter();
	$select = $tribes->select()
		->from($this->_name)
		->joinLeft('users','users.id = ' . $this->_name . '.createdBy', array('fullname'))
		->joinLeft('users','users_2.id = ' . $this->_name . '.updatedBy', array('fn' => 'fullname'))
		->order('id');
	$paginator = Zend_Paginator::factory($select);
	$paginator->setItemCountPerPage(30)
		->setPageRange(10);
	if(isset($page) && ($page != ""))  {
    $paginator->setCurrentPageNumber($page);
	}
	return $paginator;
	}

	/** Get a tribe details
	* @param integer $id the tribe id number
	* @return array
	*/
	public function getTribe($id) {
	$tribes = $this->getAdapter();
	$select = $tribes->select()
		->from($this->_name, array('id','tribe','description'))
//		->where($this->_name . '.valid = ?', (int)1)
		->where('id = ?',(int)$id)
		->order('id ASC');
        return $tribes->fetchAll($select);
    }
	/** Get a tribe to region list, cached
	* @param integer $region the region's id number
	* @return array
	*/
	public function getIronAgeTribeRegion($region) {
	$tribes = $this->getAdapter();
	$select = $tribes->select()
		->from($this->_name, array('id','term' => 'tribe'))
		->joinLeft('ironageregionstribes','ironageregionstribes.tribeID = ironagetribes.id', array())
		->joinLeft('geographyironage','ironageregionstribes.regionID = geographyironage.id', array())
		->where('geographyironage.id = ?', (int)$region)
		->order('ironagetribes.tribe ASC');
	return $tribes->fetchAll($select);
	}

	/** Get a tribe list for xml site map
	* @return array
	*/
	public function getSitemap(){
	if (!$data = $this->_cache->load('tribeslist')) {
	$tribes = $this->getAdapter();
	$select = $tribes->select()
		->from($this->_name, array('id','term' => 'tribe','updated'))
		->order('ironagetribes.tribe ASC');
	$data =  $tribes->fetchAll($select);
	$this->_cache->save($data, 'tribeslist');
	}
	return $data;
	}

}
