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
class MackTypes extends Pas_Db_Table_Abstract {

	protected $_name = 'macktypes';

	protected $_primary = 'id';

	/** Retrieve key value paired dropdown list array
	* @return array $paginator
	*/
	public function getMackTypesDD(){
	    $select = $this->select()
			->from($this->_name, array('type', 'type'))
			->order('type');
        $options = $this->getAdapter()->fetchPairs($select);
        return $options;
    }

    /** Retrieve data for an autocomplete ajax query
    * @param string $q
	* @return array $paginator
	* @todo reckon this can be made more efficient in the controller action
	*/
    public function getTypes($q) {
		$types = $this->getAdapter();
		$select = $types->select()
            ->from($this->_name, array('id','term' => 'type'))
			->where('type LIKE ? ', $q . '%')
			->order('type')
			->limit(10);
	   return $types->fetchAll($select);
	}

	/** Retrieve paginated mack types
    * @param integer $page
	* @return array $paginator
	*/
	public function getMackTypes($params) {
		$types = $this->getAdapter();
		$select = $types->select()
            ->from($this->_name)
			->joinLeft('coins','coins.mack_type = macktypes.type',array())
			->order($this->_name . '.type')
			->group($this->_name . '.type');
	$paginator = Zend_Paginator::factory($select);
	$paginator->setCache($this->_cache);
	$paginator->setItemCountPerPage(30)
	          ->setPageRange(10);
	if(isset($params['page']) && ($params['page'] != "")) {
    $paginator->setCurrentPageNumber($params['page']);
	}
	return $paginator;
	}
}