<?php
/** Model for auditing changes to personal data entries
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

class PeopleAudit extends Pas_Db_Table_Abstract {

	protected $_name = 'peopleAudit';

	protected $_primaryKey = 'id';

	/** Get all audited changes for one person
	* @param integer $personID 
	* @return array
	*/
	public function getChanges($personID) {
		$finds = $this->getAdapter();
		$select = $finds->select()
			->from($this->_name,array($this->_name . '.created','recordID','editID'))
			->joinLeft('users','users.id = ' . $this->_name . '.createdBy',
			array('id','fullname','username'))
			->where($this->_name . '.secuid= ?',(int)$personID)
			->order($this->_name . '.id DESC')
			->group($this->_name . '.created');
	return  $finds->fetchAll($select);
	}

	/** Get audited personal changes by edit number
	* @param integer $editID 
	* @return array
	*/
	public function getChange($editID) {
		$finds = $this->getAdapter();
		$select = $finds->select()
			->from($this->_name,array($this->_name . '.created', 'afterValue' , 'fieldName', 'beforeValue'))
			->joinLeft('users','users.id = ' . $this->_name.'.createdBy', array('id', 'fullname', 'username'))
			->where($this->_name . '.editID= ?', (int)$editID)
			->order($this->_name . '.' . $this->_primaryKey);
	return  $finds->fetchAll($select);
	}

}