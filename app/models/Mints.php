<?php
/** Retrieve and manipulate data for mints issuing coins
* @category Pas
* @package Pas_Db_Table
* @subpackage Abstract
*
* @author Daniel Pett dpett @ britishmuseum.org
* @copyright 2010 - DEJ Pett
* @license GNU General Public License
* @todo add caching throughout model as the cached version won't be changing!
*/

class Mints extends Pas_Db_Table_Abstract {

	protected $_name = 'mints';

	protected $_primary = 'id';

	/** Get all Roman mints as a key value pair list for dropdown
	* @return array
	* @todo add caching
	*/
	public function getRomanMints() {
	$select = $this->select()
		->from($this->_name, array('id', 'mint_name'))
		->where('period = 21 and valid = 1')
		->order($this->_primary);
	$options = $this->getAdapter()->fetchPairs($select);
    return $options;
    }

    /** Get all Byzantine mints as a key value pair list for dropdown
	* @return array
	* @todo add caching
	*/
	public function getMintsByzantine() {
	$select = $this->select()
		->from($this->_name, array('id', 'mint_name'))
		->where('period = 67 and valid = 1')
		->order($this->_primary);
	$options = $this->getAdapter()->fetchPairs($select);
	return $options;
    }

    /** Get all Greek mints as a key value pair list for dropdown
	* @return array
	* @todo add caching
	*/
	public function getMintsGreek(){
	$select = $this->select()
		->from($this->_name, array('id', 'mint_name'))
		->where('period = 66 and valid = 1')
		->order($this->_primary);
	$options = $this->getAdapter()->fetchPairs($select);
	return $options;
    }

    /** Get all Byzantine mints as a list
	* @return array
	* @todo add caching
	*/
	public function getMintsByzantineList(){
	$select = $this->select()
		->from($this->_name, array('i' => 'id', 'mint_name','mint_id' => 'id'))
		->where('period = ?',(int)67)
		->where('valid = ?', (int)1)
		->order($this->_primary);
	$options = $this->getAdapter()->fetchAll($select);
	return $options;
    }
 	/** Get all Greek mints as a list
	* @return array
	* @todo add caching
	*/
	public function getMintsGreekList(){
	$select = $this->select()
		->from($this->_name, array('i' => 'id', 'mint_name','mint_id' => 'id'))
		->where('period = ?',(int)67)
		->where('valid = ?', (int)1)
		->order($this->_primary);
        $options = $this->getAdapter()->fetchAll($select);
	return $options;
    }

    /** Get all Medieval mints as a key value pair list for dropdown
	* @return array
	* @todo add caching
	*/
	public function getMedievalMints() {
	$select = $this->select()
		->from($this->_name, array('id', 'mint_name'))
		->where('period = 29 AND valid = 1')
		->order('mint_name');
	$options = $this->getAdapter()->fetchPairs($select);
	return $options;
    }
	/** Get all post Medieval mints as a key value pair list for dropdown
	* @return array
	* @todo add caching
	*/
	public function getPostMedievalMints() {
	$select = $this->select()
		->from($this->_name, array('id', 'mint_name'))
		->where('period = 36 and valid = 1')
 		->order($this->_primary);
	$options = $this->getAdapter()->fetchPairs($select);
	return $options;
    }
    /** Get all early Medieval mints as a key value pair list for dropdown
	* @return array
	* @todo add caching
	*/
	public function getEarlyMedievalMints(){
	$select = $this->select()
		->from($this->_name, array('id', 'mint_name'))
		->where('period = 47 and valid = 1')
		->order($this->_primary);
	$options = $this->getAdapter()->fetchPairs($select);
	return $options;
    }

    /** Get all iron age mints as a key value pair list for dropdown
	* @return array
	* @todo add caching
	*/
    public function getIronAgeMints(){
	$select = $this->select()
		->from($this->_name, array('id', 'mint_name'))
		->where('period = 16 and valid = 1')
		->order($this->_primary);
	$options = $this->getAdapter()->fetchPairs($select);
	return $options;
    }

    /** Get all  mints by period id as a key value pair list for dropdown
	* @param integer $periodID The specific ID of the period
	* @return array
	* @todo add caching
	*/
	public function getMints($periodID){
	$select = $this->select()
		->from($this->_name, array('id', 'mint_name'))
		->where('period = ?', (int)$periodID)
		->where('valid = ?', (int)1)
		->order('mint_name');
	$options = $this->getAdapter()->fetchPairs($select);
	return $options;
    }

    /** Get a list of all iron age mints
	* @return array
	* @todo add caching
	*/
	public function listIronAgeMints() {
	$rulers = $this->getAdapter();
	$select = $rulers->select()
		->from($this->_name, array('i' => 'id', 'mint_name', 'created', 'updated', 'valid', 'mint_id' => 'id'))
		->joinLeft('periods','periods.id = mints.period',array('p' => 'term'))
		->where('period = ?', (int)16)
		->where($this->_name . '.valid = ?', (int)1)
		->order('mints.id');
	return $rulers->fetchAll($select);
    }

    /** Get all list of all  mints by period id
	* @param integer $periodID The specific ID of the period
	* @return array
	*/
	public function getListMints($periodID) {
	if (!$data = $this->_cache->load('getlistmints' . $periodID)) {
	$rulers = $this->getAdapter();
	$select = $rulers->select()
		->from($this->_name, array('id', 'name' => 'mint_name', 'created', 'updated', 'valid'))
		->joinLeft('periods','periods.id = mints.period', array('p' => 'term'))
		->where('period = ?', (int)$periodID)
		->order('mint_name');
	$data =  $rulers->fetchAll($select);
	$this->_cache->save($data, 'getlistmints' . $periodID);
	}
	return $data;
	}

	/** Get details of a specific iron age mint
	* @param integer $mintID The specific ID of the mint
	* @return array
	* @todo add caching
	*/
	public function getIronAgeMint($mintID) {
	$rulers = $this->getAdapter();
	$select = $rulers->select()
		->from($this->_name, array('pasID' => 'id', 'name' => 'mint_name', 'updated', 'created'))
		->joinLeft('periods','periods.id = mints.period',array('p' => 'term', 'i' => 'id'))
		->where('mints.id = ?', (int)$mintID)
		->where('period = ?', (int)16)
		->order('mints.id ASC');
	return $rulers->fetchAll($select);
    }

    /** Get mints attached to a roman ruler
	* @param integer $rulerID The specific ID of the ruler
	* @return array
	* @todo add caching
	*/
	public function getRomanMintRuler($rulerID) {
	$mints = $this->getAdapter();
	$select = $mints->select()
		->from($this->_name, array('id', 'term' => 'mint_name'))
		->joinLeft('mints_rulers','mints_rulers.mint_id = mints.id', array())
		->joinLeft('rulers','rulers.id = mints_rulers.ruler_id', array())
		->where('rulers.id = ?', (int)$rulerID)
		->order('mints.mint_name ASC');
	return $mints->fetchAll($select);
    }

    /** Get mints attached to a roman ruler for admin console
	* @param integer $rulerID The specific ID of the ruler
	* @return array
	* @todo add caching
	*/
	public function getRomanMintRulerAdmin($rulerID)  {
	$mints = $this->getAdapter();
	$select = $mints->select()
		->from($this->_name, array('id', 'term' => 'mint_name'))
		->joinLeft('mints_rulers','mints_rulers.mint_id = mints.id', array('created', 'linkid' => 'id'))
		->joinLeft('rulers','rulers.id = mints_rulers.ruler_id', array())
		->joinLeft('users','users.id = mints_rulers.createdBy', array('fullname'))
		->joinLeft('periods','periods.id = mints.period', array('period' => 'term'))
		->where('rulers.id = ?',$rulerID)
		->order('mints.mint_name ASC');
	return $mints->fetchAll($select);
    }

    /** Get mints attached to a roman emperor
	* @param integer $emperorID The specific ID of the emperor
	* @return array
	* @todo add caching
	*/
	public function getMintEmperorList($emperorID) {
	$mints = $this->getAdapter();
	$select = $mints->select()
		->from($this->_name,array('mint_name','mint_id' => 'id', 'pleiadesID', 'woeid', 'geonamesID'))
		->joinLeft('mints_rulers','mints_rulers.mint_id = mints.id' ,array())
		->joinLeft('romanmints','romanmints.pasID = mints.id', array('i' => 'id'))
		->joinLeft('emperors','mints_rulers.ruler_id = emperors.pasID', array('pasID', 'name', 'id'))
		->where('emperors.id = ?', (int)$emperorID);
	return $mints->fetchAll($select);
	}

	/** Get early med mints attached to a ruler
	* @param integer $rulerID The specific ID of the ruler
	* @return array
	* @todo add caching
	*/
	public function getEarlyMedMintRuler($rulerID) {
	$mints = $this->getAdapter();
	$select = $mints->select()
		->from($this->_name, array('id','term' => 'mint_name'))
		->joinLeft('mints_rulers','mints_rulers.mint_id = mints.id', array())
		->joinLeft('rulers','rulers.id = mints_rulers.ruler_id', array())
		->where('rulers.id = ?', (int)$rulerID)
		->order('mints.mint_name ASC');
	return $mints->fetchAll($select);
    }

    /** Get early med mints attached to a ruler as key value pairs for dropdown
	* @param integer $rulerID The specific ID of the ruler
	* @return array
	* @todo add caching
	*/
	public function getEarlyMedMintRulerPairs($rulerID) {
	$mints = $this->getAdapter();
	$select = $mints->select()
		->from($this->_name, array('id','term' => 'mint_name'))
		->joinLeft('mints_rulers','mints_rulers.mint_id = mints.id', array())
		->joinLeft('rulers','rulers.id = mints_rulers.ruler_id', array())
		->where('rulers.id = ?', (int)$rulerID)
		->order('mints.mint_name ASC');
	return $mints->fetchPairs($select);
    }

    /** Get med mints attached to a ruler as list
	* @param integer $rulerID The specific ID of the ruler
	* @return array
	* @todo add caching
	*/
	public function getMedMintRuler($rulerID) {
	$mints = $this->getAdapter();
	$select = $mints->select()
		->from($this->_name, array('id','name' => 'mint_name'))
		->joinLeft('mints_rulers','mints_rulers.mint_id = mints.id', array('i' => 'id'))
		->joinLeft('rulers','rulers.id = mints_rulers.ruler_id', array('rulerID' => 'id'))
		->where('rulers.id = ?', (int)$rulerID)
		->order('mints.mint_name ASC');
	return $mints->fetchAll($select);
    }

    /** Get  med mints attached to a ruler - unsure what this is for
	* @param integer $rulerID The specific ID of the ruler
	* @return array
	* @todo add caching
	*/
	public function getMedMintRulerLatest($rulerID)  {
	$mints = $this->getAdapter();
	$select = $mints->select()
		->from($this->_name, array('id', 'mint_name'))
		->joinLeft('mints_rulers','mints_rulers.mint_id = mints.id', array())
		->joinLeft('rulers','rulers.id = mints_rulers.ruler_id', array())
		->where('mints_rulers.ruler_id = ?', (int)$rulerID)
		->order('mints.id ASC')
		->limit((int)1);
	return $mints->fetchAll($select);
    }

    /** Get specific mint name from an id
	* @param integer $mintID The specific ID of the mint
	* @return array
	* @todo add caching and change to fetch row
	*/
	public function getMintName($mintID) {
	$mints = $this->getAdapter();
	$select = $mints->select()
		->from($this->_name, array('mint_name'))
		->joinLeft('periods','periods.id = mints.period', array('term'))
		->where('mints.id = ?', (int)$mintID)
		->limit(1);
	return $mints->fetchAll($select);
    }

    /** Get specific mint name from a reverse type IOD
	* @param integer $reverseID The specific ID of the reverse
	* @return array
	* @todo add caching
	*/
	public function getMintReverseType($reverseID){
	$mints = $this->getAdapter();
	$select = $mints->select()
		->from($this->_name, array('mint_name', 'mint_id' => 'id'))
		->joinLeft('romanmints','mints.id = romanmints.pasID', array('i' => 'id'))
		->joinLeft('mint_reversetype','mints.id = mint_reversetype.mintID', array())
		->joinLeft('revtypes','mint_reversetype.reverseID = revtypes.id', array('id'))
		->where('revtypes.id = ?', (int)$reverseID);
	return $mints->fetchAll($select);
	}

	/** Get specific mint details from an id
	* @param integer $mintID The specific ID of the mint
	* @return array
	* @todo add caching
	*/
	public function getMintDetails($mintID) {
	$mints = $this->getAdapter();
	$select = $mints->select()
		->from($this->_name)
		->joinLeft('periods', 'periods.id = mints.period', array('term'))
		->where('mints.id = ?', (int)$mintID)
		->limit(1);
	return $mints->fetchAll($select);
    }

    /** Get paginated list of mints
	* @param integer $params['period'] The period id number
	* @param integer $params['page'] The page number
	* @return array
	* @todo add caching
	*/
	public function getMintsListAll($params) {
	$mints = $this->getAdapter();
	$select = $mints->select()
		->from($this->_name)
		->joinLeft('periods','periods.id = mints.period', array('t' => 'term'))
		->where($this->_name . '.valid = ?',(int)1);
	if(isset($params['period']) && ($params['period'] != "")) {
	$select->where('period = ?',(int)$params['period']);
	}
	$paginator = Zend_Paginator::factory($select);
	$paginator->setItemCountPerPage(30)
		->setPageRange(10);
	if(isset($params['page']) && ($params['page'] != "")) {
	$paginator->setCurrentPageNumber($params['page']);
	}
	return $paginator;
    }

    /** Get paginated list of mints for admin console
	* @param integer $params['period'] The period id number
	* @param integer $params['page'] The page number
	* @return array
	* @todo add caching
	*/
	public function getMintsListAllAdmin($params){
	$mints = $this->getAdapter();
	$select = $mints->select()
		->from($this->_name)
		->joinLeft('periods','periods.id = mints.period', array('t' => 'term'))
		->joinLeft('users',$this->_name . '.createdBy = users.id', array('fullname'))
		->joinLeft('users',$this->_name . '.updatedBy = users_2.id', array('fn' => 'fullname'))
		->where($this->_name . '.valid = ?',(int)1);
	if(isset($params['period']) && ($params['period'] != "")) {
	$select->where('period = ?',(int)$params['period']);
	}
	$paginator = Zend_Paginator::factory($select);
	$paginator->setItemCountPerPage(30)
		->setPageRange(10);
	if(isset($params['page']) && ($params['page'] != "")) {
	$paginator->setCurrentPageNumber($params['page']);
	}
	return $paginator;
    }

    /** Get mints by period for sitemap
	* @param integer $periodID The period id number
	* @return array
	*/
	public function getMintsSiteMap($periodID){
	if (!$data = $this->_cache->load('mintlistsmap' . $periodID)) {
	$mints = $this->getAdapter();
	$select = $mints->select()
		->from($this->_name, array('id', 'mint_name', 'updated'))
		->where('period = ?', $periodID)
		->where('valid = ?', (int)1)
		->order('mints.id ASC');
	$data = $mints->fetchAll($select);
	$this->_cache->save($data, 'mintlistsmap' . $periodID);
	}
	return $data;
    }
    
    public function getPleiadesID($mint){
//    if (!$data = $this->_cache->load('pleiades' . $mint)) {
	$mints = $this->getAdapter();
	$mint = $mints->fetchRow($this->select()->where('id =' . $mint));
	$data = $mint->pleiadesID;
//	$this->_cache->save($data, 'pleiades' . $mint);
//	}
	return $data;	
    }
}