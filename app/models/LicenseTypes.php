<?php
/** Model for manipulating completeness details
* @category Pas
* @package Pas_Db_Table
* @subpackage Abstract
* @author Daniel Pett dpett @ britishmuseum.org
* @copyright 2010 - DEJ Pett
* @license 		GNU General Public License
* @version 		1
* @since 		22 September 2011
* @todo add some caching to model
*/
class LicenseTypes extends Pas_Db_Table_Abstract {

	protected $_name = 'licenseType';
	
	protected $_primary = 'id';
	
	/** Get dropdown values for personal copyrights
    * @param integer $id
	* @return array
	*/
	public function getList() {
	if (!$options = $this->_cache->load('cclicenses')) {
	$select = $this->select()
		->from($this->_name, array('id', 'license'))
		->order('id');
	$options = $this->getAdapter()->fetchPairs($select);
	$this->_cache->save($options, 'cclicenses');
	}
	return $options;
	}
	
}