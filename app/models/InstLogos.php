<?php
/** Model for interacting with institutional logos
* @category Pas
* @package Pas_Db_Table
* @subpackage Abstract
* @author Daniel Pett dpett @ britishmuseum.org
* @copyright 2010 - DEJ Pett
* @license 		GNU General Public License
* @version 		1
* @since 		22 September 2011
* */
class InstLogos extends Pas_Db_Table_Abstract {

	protected $_name = 'instLogos';

	protected $_primary = 'id';

	/** Get the logo for an institution
	* @param string $inst 
	* @return array $data
	* @todo add caching
	*/
	public function getLogosInst($inst) {
		$logos = $this->getAdapter();
		$select = $logos->select()
            ->from($this->_name, array('image'))
			->joinLeft('institutions','institutions.institution = ' . $this->_name.'.instID',array())
			->where('institutions.institution = ?', (string)$inst);
     return  $logos->fetchAll($select);
	}
}