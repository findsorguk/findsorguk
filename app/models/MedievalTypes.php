<?php
/** Retrieve and manipulate data for medieval coin types
* @category 	Pas
* @package 		Pas_Db_Table
* @subpackage 	Abstract
* @author 		Daniel Pett dpett @ britishmuseum.org
* @copyright 	2010 - DEJ Pett
* @license 		GNU General Public License
* @version 		1
* @since 		22 September 2011
* @todo add caching throughout model as the cached version won't be changing!
*/

class MedievalTypes extends Pas_Db_Table_Abstract {

	protected $_name = 'medievaltypes';

	protected $_primary = 'id';


	/** Get all the early medieval types attached to a ruler
	* @param integer $rulerID
	* @return array
	* @todo add cache
	*/
	public function getEarlyMedTypes($rulerID) {
	$select = $this->select()
		->from($this->_name, array('id','term' => 'CONCAT(type," (",datefrom," - ",dateto,")")'))
		->where('periodID = ? ', (int)47)
		->where('rulerID = ?', (int)$rulerID)
		->order($this->_primary);
        $options = $this->getAdapter()->fetchAll($select);
        return $options;
    }

	/** Get all the early medieval types attached to a ruler
	* @param integer $rulerID
	* @return array
	* @todo add cache
	* @todo this replicates the previous method!
	*/
	public function getEarlyMedTypeRuler($rulerID) {
	$rulers = $this->getAdapter();
	$select = $rulers->select()
		->from($this->_name, array('id','term' => 'CONCAT(type," (",datefrom," - ",dateto,")")'))
		->joinLeft('rulers','rulers.id = medievaltypes.rulerID', array())
		->where('medievaltypes.rulerID = ?', (int)$rulerID)
		->order('medievaltypes.id');
	return $rulers->fetchAll($select);
    }

    /** Get all the early medieval types attached to a ruler for admin
	* @param integer $rulerID
	* @return array
	* @todo add cache
	*/
	public function getEarlyMedTypeRulerAdmin($rulerID){
	$rulers = $this->getAdapter();
	$select = $rulers->select()
		->from($this->_name, array('linkid' => 'id','term' => 'CONCAT(type," (",datefrom," - ",dateto,")")','created'))
		->joinLeft('rulers','rulers.id = medievaltypes.rulerID',array())
		->joinLeft('users','users.id = ' . $this->_name . '.createdBy', array('fullname'))
		->where('medievaltypes.rulerID = ?', (int)$rulerID)
		->order('medievaltypes.id');
	return $rulers->fetchAll($select);
    }

    /** Get all the medieval types attached to a ruler
	* @param integer $rulerID
	* @return array
	* @todo add cache
	*/
	public function getMedievalTypeToRuler($rulerID) {
	$rulers = $this->getAdapter();
	$select = $rulers->select()
		->from($this->_name, array('id','type','datefrom','dateto'))
		->joinLeft('rulers','rulers.id = medievaltypes.rulerID', array())
		->where('period = ?', (int)29)
		->where('medievaltypes.rulerID = ?', (int)$rulerID)
		->order('medievaltypes.id');
	return $rulers->fetchAll($select);
    }

 	/** Get all the early medieval types attached to a ruler
	* @param integer $rulerID
	* @return array
	* @todo add cache
	*/
	public function getEarlyMedievalTypeToRuler($rulerID) {
	$rulers = $this->getAdapter();
	$select = $rulers->select()
		->from($this->_name, array('id','type','datefrom','dateto'))
		->joinLeft('rulers','rulers.id = medievaltypes.rulerID', array())
		->where('period = ?', (int)47)
		->where('medievaltypes.rulerID = ?', (int)$rulerID)
		->order('medievaltypes.id');
	return $rulers->fetchAll($select);
    }


	/** Get all the post medieval types attached to a ruler
	* @param integer $rulerID
	* @return array
	* @todo add cache
	*/
	public function getPostMedievalTypeToRuler($rulerID) {
	$rulers = $this->getAdapter();
	$select = $rulers->select()
		->from($this->_name, array('id','type','datefrom','dateto'))
		->joinLeft('rulers','rulers.id = medievaltypes.rulerID', array())
		->where('period = ?', (int)36)
		->where('medievaltypes.rulerID = ?', (int)$rulerID)
		->order('medievaltypes.id');
	return $rulers->fetchAll($select);
    }

    /** Get all the medieval types attached to a ruler as dropdown ket value pairs
	* @param integer $rulerID
	* @return array
	* @todo add cache
	*/
	public function getMedievalTypeToRulerMenu($rulerID) {
	$rulers = $this->getAdapter();
	$select = $rulers->select()
		->from($this->_name, array('id','term' => 'CONCAT(type," (",datefrom," - ",dateto,")")'))
		->joinLeft('rulers','rulers.id = medievaltypes.rulerID', array())
//		->where('period = ?', (int)29)
		->where('medievaltypes.rulerID = ?',(int)$rulerID)
		->order('medievaltypes.id');
	return $rulers->fetchPairs($select);
	}


	/** Get all the medieval types attached to a specific ruler no concatenation
	* @param integer $rulerID The ruler ID
	* @return array
	* @todo add cache
	*/
	public function getMedievalRulersToType($rulerID) {
	$rulers = $this->getAdapter();
	$select = $rulers->select()
		->from($this->_name, array())
		->joinLeft('rulers','rulers.id = medievaltypes.rulerID',array('id', 'issuer', 'date1', 'date2'))
		->where('medievaltypes.id = ?',(int)$rulerID)
		->order('medievaltypes.id');
	return $rulers->fetchAll($select);
    }

    /** Get all the medieval types attached to a specific category
	* @param integer $catID The category ID
	* @return array
	* @todo add cache
	*/
	public function getCoinTypeCategory($catID)  {
            $key = md5('cointypeCat' . $catID);
            if (!$data = $this->_cache->load($key)) {
	$rulers = $this->getAdapter();
	$select = $rulers->select()
		->from($this->_name, array('id', 'type', 'datefrom', 'dateto'))
		->where('categoryID = ?', (int)$catID)
		->order($this->_primary);
	$data =  $rulers->fetchAll($select);
        $this->_cache->save($data, $key);
    }
    return $data;
    }

    /** Get all the medieval types paginated by period
	* @param integer $periodID The period ID number
	* @param integer $page  The page number
	* @return array
	* @todo add cache
	*/
	public function getTypesByPeriod($periodID,$page) {
	$rulers = $this->getAdapter();
	$select = $rulers->select()
		->from($this->_name)
		->joinLeft('users','users.id = ' . $this->_name . '.createdBy', array('fullname'))
		->joinLeft('users','users_2.id = ' . $this->_name . '.updatedBy', array('fn' => 'fullname'))
		->joinLeft('medievalcategories',$this->_name . '.categoryID = medievalcategories.id', array('c' => 'category'))
		->joinLeft('rulers','rulers.id = ' . $this->_name . '.rulerID',
		array('ruler' => 'issuer','i' => 'id'))
		->where('medievaltypes.periodID = ?',(int)$periodID)
		->order('medievaltypes.id');
	$paginator = Zend_Paginator::factory($select);
	$paginator->setItemCountPerPage(30)
		->setPageRange(10);
	if(isset($page) && ($page != "")) {
	$paginator->setCurrentPageNumber($page);
	}
	return $paginator;
	}

	/** Get all the medieval types paginated by period for admin console
	* @param integer $params['periodID'] The period ID number
	* @param integer $params['page']  The page number
	* @return array
	* @todo add cache
	*/
	public function getTypesByPeriodAdmin($params) {
	$rulers = $this->getAdapter();
	$select = $rulers->select()
		->from($this->_name)
		->joinLeft('users','users.id = ' . $this->_name . '.createdBy', array('fullname'))
		->joinLeft('users','users_2.id = ' . $this->_name . '.updatedBy', array('fn' => 'fullname'))
		->joinLeft('medievalcategories', $this->_name . '.categoryID = medievalcategories.id', array('c' => 'category'))
		->joinLeft('rulers','rulers.id = '. $this->_name . '.rulerID', array('ruler' => 'issuer','i' => 'id'))
		->where('medievaltypes.periodID = ?',(int)$params['period'])
		->order('medievaltypes.id');
	$paginator = Zend_Paginator::factory($select);
	$paginator->setItemCountPerPage(30)
		->setPageRange(10);
	if(isset($params['page']) && ($params['page'] != "")) {
	$paginator->setCurrentPageNumber($params['page']);
	}
	return $paginator;
	}

	/** Get a specific medieval type details
	* @param integer $id  The type number
	* @return array
	* @todo add cache
	*/
	public function getTypeDetails($id) {
	$types = $this->getAdapter();
	$select = $types->select()
		->from($this->_name)
		->joinLeft('users','users.id = ' . $this->_name . '.createdBy',array('fullname'))
		->joinLeft('users','users_2.id = ' . $this->_name . '.updatedBy',array('fn' => 'fullname'))
		->joinLeft('rulers','rulers.id = ' . $this->_name . '.rulerID',array('ruler' => 'issuer','i' => 'id'))
		->joinLeft('categoriescoins',$this->_name . '.categoryID = categoriescoins.id',array('category'))
		->where($this->_name . '.id = ?',(int)$id);
	return $types->fetchAll($select);
	}

	/** Get all the medieval types for sitemap
	* @param integer $periodID The period ID number
	* @return array
	* @todo add cache
	*/
	public function getTypesSiteMap($periodID) {
	if (!$data = $this->_cache->load('sitemaptypes' . $periodID)) {
	$types = $this->getAdapter();
	$select = $types->select()
		->from($this->_name, array('id', 'updated', 'type'))
		->where($this->_name . '.periodID = ?',(int)$periodID);
	$data =  $types->fetchAll($select);
	$this->_cache->save($data, 'sitemaptypes' . $periodID);
	}
	return $data;
	}
	
	public function getMedievalTypesForm($periodID){
	if (!$data = $this->_cache->load('searchformedieval' . $periodID)) {
	$select = $this->select()
		->from($this->_name, array('id','term' => 'CONCAT(type," (",datefrom," - ",dateto,")")'))
		->where('periodID = ? ', (int)$periodID)
		->order($this->_primary);
        $data = $this->getAdapter()->fetchPairs($select);
    $this->_cache->save($data, 'searchformedieval' . $periodID);
	}
	return $data;
    }
}