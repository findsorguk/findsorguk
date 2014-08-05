<?php
/** Model for interacting with the user's table
 *
 * An example of use:
 *
 * <code>
 * <?php
 * $model = new Users();
 * $data = $model->activate($data);
 * ?>
 * </code>
 *
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category Pas
 * @package Db_Table
 * @subpackage Abstract
 @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @example /app/forms/ContactForm.php
*/

class Users extends Pas_Db_Table_Abstract {

    /** The table name
     * @access protected
     * @var string
     */
    protected $_name = 'users';

    /** The primary key
     * @access protected
     * @var integer
     */
    protected $_primary = 'id';

    /** Work out who created a user account
     * @access public
     * @param integer $createdby
     * @return array
     */
    public function getCreatedBy($createdby) {
        $select = $this->select()
                ->from($this->_name, array('fullname'))
                ->joinLeft('staff','staff.dbaseID = users.id', array('i' => 'id'))
                ->where('users.id = ?' , (int)$createdby)
                ->limit(1);
        return $this->getAdapter()->fetchAll($select);
    }

    /** Get a key value list of userid and fullname for a dropdown population
     * @access public
     * @return array
     */
    public function getOptions() {
        $select = $this->select()
                ->from($this->_name, array('ID', 'CONCAT(username," - ",fullname)'))
                ->where('institution IS NOT NULL')
                ->where('valid = ?',1)
                ->order('username ASC');
        return $this->getAdapter()->fetchPairs($select);
    }

    /** Get a key value list of userid and fullname for a dropdown population for members higher than basic level
    * and where institution is inserted.
     * @access public
     * @return array
     */
    public function getUserNamesSearch() {
        $select = $this->select()
                ->from($this->_name, array('ID', 'username'))
                ->where('institution IS NOT NULL')
                ->where('role != ?','member')
                ->order('fullname ASC');
        return $this->getAdapter()->fetchPairs($select);
    }

    /** Reset a password
     * @access public
     * @param array $data
     * @return integer
     */
    public function resetPassword( array $data ){
        unset($data['csrf']);
        unset($data['captcha']);
        $where = array();
        $where[] = $this->getAdapter()->quoteInto('activationKey = ?', $data['activationKey']);
        $where[] = $this->getAdapter()->quoteInto('email = ?', $data['email']);
        $person = $this->getUserByUsername($data['email']);
        $updateData = array(
            'password' => SHA1($this->_config->auth->salt. $data['password']),
            'activationKey' => null,
            'valid' => 1,
            'updated' => parent::timeCreation(),
            'updatedBy' => $person['id']
        );
        return parent::update($updateData, $where);
    }

    /** Register a person
     * @access public
     * @param array $data
     * @return integer
     */
    public function register(array $data){
        unset($data['csrf']);
        unset($data['captcha']);
        $data['password'] = SHA1($this->_config->auth->salt. $data['password']);
        $data['activationKey'] = md5($data['username'] . $data['first_name']);
        $data['fullname'] = $data['first_name'] . ' ' . $data['last_name'];
        $data['valid'] = 0;
        $data['role'] = 'member';
        $data['institution'] = 'PUBLIC';
        $data['imagedir'] = 'images/' . $data['username'] . '/';
        $data['created'] = parent::timeCreation();
        $data['createdBy'] = parent::userNumber();
        return parent::insert($data);
    }

    /** Activate an account
     * @access public
     * @param array $data
     * @return integer
     */
    public function activate(array $data){
        unset($data['csrf']);
        $where = array();
        $where[] = $this->getAdapter()->quoteInto('activationKey = ?', $data['activationKey']);
        $where[] = $this->getAdapter()->quoteInto('username = ?', $data['username']);
        $where[] = $this->getAdapter()->quoteInto('email = ?', $data['email']);
        $data = array (
            'valid' => 1,
            'activationKey' => null,
        );
        $username = $data['username'];
        $perm = 0775;
        mkdir(IMAGE_PATH . $username, $perm);
        mkdir(IMAGE_PATH . $username . '/small/', $perm);
        mkdir(IMAGE_PATH . $username . '/medium/', $perm);
        mkdir(IMAGE_PATH . $username . '/display/', $perm);
        mkdir(IMAGE_PATH . $username . '/zoom/', $perm);
        return parent::update($data, $where);
    }

    /** Work out whether activation key exists
     * @access public
     * @param string $key
     * @param string $username
     * @param integer $valid
     * @return array
     */
    public function activation($key, $username, $valid = 0) {
        $select = $this->select()
                ->from($this->_name, array('activationKey', 'username', 'valid'))
                ->where('users.activationKey = ?', (string)$key)
                ->where('users.username = ?', (string)$username)
                ->where('users.valid = ?', (int)$valid);
        return $this->getAdapter()->fetchAll($select);
    }

    /** Retrieve a user based around their email and their username
     * @access public
     * @param string $email
     * @param string $username
     * @return array
     */
    public function findUser($email,$username){
        $select = $this->select()
                ->from($this->_name, array('username','fullname'))
                ->where('users.email = ?', (string) $email)
                ->where('users.username = ?', (string)$username);
        return $this->getAdapter()->fetchAll($select);
    }

    /** Retrieve a user based around their email
     * @access public
     * @param string $email
     * @return array
     */
    public function getUserByUsername($email) {
        $select = $this->select()
                ->from($this->_name, array('username', 'fullname', 'id'))
                ->where('users.email = ?', (string)$email);
        return $this->getAdapter()->fetchAll($select);
    }

    /** Retrieve a user's profile with additional info, such as creator
     * @access public
     * @param integer $id
     * @return array
     */
    public function getUserProfile($id) {
        $select = $this->select()
                ->from($this->_name)
                ->joinLeft($this->_name,$this->_name
                        . '.createdBy = ' . $this->_name . '_2.id',
                        array('creator' => 'fullname'))
                ->joinLeft($this->_name,$this->_name
                        . '.updatedBy = ' . $this->_name . '_3.id',
                        array('updater' => 'fullname'))
                ->where('users.id = ?', (int)$id);
        return $this->getAdapter()->fetchAll($select);
    }

    /** Retrieve a list of authors for the content section.
    */
    public function getAuthors() {
        if (!$accounts = $this->_cache->load('authorlist')) {
            $select = $this->select()
                ->from($this->_name, array('id', 'fullname'))
                ->where('role IN ("admin", "flos", "fa", "treasure")')
                ->order('fullname');
            $accounts =  $this->getAdapter()->fetchPairs($select);
            $this->_cache->save($accounts, 'authorlist');
        }
        return $accounts;
    }

    /** Retrieve a list of users and paginate
     * @access public
     * @param type $params
     * @return \Zend_Paginator
     */
    public function getUsersAdmin(array $params) {
        $select = $this->select()
                ->from($this->_name)
                ->where('valid = ?',1)
                ->order('lastlogin DESC');
        if(isset($params['username']) && ($params['username'] != "")) {
            $un = strip_tags($params['username']);
            $select->where('username LIKE ?', (string)'%'.$un.'%');
        }
        if(isset($params['role']) && ($params['role'] != "")) {
            $r = strip_tags($params['role']);
            $select->where('role = ?', (string)$r);
        }
        if(isset($params['fullname']) && ($params['fullname'] != "")) {
            $fn = strip_tags($params['fullname']);
            $select->where('fullname = ?', (string)$fn);
        }
        if(isset($params['visits']) && ($params['visits'] != ""))  {
            $v = strip_tags($params['visits']);
            $select->where('visits >= ?', (string)$v);
        }
        if(isset($params['lastlogin']) && ($params['lastlogin'] != "")) {
            $ll = strip_tags($params['lastlogin']);
            $select->where('lastLogin >= ?', $ll.' 00:00:00');
        }
        $paginator = Zend_Paginator::factory($select);
        Zend_Paginator::setCache($this->_cache);
        $paginator->setItemCountPerPage(20)->setPageRange(10) ;
        if(isset($params['page']) && ($params['page'] != ""))  {
            $paginator->setCurrentPageNumber($params['page']);
        }
        return $paginator;
    }

    /** Retrieve a user with additional info using their username as the lookup
     * @access public
     * @param string $username
     * @return array
     */
    public function findUserAccount($username) {
        $select = $this->select()
            ->from($this->_name)
            ->joinLeft($this->_name,$this->_name . '.createdBy = '
                    . $this->_name . '_2.id',
                    array('creator' => 'fullname'))
            ->joinLeft($this->_name,$this->_name . '.updatedBy = '
                    . $this->_name . '_3.id',
                    array('updater' => 'fullname'))
            ->where('users.username = ?', (string)$username);
        return $this->getAdapter()->fetchAll($select);
    }

    /** Retrieve a list of members attached to an institution on the system
     * @access public
     * @param integer $instID
     * @return array
     */
    public function getMembersInstitution($instID) {
        $select = $this->select()
                ->from($this->_name)
                ->joinLeft('institutions',$this->_name
                        .'.institution = institutions.institution', array())
                ->where('institutions.id = ?',(int)$instID);
        return $this->getAdapter()->fetchAll($select);
    }

    /** Retrieve a count of registered users who have been more active than you
     * @param integer $visits
     * @return array
     */
    public function getMoreTotals($visits) {
        $select = $this->select()
                ->from($this->_name ,array('morethan' => 'COUNT(*)'))
                ->where($this->_name.'.visits > ?',(int)$visits);
        return $this->getAdapter()->fetchAll($select);
    }

    /** Retrieve a paginated list of members with certain privilege
    * @param integer $visits
    * @return \Zend_Paginator
    */
    public function getRolesMembers($role,$page) {
        $select = $this->select()
                ->from($this->_name,
                        array(
                            'username', 'createdBy', 'updatedBy',
                            'id', 'fullname'
                            ))
                ->joinLeft('roles',$this->_name . '.role = roles.role', array())
                ->where('roles.id = ?',(int)$role);
        $data = $this->getAdapter()->fetchAll($select);
        $paginator = Zend_Paginator::factory($data);
        if(isset($page) && ($page != "")) {
            $paginator->setCurrentPageNumber((int)$page);
        }
        $paginator->setItemCountPerPage(50)->setPageRange(10);
        return $paginator;
    }

    /** Retrieve a user's data via username
     * @access public
     * @param string $username
     * @return array
     */
    public function getUserAccountData($username) {
        $select = $this->select()
                ->from($this->_name)
                ->where('users.username = ?', (string)$username);
        return $this->getAdapter()->fetchAll($select);
    }

    /** Retrieve a user's count and quantity of finds recorded
     * @access public
     * @param integer $id
     * @return array
     */
    public function getCountFinds($id) {
        $select = $this->select()
                ->from('finds', array(
                    'records' => 'COUNT(finds.id)',
                    'finds' => 'SUM(finds.quantity)'))
                ->where('finds.createdBy = ?', (int)$id);
        return $this->getAdapter()->fetchAll($select);
    }

    /** Retrieve a user's ID number
     * @access public
     * @param string $username
     * @return array
     */
    public function getUserID($username){
        $select = $this->select()
                ->from($this->_name,array('id'))
                ->where($this->_name . '.username = ?', (string)$username);
        return $this->getAdapter()->fetchAll($select);
    }

    /** Retrieve a count of who has requested higher level access
     * @access public
     * @return array
     */
    public function getNewHigherLevelRequests()	{
        $select = $this->select()
            ->from($this->_name,array('applicants' => 'COUNT(id)'))
            ->where($this->_name.'.higherLevel = ?', (int)1)
            ->where('role IN ( "public" ,"member" )');
        return $this->getAdapter()->fetchAll($select);
    }

    /** Retrieve a list of requested upgrades paginated
     * @access public
     * @param integer $page
     * @return \Zend_Paginator
     */
    public function getUpgrades($page) {
        $select = $this->select()
                ->from($this->_name)
                ->where($this->_name.'.higherLevel = ?', (int)1)
                ->where('role IN ( "public" ,"member" )');
        $paginator = Zend_Paginator::factory($select);
        $paginator->setCache($this->_cache);
        $paginator->setItemCountPerPage(20)->setPageRange(10) ;
        if(isset($page) && ($page != "")) {
            $paginator->setCurrentPageNumber($page);
        }
        return $paginator;
    }

    /** Retrieve a cached list of who signed up today
     * @access public
     * @return array
     */
    public function newPeople() {
        $key = md5('newUsers');
        if (!$accounts = $this->_cache->load($key)) {
            $select = $this->select()
                    ->from($this->_name,array('username'))
                    ->where('created >= CURDATE()')
                    ->where('activationKey IS NULL')
                    ->where('valid = ?', 1);
            $accounts = $this->getAdapter()->fetchAll($select);
            $this->_cache->save($accounts, $key);
        }
        return $accounts;
    }

    /** Update number of visits
     * @access public
     * @param array $data
     * @param array $where
     * @return integer
     */
    public function updateVisits(array $data, array $where){
        if(array_key_exists('csrf', $data)){
            unset($data['csrf']);
        }
        foreach($data as $k => $v) {
            if ( $v == "") {
                $data[$k] = NULL;
            }
        }
        $tableSpec = ($this->_schema ? $this->_schema . '.' : '') . $this->_name;
        return $this->_db->update($tableSpec, $data, $where);
    }

    /** Retrieve a user based around their email and their username
    * @param string $q The user's name on system
    * @return array
    */
    public function userNames($q){
        $select = $this->select()
                ->from($this->_name, array(
                    'id' => 'username',
                    'term' => 'username'
                    ))
                ->where('users.username LIKE ?', '%' . (string)$q . '%')
                ->limit(10);
        return $this->getAdapter()->fetchAll($select);
    }

    /** Retrieve a user based around their email and their username
     * @access public
     * @param string $q
     * @return array
     */
    public function userFullNames($q){
        $select = $this->select()
            ->from($this->_name, array('id' => 'fullname','term' => 'fullname'))
            ->where('users.fullname LIKE ?', '%' . (string)$q . '%')
            ->limit(10);
        return $this->getAdapter()->fetchAll($select);
    }
}