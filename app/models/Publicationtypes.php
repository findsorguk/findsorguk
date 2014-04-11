<?php
/** Model for interacting with publication types
* @category 	Pas
* @package 		Pas_Db_Table
* @subpackage 	Abstract
* @author 		Daniel Pett dpett @ britishmuseum.org
* @copyright 	2010 - DEJ Pett
* @license 		GNU General Public License
* @version 		1
* @since 		22 September 2011
* @todo add edit and delete functions and caching
*/
class Publicationtypes extends Pas_Db_Table_Abstract {

	protected $_name = 'publicationtypes';

	protected $_primary = 'id';

	/** Get dropdown list of publication types
	* @return array
	*/
	public function getTypes() {
	$select = $this->select()
		->from($this->_name, array('id', 'term'))
		->order('term ASC');
	$options = $this->getAdapter()->fetchPairs($select);
	return $options;
    }
}
