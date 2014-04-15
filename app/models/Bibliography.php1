<?php
/** A model for manipulating the bibliograpic data
* Books are stored in publications table
* @category Pas
* @package Pas_Db_Table
* @subpackage Abstract
* @author Daniel Pett dpett @ britishmuseum.org
* @copyright 2010 - DEJ Pett
* @license GNU General Public License
* @version		1.0
* @since		22 September 2011
*/

class Bibliography extends Pas_Db_Table_Abstract {

	protected $_name = 'bibliography';
	
	protected $_primary = 'id';
	
	/** Get cached data for a book
	* @param integer $id
	* @return array
	*/
	public function fetchFindBook($id){
	if (!$data = $this->_cache->load('bibliobook' . (int)$id)) {
	$refs = $this->getAdapter();
	$select = $refs->select()
		->from($this->_name, array('pages_plates','reference','pubID'))
		->joinLeft('publications','publications.secuid = bibliography.pubID', 
		array('publicationtitle' => 'title'))
		->joinLeft('finds','finds.secuid = bibliography.findID', array('id'))
		->where($this->_name . '.id = ?', $id);
	$data = $refs->fetchAll($select);
	$this->_cache->save($data, 'bibliobook' . (int)$id);
	}
	return $data;
	}
}
