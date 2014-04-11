<?php
/** Model for interacting with landuse entries in db
* @category Pas
* @package Pas_Db_Table
* @subpackage Abstract
* @author Daniel Pett dpett @ britishmuseum.org
* @copyright 2010 - DEJ Pett
* @license 		GNU General Public License
* @version 		1
* @since 		22 September 2011
* @todo add edit and delete functions
* @todo add caching throughout
*/
class Landuses extends Pas_Db_Table_Abstract {
	
	protected $_name = 'landuses';

	protected $_primary = 'id';
	
	/** get list of landuses where valid and at top level
	* @return array 
	*/
	public function getLanduses(){
		$landuses = $this->getAdapter();
		$select = $landuses->select()
				->from($this->_name)
				->where('belongsto IS NULL')
				->where('valid = ?',(int)1);
        return $landuses->fetchAll($select);
	}
	
	/** get list of landuses for admin list
	* @return array 
	*/
	public function getLandusesAdmin() {
		$landuses = $this->getAdapter();
		$select = $landuses->select()
				->from($this->_name)
				->joinLeft('users','users.id = ' . $this->_name . '.createdBy', array('fullname'))
   				->joinLeft('users','users_2.id = ' . $this->_name . '.updatedBy', array('fn' => 'fullname'));
        return $landuses->fetchAll($select);
	}

	/** get landuse details by id number
	* @param integer $id  
	* @return array 
	*/
	public function getLanduseDetails($id) {
		$landuses = $this->getAdapter();
		$select = $landuses->select()
            ->from($this->_name)
		    ->where('landuses.id = ?', (int)$id);
        return $landuses->fetchAll($select);
	}

	/** get child landuse details by id number
	* @param integer $id  
	* @return array 
	*/
	public function getLandusesChild($id) {
		$landuses = $this->getAdapter();
		$select = $landuses->select()
            ->from($this->_name)
			->where('belongsto = ?',(int)$id);
        return $landuses->fetchAll($select);
	}

	/** get list of child landuses
	* @param integer $id  
	* @return array 
	*/
	public function getLandusesChildList($id) { 
		$landuses = $this->getAdapter();
		$select = $landuses->select()
            ->from($this->_name,array('id','term'))
			->where('belongsto = ?',(int)$id);
        return $landuses->fetchPairs($select);
	}

	/** get list of child landuses for ajax menus as key value pairs
	* @param integer $id  
	* @return array 
	*/
	public function getLandusesChildAjax($id) {
		$landuses = $this->getAdapter();
		$select = $landuses->select()
            ->from($this->_name,array('id','term'))
			->where('belongsto = ?',(int)$id);
        return $landuses->fetchPairs($select);
	}

	/** get list of child landuses for ajax menus 
	* @param integer $id  
	* @return array
	* @todo delete possibly as a duplicate of above! 
	*/
	public function getLandusesChildAjax2($id) {
		$landuses = $this->getAdapter();
		$select = $landuses->select()
            ->from($this->_name,array('id','term'))
			->where('belongsto = ?',(int)$id);
        return $landuses->fetchAll($select);
	}

	/** get list of landuses as key value pairs for menus 
	* @return array
	* @todo delete possibly as a duplicate of above! 
	*/
	public function getUses() {
        $select = $this->select()
                       ->from($this->_name, array('id', 'term'))
                       ->order('term ASC');
        $options = $this->getAdapter()->fetchPairs($select);
        return $options;
    }
    
    /** get list of key value pairs for menus 
	* @return array
	* @todo delete possibly as a duplicate of above! 
	*/
	public function getUsesValid() {
        $select = $this->select()
                       ->from($this->_name, array('id', 'term'))
                       ->where('valid = 1 AND belongsto IS NULL')
					   ->order('id ASC');
        $opts = $this->getAdapter()->fetchPairs($select);
        return $opts;
    }

     /** get list of valid codes for EH thesaurus as key value pairs 
	* @return array
	* @todo delete possibly as a duplicate of above! 
	*/
    public function getCodesValid() {
        $select = $this->select()
                       ->from($this->_name, array('id', 'term'))
                       ->where('valid = 1 AND belongsto IS NOT NULL')
					   ->order('id ASC');
        $opts = $this->getAdapter()->fetchPairs($select);
        return $opts;
    }
}
