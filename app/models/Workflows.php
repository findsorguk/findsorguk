<?php
/** Model for interacting with workflow
* @category 	Pas
* @package 		Pas_Db_Table
* @subpackage 	Abstract
* @author 		Daniel Pett dpett @ britishmuseum.org
* @copyright 	2010 - DEJ Pett
* @license 		GNU General Public License
* @todo 		add edit and delete functions, caching
*/
class Workflows extends Pas_Db_Table_Abstract {
	
	protected $_name = 'workflowstages';

	protected $_primary = 'id';

	/** Retrieve a key value pair list of workflows for use in dropdowns
	* @return array 
	*/
	public function getUses() {
        $select = $this->select()
		->from($this->_name, array('id', 'workflowstage'))
		->where($this->_name.'.valid = ?', (int)1)
		->order('workflowstage ASC');
        $options = $this->getAdapter()->fetchPairs($select);
        return $options;
    }
    
    /** Retrieve workflow stage details by ID
	* @param integer $stage
	* @return array 
	*/
	public function getStageName($stage) {
        $select = $this->select()
		->from($this->_name)
                      // ->order('workflowstage ASC') why is this here?
		->where('id = ?',(int)$stage)
		->where($this->_name.'.valid = ?',(int)1)
		->limit(1);
        $options = $this->getAdapter()->fetchAll($select);
        return $options;
    }
	
    /** Retrieve stage names for valid workflow stages
	* @return array 
	*/
	public function getStageNames()  {
        $select = $this->select()
		->from($this->_name)
		->order('workflowstage ASC')
		->where($this->_name . '.valid = ?', (int)1);
        $options = $this->getAdapter()->fetchAll($select);
        return $options;
    }

    /** Retrieve stage names for admin section
	* @return array 
	*/
    public function getStageNamesAdmin() {
		$stages = $this->getAdapter();
	    $select = $stages ->select()
		->from($this->_name)
		->order('workflowstage ASC')
		->joinLeft('users','users.id = ' . $this->_name . '.createdBy', 
                       array('fullname'))
		->joinLeft('users','users_2.id = ' . $this->_name . '.updatedBy', 
   					   array('fn' => 'fullname'));
        return $stages->fetchAll($select);
    }

    /** Retrieve stage counts for telling people to work harder!
    * @param integer $stage workflow stage
	* @return array 
	*/
	public function getStageCounts($stage) {
       	$stages = $this->getAdapter();
		$select = $stages->select()
		->from($this->_name, array('id', 'workflowstage'))
		->joinLeft('finds',$this->_name . '.id = finds.secwfstage',array('c' => 'count(*)'))
		->order('workflowstage ASC')
		->where($this->_name . '.id = ?',(int)$stage)
		->where($this->_name . '.valid = ?',(int)1)
		->group($this->_name . '.id');
        return $stages->fetchAll($select);
    }

}
