<?php
/**
 * Model for auditing when people login
 *
 * Example of use:
 *
 * <code>
 * <?php
 * $model = new Logins();
 * $data = $model->myLogins($id);
 * ?>
 * </code>
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @copyright (c) 2014, Daniel Pett
 * @category Pas
 * @package Db_Table
 * @subpackage Abstract
 * @license  http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @since 22 September 2011
 * @todo needs caching applied!!!
 * @example /app/modules/database/controllers/IndexController.php
 */
class Logins extends Pas_Db_Table_Abstract {

    /** The table name
     * @access protected
     * @var type
     */
    protected $_name = 'logins';

    /** The primary key
     * @access protected
     * @var int
     */
    protected $_primary = 'id';


    /** Retrieve a paginated list of user's logins
     * @access public
     * @param string $user username
     * @param integer $page page number
     * @return array
     */
    public function myLogins($user, $page){
	$logins = $this->getAdapter();
	$select = $logins->select()
                ->from($this->_name)
                ->where('username =  ? ',(string)$user)
                ->order('id DESC');
	$paginator = Zend_Paginator::factory($select);
	$paginator->setItemCountPerPage(30)
	    	  ->setPageRange(10);
	if(isset($page) && ($page != "")) {
            $paginator->setCurrentPageNumber($page);
	}
	return $paginator;
    }

    /** Retrieve ip address count and addresses used per user for logins
     * @access public
     * @param string $user username
     * @return array
     */
    public function myIps($user, $page) {
        $logins = $this->getAdapter();
        $select = $logins->select()
                ->from($this->_name, array('count' => 'COUNT(ipAddress)','ipAddress'))
                ->where('username =  ? ',$user)
                ->group('ipAddress')
                ->order('id DESC');
        $paginator = Zend_Paginator::factory($select);
        $paginator->setItemCountPerPage(10)
                  ->setPageRange(10);
        Zend_Paginator::setCache($this->_cache);
        if(isset($page) && ($page != "")) {
            $paginator->setCurrentPageNumber($page);
        }
        return $paginator;
    }

    /** Retrieve a list and count of users for a specific IP address
     * @access public
     * @param string $ip ip address
     * @return array
     */
    public function users2Ip($ip) {
        $logins = $this->getAdapter();
        $select = $logins->select()
                ->from($this->_name,
                        array('username','ipAddress','count' => 'COUNT(id)'))
                ->where('ipAddress =  ? ',$ip)
                ->group('username')
                ->order('id DESC');
        return $logins->fetchAll($select);
    }

    /** Retrieve a list of all IP addresses used and by whom
     * @access public
     * @param int $page Page number
     * @return arrau
     */
    public function listIps($page) {
        $logins = $this->getAdapter();
        $select = $logins->select()
                ->from($this->_name,
                        array('count' => 'COUNT(DISTINCT(username))','ipAddress'))
                ->group('ipAddress')
                ->order('id DESC');
        $paginator = Zend_Paginator::factory($select);
        $paginator->setItemCountPerPage(30)
                ->setPageRange(10);
        if(isset($page) && ($page != "")) {
            $paginator->setCurrentPageNumber($page);
        }
        return $paginator;
    }

    /** List recent logins by a specific user
     * @access public
     * @param string $user username
     * @return Array
     */
    public function recentLogin($user){
        $logins = $this->getAdapter();
        $select = $logins->select()
                ->from($this->_name, array('logindate'))
                ->where('username = ?', (string)$user)
                ->order('id DESC')
                ->limit('1 OFFSET 1');
        return $logins->fetchAll($select);
    }

    /** Get a list of who has logged into system today
     * @access public
     * @return array
     */
    public function todayVisitors() {
        $logins = $this->getAdapter();
        $select = $logins->select()
                ->from($this->_name,array('username'))
                ->where('loginDate >= CURDATE()')
                ->group('username');
        return $logins->fetchAll($select);
    }
}