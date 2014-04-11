<?php
/** Model for findspot table auditing
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

class FindSpotsAudit extends Pas_Db_Table_Abstract {

	protected $_name = 'findspotsAudit';

	protected $_primary = 'id';

	
	/** Get all changes to a findspot
	* @param integer $id
	* @return array
	* @todo add caching functions
	*/
	public function getChanges($id) {
		$finds = $this->getAdapter();
		$select = $finds->select()
			->from($this->_name,array($this->_name . '.created', 'recordID', 'editID'))
			->joinLeft('users','users.id = ' . $this->_name . '.createdBy',
			array('id', 'fullname', 'username'))
			->where($this->_name . '.entityID= ?',(int)$id)
			->order($this->_name . '.id DESC')
			->group($this->_name . '.created');
	return  $finds->fetchAll($select);
	}

	/** Get changes to a findspot
	* @param integer $id
	* @return array
	* @todo add caching
	*/
	public function getChange($id) {
		$finds = $this->getAdapter();
		$select = $finds->select()
			->from($this->_name,array($this->_name . '.created', 'afterValue', 'fieldName', 'beforeValue'))
			->joinLeft('users','users.id = '.$this->_name . '.createdBy',array('id', 'fullname', 'username'))
			->where($this->_name . '.editID = ?', $id);
	return  $finds->fetchAll($select);
	}

}