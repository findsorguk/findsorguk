<?php
/** Model for interacting with macktypes table
* @category Pas
* @package Pas_Db_Table
* @subpackage Abstract
* @author Daniel Pett dpett @ britishmuseum.org
* @copyright 2010 - DEJ Pett
* @license 		GNU General Public License
* @version 		1
* @since 		22 September 2011
* @todo add, edit and delete functions to be created and moved from controllers
*/
class AbcNumbers extends Pas_Db_Table_Abstract {

	protected $_name = 'abcNumbers';

	protected $_primary = 'id';

	/** Retrieve key value paired dropdown list array
	* @return array $paginator
	*/
	public function getTerms(){
    if (!$options = $this->_cache->load('abc')) {
	    $select = $this->select()
                       ->from($this->_name, array('term', 'term'))
                       ->order('id');
        $options = $this->getAdapter()->fetchPairs($select);
		$this->_cache->save($options, 'abc');
		}
        return $options;
    }

}