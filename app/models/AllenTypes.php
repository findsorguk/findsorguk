<?php
/** A model for manipulating data from the Iron Age class system Allen Types
* @category Pas
* @package Pas_Db_Table
* @subpackage Abstract
* @author Daniel Pett
* @copyright 2010 - DEJ Pett dpett @ britishmuseum.org
* @license GNU General Public License
* @version		1.0
* @since		22 September 2011
*/

class AllenTypes extends Pas_Db_Table_Abstract {

	/** Set the table name
	 * @var string $_name
	 * @access protected
	 */
	protected $_name = 'allentypes';

	/** Set the primary key
	 * @var string $_primary
	 * @access protected
	 */
	protected $_primary = 'id';

	/** Get all valid Allen Types for a dropdown listing
	* @access public
	* @return array $options
	*/
	public function getATypes(){
	if (!$options = $this->_cache->load('atypedd')) {
	$select = $this->select()
		->from($this->_name, array('type', 'type'))
		->order('type');
	$options = $this->getAdapter()->fetchPairs($select);
	$this->_cache->save($options, 'atypedd');
	}
	return $options;
    }

    /** Get all valid Allen Types for a dropdown listing from a query string
    * @param string $q
	* @return array
	* @access public
	*/
	public function getTypes($q){
	$types = $this->getAdapter();
	$select = $types->select()
		->from($this->_name, array('id','term' => 'type'))
		->where('type LIKE ? ', $q.'%')
		->order('type')
		->limit(10);
	return $types->fetchAll($select);
	}

	 /** Get all valid Allen Types for a dropdown listing from a query string
    * @param array $params
	* @return array
	* @access public
	*/
	public function getAllenTypes($params){
	$types = $this->getAdapter();
	$select = $types->select()
		->from($this->_name)
		->order($this->_name . '.type')
		->group($this->_name . '.type');
	$paginator = Zend_Paginator::factory($select);
	$paginator->setItemCountPerPage(30)
		->setPageRange(10)
		->setCache($this->_cache);
	if(isset($params['page']) && ($params['page'] != "")) {
	$paginator->setCurrentPageNumber($params['page']);
	}
	return $paginator;
	}
}
