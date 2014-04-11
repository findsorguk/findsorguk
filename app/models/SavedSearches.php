<?php 
/** Data model for accessing and manipulating saved searches
* @category 	Pas
* @package 		Pas_Db_Table
* @subpackage 	Abstract
* @author 		Daniel Pett dpett @ britishmuseum.org
* @copyright 	2010 - DEJ Pett
* @license 		GNU General Public License
* @version 		1
* @since 		22 October 2010, 17:12:34
*/
class SavedSearches extends Pas_Db_Table_Abstract {
	
	protected $_name = 'savedSearches';
	
	protected $_primary = 'id';
	
	
	/** Get all saved searches as a paginated array
	* @param integer $userid the user's id number 
	* @param integer $page the page number
	* @param boolean $private whether the search is public or private 
	* @return Array
	* @todo add caching
	*/
	public function getSavedSearches($userid,$page,$private) {
	$search = $this->getAdapter();
	$select = $search->select()
	->from($this->_name)
	->joinLeft('users',$this->_name . '.createdBy = users.id', array( 'username' ))
	->order('id DESC');
	if(isset($userid)) {
	$select->where($this->_name . '.createdBy = ?', (int)$userid);
	}
	if(!isset($private)) {
	$select->where($this->_name . '.public = ?',(int)1);
	}
	$paginator = Zend_Paginator::factory($select);
	if(isset($page) && ($page != "")) {
	$paginator->setCurrentPageNumber((int)$page); 
	}
	$paginator->setItemCountPerPage(10) 
	->setPageRange(10); 
	return $paginator;
	}
}