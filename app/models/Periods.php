<?php
/** Retrieve and manipulate data the period thesauri
* @category 	Pas
* @package 		Pas_Db_Table
* @subpackage 	Abstract
* @author Daniel Pett dpett @ britishmuseum.org
* @copyright 2010 - DEJ Pett
* @license 		GNU General Public License
* @version 		1
* @since 		22 September 2011
* @todo add caching
*/
class Periods extends Pas_Db_Table_Abstract {

	protected $_name = 'periods';
	
	protected $_primary = 'id';
	
	/** Get period from dropdown
	* @return array
	*/
	public function getPeriodFrom() {
	if (!$options = $this->_cache->load('periodlistfrom')) {
	$select = $this->select()
  		->from($this->_name, array('id', 'term'))
		->order('id')
		->where('valid = ?', (int)1);
	$options = $this->getAdapter()->fetchPairs($select);
	$this->_cache->save($options, 'periodlistfrom');
	} 
	return $options;
    }
    
    /** Get period from in words dropdown
	* @return array
	*/
	public function getPeriodFromWords(){
	if (!$options = $this->_cache->load('periodlistwords')) {
	$select = $this->select()
		->from($this->_name, array('term', 'term'))
		->order('id')
		->where('valid = ?', (int)1);
	$options = $this->getAdapter()->fetchPairs($select);
	$this->_cache->save($options, 'periodlistwords');
	} 
	return $options;
    }
	
    /** Get period from second list (why)?
	* @return array
	*/
	public function getPeriodFrom2() {
	if (!$options = $this->_cache->load('periodlistfrom')) {
	$select = $this->select()
		->from($this->_name, array('id', 'term'))
		->order('id')
		->where('valid = 1');
	$options = $this->getAdapter()->fetchPairs($select);
	$this->_cache->save($options, 'periodlistfrom');
	} 
	return $options;
    }

	/** Get periods for coin mints
	* @return array
	*/
	public function getMintsActive() {
	if (!$options = $this->_cache->load('activemints')) {
	$select = $this->select()
		->from($this->_name, array('id', 'term'))
		->where('id IN (16,21,29,36,41,47,66,67)')
		->order('term');
	$actives = $this->getAdapter()->fetchPairs($select);
	$this->_cache->save($options, 'activemints');
	}
	return $actives;
    }
    
    /** Get periods for coins
	* @return array
	*/
	public function getCoinsPeriod(){
	if (!$options = $this->_cache->load('coinperiods')) {
	$select = $this->select()
		->from($this->_name, array('id', 'term'))
		->where('id IN (16,21,29,36,41,47,66,67) AND valid = 1')
		->order(array('fromdate','id'));
	$options = $this->getAdapter()->fetchPairs($select);
	$this->_cache->save($options, 'coinperiods');
	} 
	return $options;
    }

    /** Get medieval periods for coin mints
	* @return array
	*/
	public function getMedievalCoinsPeriodList()  {
	$select = $this->select()
		->from($this->_name, array('id', 'term'))
		->where('id IN (29,36,47) AND valid = 1')
		->order(array('fromdate','id'));
	$options = $this->getAdapter()->fetchPairs($select);
	return $options;
    }

	/** Get period name by period number
	* @param integer $period The period number
	* @return array
	*/
	public function getPeriodName($period) {
	$periods = $this->getAdapter();
	$select = $periods->select()
		->from($this->_name, array('id','term'))
		->order('id')
		->limit(1)
		->where('id = ?',(int)$period);
	return $periods->fetchAll($select);
	}

	/** Get valid periods
	* @return array
	*/
	public function getPeriods() {
	$periods = $this->getAdapter();
	$select = $periods->select()
		->from($this->_name)
		->where('valid = ?', (int)1)
		->order('fromdate ASC');
	return $periods->fetchAll($select);
	}

	/** Get periods for admin
	* @param integer $period The period number
	* @return array
	*/
	public function getPeriodsAll() {
	$periods = $this->getAdapter();
	$select = $periods->select()
		->from($this->_name)
		->joinLeft('users','users.id = '.$this->_name.'.createdBy',array('fullname'))
		->joinLeft('users','users_2.id = '.$this->_name.'.updatedBy',array('fn' => 'fullname'))
		->order('valid DESC');
	return $periods->fetchAll($select);
	}

	/** Get specific period details
	* @param integer $id The period number
	* @return array
	*/
	public function getPeriodDetails($id){
	$periods = $this->getAdapter();
	$select = $periods->select()
		->from($this->_name)
		->where('valid = ?',(int)1)
		->where('id = ?',(int)$id);
	return $periods->fetchAll($select);
	}

	/** Get object types by period
	* @param integer $period The period number
	* @return array
	*/
	public function getObjectTypesByPeriod($period) {
	if (!$data = $this->_cache->load('objbyperiod'.$period)) {
	$periods = $this->getAdapter();
	$select = $periods->select()
		->from($this->_name,array('term'))
		->joinLeft('finds',$this->_name . '.term = finds.broadperiod',
		array('title' => 'objecttype','weight' => 'COUNT(*)'))
		->where($this->_name . '.id =?',(int)$period)
		->order('finds.objecttype')
		->group('finds.objecttype');
	$data =  $periods->fetchAll($select);
	$this->_cache->save($data, 'objbyperiod'.$period);
	}
	return $data;
	}
}
