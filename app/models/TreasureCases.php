<?php
/**  Data model for accessing treasure cases in the database
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
class TreasureCases extends Pas_Db_Table_Abstract {

	protected $_primary = 'id';

	protected $_name = 'finds';

	protected $_access = array('fa','flos','admin','treasure');


	/** Retrieve role of user - needs DRY
	* @return string
	*/
	public function getRole(){
	if($this->_auth->hasIdentity()){
	$user = $this->_auth->getIdentity();
	$role = $user->role;
	return $role;
	} else {
	$role = 'public';
	return $role;
	}
	}

	/** get a list of Treasure cases in paginated format
	* @param integer $params['page']
	* @return string
	*/
	public function getCases($params){
		$finds = $this->getAdapter();
		$select = $finds->select()
            ->from($this->_name,array('id','old_findID','treasureID','updated'))
            ->joinLeft('findspots','findspots.findID = finds.secuid', array('county'))
			->where('finds.treasure = ?', (int)1)
			->order($this->_name . '.treasureID ASC')
			->group('finds.treasureID');
		if(!in_array($this->getRole(),$this->_access)){
			$select->where('finds.secwfstage > ?', (int)2);
		}
		if(isset($params['year'])){
		$select->where($this->_name . '.treasureID LIKE ?',$params['year'].'%');
		}
        $data =  $finds->fetchAll($select);
        $paginator = Zend_Paginator::factory($data);
		$paginator->setItemCountPerPage(30)
	          	  ->setPageRange(10);
	    if(isset($params['page']) && ($params['page'] != "")){
    	$paginator->setCurrentPageNumber((int)$params['page']);
		}
        return $paginator;
	}

	/** Get a Treasure case's details
	* @param integer $treasureID The id of the case
	* @return string
	*/
	public function getBasicHistory($treasureID){
		$finds = $this->getAdapter();
		$select = $finds->select()
            ->from($this->_name, array('id','old_findID','treasureID','updated'))
            ->joinLeft('findspots','findspots.findID = finds.secuid',array('county'))
			->where('finds.treasure = ?', (int)1)
			->where('finds.treasureID = ?',(int)$treasureID);
		$data = $finds->fetchAll($select);
		return $data;
	}

	/** Get an extended set of a Treasure case's details
	* @param integer $treasureID The id of the case
	* @return string
	*/
	public function getCaseHistory($treasureID){
		$finds = $this->getAdapter();
		$select = $finds->select()
            ->from($this->_name,array('id','old_findID','treasureID','updated','objecttype'))
            ->joinLeft('findspots','findspots.findID = finds.secuid',array('county'))
			->where('finds.treasure = ?',(int)1)
			->where('finds.treasureID = ?', (int)$treasureID);
		$data = $finds->fetchAll($select);
		return $data;
	}
}

