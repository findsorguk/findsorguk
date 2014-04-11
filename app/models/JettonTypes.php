<?php
/**
* A model to manipulate data for the Counties of England and Wales. Scotland may be added
* in the future 
* @category Pas
* @package Pas_Db_Table
* @subpackage Abstract
* @author Daniel Pett dpett @ britishmuseum.org
* @copyright 2010 - DEJ Pett
* @license 		GNU General Public License
* @version 		1
* @since 		22 September 2011
*/

class JettonTypes extends Pas_Db_Table_Abstract {
	
	protected $_name = 'jettonTypes';
	protected $_primary = 'id';

	/** retrieve a key pair list of counties in England and Wales for dropdown use
	* @return array
	*/
	public function getTypes() {
	if (!$data = $this->_cache->load('jettonTypes')) {
	$select = $this->select()
		->from($this->_name, array('id', 'typeName'));
	$data = $this->getAdapter()->fetchPairs($select);
	$this->_cache->save($data, 'jettonTypes');
	}
	return $data;
    }
	
	public function getTypesToGroups( $groupID ) {
    	$select = $this->select()
				->from($this->_name, array('id', 'term' => 'typeName'))
				->joinLeft('groupsJettonsTypes', 'groupsJettonsTypes.typeID = jettonTypes.id ',array())
				->where('groupsJettonsTypes.groupID = ?', $groupID);
		$data = $this->getAdapter()->fetchAll($select);
		return $data;
    }

}
