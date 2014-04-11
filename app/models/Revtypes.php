<?php
/** Data model for accessing and manipulating Roman reverse type database table
* @category 	Pas
* @package 		Pas_Db_Table
* @subpackage 	Abstract
* @author 		Daniel Pett dpett @ britishmuseum.org
* @copyright 	2010 - DEJ Pett
* @license 		GNU General Public License
* @version 		1
* @since 		22 October 2010, 17:12:34
* @todo 		add edit and delete functions
* @todo 		add caching
*/

class Revtypes extends Pas_Db_Table_Abstract {

	protected $_name = 'revtypes';
	
	protected $_primary = 'id';

	/** Retrieve reverse types assigned to a ruler with concatenation over reeceperiod
	* @param integer $ruler Ruler identification number
	* @return array
	* @todo add caching
	*/
	public function getTypes($ruler){
		$types = $this->getAdapter();
		$select = $types->select()
			->from($this->_name, array('id','term' => 'CONCAT(type," Reece period: ",reeceID, " ", description)'))
			->joinLeft('ruler_reversetype','ruler_reversetype.reverseID = revtypes.id',array())
			->joinLeft('rulers','rulers.id = ruler_reversetype.rulerID',array())
			->where('rulers.id = ?',(int)$ruler)
			->order('type');
	return $types->fetchAll($select);
    }
    
    /** Retrieve reverse types for the administration module with concatenation over reeceperiod
	* @param integer $ruler Ruler identification number
	* @return array
	* @todo add caching
	*/
	public function getTypesAdmin($ruler) {
        $types = $this->getAdapter();
		$select = $types->select()
            ->from($this->_name, array('id','term' => 'CONCAT(type," Reece period: ",reeceID)'))
			->joinLeft('ruler_reversetype','ruler_reversetype.reverseID = revtypes.id',array('created','linkid' => 'id'))
			->joinLeft('rulers','rulers.id = ruler_reversetype.rulerID',array())
            ->joinLeft('users','users.id = ruler_reversetype.createdBy',array('fullname'))
			->where('rulers.id = ?',(int)$ruler)
			->order('type');
        return $types->fetchAll($select);
    }

    /** Retrieve reverse types as key value pairs for form dropdown
	* @param integer $ruler Ruler identification number
	* @return array
	* @todo add caching
	* @todo make a better function name?
	*/
	public function getRevTypesForm($ruler = NULL) {
        $types = $this->getAdapter();
		$select = $types->select()
            ->from($this->_name, array('id','term' => 'CONCAT(type," Reece period: ",reeceID)'))
			->joinLeft('ruler_reversetype','ruler_reversetype.reverseID = revtypes.id',array())
			->joinLeft('rulers','rulers.id = ruler_reversetype.rulerID',array())
			->where('rulers.id = ?', (int)$ruler)
			->order('type');
		return  $types->fetchPairs($select);
    }

    /** Retrieve reverse type for single instance
	* @param integer $reverse reverse identification number
	* @return array
	* @todo add caching
	*/
	public function getRevType($reverse) {
        $types = $this->getAdapter();
		$select = $types->select()
            ->from($this->_name, array('id','term' => 'type'))
			->where('id = ?',(int)$reverse);
        return $types->fetchAll($select);
    }	
	
    /** Get key value pairs for dropdown where type is not null
	* @return array
	* @todo add caching
	* @todo make a better function name?
	*/
	public function getRevTypes() {
        $types = $this->getAdapter();
		$select = $types->select()
            ->from($this->_name, array('id','term' => 'CONCAT(type," Reece period: ",reeceID, " ", description)'))
			->where('type IS NOT NULL')
			->order('type');
        return $types->fetchPairs($select);
    }	

    /** Get most common reverse types list
    * @param integer $common common type number
	* @return array
	* @todo add caching
	* @todo make a better function name?
	*/
	public function getReverseTypeList($common) {
		$types = $this->getAdapter();
		$select = $types->select()
			->from('revtypes')
			->joinLeft('reeceperiods', 'reeceperiods.id = revtypes.reeceID', 
			array( 'period_name', 'date_range', 'i' => 'id'))
			->where('revtypes.common = ?',(int)$common)
			->order('reeceID');
		return $types->fetchAll($select);
	}

	/** Get reverse details enhanced
    * @param integer $id reverse id
	* @return array
	* @todo add caching
	* @todo make a better function name?
	*/
	public function getReverseTypesDetails($id) {
		$types = $this->getAdapter();
		$select = $types->select()
            ->from($this->_name, array('id', 'type', 'reeceID', 'description', 'gendate'))
			->joinLeft('reeceperiods','reeceperiods.id = revtypes.reeceID', 
			array('period_name', 'date_range', 'i' => 'id'))
			->where('type IS NOT NULL')
			->where($this->_name . '.id =?',(int)$id);
        return $types->fetchAll($select);
	}

	/** Get reverse type allied to reece type
    * @param integer $id type number
	* @return array
	* @todo add caching
	* @todo make a better function name?
	*/
	public function getRevTypeReece($id) {
		$types = $this->getAdapter();
		$select = $types->select()
            ->from($this->_name)
			->where('reeceID = ?', (int)$id);;
        return $types->fetchAll($select);
	}
}
