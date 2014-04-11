<?php
/** Model for pulling reverse information from db
* @category 	Pas
* @package 		Pas_Db_Table
* @subpackage 	Abstract
* @author Daniel Pett dpett @ britishmuseum.org
* @copyright 2010 - DEJ Pett
* @license GNU General Public License
* @todo add edit and delete functions and cache
*/
class Reverses extends Pas_Db_Table_Abstract {
	
	protected $_name = 'reverses';
	
	protected $_primaryKey = 'id';

	/** Get reverse personifications by type for Roman period
	* @param string $type 
	* @return array
	*/
	public function getPersonifications($type) {
	$personify = $this->getAdapter();
	$select = $personify->select()
			->from($this->_name)
			->where('type = ?',(string)$type);
    return $personify->fetchAll($select);
	}
	
	/** Get reverse personifications by name for Roman period
	* @param string $name 
	* @return array
	*/
	public function getPersonification($name) {
	$personify = $this->getAdapter();
	$select = $personify->select()
			->from($this->_name)
			->where('name = ?',(string)$name);
    return $personify->fetchAll($select);
	}

}