<?php
/** Model for interacting with the people table
* @category 	Pas
* @package 		Pas_Db_Table
* @subpackage 	Abstract
* @author 		Daniel Pett dpett @ britishmuseum.org
* @copyright 	2010 - DEJ Pett
* @license 		GNU General Public License
* @version 		1
* @since 		22 September 2011
*/
class Peoples extends Pas_Db_Table_Abstract {

	protected $_name = 'people';

	protected $_primary = 'id';

	/** Get people's name from an ajax lookup with concatenation different to next function
	* @param string $q
	* @return array
	*/
	public function getNames($q) {
	$select = $this->select()
		->from($this->_name, array('id' => 'secuid', 'term' => 'CONCAT(fullname," ",ifnull(county,""))'))
		->where('fullname LIKE ?', '%' . $q . '%')
		->order('secuid')
		->limit(20);
    $options = $this->getAdapter()->fetchAll($select);
    return $options;
    }

    /** Get basic names from query
	* @param string $q
	* @return array
	*/
	public function getNamesSearch($q) {
	$select = $this->select()
        ->from($this->_name, array('id' => 'secuid', 'term' => 'fullname'))
		->where('fullname LIKE ?', '%' . $q . '%')
        ->order('secuid')
	   ->limit(20);
    $options = $this->getAdapter()->fetchAll($select);
    return $options;
    }

    /** Get basic names from query
	* @param string $q
	* @return Array
	*/
	public function getNames2() {
	$select = $this->select()
		->from($this->_name, array('id' => 'secuid', 'term' => 'fullname'))
		->order('secuid');
    $options = $this->getAdapter()->fetchAll($select);
    return $options;
    }

    /** Get personal details to an individual find
	* @param integer $id
	* @return boolean
	*/
    public function getPerson($id) {
	$select = $this->select()
		->from($this->_name, array('id' => 'secuid', 'term' => 'fullname'))
		->joinLeft('finds','finds.finderID = people.secuid', array())
		->where('fullname IS NOT NULL')
		->where('finds.id = ?', (int)$id)
        ->order('secuid');
	$options = $this->getAdapter()->fetchAll($select);
	return $options;
	}

	/** Get personal details for a finder by secuid string
	* @param string $finder
	* @return array
	*/
	public function getName($finder) {
	$select = $this->select()
		->from($this->_name, array('id' => 'secuid', 'term' => 'fullname'))
		->where('fullname IS NOT NULL')
		->where('secuid = ?', (string)$finder)
		->limit(1)
        ->order('secuid');
	return	$this->getAdapter()->fetchAll($select);
    }

    /** Get personal details for a finder by id with enhanced information
	* @param integer $id
	* @return array
	*/
	public function getPersonDetails($id) {
	$persons = $this->getAdapter();
	$select = $persons->select()
		->from($this->_name)
		->joinLeft('countries','people.country = countries.iso', array('abode' => 'printable_name'))
		->joinLeft('primaryactivities','people.primary_activity = primaryactivities.id',
		array('role' => 'term'))
		->joinLeft('organisations', 'people.organisationID = organisations.secuid',
		array('secid' => 'secuid','orgaddress' => 'address', 'orgcounty' => 'county', 'orgpostcode' => 'postcode',
		'orglat' => 'lat', 'orglon' => 'lon', 'org' => 'name',
		'i' => 'id', 'orgwoeid' => 'woeid', 'orgwebsite' => 'website'))
		->joinLeft(array('count' => 'countries'),'organisations.country = count.iso',
		array('orgcountry' => 'printable_name'))
		->joinLeft('users','users.id = ' . $this->_name . '.createdBy', array('creator' => 'fullname'))
   		->joinLeft('users','users_2.id = ' . $this->_name . '.updatedBy', array('fn' => 'fullname'))
		->where('people.id = ?', (int)$id);
     return	$persons->fetchAll($select);
    }

    /** Get list of people in paginated format
	* @param string $params['fullname']
	* @param string $params['primary_activity']
	* @param string $params['organisationID']
	* @param string $params['county']
	* @param integer $params['page']
	* @return array
	*/
    public function getPeopleList($params){
	$persons = $this->getAdapter();
	$select = $persons->select()
		->from($this->_name)
		->joinLeft('primaryactivities','people.primary_activity = primaryactivities.id',array('role' => 'term'))
		->joinLeft('organisations','people.organisationID = organisations.secuid',array('org' => 'name', 'i' => 'id'))
		->order(array('surname','forename'));
	if(isset($params['fullname']) && ($params['fullname'] != "")){
	$fullname = strip_tags($params['fullname']);
	$select->where('fullname LIKE ?', '%' . $fullname . '%');
	}
	if(isset($params['primary_activity']) && ($params['primary_activity'] != "")) {
	$pa = strip_tags($params['primary_activity']);
	$select->where('primary_activity = ?', (int)$pa);
	}
	if(isset($params['organisationID']) && ($params['organisationID'] != "")) 	{
	$orgID = strip_tags($params['organisationID']);
	$select->where('organisationID = ?', (string)$orgID);
	}
	if(isset($params['county']) && ($params['county'] != "")) {
	$county = strip_tags($params['county']);
	$select->where($this->_name . '.county = ?', (string)$county);
	}
	$paginator = Zend_Paginator::factory($select);
	$paginator->setItemCountPerPage(30)
	          ->setPageRange(10);
	if(isset($params['page']) && ($params['page'] != "")) {
    $paginator->setCurrentPageNumber($params['page']);
	}
	return $paginator;
	}

	/** Get count of people to annoy Paul Barford
	* @return array
	*/
	public function getCountAllPeople() {
	$persons = $this->getAdapter();
	$select = $persons->select()
          ->from($this->_name,array('total' => 'COUNT(*)'));
    return	$persons->fetchAll($select);
	}

	/** Get secuid and fullname, isn't this the same as earlier function
	* @param integer $id
	* @return array
	*/
	public function getSecuids($id){
	$persons = $this->getAdapter();
	$select = $persons->select()
		->from($this->_name,array('secuid','fullname'))
		->joinLeft('users',$this->_name.'.secuid = users.peopleID',array())
		->where('users.id = ?',(int)$id);
	return	$persons->fetchAll($select);
	}

	/** Get dropdown list of curators names
	* @return array
	*/
	public function getCurators() {
	if (!$data = $this->_cache->load('curators')) {
	$persons = $this->getAdapter();
	$select = $persons->select()
		->from($this->_name,array('secuid','fullname'))
		->where($this->_name . '.primary_activity  = ?',(int)18);
    $data = $persons->fetchPairs($select);
	$this->_cache->save($data, 'curators');
	}
    return $data;
	}

	/** Get dropdown list of valuers names
	* @return array
	*/
	public function getValuers() {
	if (!$data = $this->_cache->load('valuers')) {
	$persons = $this->getAdapter();
	$select = $persons->select()
		->from($this->_name,array('secuid','fullname'))
		->where($this->_name . '.primary_activity  = ?',(int)19);
    $data = $persons->fetchPairs($select);
	$this->_cache->save($data, 'valuers');
	}
    return $data;
	}

	/** Get people data for solr updates
	 *
	 */
	public function getSolrData($id){
	$persons = $this->getAdapter();
	$select = $persons->select()
		->from($this->_name,array(
			'identifier' => 'CONCAT("people-",people.id)','people.id',
			'fullname', 'surname','forename',
			'lon','lat',
			'email','created','updated',
			'coordinates' => 'CONCAT(lat,",",lon)',
			'place' => 'CONCAT(address," ",town_city," ",county)',
		'county', 'postcode'
			 ))
		->joinLeft('primaryactivities',$this->_name . '.primary_activity = primaryactivities.id',
		array('activity' => 'term'))
		->where('people.id = ?',(int)$id);
	return	$persons->fetchAll($select);
	}

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
	
	if(array_key_exists('countyID', $data) && !is_null($data['countyID'])){
		$counties = new OsCounties();
		$data['county'] = $counties->fetchRow($counties->select()->where('osID = ?', 
		$data['countyID']))->label;
	}
        foreach($data as $k => $v) {

            if ( $v == "") {
            $data[$k] = NULL;
            }
        }
	$secuid = new Pas_Generator_SecuID();
	$data['secuid'] = $secuid->secuid();
	return self::insert($data);
	}
	

	/** Function for updating findspots with processing of geodata
	 * @access public
	 * @param array $data
	 * @param array $where
	 */
	public function updateAndProcess($data){
	if(is_array($data)){
	foreach($data as $k => $v) {
	if ( $v == "") {
	$data[$k] = NULL;
	}
	}
	}
	if(array_key_exists('csrf', $data)){
	unset($data['csrf']);
	}

	if(array_key_exists('countyID', $data) && !is_null($data['countyID'])){
		$counties = new OsCounties();
		$data['county'] = $counties->fetchRow($counties->select()->where('osID = ?', $data['countyID']))->label;
	}
	return $data;
	}
	
	
	
	public function checkEmailOwner($findID){
//	if (!$data = $this->_cache->load('valuers')) {
	$persons = $this->getAdapter();
	$select = $persons->select()
		->from($this->_name,array('name' => 'fullname', 'email'))
		->joinLeft('finds', 'finds.finderID = people.secuid',array())
		->where('finds.id = ?', $findID);
    $data = $persons->fetchAll($select);
//	$this->_cache->save($data, 'valuers');
//	}
    return $data;	
	}
}
