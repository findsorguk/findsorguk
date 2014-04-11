<?php
/** Get materials from the thesaurus
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
class Materials extends Pas_Db_Table_Abstract {

	protected $_name = 'materials';

	protected $_primary = 'id';

	/** Get primary materials
	* @return array
	*/
	public function getPrimaries(){
	if (!$options = $this->_cache->load('primarydd')) {
	$select = $this->select()
		->from($this->_name, array('id', 'term'))
		->where('valid = ?',(int)1)
		->order('term ASC');
	$options = $this->getAdapter()->fetchPairs($select);
	$this->_cache->save($options, 'primarydd');
	}
	return $options;
    }

    /** Get secondary materials
	* @return array
	*/
	public function getSecondaries(){
	if (!$options = $this->_cache->load('secondarydd')) {
	$select = $this->select()
		->from($this->_name, array('id', 'term'))
		->where('valid = ?',(int)1)
		->order('term ASC');
	$options = $this->getAdapter()->fetchPairs($select);
	$this->_cache->save($options, 'secondarydd');
	}
	return $options;
    }
    /** Get metals
	* @return array
	* @todo add caching
	*/
	public function getMetals() {
	$select = $this->select()
		->from($this->_name, array('id', 'term'))
		->where('parentID IN (1,6) AND valid =1')
		->order('term ASC');
	$options = $this->getAdapter()->fetchPairs($select);
	return $options;
	}

	/** Get material name
	* @param integer $mat
	* @return array
	* @todo add caching
	*/
	public function getMaterialName($mat){
	$select = $this->select()
		->from($this->_name, array('term'))
		->where('id = ?',$mat);
	$options = $this->getAdapter()->fetchAll($select);
	return $options;
    }

    /** Get material name list
	* @return array
	* @todo add caching
	*/
	public function getMaterials(){
	$select = $this->select()
		->from($this->_name)
		->where('valid = ?',(int)1)
		->order('id');
	$options = $this->getAdapter()->fetchAll($select);
	return $options;
    }

    /** Get material name list for admin section
	* @return array
	* @todo add caching
	*/
    public function getMaterialsAdmin($page){
	$materials = $this->getAdapter();
	$select = $materials->select()
		->from($this->_name)
		->joinLeft('users','users.id = '.$this->_name.'.createdBy',array('fullname'))
		->joinLeft('users','users_2.id = '.$this->_name.'.updatedBy',array('fn' => 'fullname'))
		->order('id');$paginator = Zend_Paginator::factory($select);
	$paginator->setItemCountPerPage(30)
		->setPageRange(10);
	if(isset($page) && ($page != "")) {
    $paginator->setCurrentPageNumber($page);
	}
	return $paginator;
	}

	/** Get material details
	* @param integer $id
	* @return array
	* @todo add caching
	*/
	public function getMaterialDetails($id) {
	$select = $this->select()
		->from($this->_name)
		->where('valid = ?',(int)1)
		->where('id = ?',(int)$id);
	return $this->getAdapter()->fetchAll($select);
    }

    /** Get material count
	* @param integer $id
	* @return array
	* @todo add caching
	*/
	public function getMaterialCount($id) {
	$persons = $this->getAdapter();
	$select = $persons->select()
		->from($this->_name)
		->joinLeft('finds','finds.material1 = '.$this->_name.'.id',array('c' => 'count(finds.id)'))
		->where('valid = ?',(int)1)
		->where($this->_name.'.id = ?',(int)$id)
		->group('materials.id');
	return $persons->fetchAll($select);
    }

}
