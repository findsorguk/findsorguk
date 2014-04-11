<?php
/**
* A model for pulling a list of crime types from the database. To delete? 
* @category Pas
* @package Pas_Db_Table
* @subpackage Abstract
* @author Daniel Pett dpett @ britishmuseum.org
* @copyright 2010 - DEJ Pett
* @license 		GNU General Public License
* @version 		1
* @since 		22 September 2011
*/

class CrimeTypes extends Pas_Db_Table_Abstract
{
	protected $_primary = 'id';
	protected $_name = 'crimeTypes';
	
	/** Get a list of all crime types as key pair values
	* @return array
	*/
	public function getTypes(){
	if (!$options = $this->_cache->load('crimetypes')) {
	$select = $this->select()
		->from($this->_name, array('id', 'term'))
		->order('id');
	$options = $this->getAdapter()->fetchPairs($select);
	$this->_cache->save($options, 'crimetypes');
	}
	return $options;
	}	
}