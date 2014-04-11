<?php
/** Model for sorting out which HERs have signed up
* @category 	Pas
* @package 		Pas_Db_Table
* @subpackage 	Abstract
* @author 		Daniel Pett dpett @ britishmuseum.org
* @copyright 	2010 - DEJ Pett
* @license 		GNU General Public License
* @version 		1
* @since 		22 September 2011
* @todo 		add caching and work out if still valid function
*/

class Hers extends Pas_Db_Table_Abstract {

	protected $_name = 'hers';

	protected $_primary = 'id';

	/** Retrieval of all HERs
	* @return array $data
	*/
	public function getAll($params) {
		$types = $this->getAdapter();
		$select = $types->select()
            ->from($this->_name)
			->order('name');
	$paginator = Zend_Paginator::factory($select);
	Zend_Paginator::setCache(Zend_Registry::get('cache'));
	if(isset($params['page']) && ($params['page'] != "")) {
	$paginator->setCurrentPageNumber((int)$params['page']); 
	}
	$paginator->setItemCountPerPage(20) 
    	      ->setPageRange(10); 
	return $paginator;
	}

}