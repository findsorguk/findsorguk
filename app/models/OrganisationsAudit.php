<?php
/** Model for pulling audited data from database for organisations
* @category 	Pas
* @package 		Pas_Db_Table
* @subpackage 	Abstract
* @author Daniel Pett dpett @ britishmuseum.org
* @copyright 2010 - DEJ Pett
* @license 		GNU General Public License
* @version 		1
* @since 		22 September 2011
* @todo add caching
*/
class OrganisationsAudit extends Pas_Db_Table_Abstract {

	protected $_name = 'organisationsAudit';
	
	protected $_primary = 'id';

	 /** Get all changes for a particular organisation
	* @param integer $organisationID organisation ID number
	* @return array
	*/
	
	public function getChanges($organisationID) {
		$finds = $this->getAdapter();
		$select = $finds->select()
			->from($this->_name,array($this->_name . '.created','findID','editID'))
			->joinLeft('users','users.id = ' . $this->_name . '.createdBy', 
			array('id', 'fullname', 'username'))
			->where($this->_name . '.id= ?',(int)$organisationID)
			->order($this->_name . '.id DESC')
			->group($this->_name . '.created');
	return  $finds->fetchAll($select);
	}

	
	 /** Get a specific change to an organisation by edit number
	* @param integer $editID 
	* @return array
	* @todo do something better!
	*/
	public function getChange($editID) {
		$finds = $this->getAdapter();
		$select = $finds->select()
			->from($this->_name,array($this->_name.'.created','afterValue','fieldName','beforeValue'))
			->joinLeft('users','users.id = ' . $this->_name . '.createdBy', 
			array('id', 'fullname', 'username'))
			->where($this->_name . '.editID= ?',$editID)
			->order($this->_name . '.' . $this->_primaryKey);
	return  $finds->fetchAll($select);
	}

}