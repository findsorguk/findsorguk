<?php

/** Data model for accessing volunteer opportunities
* @category 	Pas
* @package 		Pas_Db_Table
* @subpackage 	Abstract
* @author 		Daniel Pett dpett @ britishmuseum.org
* @copyright 	2010 - DEJ Pett
* @license 		GNU General Public License
* @version 		1
* @since 		22 October 2010, 17:12:34
* @todo 		implement edit and delete function methods
*/
class Volunteers extends Pas_Db_Table_Abstract {
	
	protected $_name = 'volunteers';
	
	protected $_primary = 'id';
	
	protected $_dependentTables = array('People' => array('fullname'),'ProjectTypes' => array('title'));
	
	
	/** Get all current opps as paginated list
	* @param integer $params['page'] the current page 
	* @return array
	*/
	public function getCurrentOpps($params){
	$vols = $this->getAdapter();
	$select = $vols->select()
		->from($this->_name)
		->joinLeft('projecttypes',$this->_name . '.suitableFor = projecttypes.id', array('type' => 'title'))
//		->joinLeft('people',$this->_name . '.managedBy = people.secuid', array('fullname'))
		->joinLeft('users', $this->_name .  '.managedBy = users.id', array('fullname'))
		->where($this->_name . '.status = ?', (int)1)
		->order(array($this->_name . '.created'));
	$data = $vols->fetchAll($select);
	$paginator = Zend_Paginator::factory($data);
	Zend_Paginator::setCache($this->_cache);
	if(isset($params['page']) && ($params['page'] != "")) {
    $paginator->setCurrentPageNumber((int)$params['page']); 
	}
	$paginator->setItemCountPerPage(50) 
		->setPageRange(10); 
	return $paginator;
	}
	
	/** Get vacancy details
	* @param integer $id the opportunity ID 
	* @return array
	*/
	public function getOppDetails($id){
	$vols = $this->getAdapter();
	$select = $vols->select()
		->from($this->_name)
		->joinLeft('projecttypes',$this->_name . '.suitableFor = projecttypes.id', array('type' => 'title'))
		->joinLeft('people',$this->_name . '.managedBy = people.secuid', array('fullname'))
		->where($this->_name . '.status = ?', (int)1)
		->where($this->_name . '.id = ?', (int)$id)
		->order(array($this->_name . '.created'));
	return $vols->fetchAll($select);
	}
}