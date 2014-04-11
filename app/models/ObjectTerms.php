<?php
/** Model for getting object terms from database
* @category 	Pas
* @package 		Pas_Db_Table
* @subpackage 	Abstract
* @author Daniel Pett dpett @ britishmuseum.org
* @copyright 2010 - DEJ Pett
* @license 		GNU General Public License
* @version 		1
* @since 		22 September 2011
* @todo add edit and delete functions
*/
class ObjectTerms extends Pas_Db_Table_Abstract {
	
protected $_name = 'objectterms';

protected $_id = 'id';

	/** Retrieve all object terms by string query
	* @param string $q 
	* @return array
	*/
	public function getObjectterm($q) {
	$objectterms = $this->getAdapter();
	$select = $objectterms->select()
		->from($this->_name, array('id','term'))
		->where('term LIKE ?', (string)$q.'%')
		->where('indexTerm = ?','Y')
		->order('term');
	return $objectterms->fetchAll($select);
	}
	
	/** Retrieve all object terms
	* @return array
	*/
	public function getObjectNames(){
	if (!$data = $this->_cache->load('objectnames')) {
   	$select = $this->select()
		->from($this->_name, array('term'))
//		->where('status = ?','P')
		->where('indexTerm = ?','Y');
   	$data = $this->getAdapter()->fetchAll($select);
	$this->_cache->save($data, 'objectnames');
    } 
	return $data;
    }

    /** Retrieve paginated object terms
	* @param integer $params['page'] 
	* @return array
	*/
    public function getAllObjectData($params){
	$objectterms = $this->getAdapter();
	$select = $objectterms->select()
		->from($this->_name)
		->order('term');
	$paginator = Zend_Paginator::factory($select);
	$paginator->setItemCountPerPage(30) 
		->setPageRange(10);
	if(isset($params['page']) && ($params['page'] != "")) {
	$paginator->setCurrentPageNumber($params['page']); 
	}
	return $paginator;
	}

	/** Retrieve object term details
	* @param string $term 
	* @return array
	*/
	public function getObjectTermDetail($term) {
	$objectterms = $this->getAdapter();
	$select = $objectterms->select()
		->from($this->_name)
		->where('term = ?', (string)$term);
	return $objectterms->fetchAll($select);
	}

}
