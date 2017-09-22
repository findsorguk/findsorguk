<?php
/** Data model for accessing searches from database
 * 
 * An example of use:
 * 
 * <code>
 * <?php
 * $search = new Searches();
 * $searches = $search->getAllSearchesAdmin($page);
 * ?>
 * </code>
 * 
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category Pas
 * @package Db_Table
 * @subpackage Abstract
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @since 22 October 2010, 17:12:34
 * @example /app/modules/admin/controllers/SearchController.php
*/

class Searches extends Pas_Db_Table_Abstract {

    /** The table name
     * @access public
     * @var string
     */
    protected $_name = 'searches';

    /** The primary key
     * @access protected
     * @var type 
     */
    protected $_primary = 'id';

    /** Insert search string into database
     * @access public
     * @param string $searchstring
     * @return array
     */
    public function insertResults($searchstring) {
        $table = $this->_name;
        $searches = $this->getAdapter();
        if(isset($searchstring)) {
            $updatesdata = array(
                'searchString' => $searchstring,
                'date' => $this->timeCreation(),
                'userid' => $this->getUserNumber(),
                'ipaddress' => $_SERVER['REMOTE_ADDR'],
                'useragent' => $_SERVER['HTTP_USER_AGENT']
                );
            return $searches->insert($table, $updatesdata);
        }
    }

    /** Get top 5 searches
     * @access public
     * @param integer $id
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
     * @access public
     * @param integer $id
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
     * @access public
     * @param integer $id
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
    * @return \Zend_Paginator
    */
    public function getAllSearches($userid, $page) {
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
        $paginator->setItemCountPerPage(10)->setPageRange(10);
        return $paginator;
    }

    /** Get paginated list of all saved searches
     * @access public
     * @param integer $userid
     * @param integer $page
     * @return \Zend_Paginator
     */
    public function getAllSavedSearches($userid, $page) {
        $search = $this->getAdapter();
        $select = $search->select()
                ->from('savedSearches')
                ->order('created DESC')
                ->joinLeft('users','users.id = savedSearches.createdBy',
                        array('username'));
        if(!is_null($userid)){
            $select->where('savedSearches.createdBy = ?', (int)$userid);
        } else {
            $select->where('public = ?', 1);
        }
        $paginator = Zend_Paginator::factory($select);
        Zend_Paginator::setCache($this->_cache);
        if(isset($page) && ($page != "")) {
            $paginator->setCurrentPageNumber((int)$page);
        }
        $paginator->setItemCountPerPage(10)->setPageRange(10);
        return $paginator;
    }

    /** Get paginated list of all searches for admin interface
     * @access public
     * @param integer $page
     * @param integer $userid
     * @return \Zend_Paginator
     */
    public function getAllSearchesAdmin($page, $userid) {
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
        $paginator->setItemCountPerPage(20)->setPageRange(10);
        return $paginator;
    }
}