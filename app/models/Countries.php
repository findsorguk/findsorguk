<?php
/** A model for retrieving a list of ISO countries 
* @category Pas
* @package Pas_Db_Table
* @subpackage Abstract
* @author Daniel Pett dpett @ britishmuseum.org
* @copyright 2010 - DEJ Pett
* @license 		GNU General Public License
* @version 		1
* @since 		22 September 2011
* @todo add caching
*/

class Countries extends Pas_Db_Table_Abstract {

	protected $_name = 'countries';

	protected $_primary = 'iso';
	/** retrieve a key pair list of ISO countries
	* @return array
	*/
	public function getOptions() {
	$select = $this->select()
		->from($this->_name, array('iso', 'printable_name'))
		->order('printable_name ASC');
	$options = $this->getAdapter()->fetchPairs($select);
	return $options;
	}
}
