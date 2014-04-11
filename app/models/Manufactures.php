<?php
/** Model for manipulating methods of manufacture from DB
* @category Pas
* @package Pas_Db_Table
* @subpackage Abstract
* @author Daniel Pett dpett @ britishmuseum.org
* @copyright 2010 - DEJ Pett
* @license 		GNU General Public License
* @version 		1
* @since 		22 September 2011
* @todo add edit and delete functions
* @todo add caching
*/
class Manufactures extends Pas_Db_Table_Abstract {

	protected $_name = 'manufactures';

	protected $_primary = 'id';

	/** get a key pair value list for dropdowns for manufacturing methods
	* @return array
	*/
	public function getOptions() {
        $select = $this->select()
                       ->from($this->_name, array('id', 'term'))
                       ->order('term ASC')
					   ->where('valid = ?',(int)1);
        $options = $this->getAdapter()->fetchPairs($select);
        return $options;
    }

    /** get a list of manufacturing methods
	* @return array
	*/
	public function getManufacturesListed() {
		$manufactures = $this->getAdapter();
		$select = $manufactures->select()
					->from($this->_name)
					->where('valid = ?',(int)1);
       return $manufactures->fetchAll($select);
    }
	
     /** get a list of manufacturing methods for admin interface
	* @return array
	*/
	public function getManufacturesListedAdmin() {
		$manufactures = $this->getAdapter();
		$select = $manufactures->select()
						->from($this->_name)
						->joinLeft('users','users.id = ' . $this->_name . '.createdBy', array('fullname'))
						->joinLeft('users','users_2.id = ' . $this->_name . '.updatedBy', array('fn' => 'fullname'));
       return $manufactures->fetchAll($select);
    }

    /** get manufacturing method details by id number
    * @param integer $id
	* @return array
	*/
	public function getManufactureDetails($id) {
		$manufactures = $this->getAdapter();
		$select = $manufactures->select()
                       ->from($this->_name)
					   ->where('id = ?', (int)$id)
					   ->where('valid = ?', (int)1);
       return $manufactures->fetchAll($select);
    }
	
    /** get manufacturing method counts by id number
    * @param integer $id
	* @return array
	* @todo this doesn't make sense, why is set to join on decstyle? Is this an error?
	*/
    public function getManufacturesCounts($id) {
		$manufactures = $this->getAdapter();
		$select = $manufactures->select()
                       ->from($this->_name)
					   ->joinLeft('finds','finds.decstyle = ' . $this->_name . '.id', array('c' => 'count(finds.id)'))
                       ->where('valid = ?',(int)1)
					   ->where($this->_name . '.id = ?',(int)$id)
					   ->group($this->_name . '.id');
       return $manufactures->fetchAll($select);
    }

}
