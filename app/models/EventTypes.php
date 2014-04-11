<?php 
/** Model for manipulating event types
* @category Zend
* @package Pas_Db_Table
* @subpackage Abstract
* @author Daniel Pett dpett @ britishmuseum.org
* @copyright 2010 - DEJ Pett
* @license 		GNU General Public License
* @version 		1
* @since 		22 September 2011
*/

class EventTypes extends Pas_Db_Table_Abstract
{
	protected $_name = 'eventtypes';
	protected $_primaryKey = 'id';
	/**
     * Retrieves all event types that we list
     * @param integer $type
     * @return array
	*/
	
	public function getType($type){
		if(!$data = $this->_cache->load(md5('eventtypes' . $type ))) {
		$events = $this->getAdapter();
		$select = $events->select()
						 ->from($this->_name, array('id','type'));
		$data =  $events->fetchRow($select); 
		$this->_cache->save($data, md5('eventtypes' . $type ));
	   }
	   return $data;	
	}
	
	/**
     * Retrieves all event types that we list
     * 
     * @return array
	*/
	
	public function getTypes(){
	if(!$data = $this->_cache->load('eventtypes')) {
		$events = $this->getAdapter();
		$select = $events->select()
						 ->from($this->_name, array('id','type'));
		$data =  $events->fetchPairs($select); 
		$this->_cache->save($data, 'eventtypes');
	   }
	   return $data;
	}
	
/**
     * Retrieves all event types that we list as word pairs
     * 
     * @return array
	*/
	
	public function getTypesWords(){
	if(!$data = $this->_cache->load('eventtypesWords')) {
		$events = $this->getAdapter();
		$select = $events->select()
						 ->from($this->_name, array('type','type'));
		$data =  $events->fetchPairs($select); 
		$this->_cache->save($data, 'eventtypesWords');
	   }
	   return $data;
	}

}