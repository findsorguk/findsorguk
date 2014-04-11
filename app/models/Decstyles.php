<?php
/** A model for pulling decorative styles from the database
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

class Decstyles extends Pas_Db_Table_Abstract {

	protected $_name = 'decstyles';
	protected $_primaryKey = 'id';

	/** Retrieve a list of decoration styles as a key pair value chain
	* @return array
	*/
	
	public function getStyles() {
	if (!$options = $this->_cache->load('decstyledd')) {
	$select = $this->select()
		->from($this->_name, array('id', 'term'))
		->order('term');
	$options = $this->getAdapter()->fetchPairs($select);
	$this->_cache->save($options, 'decstyledd');
	}
	return $options;
	}

	/** Retrieve an array of decoration style by term id
	* @param integer $decstyle
	* @return array
	*/
	public function getDecStyle($decstyle){
	$select = $this->select()
		->from($this->_name, array('term'))
		->where('id = ?',(int)$decstyle);
	$options = $this->getAdapter()->fetchAll($select);
	return $options;
	}
	
	/** Retrieve a list of decoration styles (all columns)
	* @return object
	*/
	
	public function getDecStyles(){
	$styles = $this->getAdapter();
	$select = $styles->select()
		->from($this->_name)
		->where('valid = ?',(int)1);
	return $styles->fetchAll($select);
	}

	/** Retrieve an individual decoration style (all columns)
	* @param integer $id
	* @return object
	* @todo maybe merge this with an earlier function
	* @todo add caching
	*/
	
	public function getDecStyleDetails($id) {
	$select = $this->select()
		->from($this->_name)
		->where('valid = ?',(int)1)
		->where('id = ?',(int)$id);
	$options = $this->getAdapter()->fetchAll($select);
	return $options;
	}

	/** Retrieve an individual decoration style count by objects - expensive
	* @param integer $id
	* @return object
	* @todo add caching
	*/
	public function getDecStylesCounts($id) {
	$styles = $this->getAdapter();
	$select = $styles->select()
		->from($this->_name)
		->joinLeft('finds','finds.decstyle = ' . $this->_name . '.id', array('c' => 'count(finds.id)'))
		->where('valid = ?',(int)1)
		->where($this->_name . '.id = ?',(int)$id)
		->group($this->_name . '.id');
	return $styles->fetchAll($select);
    }
    
    /** Retrieve a list of decorative styles for admin interface
	* @return object
	* @todo add caching
	*/
	public function getDecStylesAdmin() {
	$styles = $this->getAdapter();
	$select = $styles->select()
		->from($this->_name)
		->joinLeft('users','users.id = ' . $this->_name . '.createdBy',array('fullname'))
		->joinLeft('users','users_2.id = ' . $this->_name . '.updatedBy',array('fn' => 'fullname'));
	return $styles->fetchAll($select);
    }

}
