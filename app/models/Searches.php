<?php
/** Data model for accessing searches from database
* @category 	Pas
* @package 		Pas_Db_Table
* @subpackage 	Abstract
* @author 		Daniel Pett dpett @ britishmuseum.org
* @copyright 	2010 - DEJ Pett
* @license 		GNU General Public License
* @version 		1
* @since 		22 October 2010, 17:12:34
* @todo 		implement edit and delete function methods
*/

class Searches extends Pas_Db_Table_Abstract {

    protected $_name = 'searches';

    protected $_primary = 'id';

    /** Insert search string into database
    * @param array $searchstring the search string as params
    * @return boolean
    */
    public function insertResults($searchstring) {
    $table = $this->_name;
    $searches = $this->getAdapter();
    if(isset($searchstring)) {
    $updatesdata = array(
        'searchString' => $searchstring,
        'date' => Zend_Date::now()->toString('yyyy-MM-dd HH:mm:ss'),
        'userid' => $this->userNumber(),
        'ipaddress' => $_SERVER['REMOTE_ADDR'],
        'useragent' => $_SERVER['HTTP_USER_AGENT']
        );
    return $searches->insert($table, $updatesdata);
    }
    }

    /** Get top 5 searches
    * @param integer $id get the top 5 searches entered
    * @return array
    */
    public function getTopSearch($id) {
    $search = $this->getAdapter();
    $select = $search->select()
            ->from($this->_name,array(
                'searchString',
                'C' => 'COUNT(searchString)',
                'date'))
            ->where($this->_name.'.userID = ?', (int)$id)
            ->group('searchString')
            ->order('C DESC')
            ->limit(5);
    return $search->fetchAll($select);
    }

    /** Get top searches by user
    * @param integer $id the userid
    * @return array
    */
    public function getTopSearchQuantity($id) {
    $search = $this->getAdapter();
    $select = $search->select()
            ->from($this->_name,array('C' => 'COUNT(searchString)'))
            ->where($this->_name.'.userID = ?', (int)$id);
    return $search->fetchAll($select);
    }

    /** Get user's last search
    * @param integer $id the userid
    * @return array
    */
    public function getMyLastSearch($id) {
    $search = $this->getAdapter();
    $select = $search->select()
            ->from($this->_name,array('searchString'))
            ->where($this->_name.'.userID = ?', (int)$id)
            ->order($this->_primary . ' DESC')
            ->limit(1);
    return $search->fetchAll($select);
    }

    /** Get paginated list of searches
    * @param integer $userid the userid
    * @param integer $page
    * @return array
    */
    public function getAllSearches($userid,$page) {
    $search = $this->getAdapter();
    $select = $search->select()
            ->from($this->_name)
            ->where($this->_name . '.userID = ?',(int)$userid)
            ->order('id DESC');
    $paginator = Zend_Paginator::factory($select);
    Zend_Paginator::setCache($this->_cache);
    if(isset($page) && ($page != "")) {
    $paginator->setCurrentPageNumber((int)$page);
    }
    $paginator->setItemCountPerPage(10)
            ->setPageRange(10);
    return $paginator;
    }

    /** Get paginated list of all saved searches
    * @param integer $userid the userid
    * @param integer $page
    * @return array
    */
    public function getAllSavedSearches($userid, $page, $private) {
    $search = $this->getAdapter();
    $select = $search->select()
            ->from('savedSearches')
            ->order('created DESC')
            ->joinLeft('users','users.id = savedSearches.createdBy',array('username'));
    if(!is_null($userid)){
    $select->where('savedSearches.createdBy = ?',$userid);
    } else {
    	$select->where('public = ?', 1);
    }

    $paginator = Zend_Paginator::factory($select);
    if(isset($page) && ($page != "")) {
    $paginator->setCurrentPageNumber((int)$page);
    }
    $paginator->setItemCountPerPage(10)
            ->setPageRange(10);
	$paginator->setCache($this->_cache);
    return $paginator;
    }

    /** Get paginated list of all searches for admin interface
    * @param integer $userid the userid
    * @param integer $page
    * @return array
    */
    public function getAllSearchesAdmin($page,$userid) {
    $search = $this->getAdapter();
    $select = $search->select()
            ->from($this->_name)
            ->joinLeft('users','users.id = searches.userid',array('username'))
            ->order('id DESC');
    if(isset($userid)) {
    $select->where($this->_name.'.userID = ?',$userid);
    }
    $paginator = Zend_Paginator::factory($select);
    Zend_Paginator::setCache($this->_cache);
    if(isset($page) && ($page != "")) {
    $paginator->setCurrentPageNumber((int)$page);
    }
    $paginator->setItemCountPerPage(20)
            ->setPageRange(10);
    return $paginator;
    }
}