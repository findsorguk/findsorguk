<?php
/** Model for pulling hoard cover sheets from database
* @category 	Pas
* @package 		Pas_Db_Table
* @subpackage 	Abstract
* @author 		Daniel Pett dpett @ britishmuseum.org
* @copyright 	2010 - DEJ Pett
* @license 		GNU General Public License
* @version 		1
* @since 		22 September 2011
* @todo add caching
*/

class Hoards extends Pas_Db_Table_Abstract {

	protected $_name = 'hoards';

	protected $_primary = 'id';

	/** Retrieval of all hoards on database
	* @return array $data
	* @todo add caching
	*/
	public function getHoards() {
        $select = $this->select()
                       ->from($this->_name, array('id', 'term'))
                       ->order('term ASC');
        $options = $this->getAdapter()->fetchPairs($select);
        return $options;
	}

	/** Retrieval of hoard data by ID number
	* @param integer $id
	* @return array $data
	* @todo add caching
	*/
	public function getHoardDetails($id) {
		$hoards = $this->getAdapter();
		$select = $hoards->select()
			->from($this->_name)
			->joinLeft('periods','periods.id = hoards.period', array('t' => 'term'))
			->where('hoards.id =? ',(int)$id);
		 return $hoards->fetchAll($select);
		 }

	/** get paginated hoard list
	* @param integer $page
	* @return array $data
	* @todo add caching
	*/
	public function getHoardList($params) {
		$hoards = $this->getAdapter();
		$select = $hoards->select()
			->from($this->_name)
			->joinLeft('finds','finds.hoardID = hoards.id', array('q' => 'SUM(quantity)'))
			->joinLeft('periods','periods.id = hoards.period', array('t' => 'term'))
			->group('hoards.id')
			->order($this->_name . '.id ASC');
		$paginator = Zend_Paginator::factory($select);
		$paginator->setItemCountPerPage(30)
	          ->setPageRange(10);
		if(isset($params['page']) && ($params['page'] != "")) {
    	      $paginator->setCurrentPageNumber((int)$params['page']);
		}
        return $paginator;
		}


}
