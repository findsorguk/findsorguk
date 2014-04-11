<?php

/** Model for interacting with rallies database table
* @category 	Pas
* @package 		Pas_Db_Table
* @subpackage 	Abstract
* @author 		Daniel Pett dpett @ britishmuseum.org
* @copyright 	2010 - DEJ Pett
* @license 		GNU General Public License
* @version 		1
* @since 		22 September 2011
*/
class Rallies extends Pas_Db_Table_Abstract {

	protected $_name = 'rallies';
	protected $_primary = 'id';

	/**
     * Retrieves dropdown list array for rallies (cached)
     * @return array
	*/
	public function getRallies() {
	if (!$options = $this->_cache->load('rallydds')) {
	$select = $this->select()
		->from($this->_name, array('id', 'rally_name'))
		->order('rally_name');
	$options = $this->getAdapter()->fetchPairs($select);
	$this->_cache->save($options, 'rallydds');
	}
	return $options;
    }

    /**
     * Retrieves list array for rallies (cached) and paginated
     * @param array $params
     * @return array
	*/
	public function getRallyNames($params)  {
	$rallies = $this->getAdapter();
	$select = $rallies->select()
		->from($this->_name, array(
		'id', 'rally_name', 'date_from',
		'date_to', 'latitude', 'longitude',
		'county', 'district', 'easting',
		'parish', 'map10k', 'map25k',
  		'created', 'updated'))
		->joinLeft('users','users.id = ' . $this->_name.'.createdBy', array('fullname','personid' => 'id'))
		->joinLeft('users','users_2.id = '.$this->_name.'.updatedBy',array('fn' => 'fullname'))
		->order('date_from DESC');
	if(isset($params['year'])){
	$select->where('EXTRACT(YEAR FROM date_to)= ?',$params['year']);
	}
	$paginator = Zend_Paginator::factory($select);
	$paginator->setItemCountPerPage(30)
		->setPageRange(10);
	if(isset($params['page']) && ($params['page'] != "")) {
	$paginator->setCurrentPageNumber((int)$params['page']);
	}
	return $paginator;
    }

    /**
     * Retrieves rally details
     * @param integer $id
     * @return array
	*/
	public function getRally($id) {
	$rallies = $this->getAdapter();
	$select = $rallies->select()
		->from($this->_name, array(
		'id','rally_name','df' => 'DATE_FORMAT(date_from,"%D %M %Y")',
		'dt' => 'DATE_FORMAT(date_to,"%D %M %Y")','comments','parish',
		'county','gridref','district',
		'latitude','longitude','easting',
		'northing','fourFigure','created',
		'updated','map25k','map10k'))
		->joinLeft('users','users.id = '.$this->_name.'.createdBy',array('fullname'))
		->joinLeft('users','users_2.id = '.$this->_name.'.updatedBy',array('fn' => 'fullname'))
		->joinLeft('people',$this->_name.'.organiser = people.secuid',array('organiser' => 'fullname'))
		->joinLeft('finds','finds.rallyID = rallies.id',array('finds' => 'SUM(quantity)'))
		->where('rallies.id = ?',(int)$id)
		->order('date_from DESC')
		->group($this->_primary);
	return $rallies->fetchAll($select);
    }

     /**
     * Retrieves rally names by id
     * @param integer $id
     * @return array
	*/
	public function getFindRallyNames($id) {
	$rallies = $this->getAdapter();
	$select = $rallies->select()
		->from($this->_name, array(
		'id','rally_name','df' => 'DATE_FORMAT(date_from,"%D %M %Y")',
		'dt' => 'DATE_FORMAT(date_to,"%D %M %Y")'))
		->where('finds.id = ?', (int)$id)
		->limit('1');
	return $rallies->fetchAll($select);
    }

     /**
     * Retrieves rally names by id
     * @param integer $id
     * @return array
	*/
	public function getFindToRallyNames($id) {
	$rallies = $this->getAdapter();
	$select = $rallies->select()
		->from($this->_name, array(
		'id','rally_name','df' => 'DATE_FORMAT(date_from,"%D %M %Y")',
		'dt' => 'DATE_FORMAT(date_to,"%D %M %Y")'))
		->joinLeft('finds','rallies.id = finds.rallyID',array())
		->where('finds.id = ?',(int)$id)
		->limit('1');
	return $rallies->fetchAll($select);
    }

     /**
     * Retrieves rally names for mapping xml view
     * @param integer $year
     * @return array
	*/
	public function getMapdata($year = NULL){
	$rallies = $this->getAdapter();
	$select = $rallies->select()
		->from($this->_name, array(
		'id','name' => 'rally_name','df' => 'DATE_FORMAT(date_from,"%D %M %Y")',
		'dt' => 'DATE_FORMAT(date_to,"%D %M %Y")','lat' => 'latitude', 'lng' => 'longitude'))
		->where('latitude > ?',0);
	if(isset($year)){
	$select->where('EXTRACT(YEAR FROM date_to)= ?', (int)$year);
	}
	return $rallies->fetchAll($select);
    }


    /** Function for processing findspot
	 *
	 * @param array $data
	 */
	protected function _processFindspot($data){
	if(is_array($data)) {
	$conversion = new Pas_Geo_Gridcalc($data['gridref']);
	$results = $conversion->convert();
	$data['longitude'] = $results['decimalLatLon']['decimalLongitude'];
	$data['latitude'] = $results['decimalLatLon']['decimalLatitude'];
	$data['easting'] = $results['easting'];
	$data['northing'] = $results['northing'];
	$data['map10k'] = $results['10kmap'];
	$data['map25k'] = $results['25kmap'];
	$data['fourFigure'] = $results['fourFigureGridRef'];
	return $data;
	} else {
	return $data;
	}
	}

        /** Function for adding and processing the findspot data
	 * @access public
	 * @param array $data
	 */
	public function addAndProcess($data){
	if(is_array($data)){
	//Set null values
        foreach($data as $k => $v) {
	if ( $v == "") {
	$data[$k] = NULL;
	}
	}

	if(!is_null($data['gridref'])) {
	$data = $this->_processFindspot($data);
	}

	if(array_key_exists('parishID', $data) &&!is_null($data['parishID'])){
		$parishes = new OsParishes();
		$data['parish'] = $parishes->fetchRow($parishes->select()->where('osID = ?', $data['parishID']))->label;
	}

	if(array_key_exists('countyID', $data) && !is_null($data['countyID'])){
		$counties = new OsCounties();
		$data['county'] = $counties->fetchRow($counties->select()->where('osID = ?', $data['countyID']))->label;
	}

	if(array_key_exists('districtID', $data) && !is_null($data['districtID'])){
		$district = new OsDistricts();

		$data['district'] = $district->fetchRow($district->select()->where('osID = ?', $data['districtID']))->label;
	}


        if(array_key_exists('organisername', $data)){
		unset($data['organisername']);
	}

        if(array_key_exists('csrf', $data)){
 		unset($data['csrf']);
  	}
        if(array_key_exists('submit', $data)){
            unset($data['submit']);
        }

        if(empty($data['created'])){
		$data['created'] = $this->timeCreation();
	}

        if(empty($data['createdBy'])){
		$data['createdBy'] = $this->userNumber();
        }
        return parent::insert($data);

	} else {
		throw new Exception('The data submitted is not an array',500);
	}
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
	if(!is_null($data['gridref'])) {
	$data = $this->_processFindspot($data);
	}
	}

	if(array_key_exists('csrf', $data)){
            unset($data['csrf']);
	}
        if(array_key_exists('submit', $data)){
            unset($data['submit']);
        }
	if(array_key_exists('organisername', $data)){
            unset($data['organisername']);
	}
	if(array_key_exists('parishID', $data) && !is_null($data['parishID'])){
		$parishes = new OsParishes();
		$data['parish'] = $parishes->fetchRow($parishes->select()->where('osID = ?', $data['parishID']))->label;
	}

	if(array_key_exists('countyID', $data) && !is_null($data['countyID'])){
		$counties = new OsCounties();
		$data['county'] = $counties->fetchRow($counties->select()->where('osID = ?', $data['countyID']))->label;
	}

	if(array_key_exists('districtID', $data) && !is_null($data['districtID'])){
		$district = new OsDistricts();

		$data['district'] = $district->fetchRow($district->select()->where('osID = ?', $data['districtID']))->label;
	}

	return $data;
	}

}


