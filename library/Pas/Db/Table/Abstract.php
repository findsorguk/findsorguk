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
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014, Daniel Pett
 * @uses Pas_ArrayFunctions
 * @uses Pas_User_Details
 * @uses Zend_Registry
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
    
    /** The array functions class
     * @access protected
     * @var \Pas_ArrayFunctions
     */
    protected $_cleaner;

    const DBASE_ID = 'PAS';

    const SECURE_ID = '001';
    
    /** The array cleaner functions
     * @access public
     * @return \Pas_ArrayFunctions
     */
    public function getCleaner() {
        $this->_cleaner = new Pas_ArrayFunctions();
        return $this->_cleaner;
    }

    
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
    public function getUserNumber(){
	return $this->_user->getIdentityForForms();
    }

    /** Get the user's role
     * @access public
     * @return string
     */
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

    /** Get the institution of the user
     * @access 	protected
     * @uses 	Pas_User_Details
     * @return  array
     * @throws Pas_Exception_BadJuJu
     */
    protected function getInstitution(){
        $user = new Pas_User_Details();
        $person = $user->getPerson();
        if($person){
            return $person->institution;
        } else {
            throw new Pas_Exception_BadJuJu('No user credentials found', 500);
        }
    }

    /** Generates a secuid for various types of new records
     * @access 	public
     * @uses	Pas_GenerateSecuID
     * @return	string $secuid The secuID
     */
    protected function generateSecuId() {
        list($usec, $sec) = explode(" ", microtime());
        $ms = dechex(round($usec * 4080));
        while(strlen($ms) < 3) {
            $ms = '0' . $ms;
        }
        $secuid = strtoupper(self::DBASE_ID . dechex($sec) . self::SECURE_ID . $ms);

        return $secuid;
    }

    /** Add the data to the model
     * @access public
     * @param array $data
     */
	
    public function add(  $data ){
   	
        $data = $this->getCleaner()->array_cleanup($data);
	if(empty($data['created'])){
            $data['created'] = $this->timeCreation();
	}
	if(empty($data['createdBy'])){
            $data['createdBy'] = $this->getUserNumber();
	}
        foreach($data as $k => $v) {
            if ( $v == "") {
                $data[$k] = NULL;
            }
        }
        return parent::insert(  $data);
    }    

    /** The update over ride
     * @access public
     * @param array $data
     * @param array $where
     * @return integer
     */
    public function update (array $data, $where ) {
        $data = $this->getCleaner()->array_cleanup($data);
        if(empty($data['updated'])){
		$data['updated'] = $this->timeCreation();
	}
	if(empty($data['updatedBy'])){
		$data['updatedBy'] = $this->getUserNumber();
	}
	return parent::update( $data, $where);
    }
    /** Fetch pairs from the model
     * @access public
     * @param string $sql
     * @param array $bind
     * @return array
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