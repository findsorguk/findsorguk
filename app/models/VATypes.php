<?php
/** Data model for accessing treasure valuation dates and cases from link table
* @category 	Pas
* @package 		Pas_Db_Table
* @subpackage 	Abstract
* @author 		Daniel Pett dpett @ britishmuseum.org
* @copyright 	2010 - DEJ Pett
* @license 		GNU General Public License
* @version 		1
* @since 		22 October 2010, 17:12:34
* @todo 		integrate with the VanArsdellTypes
*/
class VATypes extends Pas_Db_Table_Abstract {

	protected $_name = 'vanarsdelltypes';
	
	protected $_primary = 'id';

	/** get Van Arsdell types
	* @param string $q
	* @return array
	*/
	public function getTypes($q){
	$types = $this->getAdapter();
	$select = $types->select()
		->from($this->_name, array('id','term' => 'type'))
		->where('type LIKE ? ', (string)$q.'%')
		->order('type')
		->limit(10);
	return $types->fetchAll($select);
	}
}