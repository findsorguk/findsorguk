<?php
/** Data model for accessing van arsdell iron age coin types
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

class VanArsdellTypes extends Pas_Db_Table_Abstract {

	protected $_name = 'vanarsdelltypes';

	protected $_primary = 'id';

	/** Get a dropdown list of VA types as key value array
	* @return array
	*/
	public function getVATypesDD() {
    if (!$options = $this->_cache->load('vatypedd')) {
	$select = $this->select()
		->from($this->_name, array('type', 'type'))
		->order('type');
	$options = $this->getAdapter()->fetchPairs($select);
	$this->_cache->save($options, 'vatypedd');
	}
	return $options;
    }

    /** Get a list of types based on query string
    * @param string $q
	* @return array
	*/
	public function getTypes($q) {
	$types = $this->getAdapter();
	$select = $types->select()
		->from($this->_name, array('id','term' => 'type'))
		->where('type LIKE ? ', (string)$q.'%')
		->order('type')
		->limit(10);
	return $types->fetchAll($select);
	}

	/** Get a list of va types paginated
    * @param integer $params['page'] page number requested
	* @return array
	*/
	public function getVaTypes($params) {
	$types = $this->getAdapter();
	$select = $types->select()
		->from($this->_name);
	$paginator = Zend_Paginator::factory($select);
	$paginator->setItemCountPerPage(30)
		->setPageRange(10);
	if(isset($params['page']) && ($params['page'] != "")) {
    $paginator->setCurrentPageNumber($params['page']);
	}
	return $paginator;
	}

}