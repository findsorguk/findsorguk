<?php
/** Monarchs model for pulling data from monarchs table
* @category 	Pas
* @package 		Pas_Db_Table
* @subpackage 	Abstract
* @author 		Daniel Pett dpett @ britishmuseum.org
* @copyright 	2010 - DEJ Pett
* @license 		GNU General Public License
* @version 		1
* @since 		22 September 2011
* @todo 		add edit and delete functions and cache
*/
class Monarchs extends Pas_Db_Table_Abstract {

	protected $_name = 'monarchs';

	protected $_primary = 'id';

	/** Retrieve monarch profile for medieval kings
	* @param integer $id monarch id
	* @return array $paginator
	*/
	public function getProfileMedieval($id) {
 		$monarchs = $this->getAdapter();
		$select = $monarchs->select()
		->from($this->_name, array('name', 'biography', 'styled',
        						   'alias', 'date1' => 'date_from', 'date2' => 'date_to',
        						   'born', 'died', 'created', 
        						   'createdBy', 'updated', 'updatedBy'))
		->joinLeft('rulers','rulers.id = monarchs.dbaseID', array('id', 'issuer'))
		->where('valid',(int)'1')
		->where('rulers.id = ?',(int)$id);
        return $monarchs->fetchAll($select);
	}

	/** Retrieve monarch biography
	* @param integer $id monarch id
	* @return array $paginator
	*/
	public function getBiography($id) {
	$monarchs = $this->getAdapter();
	$select = $monarchs->select()
		->from($this->_name, array('id', 'biography', 'dbaseID'))
		->where('monarchs.dbaseID = ?',(int)$id);
    return $monarchs->fetchAll($select);
	}
}
