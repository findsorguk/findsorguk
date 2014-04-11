<?php
/** Model for manipulating emperor data
* @category Pas
* @package Pas_Db_Table
* @subpackage Abstract
* @author Daniel Pett dpett @ britishmuseum.org
* @copyright 2010 - DEJ Pett
* @license 		GNU General Public License
* @version 		1
* @since 		22 September 2011
* @todo add caching
*/
class Emperors extends Pas_Db_Table_Abstract {

	protected $_name = 'emperors';

	protected $_primary = 'id';

	/** get Roman Emperor details by id number
	* @param integer $id
	* @return array
	*/
	public function getEmperorDetails($id){
	if (!$data = $this->_cache->load('empdetails' . $id)) {
	$emperors = $this->getAdapter();
	$select = $emperors->select()
		->from($this->_name)
		->joinLeft(array('r' => 'reeceperiods'),'r.id = emperors.reeceID', array('p' => 'period_name'))
		->joinLeft(array('d' => 'dynasties'),'d.id = emperors.dynasty', array('i' => 'id','dyn' => 'dynasty'))
		->joinLeft('rulerImages','emperors.pasID = rulerImages.rulerID', array('filename'))
		->where('emperors.id= ?', (int)$id);
    $data = $emperors->fetchAll($select);
 	$this->_cache->save($data, 'empdetails' . $id);
	}
        return $data;
    }

    /** get Roman Emperor reverse types
	* @param integer $id
	* @return array
	* @todo perhaps move this to reverse types model?
	*/
	public function getEmperorRevTypes($id) {
	if (!$data = $this->_cache->load('emprevs'.$id)) {
	$emperors = $this->getAdapter();
	$select = $emperors->select()
		->from($this->_name,array('name','i' => 'id'))
		->joinLeft('rulers','emperors.pasID = rulers.id', array())
		->joinLeft('ruler_reversetype','rulers.id = ruler_reversetype.rulerID', array())
		->joinLeft('revtypes','revtypes.id = ruler_reversetype.reverseID', array())
		->where('revtypes.id = ?', (int)$id)
		->order('emperors.date_from');
	$data =$emperors->fetchAll($select);
	$this->_cache->save($data, 'emprevs'.$id);
	}
    return $data;
	}

	/** get Roman Emperor's available denominations by join on denoms to emperor table
	* @param integer $id
	* @return array
	* @todo perhaps move this to denominations model?
	*/
	public function getDenomEmperor($id) {
	if (!$data = $this->_cache->load('emprevs' . (int)$id)) {
	$emperors = $this->getAdapter();
	$select = $emperors->select()
		->from($this->_name)
		->joinLeft('rulers','emperors.pasID = rulers.id', array('i' => 'id'))
		->joinLeft('coins_denomxruler','rulers.id = coins_denomxruler.rulerID', array())
		->joinLeft('denominations','denominations.id = coins_denomxruler.denomID', array('denomID' => 'id'))
		->where('denominations.id = ?', (int)$id)
		->order($this->_name . '.' . $this->_primary);
       $data =  $emperors->fetchAll($select);
	$this->_cache->save($data, 'emprevs' . (int)$id);
	}
	return $data;
	}

	/** get Reece period for a Roman emperor
	* @param integer $id
	* @return array
	* @todo perhaps move this to reece period model?
	*/
	public function getReeceDetail($id) {
    if (!$data = $this->_cache->load('reecedetails'.$id)) {
    $reeces = $this->getAdapter();
	$select = $reeces->select()
		->from('emperors', array( 'id', 'issuer' => 'name', 'date_from', 
           'date_to','image','dbaseID' => 'pasID'))
		->joinLeft(array('r' => 'reeceperiods'),'r.id = emperors.reeceID', 
		array('period_name', 'description', 'date_range'))
		->where('emperors.reeceID = ?', (int)$id)
		->order($this->_name . '.' . $this->_primary);
	$data = $reeces->fetchAll($select);
    $this->_cache->save($data, 'reecedetails'.$id);
	}
        return $data;
    }

    /** get Reece period for a Roman emperor
	* @param integer $id
	* @return array
	* @todo why is this not in the dynasty model? Fool!
	*/
	public function getEmperorsDynasty($id) {
	$emperors = $this->getAdapter();
	$select = $emperors->select()
		->from('emperors', array('id', 'issuer' => 'name', 'date_from',
           'date_to','image','dbaseID' => 'pasID'))
	   ->where('emperors.dynasty = ?',$id)
	   ->order($this->_name . '.' . $this->_primary);
    return $emperors->fetchAll($select);
	}

	/** get administration list of emperors and paginate it
	* @return array
	*/
	public function getEmperorsAdminList($page) {
	$emperors = $this->getAdapter();
	$select = $emperors->select()->from($this->_name)
		   ->joinLeft('users',$this->_name . '.createdBy = users.id', array('fullname'))
		   ->joinLeft('users',$this->_name . '.updatedBy = users_2.id', array('fn' => 'fullname'))
		   ->group($this->_name . '.' . $this->_primary)
		   ->order($this->_name . '.' . $this->_primary);
	$paginator = Zend_Paginator::factory($select);
	$paginator->setItemCountPerPage(30)
				->setPageRange(10)
				->setCache($this->_cache);
	if(isset($page) && ($page != "")) {
	$paginator->setCurrentPageNumber($page);
	}
	return $paginator;
	}

	/** get dynasty to emperors
	* @return array
	* @todo is this a duplication of getEmperorsDynasty function?
	*/
	public function getDynEmp($id) {
	if (!$data = $this->_cache->load('dynasties' . $id)) {
	$emperors = $this->getAdapter();
	$select = $emperors->select()
		->from('emperors', array('id','issuer' => 'name','date_from','date_to','dbaseID' => 'pasID', 'dbpedia', 'viaf'))
        ->joinLeft('rulerImages',$this->_name . '.pasID = rulerImages.rulerID', array('image' => 'filename'))
	    ->where('emperors.dynasty = ?', $id)
	    ->order('emperors.date_from');
	$data = $emperors->fetchAll($select);
	$this->_cache->save($data, 'dynasties' . $id);
	}
    return $data;
	}

	/** Produce a sitemap list of emperors
	* @return array
	* @todo is this a duplication of getEmperorsDynasty function?
	*/
	public function getEmperorsSiteMap() {
	if (!$data = $this->_cache->load('empsSiteMap')) {
	$emperors = $this->getAdapter();
	$select = $emperors->select()
		->from('emperors', array('id','issuer' => 'name','dbaseID' => 'pasID','updated'))
	    ->order('date_from');
    $data = $emperors->fetchAll($select);
    $this->_cache->save($data, 'empsSiteMap');
	}
	return  $data;
	}

    public function getEmperorsTimeline(){
    if (!$data = $this->_cache->load('empsTimeline')) {
	$emperors = $this->getAdapter();
	$select = $emperors->select()->from('emperors')
	->order('date_from')
	->joinLeft('rulerImages',$this->_name . '.pasID = rulerImages.rulerID', array( 'filename'))
	->where('emperors.image IS NOT NULL');
	$data = $emperors->fetchAll($select);
    $this->_cache->save($data, 'empsTimeline');
	}
	return  $data;
    }
    
	/** get dynasty to emperors
	* @return array
	* @todo is this a duplication of getEmperorsDynasty function?
	*/
	public function getEmperors() {
	if (!$data = $this->_cache->load('emperorsJsonList')) {
	$emperors = $this->getAdapter();
	$select = $emperors->select()
		->from('emperors', array('id','issuer' => 'name','date_from','date_to','dbaseID' => 'pasID', 'dbpedia', 'viaf'))
        ->joinLeft('rulerImages',$this->_name . '.pasID = rulerImages.rulerID', array('image' => 'filename'))
	    ->order('emperors.date_from');
	$data = $emperors->fetchAll($select);
	$this->_cache->save($data, 'emperorsJsonList');
	}
    return $data;
	}
}
