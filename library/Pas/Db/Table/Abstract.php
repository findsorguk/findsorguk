<?php
/** 
 * Abstracted Db table method for adding and deleting data
 * Has config, cache and auth objects setp. This is the base code for all 
 * models used in this app.
 * 
 * @category	Pas
 * @package	Pas_Db_Table
 * @subpackage	Abstract
 * @version 1
 * @since 22nd September 2011
 * @license GNU General Public License
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014, Daniel Pett
 *
 */
class Pas_Db_Table_Abstract extends Zend_Db_Table_Abstract {

    /** The config object
     * @access public
     * @var \Zend_Config
     */
    public $_config;

    /** The cache object
     * @access public
     * @var \Zend_Cache
     */
    public $_cache;

    /** The auth object
     * @access public
     * @var \Zend_Auth
     */
    public $_auth;
    
    /** The user model class
     * @access public
     * @var type 
     */
    protected $_user;

    /** Construct the objects
     * @access public
     */
    public function __construct(){
        $this->_config	= Zend_Registry::get('config');
	$this->_cache	= Zend_Registry::get('cache');
	$this->_auth	= Zend_Registry::get('auth');
        $this->_user = new Pas_User_Details();
	parent::__construct();
    }

    /** Get the user's full details
     * @access public
     * @uses Pas_User
     * @return object
     */
    public function user(){
	return $this->_user->getPerson();
    }
	
    /** Get the user number for updating
     * @access 	public
     * @uses 	Pas_UserDetails
     * @return object
     */
    public function userNumber(){
	return $this->_user->getIdentityForForms();
    }

    public function getUserRole() {
        return $this->_user->getRole();
    }
	
    /** Create an update time stamp
     * @access public
     * @uses Zend_Date 
     * @return	string $dateTime The timestamp
     */
    public function timeCreation(){
        return Zend_Date::now()->toString('yyyy-MM-dd HH:mm:ss');
    }

    /** Add the data to the model
     * @access public
     * @param array $data
     */
	
    public function add( array $data){
   	if(array_key_exists('csrf', $data)){
            unset($data['csrf']);
        }
	if(empty($data['created'])){
            $data['created'] = $this->timeCreation();
	}
	if(empty($data['createdBy'])){
            $data['createdBy'] = $this->userNumber();
	}
        foreach($data as $k => $v) {
            if ( $v == "") {
                $data[$k] = NULL;
            }
        }
        return parent::insert($data);
    }    

    /** Delete data from model
     * @access public
     * @param array $where
     */
    public function delete( array $where) {
        parent::delete($where);
    }

    /** Fetch pairs from the model
     * @access public
     * @param type $sql
     * @param type $bind
     * @return type
     */
    public function fetchPairs($sql, $bind = array()) {
        $id = md5($sql);
        if ((!($this->_cache->test($id))) || (!$this->cache_result)) {
            $result = parent::fetchPairs($sql, $bind);
            $this->_cache->save($result);
            return $result;
        } else {
            return $this->_cache->load($id);
        }
    }
}