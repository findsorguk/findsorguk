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

class JettonGroups extends Pas_Db_Table_Abstract {
	
	protected $_name = 'jettonGroup';
	protected $_primary = 'id';

	/** retrieve a key pair list of counties in England and Wales for dropdown use
	* @return array
	*/
	public function getGroups() {
//	if (!$data = $this->_cache->load('jettonClasses')) {
	$select = $this->select()
		->from($this->_name, array('id', 'groupName'));
	$data = $this->getAdapter()->fetchPairs($select);
//	$this->_cache->save($data, 'jettonClasses');
//	}
	return $data;
    }
    
    public function getGroupsToClasses( $classID ) {
    	$select = $this->select()
				->from($this->_name, array('id', 'term' => 'groupName'))
				->joinLeft('classesJettonGroups', 'classesJettonGroups.groupID = jettonGroup.id ',array())
				->where('classesJettonGroups.classID = ?', $classID);
		$data = $this->getAdapter()->fetchAll($select);
		return $data;
    }
	

}
