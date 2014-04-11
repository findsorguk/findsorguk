<?php
/** Data model for accessing and manipulating rulers or issuers of coins
* @category 	Pas
* @package 		Pas_Db_Table
* @subpackage 	Abstract
* @author 		Daniel Pett dpett @ britishmuseum.org
* @copyright 	2010 - DEJ Pett
* @license 		GNU General Public License
* @version 		1
* @since 		22 October 2010, 17:12:34
*/
class Rulers extends Pas_Db_Table_Abstract {

	protected $_name = 'rulers';

	protected $_primary = 'id';

	/** Get all roman issuers as a key value pair for dropdown listing
	* @return Array $options
	* @todo add caching
	*/
	public function getOptions() {
		$select = $this->select()
		->from($this->_name, array('id', 'issuer'))
		->where('period = ?', (int)21)
		->order('issuer');
		$options = $this->getAdapter()->fetchPairs($select);
	return $options;
    }

    /** Get all issuers as an array by period
    * @param integer $periodID the period ID to query by
	* @return Array
	* @todo add caching
	*/
	public function getAllRulers($periodID) {
		$rulers = $this->getAdapter();
		$select = $rulers->select()
			->from($this->_name, array('id','term' => 'issuer'))
			->where('period = ?', (int)$periodID)
			->order($this->_primary);
    return $rulers->fetchAll($select);
    }

    /** Get all issuers as an array of key value pairs for the Greek
    * and Roman Provincial period
	* @return Array
	* @todo add caching
	*/
	public function getRulersGreek() {
		$select = $this->select()
			->from($this->_name, array('id','term' => 'issuer'))
			->where('period = ?', (int)66)
			->order('date1')
			->order('date2')
			->where('valid = ?', (int)1);
		$options = $this->getAdapter()->fetchPairs($select);
	return $options;
    }

	/** Get all issuers as an array of key value pairs for the Byzantine period
	* @return Array
	* @todo add caching
	*/
	public function getRulersByzantine() {
		$select = $this->select()
			->from($this->_name, array('id','term' => 'issuer'))
			->where('period = ?', (int)67)
			->order('term')
			->where('valid = ?', (int)1);
		$options = $this->getAdapter()->fetchPairs($select);
	return $options;
    }

    /** Get all issuers as a paginated array for the Byzantine period
    * @param integer $page page id
	* @return Array
	* @todo add caching
	*/
	public function getRulersByzantineList($page) {
	$rulers = $this->getAdapter();
	$select = $this->select()
			->from($this->_name)
			->where('period = ?',(int)67)
			->order('date1')
			->order('date2')
			->where('valid = ?',(int)1);
	$data = $rulers->fetchAll($select);
	$paginator = Zend_Paginator::factory($data);
	$paginator->setItemCountPerPage(30)
	          ->setPageRange(10);
	if(isset($page) && ($page != "")) {
	$paginator->setCurrentPageNumber($page);
	}
	return $paginator;
	}

	/** Get all issuers as a paginated array for the Greek and Roman Provincial period
	* @param integer $params['page'] page number
	* @return Array
	* @todo add caching
	*/
	public function getRulersGreekList($params) {
	$rulers = $this->getAdapter();
	$select = $this->select()
		->from($this->_name)
		->where('period = ?',(int)66)
		->order('date1')
		->order('date2')
		->where('valid = ?',(int)1);
	$data = $rulers->fetchAll($select);
	$paginator = Zend_Paginator::factory($data);
	$paginator->setItemCountPerPage(30)
			->setPageRange(10);
	if(isset($params['page']) && ($params['page'] != "")){
	$paginator->setCurrentPageNumber($params['page']);
	}
	return $paginator;
	}

	/** Get early medieval rulers, concatenated dates as a key value pair
	* @return Array
	* @todo add caching
	*/
	public function getEarlyMedRulers() {
		$rulers = $this->getAdapter();
		$select = $rulers->select()
			->from($this->_name, array('id','term' => 'CONCAT(issuer," (",date1," - ",date2,")")'))
			->where('period = ?',(int)47)
			->where('valid = ?',(int)1)
			->order('date1');
	return $rulers->fetchPairs($select);
    }

    /** Get Roman rulers, concatenated dates as a key value pair
	* @return Array
	* @todo add caching
	*/
	public function getRomanRulers() {
		$select = $this->select()
			->from($this->_name, array('id','term' => 'CONCAT(issuer," (",date1," - ",date2,")")'))
			->where('period = ?',(int)21)
			->where('valid =?',(int)1)
			->order('date1')
			->order('date2');
		$options = $this->getAdapter()->fetchPairs($select);
	return $options;
    }

    /** Get Medieval rulers as a key value pair array
	* @return Array
	* @todo add caching
	*/
	public function getAllMedRulers() {
		$select = $this->select()
			->from($this->_name, array('id', 'term' => 'issuer'))
			->where('period IN (29,36,47)')
			->order('date1')
			->order('date2');
		$options = $this->getAdapter()->fetchPairs($select);
	return $options;
    }

    /** Get Medieval rulers as a key value pair array
	* @return Array
	* @todo add caching
	*/
	public function getMedievalRulers() {
		$rulers = $this->getAdapter();
		$select = $rulers->select()
			->from($this->_name, array('id', 'term' => 'CONCAT(issuer," (",date1," - ",date2,")")'))
			->where('period = ?', (int)29)
			->where('valid =?',(int)1)
			->order('id');
	return $rulers->fetchPairs($select);
    }

    /** Get Medieval rulers as an array
	* @return Array
	*/
	public function getMedievalRulersList() {
	if (!$data = $this->_cache->load('medievalListRulers')) {
		$rulers = $this->getAdapter();
		$select = $rulers->select()
			->from($this->_name)
			->where('period = ?',(int)29)
			->order('id');
		$data = $rulers->fetchAll($select);
		$this->_cache->save($data, 'medievalListRulers');
		}
	return $data;
    }

    /** Get Early Medieval rulers as an array
	* @return Array
	*/
	public function getEarlyMedievalRulersList() {
	if (!$data = $this->_cache->load('earlymedievalListRulers')) {
		$rulers = $this->getAdapter();
		$select = $rulers->select()
			->from($this->_name)
			->where('period = ?',(int)29)
			->order('id');
		$data = $rulers->fetchAll($select);
		$this->_cache->save($data, 'earlymedievalListRulers');
    	}
	return $data;
    }

    /** Get Iron Age rulers as an array
	* @return Array
	*/
	public function getIARulersList() {
	if (!$data = $this->_cache->load('ialistRulers')) {
		$rulers = $this->getAdapter();
		$select = $rulers->select()
			->from($this->_name)
			->where('period = ?',(int)16)
			->order('id');
		$data = $rulers->fetchAll($select);
		$this->_cache->save($data, 'islistRulers');
    	}
	return $data;
    }

    /** Get Greek and Roman rulers as an array
	* @return Array
	*/
	public function getGreekRulersList(){
	if (!$data = $this->_cache->load('greeklistRulers')) {
		$rulers = $this->getAdapter();
		$select = $rulers->select()
			->from($this->_name)
			->where('period = ?',(int)66)
			->order('id');
		$data = $rulers->fetchAll($select);
		$this->_cache->save($data, 'greeklistRulers');
	}
    return $data;
    }

    /** Get Byzantine rulers as an array
	* @return Array
	*/
	public function getByzRulersList() {
	if (!$data = $this->_cache->load('byzlistRulers')) {
		$rulers = $this->getAdapter();
		$select = $rulers->select()
			->from($this->_name)
			->where('period = ?',(int)67)
			->order('id');
		$data = $rulers->fetchAll($select);
		$this->_cache->save($data, 'byzlistRulers');
    	}
	return $data;
    }

    /** Get Post Medieval rulers as an array
	* @return Array
	*/
	public function getPostMedievalRulersList() {
	if (!$data = $this->_cache->load('pmedlistRulers')) {
		$rulers = $this->getAdapter();
		$select = $rulers->select()
			->from($this->_name)
			->where('period = ?', (int)36)
			->order('id');
		$data = $rulers->fetchAll($select);
		$this->_cache->save($data, 'pmedlistRulers');
	}
	return $data;
    }

    /** Get Post Medieval rulers as an array of key value pairs
	* @return Array
	*/
	public function getPostMedievalRulers() {
		$rulers = $this->getAdapter();
		$select = $rulers->select()
			->from($this->_name, array('id','term' => 'CONCAT(issuer," (",date1," - ",date2,")")'))
			->where('period = ?', (int)36)
			->where('valid = ?', (int)1)
			->order('date1');
	return $rulers->fetchPairs($select);
    }

    /** Get Early Medieval rulers as an array by category ID
    * @param integer $catID
	* @return Array
	*/
	public function getEarlyMedievalRulers($catID)  {
		$rulers = $this->getAdapter();
		$select = $rulers->select()
			->from($this->_name)
			->joinLeft('medievaltypes','medievaltypes.rulerID = ' . $this->_name . '.id',array())
			->joinLeft('categoriescoins','categoriescoins.id = medievaltypes.categoryID',array('category'))
			->where('period = ?', (int)47)
			->where($this->_name.'.valid', (int)1)
			->where('medievaltypes.categoryID = ?', (int)$catID)
			->group($this->_name.'.id')
			->order('date1');
	return $rulers->fetchAll($select);
    }

    /** Get Early Medieval rulers as an array for ajax by category ID
    * @param integer $catID
	* @return Array
	*/
	public function getEarlyMedievalRulersAjax($catID) {
		$rulers = $this->getAdapter();
		$select = $rulers->select()
			->from($this->_name, array('id','term' => 'CONCAT(issuer," (",date1," - ",date2,")")'))
			->joinLeft('medievaltypes','medievaltypes.rulerID = ' . $this->_name . '.id',array())
			->joinLeft('categoriescoins','categoriescoins.id = medievaltypes.categoryID',array())
			->where('period = ?', (int)47)
			->where($this->_name.'.valid', (int)1)
			->where('medievaltypes.categoryID = ?', (int)$catID)
			->group($this->_name.'.id')
			->order('date1');
	return $rulers->fetchAll($select);
    }

    /** Get Medieval rulers as an array for ajax by category ID
    * @param integer $catID
	* @return Array
	*/
	public function getMedievalRulersAjax($catID) {
		$rulers = $this->getAdapter();
		$select = $rulers->select()
			->from($this->_name, array('id','term' => 'CONCAT(issuer," (",date1," - ",date2,")")'))
			->joinLeft('medievaltypes','medievaltypes.rulerID = ' . $this->_name . '.id',array())
			->joinLeft('categoriescoins','categoriescoins.id = medievaltypes.categoryID',array())
			->where('period = ?', (int)29)
			->where($this->_name . '.valid', (int)1)
			->where('medievaltypes.categoryID = ?', (int)$catID)
			->group($this->_name . '.id')
			->order('date1');
        return $rulers->fetchAll($select);
    }

    /** Get Post Medieval rulers as an array for ajax by category ID
    * @param integer $catID
	* @return Array
	*/
	public function getPostMedievalRulersAjax($catID) {
		$rulers = $this->getAdapter();
		$select = $rulers->select()
			->from($this->_name, array('id','term' => 'CONCAT(issuer," (",date1," - ",date2,")")'))
			->joinLeft('medievaltypes','medievaltypes.rulerID = ' . $this->_name . '.id',array())
			->joinLeft('categoriescoins','categoriescoins.id = medievaltypes.categoryID',array())
			->where('period = ?', (int)36)
			->where($this->_name . '.valid', (int)1)
			->where('medievaltypes.categoryID = ?', (int)$catID)
			->group($this->_name . '.id')
			->order('date1');
       return $rulers->fetchAll($select);
    }

    /** Get Medieval rulers listed as an array for ajax by category ID and period
    * @param integer $catID category ID
    * @param integer $period period ID
	* @return Array
	*/
	public function getMedievalRulersListed($catID, $period) {
        $rulers = $this->getAdapter();
		$select = $rulers->select()
			->from($this->_name, array('id','issuer','date1','date2'))
			->joinLeft('medievaltypes','medievaltypes.rulerID = ' . $this->_name . '.id',array())
			->joinLeft('categoriescoins','categoriescoins.id = medievaltypes.categoryID',array('category'))
			->where('period = ?',(int)$period)
			->where('country IS NULL')
			->where($this->_name . '.valid = ?', (int)1)
			->where('medievaltypes.categoryID = ?', (int)$catID)
			->where('display = ?', (int)1)
			->group($this->_name . '.id')
			->order('date1');
	return $rulers->fetchAll($select);
    }

    /** Get Medieval rulers listed as an array for ajax by category ID and period
    * @param integer $catID category ID
    * @param integer $period period ID
	* @return Array
	*/
	public function getMedievalRulersListedMain($period) {
        $rulers = $this->getAdapter();
		$select = $rulers->select()
			->from($this->_name, array('id','issuer','date1','date2'))
			->joinLeft('medievaltypes','medievaltypes.rulerID = ' . $this->_name . '.id',array())
			->where('period = ?', (int)$period)
			->where('country IS NULL')
			->where($this->_name . '.valid = ?', (int)1)
			->where('display = ?', (int)1)
			->group($this->_name . '.id')
			->order('date1');
	return $rulers->fetchAll($select);
    }

    /** Get rulers listed as an array for ajax by category ID and country
    * @param integer $catID category ID
    * @param integer $country country ID
	* @return Array
	*/
	public function getForeign($period, $country ){
        $rulers = $this->getAdapter();
		$select = $rulers->select()
			->from($this->_name, array('id', 'issuer', 'date1', 'date2'))
			->joinLeft('medievaltypes','medievaltypes.rulerID = ' . $this->_name.'.id',array())
			->where('period = ?', (int)$period)
			->where($this->_name . '.valid = ?', (int)1)
			->where('display = ?', (int)1)
			->where('country = ?', (int)$country)
			->group($this->_name . '.id')
			->order('date1');
	return $rulers->fetchAll($select);
    }

	/** Get medieval ruler profile by id number
    * @param integer $id ruler id
	* @return Array
	*/
	public function getMedievalRulerProfile($id) {
		$rulers = $this->getAdapter();
		$select = $rulers->select()
			->from($this->_name, array('id', 'issuer', 'date1', 'date2'))
			->where('valid = ?', (int)1)
			->where('id = ?', (int)$id)
			->limit(1);
	return $rulers->fetchAll($select);
    }

    /** Get any  ruler profile by id number
    * @param integer $id ruler id
	* @return Array
	*/
	public function getRulerProfile($id) {
		$rulers = $this->getAdapter();
		$select = $rulers->select()
			->from($this->_name)
			->where('valid = ?', (int)1	)
			->where('id = ?', (int)$id)
			->limit(1);
	return $rulers->fetchAll($select);
    }

    /** Get any  ruler profile by id number for admin section
    * @param integer $id ruler id
	* @return Array
	*/
	public function getRulerProfileAdmin($id) {
		$rulers = $this->getAdapter();
		$select = $rulers->select()
			->from($this->_name)
			->where('id = ?',(int)$id)
			->limit('1');
	return $rulers->fetchAll($select);
    }

    /** Get northumbrian issuers
	* @return Array
	*/
	public function getEarlyMedievalRulersNorthumbrian() {
		$rulers = $this->getAdapter();
		$select = $rulers->select()
			->from($this->_name, array('id','issuer','date1','date2'))
			->where('period = ?',(int)47)
			->where('valid',(int)1)
			->order('date1');
	return $rulers->fetchAll($select);
    }

    /** Get all Iron Age rulers as key value pair array
	* @return Array
	*/
	public function getIronAgeRulers() {
		$rulers = $this->getAdapter();
		$select = $rulers->select()
			->from($this->_name, array('id','issuer'))
			->where('period = ?', (int)16)
			->order('issuer ASC');
	return $rulers->fetchPairs($select);
    }

    /** Get all Iron Age rulers as a list
	* @return Array
	*/
	public function getIronAgeRulersListed()  {
		$rulers = $this->getAdapter();
		$select = $rulers->select()
			->from($this->_name, array('id','issuer','region'))
			->where('period = ?', (int)16)
			->where('valid = ?', (int)1)
			->order('issuer ASC');
	return $rulers->fetchAll($select);
    }

    /** Get an Iron Age ruler profile
    * @param integer $id ruler id number
	* @return Array
	*/
	public function getIronAgeRuler($id) {
		$rulers = $this->getAdapter();
		$select = $rulers->select()
			->from($this->_name, array('id','issuer','date1','date2','region'))
			->where('id = ?', (int)$id)
			->where('period = ?', (int)16)
			->order('id ASC');
	return $rulers->fetchAll($select);
    }

    /** Get rulers by denomination
    * @param integer $denomination denomination number
	* @return Array
	*/
	public function getRomanDenomRuler($denomination) {
		$rulers = $this->getAdapter();
		$select = $rulers->select()
			->from($this->_name, array('id','term' => 'CONCAT(issuer," (",date1," - ",date2,")")'))
			->joinLeft('denominations_rulers','rulers.id = denominations_rulers.ruler_id', array())
			->joinLeft('denominations','denominations.id = denominations_rulers.denomination_id', array())
			->where('denominations.id = ?', (int)$denomination)
			->order('issuer ASC');
	return $rulers->fetchAll($select);
	}

	/** Get Iron Age  region  by ruler
    * @param integer $ruler ruler number
	* @return Array
	*/
	public function getIronAgeRulerRegion($ruler) {
		$rulers = $this->getAdapter();
		$select = $rulers->select()
            ->from($this->_name, array('id','term' => 'issuer'))
			->joinLeft('ironagerulerxregion','ironagerulerxregion.rulerID = rulers.id', array())
			->joinLeft('geographyironage','ironagerulerxregion.regionID = geographyironage.id', array())
			->where('geographyironage.id = ?', (int)$ruler)
            ->order('issuer ASC');
	return $rulers->fetchAll($select);
	}

	/** Get Iron Age rulers by region
    * @param integer $region region number
	* @return Array
	*/
	public function getIronAgeRulerToRegion($region) {
		$rulers = $this->getAdapter();
		$select = $rulers->select()
			->from($this->_name, array('id', 'issuer', 'region'	))
			->joinLeft('ironagerulerxregion','ironagerulerxregion.rulerID = rulers.id', array())
			->joinLeft('geographyironage','ironagerulerxregion.regionID = geographyironage.id', array())
			->where('geographyironage.id = ?', (int)$region);
	return $rulers->fetchAll($select);
	}

	/** Get a ruler's name
    * @param integer $ruler
	* @return Array
	* @todo isn't this a duplicate method?
	*/
	public function getRulersName($ruler) {
		$rulers = $this->getAdapter();
		$select = $rulers->select()
			->from($this->_name, array('id', 'issuer'))
			->where('rulers.id = ?', (int)$ruler)
			->limit(1)
            ->order('issuer ASC');
	return $rulers->fetchAll($select);
	}

	/** Get a ruler's image
    * @param integer $ruler
	* @return Array
	*/
	public function getRulerImage($ruler) {
		$images = $this->getAdapter();
		$select = $images->select()
			->from($this->_name, array('id'))
			->where('valid', (int)1)
			->where('rulers.id = ?',(int)$ruler);
	return $images->fetchAll($select);
	}

	/** Get rulers for a mint
    * @param integer $mintID
	* @return Array
	*/
	public function getRomanMintRulerList($mintID) {
		$actives = $this->getAdapter();
		$select = $actives->select()
			->from($this->_name)
			->joinLeft('emperors','rulers.id = emperors.pasID', array('df' => 'date_from', 'dt' => 'date_to',
			'name', 'pasID', 'empID' => 'id'))
			->joinLeft('mints_rulers','rulers.id = mints_rulers.ruler_id', array())
			->joinLeft('mints','mints.id = mints_rulers.mint_id', array('mintid' => 'id','n' => 'mint_name' ))
			->joinLeft('romanmints','romanmints.pasID = mints.id', array('id' ))
			->where('emperors.id IS NOT NULL')
			->where('romanmints.pasID = ?',(int)$mintID)
			->order('date_from')
			->group('issuer');
	return $actives->fetchAll($select);
	}

	/** Get rulers for a mint
    * @param integer $mintID
	* @return Array
	*/
	public function getMedievalMintRulerList($mintID) {
		$actives = $this->getAdapter();
		$select = $actives->select()
			->from($this->_name)
			->joinLeft('mints_rulers','rulers.id = mints_rulers.ruler_id', array())
			->joinLeft('mints','mints.id = mints_rulers.mint_id', array('mintid' => 'id' ))
			->where('mints.id= ?',(int)$mintID)
			->group('issuer');
	return $actives->fetchAll($select);
	}

	/** Get a paginated list of all rulers
    * @param integer $params['page'] page number
	* @return Array
	*/
	public function getRulerList($params){
		$actives = $this->getAdapter();
		$select = $actives->select()
			->from($this->_name)
			->joinLeft('periods','periods.id = rulers.period', array('term','i' => 'id'))
			->joinLeft('users','users.id = ' . $this->_name . '.createdBy', array('fullname'))
            ->joinLeft('users','users_2.id = ' . $this->_name . '.updatedBy', array('fn' => 'fullname'))
            ->joinLeft('emperors', 'emperors.pasID = rulers.id', array('pasID' => 'emperors.id'))
			->where($this->_name . '.valid = ?', (int)1)
			->group('issuer');
		if(isset($params['period']) && ($params['period'] != "")) {
		$select->where('period = ?',(int)$params['period']);
		}
		$paginator = Zend_Paginator::factory($select);
		$paginator->setItemCountPerPage(30)
		          ->setPageRange(10);
		if(isset($params['page']) && ($params['page'] != ""))  {
	    $paginator->setCurrentPageNumber($params['page']);
		}
	return $paginator;
	}

	/** Get a paginated list of all rulers for admin section
    * @param integer $params['page'] page number
	* @return Array
	*/
	public function getRulerListAdmin($params) {
		$actives = $this->getAdapter();
		$select = $actives->select()
			->from($this->_name)
			->joinLeft('periods','periods.id = rulers.period', array('term','i' => 'id'))
			->joinLeft('users','users.id = '.$this->_name.'.createdBy', array('fullname'))
            ->joinLeft('users','users_2.id = '.$this->_name.'.updatedBy', array('fn' => 'fullname'));
		if(isset($params['period']) && ($params['period'] != "")) {
		$select->where('period = ?',(int)$params['period']);
		}
		if(isset($params['ruler'])) {
			$select->where('issuer LIKE ?','%'.$params['ruler'].'%');
		}
		$paginator = Zend_Paginator::factory($select);
		$paginator->setItemCountPerPage(30)
		          ->setPageRange(10);
		if(isset($params['page']) && ($params['page'] != "")) {
		$paginator->setCurrentPageNumber($params['page']);
		}
	return $paginator;
	}

	/** Get ruler profile for medieval period
    * @param integer $rulerID the issuer number
	* @return Array
	*/
	public function getRulerProfileMed($rulerID) {
		$monarchs = $this->getAdapter();
		$select = $monarchs->select()
			->from($this->_name, array('id','issuer','date1','date2', 'dbpedia', 'viaf'))
			->joinLeft('monarchs','rulers.id = monarchs.dbaseID',array('name','biography','styled','alias','born','died','created','createdBy','updated','updatedBy'))
			->where('valid',(int)1)
			->where('rulers.id = ?',(int)$rulerID);
	return $monarchs->fetchAll($select);
	}

	/** Get a list of all rulers who issue jettons
	* @return Array
	*/
	public function getJettonRulers() {
	if (!$data = $this->_cache->load('jettonRulers')) {
        $rulers = $this->getAdapter();
		$select = $rulers->select()
			->from($this->_name, array('id','term' => 'CONCAT(issuer," (",date1," - ",date2,")")'))
			->where('period = ?',(int)36)
			->where('id >= ?',(int)2207)
			->where('id <= ?',(int)2232)
			->order('id');
		$data = $rulers->fetchPairs($select);
		$this->_cache->save($data, 'jettonRulers');
	}
	return $data;
    }

}
