<?php
/** Abstracted Db table method for adding and deleting data
 * Has config, cache and auth objects setp
 * @category	Pas
 * @package		Pas_Db_Table_
 * @subpackage	Abstract
 * @version		1
 * @since		22nd September 2011
 * @license		GNU General Public License
* @author 		Daniel Pett dpett @ britishmuseum.org
* @copyright 	2010 - DEJ Pett
 *
 */
class Pas_Db_Table_Abstract
	extends Zend_Db_Table_Abstract {

	public $_config;

	public $_cache;

	public $_auth;

	public function __construct(){
	$this->_config	= Zend_Registry::get('config');
	$this->_cache	= Zend_Registry::get('cache');
	$this->_auth	= Zend_Registry::get('auth');
	parent::__construct();
	}

	/** Get the user
	*
	*/
	public function user(){
	$user =  new Pas_User_Details();
	return $user->getPerson();
	}
	
	/** Get the user number for updating
	 * @access 	public
	 * @uses 	Pas_UserDetails
	 */
	public function userNumber(){
	$user = new Pas_User_Details();
	return $user->getIdentityForForms();
	}

	/** Create an update time stamp
	 * @access 	public
	 * @uses	Zend_Date
	 * @return	string $dateTime The timestamp
	 */
	public function timeCreation(){
	$dateTime = Zend_Date::now()->toString('yyyy-MM-dd HH:mm:ss');
	return $dateTime;
	}

	/** Add the data to the model
	 * @access public
	 * @param array $data
	 */
	public function add($data){

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

	/** Update the data to the model
	 * @access public
	 * @param array $data
	 */
	public function update( $data, $where){
        if(array_key_exists('csrf', $data)){
        unset($data['csrf']);
        }

	if(empty($data['updated'])){
		$data['updated'] = $this->timeCreation();
	}
	if(empty($data['updatedBy'])){
		$data['updatedBy'] = $this->userNumber();
	}

        foreach($data as $k => $v) {
            if ( $v == "") {
            $data[$k] = NULL;
            }
        }
	$tableSpec = ($this->_schema ? $this->_schema . '.' : '') . $this->_name;
	return parent::update( $data, $where);
	}

//	public function _purgeCache(){
//    $this->_cache->clean(Zend_Cache::CLEANING_MODE_ALL);
//	}

	public function delete($where) {
    parent::delete($where);
//    $this->_purgeCache();
	}

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