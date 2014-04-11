<?php
/** Model for manipulating audit data for finds
* @category Pas
* @package Pas_Db_Table
* @subpackage Abstract
* @author Daniel Pett dpett @ britishmuseum.org
* @copyright 2010 - DEJ Pett
* @license 		GNU General Public License
* @version 		1
* @since 		22 September 2011
* @todo add caching throughout model as the cached version won't be changing!
*/
class FindsAudit extends Pas_Db_Table_Abstract {
	
	protected $_name = 'findsAudit';
	
	protected $_primary = 'id';

	/** get all audited changes on a record
	* @param integer $id 
	* @return array
	* @todo add cache
	*/
	public function getChanges($id) {
		$finds = $this->getAdapter();
		$select = $finds->select()
			->from($this->_name,array($this->_name . '.created','recordID','editID'))
			->joinLeft('users','users.id = ' . $this->_name . '.createdBy',
			array('id','fullname','username'))
			->where($this->_name  . '.recordID = ?',(int)$id)
			->order($this->_name  . '.id DESC')
			->group($this->_name  . '.editID');
	return  $finds->fetchAll($select);
	}

	/** get an audited change set on a record
	* @param integer $id 
	* @return array
	* @todo add cache
	*/
	public function getChange($id){
		$finds = $this->getAdapter();
		$select = $finds->select()
			->from($this->_name,array($this->_name . '.created',
			'afterValue','fieldName','beforeValue'))
			->joinLeft('users','users.id = ' . $this->_name . '.createdBy',
			array('id','fullname','username'))
			->where($this->_name  . '.editID = ?',$id)
			->order($this->_name.'.id');
	return  $finds->fetchAll($select);
	}
}