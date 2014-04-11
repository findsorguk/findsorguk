<?php
/** Model for manipulating contacts data for staff at the PAS
* @category Pas
* @package Pas_Db_Table
* @subpackage Abstract
* @author Daniel Pett dpett @ britishmuseum.org
* @copyright 2010 - DEJ Pett
* @license 		GNU General Public License
* @version 		1
* @since 		22 September 2011
* @license GNU General Public License
*/

class Contacts extends Pas_Db_Table_Abstract {

	protected $_name = 'staff';
	protected $_primary = 'id';

	/** Get person's details
	* @param integer $id
	* @return array
	*/
	public function getPersonDetails($id) {
	if (!$data = $this->_cache->load('currentstaffmember' . $id)) {
	$persons = $this->getAdapter();
	$select = $persons->select()
		->from($this->_name,array(
		'number' => 'id', 'firstname', 'lastname',
		'email_one', 'email_two', 'address_1',
		'address_2', 'identifier', 'town',
		'county', 'postcode', 'country',
		'profile', 'telephone', 'fax',
		'dbaseID', 'longitude', 'latitude',
		'image'))
		->joinLeft(array('locality' => 'staffregions'),'locality.ID = staff.region')
		->joinLeft('instLogos',$this->_name.'.identifier = instID', array('host' => 'image'))
		->joinLeft(array('position' => 'staffroles'),'staff.role = position.ID',
		array('staffroles' => 'role'))
		->where('staff.id = ?',(int)$id)
		->group($this->_primary);
	$data =  $persons->fetchAll($select);
	$this->_cache->save($data, 'currentstaffmember' . $id);
	}
	return $data;
	}

     /** Get person's image
    * @param integer $id
	* @return array
	* @todo add caching and change to fetchrow
	*/
	public function getImage($id) {
	$persons = $this->getAdapter();
	$select = $persons->select()
		->from($this->_name,array('image'))
		->where('staff.id= ?',(int)$id);
	return $persons->fetchAll($select);
	}

	/** Get a list of alumni
	* @return array
	*/
	public function getAlumniList() {
	if (!$data = $this->_cache->load('alumniList')) {
	$persons = $this->getAdapter();
	$select = $persons->select()
		->from('staff',array(
		'id', 'firstname', 'lastname',
		'email_one', 'address_1', 'address_2',
		'town', 'county', 'postcode',
		'telephone', 'fax', 'role'))
		->joinLeft(array('locality' => 'staffregions'),'locality.ID = staff.region',
		array('staffregions' => 'description'))
		->joinLeft(array('position' => 'staffroles'),'staff.role = position.ID',
		array('staffroles' => 'role'))
		->where('alumni = ?',(int)0)
		->order('lastname');
	$data =  $persons->fetchAll($select);
	$this->_cache->save($data, 'alumniList');
	}
    return $data;
    }

	/** Get a list of current staff to display on the map of contacts
	* @return array
	* @todo add caching
	*/
	public function getContactsForMap() {
	$persons = $this->getAdapter();
	$select = $persons->select()
		->from($this->_name,array(
		'id', 'firstname', 'lastname',
		'email_one', 'email_two', 'address_1',
		'address_2', 'identifier', 'town',
		'county', 'postcode', 'country',
		'profile', 'telephone', 'fax',
		'dbaseID', 'longitude','latitude',
		'image','alumni'))
		->joinLeft(array('locality' => 'staffregions'),'locality.ID = staff.region',
		array('area' => 'DESCRIPTION'))
		->joinLeft(array('position' => 'staffroles'),'staff.role = position.ID',
		array( 'role','roleid' => 'id'))
		->where('alumni = ?',(int)'1');
	return $contacts = $persons->fetchAll($select);
	}

	/** Get a list of current staff to display on the map of contacts
	* @param integer $params['page']
	* @return array
	* @todo add caching
	*/
	public function getContacts($params) {

	$persons = $this->getAdapter();
	$select = $persons->select()
		->from($this->_name,array(#
		'id', 'firstname', 'lastname',
		'email_one', 'email_two', 'address_1',
		'address_2', 'identifier', 'town',
		'county', 'postcode', 'country',
		'profile', 'telephone', 'fax',
		'dbaseID', 'longitude', 'latitude',
		'image','alumni'))
		->joinLeft(array('locality' => 'staffregions'),'locality.ID = staff.region',
		array('area' => 'DESCRIPTION'))
		->joinLeft(array('position' => 'staffroles'),'staff.role = position.ID',
		array( 'role','roleid' => 'id'))
		->where('alumni = ?', 1);
	$paginator = Zend_Paginator::factory($select);
	if(isset($params['page']) && ($params['page'] != "")) {
	$paginator->setCurrentPageNumber($params['page']);
	}
	$paginator->setItemCountPerPage(20)
		->setPageRange(10);
        
	return $paginator;
	}

	/** Get a list of old staff to display on the map of contacts
	* @param integer $params['page']
	* @return array
	* @todo add caching
	*/
	public function getAlumni($params) {
	$persons = $this->getAdapter();
	$select = $persons->select()
		->from($this->_name,array(#
		'id', 'firstname', 'lastname',
		'email_one', 'email_two', 'address_1',
		'address_2', 'identifier', 'town',
		'county', 'postcode', 'country',
		'profile', 'telephone', 'fax',
		'dbaseID', 'longitude', 'latitude',
		'image','alumni'))
		->joinLeft(array('locality' => 'staffregions'),'locality.ID = staff.region',
		array('area' => 'DESCRIPTION'))
		->joinLeft(array('position' => 'staffroles'),'staff.role = position.ID',
		array( 'role','roleid' => 'id'))
		->where('alumni = ?', 0);
	$paginator = Zend_Paginator::factory($select);
	if(isset($params['page']) && ($params['page'] != "")) {
	$paginator->setCurrentPageNumber((int)$params['page']);
	}
	$paginator->setItemCountPerPage(20)
		->setPageRange(10);
	return $paginator;
	}

	/** Get a list of current staff for the central unit
	* @return array
	*/
	public function getCentralUnit() {
	if (!$data = $this->_cache->load('centralUnit')) {
	$persons = $this->getAdapter();
	$select = $persons->select()
		->from($this->_name,array(
		'id', 'firstname', 'lastname',
		'email_one', 'address_1', 'address_2',
		'town', 'county', 'postcode',
		'telephone', 'fax', 'role',
		'longitude','latitude','image'))
		->joinLeft(array('position' => 'staffroles'),'staff.role = position.ID',
		array('staffroles' => 'role'))
		->where('staff.role IN (1,2,3,4,24,25) AND alumni =1')
		->order('lastname');
	$data =  $persons->fetchAll($select);
	$this->_cache->save($data, 'centralUnit');
	}
	return $data;
	}

	/** Get a list of current finds liaison officers
	* @return array
	*/
	public function getLiaisonOfficers() {
	if (!$data = $this->_cache->load('liaisonOfficers')) {
	$persons = $this->getAdapter();
	$select = $persons->select()
		->from($this->_name,array(
		'id', 'firstname', 'lastname',
		'email_one', 'address_1', 'address_2',
		'town', 'county', 'postcode',
		'telephone', 'fax', 'longitude',
		'latitude', 'image'))
		->joinLeft(array('locality' => 'staffregions'),'locality.ID = staff.region',
		array('staffregions' => 'description'))
		->joinLeft(array('position' => 'staffroles'),'staff.role = position.ID',
		array('staffroles' => 'role'))
		->where('staff.role IN (7,10) AND alumni =1')
		->order('locality.description');
	$data =  $persons->fetchAll($select);
	$this->_cache->save($data, 'liaisonOfficers');
	}
	return $data;
	}


	/** Get a list of current treasure team
	* @return array
	*/
	public function getTreasures() {
	if (!$data = $this->_cache->load('treasureTeam')) {
	$persons = $this->getAdapter();
	$select = $persons->select()
		->from($this->_name,array(
		'id', 'firstname', 'lastname',
		'email_one', 'address_1', 'address_2',
		'town', 'county', 'postcode',
		'telephone', 'fax', 'role',
		'longitude', 'latitude', 'image'))
		->joinLeft(array('position' => 'staffroles'),'staff.role = position.ID',
		array('staffroles' => 'role'))
		->where('staff.role IN (6,8) AND alumni =1')
		->order('lastname');
	$data =  $persons->fetchAll($select);
	$this->_cache->save($data, 'treasureTeam');
	}
	return $data;
	}


	/** Get a list of current finds adviser team
    * @return array
	*/
	public function getAdvisers(){
	if (!$data = $this->_cache->load('findsAdvisers')) {
	$persons = $this->getAdapter();
	$select = $persons->select()
		->from($this->_name,array(
			'id', 'firstname', 'lastname',
			'email_one', 'address_1', 'address_2',
			'town', 'county', 'postcode',
			'telephone', 'fax', 'role',
			'longitude', 'latitude', 'image'))
		->joinLeft(array('position' => 'staffroles'),'staff.role = position.ID',
		array('staffroles' => 'role'))
		->where('staff.role IN (12,16,17,18,19,20) AND alumni =1')
		->order('lastname');
	$data = $persons->fetchAll($select);
	$this->_cache->save($data, 'findsAdvisers');
	}
    return $data;
	}


	/** Get a list of all current staff
    * @return array
	*/
	public function getCurrent() {
	if (!$data = $this->_cache->load('currentstaff')) {
	$persons = $this->getAdapter();
	$select = $persons->select()
		->from($this->_name,array(
			'id', 'firstname', 'lastname',
			'email_one', 'address_1','address_2',
			'town', 'county', 'postcode',
			'telephone', 'fax', 'role',
			'longitude', 'latitude', 'created',
			'updated', 'profile'))
		->joinLeft(array('locality' => 'staffregions'),'locality.regionID = staff.region',
		array('staffregions' => 'description'))
		->joinLeft(array('position' => 'staffroles'),'staff.role = position.ID',
		array('staffroles' => 'role'))
		->order($this->_name.'.id')
		->where('alumni = ?',(int)1);
		$data =  $persons->fetchAll($select);
	$this->_cache->save($data, 'currentstaff');
	}
    return $data;
	}


	/** Get a list of all current staff
    * @return array
	*/
	public function getFloEmailsForForm() {
	if (!$data = $this->_cache->load('currentstaffpairs')) {
	$persons = $this->getAdapter();
	$select = $persons->select()
		->from($this->_name,array(
			'id' => 'dbaseID', 'name' => 'CONCAT(firstname," ",lastname,": ",county)'))
		->order($this->_name . '.id')
		->where('alumni = ?',(int)1)
		->where('role IN (7,10)');
		$data =  $persons->fetchPairs($select);
	$this->_cache->save($data, 'currentstaffpairs');
	}
    return $data;
	}

	public function getNameEmail($id){
	if (!$data = $this->_cache->load('staffemail' . $id)) {
	$persons = $this->getAdapter();
	$select = $persons->select()
		->from($this->_name,array(
			'email' => 'email_one', 'name' => 'CONCAT(firstname," ",lastname)'))
		->where('alumni = ?',(int)1)
		->where('dbaseID = ?', (int)$id);
	$data =  $persons->fetchAll($select);
	$this->_cache->save($data, 'staffemail' . $id);
	}
    return $data;
	}

	/** Get a dropdown list of attending staff
	* @return array
	*/
	public function getAttending() {
	$persons = $this->getAdapter();
	$select = $persons->select()
		->from($this->_name,array('dbaseID', 'term' => 'CONCAT(firstname," ",lastname)'))
		->order($this->_name.'.firstname');
	return $persons->fetchPairs($select);
	}


	/** Retrieve the owner of a find record
	* @param integer $findID the find record ID number
	* @return array
	*/
	public function getOwner($findID) {
	if (!$accounts = $this->_cache->load('owneroffind'.$findID)) {
	$users = $this->getAdapter();
	$select = $users->select()
	->from($this->_name,array('name' => 'CONCAT(firstname," ", lastname)',
		'email' => 'email_one'))
	->joinLeft('finds','finds.institution = ' . $this->_name . '.identifier',array())
	->where('finds.id = ?', (int)$findID)
	->where($this->_name . '.alumni = ?', 1);
	$accounts = $users->fetchAll($select);
	$this->_cache->save($accounts, 'owneroffind'.$findID);
	}
	return $accounts;
	}
}
