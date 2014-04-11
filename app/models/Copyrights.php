<?php
/** Model for manipulating copyright data
* @category Pas
* @package Pas_Db_Table
* @subpackage Abstract
* @author Daniel Pett dpett @ britishmuseum.org
* @copyright 2010 - DEJ Pett
* @license 		GNU General Public License
* @version 		1
* @since 		22 September 2011
* */

class Copyrights extends Pas_Db_Table_Abstract {
	
	protected $_name = 'copyrights';
	protected $_primary = 'id';
	/** Get dropdown values for personal copyrights
    * @param integer $id
	* @return array
	*/
	public function getStyles() {
	if (!$options = $this->_cache->load('imagecopyright')) {
	$select = $this->select()
		->from($this->_name, array('copyright', 'copyright'))
		->order('copyright');
	$options = $this->getAdapter()->fetchPairs($select);
	$this->_cache->save($options, 'imagecopyright');
	}
	return $options;
	}
}
