<?php 
/**
* Data model for accessing suggested topics from databas
* @category 	Pas
* @package 		Pas_Db_Table
* @subpackage 	Abstract
* @author 		Daniel Pett dpett @ britishmuseum.org
* @copyright 	2010 - DEJ Pett
* @license 		GNU General Public License
* @version 		1
* @since 		22 October 2010, 17:12:34
* @todo			integrate with the VanArsdellTypes
*/
class Suggested extends Pas_Db_Table_Abstract {

	protected $_primary = 'id';

	protected $_name = 'suggestedResearch';


	/** Get a list of va types paginated
    * @param integer $params['page'] page number requested
    * @param boolean $taken 
	* @return array
	*/
	public function getAll($params,$taken){
	$topics = $this->getAdapter();
	$select = $topics->select()
		->from($this->_name, array('id', 'title', 'description', 'created', 'updated'))
		->joinLeft('projecttypes',$this->_name . '.level = projecttypes.id', array('type' => 'title'))
		->joinLeft('users','users.id = ' . $this->_name .  '.createdBy', array('fullname'))
		->joinLeft('users','users_2.id = ' . $this->_name . '.updatedBy', array('fn' => 'fullname'))
		->joinLeft('periods','periods.id = ' . $this->_name . '.period', array('temporal' => 'term'))
		->where('taken = ?', (int)$taken);
	$paginator = Zend_Paginator::factory($select);
	if(isset($params['page']) && ($params['page'] != "")) {
	$paginator->setCurrentPageNumber((int)$params['page']); 
	}
	$paginator->setItemCountPerPage(20) 
		->setPageRange(10); 
	return $paginator;	
	}
	
	/** Get a topic by id number
    * @param integer $id topic requested
    * @param boolean $taken 
	* @return array
	*/
	public function getTopic($id){
	$topics = $this->getAdapter();
	$select = $topics->select()
		->from($this->_name, array('id', 'title', 'description', 'created', 'updated'))
		->joinLeft('users','users.id = ' . $this->_name . '.createdBy', array('fullname'))
		->joinLeft('users','users_2.id = ' . $this->_name . '.updatedBy', array('fn' => 'fullname'))
		->joinLeft('periods','periods.id = ' . $this->_name . '.period', array('temporal' => 'term'))
		->where($this->_name . '.id = ?', (int)$id);
	return $topics->fetchAll($select); 
	}

	/** Get a topic by type
    * @param integer $type type requested
	* @return array
	*/
	public function getTopicByType($type){
	$topics = $this->getAdapter();
	$select = $topics->select()
		->from($this->_name, array('id', 'title', 'description', 'created', 'updated'))
		->joinLeft('projecttypes',$this->_name . '.level = projecttypes.id', array('type' => 'title'))
		->joinLeft('users','users.id = '.$this->_name . '.createdBy', array('fullname'))
		->joinLeft('users','users_2.id = '.$this->_name . '.updatedBy', array('fn' => 'fullname'))
		->joinLeft('periods','periods.id = ' . $this->_name . '.period',array('temporal' => 'term'))
		->where($this->_name . '.level = ?', (int)$type);
	return $topics->fetchAll($select); 
	}
}