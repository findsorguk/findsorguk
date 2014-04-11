<?php

/** Data model for accessing and manipulating list of Roman mints
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
class Romanmints extends Pas_Db_Table_Abstract {
	
	protected $_name = 'romanmints';
	
	protected $_primary = 'id';
	
	/** Get a list of roman mints as key pair values 
	* @return array
	*/
	public function getOptions() {
        $select = $this->select()
                       ->from($this->_name, array('ID', 'name'))
                       ->order('ID');
        $options = $this->getAdapter()->fetchPairs($select);
        return $options;
    }
	
    /** Get a specific mint's details
    * @param integer $id  
	* @return array
	*/
    public function getMintDetails($id) {
		$mints = $this->getAdapter();
		$select = $mints->select()
            ->from($this->_name)
			->where('pasID = ?', (int) $id);
        return $mints->fetchAll($select);
	}

	/** Get a list of all details for Roman mints
	* @return array
	*/
	public function getRomanMintsList() {
		$mints = $this->getAdapter();
        $select = $this->select()
                       ->from($this->_name)
                       ->order('name ASC');
       return $mints->fetchAll($select);
    }

}
