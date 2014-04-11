<?php
/** Model for auditing when people login
* @category Pas
* @package Pas_Db_Table
* @subpackage Abstract
* @author Daniel Pett dpett @ britishmuseum.org
* @copyright 2010 - DEJ Pett
* @license 		GNU General Public License
* @version 		1
* @since 		22 September 2011
* @todo needs caching applied!!!
*/
class Logins extends Pas_Db_Table_Abstract {

	protected $_name = 'logins';

	protected $_primary = 'id';

	/** Retrieve a paginated list of user's logins
	* @param string $user username
	* @param integer $page page number
	* @return Array
	*/
	public function myLogins($user,$page){
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
	* @param string $user username
	* @return Array
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
	$paginator->setCache($this->_cache);
	if(isset($page) && ($page != "")) {
    	$paginator->setCurrentPageNumber($page);
	}
	return $paginator;
	}

	/** Retrieve a list and count of users for a specific IP address
	* @param string $ip ip address
	* @return Array
	*/
	public function users2Ip($ip) {
	$logins = $this->getAdapter();
	$select = $logins->select()
            ->from($this->_name, array('username','ipAddress','count' => 'COUNT(id)'))
            ->where('ipAddress =  ? ',$ip)
            ->group('username')
            ->order('id DESC');
	return $logins->fetchAll($select);
	}

	/** Retrieve a list of all IP addresses used and by whom
	* @return Array
	*/
	public function listIps($page) {
	$logins = $this->getAdapter();
	$select = $logins->select()
            ->from($this->_name, array('count' => 'COUNT(DISTINCT(username))','ipAddress'))
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
	* @param string $user
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
	* @return Array
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