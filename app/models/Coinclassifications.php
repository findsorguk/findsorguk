<?php 

/** Model for pulling coin classifications
* I have no idea why this is different to the Coins Classifications model! 
* @category   Pas
* @package    Pas_Db_Table
* @subpackage Abstract
* @author Daniel Pett dpett @ britishmuseum.org
* @copyright 2010 - DEJ Pett
* @license 		GNU General Public License
* @version 		1
* @since 		22 September 2011
*/

class Coinclassifications 
	extends Pas_Db_Table_Abstract {
	
	protected $_primary = 'id';
	protected $_name = 'coinclassifications';

	/** Get all valid references for coin classifications as a dropdown
	* @return array
	*/
	public function getClass() {
	if (!$options = $this->_cache->load('classificationsdd')) {
	$select = $this->select()
		->from($this->_name, array('id', 'referenceName'))
		->order('id')
		->where('valid = ?',(int)1);
	$options = $this->getAdapter()->fetchPairs($select);
	$this->_cache->save($options, 'classificationsdd');
	}
	return $options;
	}
	
	/** Get all valid references for coin classifications
	* @return array
	*/
	public function getAllClasses($id) {
	if (!$data = $this->_cache->load('classificationscoins' . $id)) {
	$coins = $this->getAdapter();
	$select = $coins->select()
		->from($this->_name,array('referenceName'))
		->joinLeft('coinxclass','coinxclass.classID = coinclassifications.id', 
		array('vol_no','reference','id'))
		->joinLeft('finds','finds.secuid =  coinxclass.findID', array('returnID' => 'id'))
		->where('finds.id = ?' ,(int)$id);
	$data = $coins->fetchAll($select);
	$this->_cache->save($data, 'classificationscoins' . $id);
	}
	return $data;
	}

}