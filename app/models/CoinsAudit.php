<?php
/** Model for creating the audited data for changes to coin records
* @category Pas
* @package Pas_Db_Table
* @subpackage Abstract
* @author Daniel Pett dpett @ britishmuseum.org
* @copyright 2010 - DEJ Pett
* @license 		GNU General Public License
* @version 		1
* @since 		22 September 2011
* */
class CoinsAudit extends Pas_Db_Table_Abstract {

	protected $_name = 'coinsAudit';
	protected $_primary = 'id';

	/** Get all changes to a coin record since creation
	* @param integer $id 
	* @return array
	*/
	public function getChanges($id) {
	$finds = $this->getAdapter();
	$select = $finds->select()
            ->from($this->_name,array($this->_name . '.created', 'recordID', 
                'editID'))
            ->joinLeft('users','users.id = ' . $this->_name . '.createdBy', 
                    array('id','fullname','username'))
            ->where($this->_name . '.recordID= ?',(int)$id)
            ->order($this->_name . '.id DESC')
            ->group($this->_name . '.created');
	return  $finds->fetchAll($select);
	}

	/** Get change by id
	* @param integer $id
	* @return array
	*/
	public function getChange($id) {
	$finds = $this->getAdapter();
	$select = $finds->select()
            ->from($this->_name, array($this->_name . '.created', 'afterValue',
                'fieldName', 'beforeValue'))
            ->joinLeft('users','users.id = ' . $this->_name . '.createdBy', 
		array('id','fullname','username'))
            ->where($this->_name . '.editID= ?',$id);
	return $finds->fetchAll($select);
	}

}