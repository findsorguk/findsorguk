<?php

/** A model for manipulating findspots
 *
 * An example of use:
 *
 * <code>
 * <?php
 * $findspotsdata = new Findspots();
 * $this->view->findspots = $findspotsdata->getFindSpotData($id);
 * ?>
 * </code>
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category Pas
 * @package Db_Table
 * @subpackage Abstract
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @since 22 September 2011
 * @todo add caching
 * @example /app/modules/database/controllers/ArtefactsController.php
 */
class Findspots extends Pas_Db_Table_Abstract
{

    /** The table name
     * @access protected
     * @var string $_name
     */
    protected $_name = 'findspots';

    /** The primary key
     * @access protected
     * @var integer
     */
    protected $_primary = 'id';

    /** The higher level groups
     * @access protected
     * @var array
     */
    protected $_higherlevel = array('admin', 'flos', 'fa', 'treasure');

    /** Restricted groups
     * @access protected
     * @var array
     */
    protected $_restricted = array(null, 'public', 'member', 'research', 'hero');

    /** Group for testing an edit
     * @access protected
     * @var array
     */
    protected $_edittest = array('flos', 'member');


    /** The Yahoo! appid variable for placemaker
     * @access protected
     * @var string $_appid The Application ID;
     */
    protected $_appid;

    /** The google maps api key
     * @access protected
     * @var string
     */
    protected $_gmaps;

    /** Initialise class
     * @access public
     */
    public function init()
    {
        $this->_appid = $this->_config->webservice->ydnkeys->placemakerkey;
        $this->_gmaps = $this->_config->webservice->google->apikey;
    }

    /** Retrieval of findspot row for editing
     * @access public
     * @param integer $id
     * @return array
     */
    public function getEditData($id)
    {
        $findspotdata = $this->getAdapter();
        $select = $findspotdata->select()
            ->from($this->_name)
            ->where('findspots.id = ?', (int)$id)
            ->group($this->_primary)
            ->limit('1');
        return $findspotdata->fetchAll($select);
    }

    /** Get a find number
     * @access public
     * @param integer $id
     * @return integer
     */
    public function getFindNumber($id, $table = 'finds')
    {
        if ($table == 'artefacts') {
            $table = 'finds';
        }
        $findspotdata = $this->getAdapter();
        $select = $findspotdata->select()
            ->from($this->_name, array())
            ->joinLeft(array('recordtable' => $table), 'recordtable.secuid = findspots.findID', array('id'))
            ->where('findspots.id = ?', (int)$id);
        $data = $findspotdata->fetchRow($select);
        return $data['id'];
    }

    /** Retrieval of findspot row for display (not all columns)
     * @param integer $id
     * @param string $table
     * @return array $data
     */
    public function getFindSpotData($id, $table = 'finds')
    {
        $findspotdata = $this->getAdapter();
        $select = $findspotdata->select()
            ->from($this->_name, array(
                'county',
                'district',
                'parish',
                'easting',
                'northing',
                'gridref',
                'lat' => 'declat',
                'lon' => 'declong',
                'fourFigure',
                'knownas',
                'smrref',
                'map25k',
                'map10k',
                'landusecode',
                'landusevalue',
                'id',
                'old_findspotid',
                'createdBy',
                'description',
                'comments',
                'address',
                'woeid',
                'elevation',
                'postcode',
                'landowner',
                'fourFigureLat',
                'fourFigureLon',
                'gridlen',
                'woeid',
                'geonamesID',
                'districtID',
                'countyID',
                'regionID',
                'parishID',
                'findSpotID' => 'id'
            ))
            ->joinLeft(
                array('recordtable' => $table),
                'recordtable.secuid = findspots.findID',
                array('discmethod')
            )
            ->joinLeft(
                array('land1' => 'landuses'),
                'land1.id = findspots.landusecode',
                array('landuse' => 'term')
            )
            ->joinLeft(
                array('land2' => 'landuses'),
                'land2.id = findspots.landusevalue',
                array('landvalue' => 'term')
            )
            ->joinLeft(
                'maporigins',
                'maporigins.id = findspots.gridrefsrc',
                array('source' => 'term')
            )
            ->joinLeft(
                'osRegions',
                'findspots.regionID = osRegions.osID',
                array('region' => 'label', 'regionType' => 'type')
            )
            ->joinLeft(
                'osCounties',
                'findspots.countyID = osCounties.osID',
                array('countyType' => 'type')
            )
            ->joinLeft(
                'osDistricts',
                'findspots.districtID = osDistricts.osID',
                array('districtType' => 'type')
            )
            ->joinLeft(
                'osParishes',
                'findspots.parishID = osParishes.osID',
                array(
                    'parishType' => 'type',
                    'centreLat' => 'lat',
                    'centreLon' => 'lon'
                )
            )
            ->joinLeft(
                'people',
                $this->_name . '.landowner = people.secuid',
                array('landownername' => 'fullname')
            )
            ->joinLeft(
                'discmethods',
                'recordtable.discmethod = discmethods.id',
                array('discmethod' => 'method', 'discoveryMethod' => 'recordtable.discmethod')
            )
            ->where('recordtable.id = ?', (int)$id)
            ->group('recordtable.id')
            ->limit('1');
        return $findspotdata->fetchAll($select);
    }


    /** Retrieval of findspots data for finds and findspots
     * @access public
     * @param integer $id
     * @param string $secuid
     * @return array
     */
    public function getFindtoFindspotsAdmin($id, $secuid)
    {
        $finds = $this->getAdapter();
        $select = $finds->select()
            ->from($this->_name)
            ->joinLeft('finds', 'finds.secuid = findspots.findID', array('id'))
            ->where('finds.id = ?', (int)$id)
            ->where('finds.secuid = ?', (string)$secuid);
        return $finds->fetchAll($select);
    }

    /** Retrieval of findspots data row for deletion
     * @access public
     * @param integer $id
     * @return array
     */
    public function getFindtoFindspotDelete($id, $table = 'finds')
    {
        if ($table == 'artefacts') {
            $useTable = 'finds';
        } else {
            $useTable = $table;
        }
        $finds = $this->getAdapter();
        $select = $finds->select()
            ->from($this->_name)
            ->joinLeft(
                array('recordtable' => $useTable),
                'recordtable.secuid = findspots.findID',
                array('recordID' => 'id')
            )
            ->where('findspots.id = ?', (int)$id);
        $rows = $finds->fetchAll($select);
        $rows[0]['controller'] = $table;
        return $rows;
    }

    /** Retrieval of findspots data row for cloning record
     * @param integer $userid
     * @return array $data
     * @todo add caching
     */
    public function getLastRecord($userid)
    {
        $fieldList = new CopyFindSpot(); //Get field list from model
        $fields = $fieldList->getConfig();
        $finds = $this->getAdapter();
        $select = $finds->select()
            ->from($this->_name, $fields)
            ->where('findspots.createdBy = ?', (int)$userid)
            ->order('findspots.id DESC')
            ->limit(1);
        return $finds->fetchAll($select);
    }


    /** Retrieval of findspots with missing districts to harangue the crew
     * @access public
     * @return array
     */
    public function getMissingDistrict()
    {
        $findspots = $this->getAdapter();
        $select = $findspots->select()
            ->from($this->_name, array('id', 'county', 'parish'))
            ->where('county IS NOT NULL')
            ->where('parish IS NOT NULL')
            ->where('district IS NULL')
            ->limit(5000);
        return $findspots->fetchAll($select);
    }

    /** Function for adding and processing the findspot data
     * @access public
     * @param array $data
     * @return array
     * @throws Exception
     */
    public function addAndProcess(array $data)
    {
        if (is_array($data)) {
            foreach ($data as $k => $v) {
                if ($v == "") {
                    $data[$k] = null;
                }
            }

            if (!is_null($data['gridref'])) {
                $data = $this->_processFindspot($data);
            }

            $findid = new Pas_Generator_FindID();
            $data['old_findspotid'] = $findid->generate();
            $secuid = new Pas_Generator_SecuID();
            $data['secuid'] = $secuid->secuid();
            //Get the label for the parish
            if (array_key_exists('parishID', $data) && !is_null($data['parishID'])) {
                $parishes = new OsParishes();
                $data['parish'] = $parishes->fetchRow(
                    $parishes->select()->where('osID = ?', $data['parishID'])
                )->label;
            }
            //Get the label for the county
            if (array_key_exists('countyID', $data) && !is_null($data['countyID'])) {
                $counties = new OsCounties();
                $data['county'] = $counties->fetchRow(
                    $counties->select()->where('osID = ?', $data['countyID'])
                )->label;
            }
            //Get the label for the district
            if (array_key_exists('districtID', $data) && !is_null($data['districtID'])) {
                $district = new OsDistricts();
                $data['district'] = $district->fetchRow(
                    $district->select()->where('osID = ?', $data['districtID'])
                )->label;
            }
            if (array_key_exists('landownername', $data)) {
                unset($data['landownername']);
            }

            if (array_key_exists('csrf', $data)) {
                unset($data['csrf']);
            }

            if (empty($data['created'])) {
                $data['created'] = $this->timeCreation();
            }

            if (empty($data['createdBy'])) {
                $data['createdBy'] = $this->getUserNumber();
            }

            return parent::insert($data);
        } else {
            throw new Exception('The data submitted is not an array', 500);
        }
    }

    /** Function for processing findspot
     * @access public
     * @param array $data
     * @return array The final data array
     * @throw Zend_Exception
     */
    protected function _processFindspot(array $data)
    {
        if (is_array($data)) {
            $conversion = new Pas_Geo_Gridcalc($data['gridref']);
            $results = $conversion->convert();

            $fourFigure = new Pas_Geo_Gridcalc($results['fourFigureGridRef']);
            $fourFigureData = $fourFigure->convert();

            //$place = new Pas_Service_Geo_GeoPlanet($this->_appid);

            $geoHash = new Pas_Geo_Hash();
            $hash = $geoHash->encode(
                $results['decimalLatLon']['decimalLatitude'],

                $results['decimalLatLon']['decimalLongitude']
            );
            $data['declong'] = $results['decimalLatLon']['decimalLongitude'];
            $data['declat'] = $results['decimalLatLon']['decimalLatitude'];
            $data['easting'] = $results['easting'];
            $data['northing'] = $results['northing'];
            $data['map10k'] = $results['10kmap'];
            $data['map25k'] = $results['25kmap'];
            $data['fourFigure'] = $results['fourFigureGridRef'];
            $data['accuracy'] = $results['accuracy']['precision'];
            $data['gridlen'] = $results['gridrefLength'];
            $data['geohash'] = $hash;
            $data['fourFigureLat'] = $fourFigureData['decimalLatLon']['decimalLatitude'];
            $data['fourFigureLon'] = $fourFigureData['decimalLatLon']['decimalLongitude'];

            //$yahoo = $place->reverseGeoCode($results['decimalLatLon']['decimalLatitude'],
            //    $results['decimalLatLon']['decimalLongitude']);
            //$data['woeid'] = $yahoo['woeid'];

            $elevate = new Pas_Service_Geo_Elevation($this->_gmaps);
            $data['elevation'] = $elevate->getElevation($data['declong'], $data['declat']);

            $words = new Pas_Service_What3words();
            $words->setApiKey($this->_config->webservice->what3words->apikey);
            $threewords = $words->positionToWords(array($data['fourFigureLat'], $data['fourFigureLon']));
            $data['what3words'] = $threewords->words;
        } else {
            throw new Zend_Exception('Data is not an array', 500);
        }
        return $data;
    }

    /** Function for updating findspots with processing of geodata
     * @access public
     * @param array $data
     * @param array $where
     */
    public function updateAndProcess($data)
    {
        if (is_array($data)) {
            foreach ($data as $k => $v) {
                if ($v == "") {
                    $data[$k] = null;
                }
            }
            if (!is_null($data['gridref'])) {
                $data = $this->_processFindspot($data);
            }
        }
        if (array_key_exists('csrf', $data)) {
            unset($data['csrf']);
        }
        if (array_key_exists('landownername', $data)) {
            if (empty($data['landownername'])) {
                $data['landowner'] = null;
            }
            unset($data['landownername']);
        }
        if (array_key_exists('parishID', $data) && !is_null($data['parishID'])) {
            $parishes = new OsParishes();
            $data['parish'] = $parishes->fetchRow($parishes->select()->where('osID = ?', $data['parishID']))->label;
        }
        if (array_key_exists('countyID', $data) && !is_null($data['countyID'])) {
            $counties = new OsCounties();
            $data['county'] = $counties->fetchRow($counties->select()->where('osID = ?', $data['countyID']))->label;
        }

        if (array_key_exists('districtID', $data) && !is_null($data['districtID'])) {
            $district = new OsDistricts();
            $data['district'] = $district->fetchRow($district->select()->where('osID = ?', $data['districtID']))->label;
        }
        return $data;
    }

    /** Function for updating findspots with processing of geodata
     * @access public
     * @param array $data
     * @return array
     * @throws Zend_Exception
     */
    public function updateAndProcessGrids(array $data)
    {
        if (is_array($data)) {
            foreach ($data as $k => $v) {
                if ($v == "") {
                    $data[$k] = null;
                }
            }
            if (!is_null($data['gridref'])) {
                $data = $this->_processFindspot($data);
            }
            if (array_key_exists('csrf', $data)) {
                unset($data['csrf']);
            }
            if (array_key_exists('landownername', $data)) {
                unset($data['landownername']);
            }
            return $data;
        } else {
            throw new Zend_Exception('Data is not an array', 500);
        }
    }

    /** Function for finding records with missing grid references
     * @access public
     * @param integer $limit
     * @return array
     */
    public function missingGrids($limit = 1)
    {
        $findspots = $this->getAdapter();
        $select = $findspots->select()
            ->from($this->_name)
            ->joinLeft(
                'finds',
                'finds.secuid = findspots.findID',
                array('recordID' => 'id')
            )
            ->where('gridref IS NOT NULL')
            ->where('gridlen IS NULL')
            ->limit($limit);
        return $findspots->fetchAll($select);
    }

    /** Missing four figure grid references
     * @access public
     * @param integer $limit
     * @return array
     */
    public function missingfour($limit = 1)
    {
        $findspots = $this->getAdapter();
        $select = $findspots->select()
            ->from($this->_name)
            ->joinLeft(
                'finds',
                'finds.secuid = findspots.findID',
                array('recordID' => 'id')
            )
            ->where('fourFigureLat = ?', 0)
            ->where('gridref IS NOT NULL')
            ->limit($limit);
        return $findspots->fetchAll($select);
    }

    /** Function to get records with missing eastings
     * @access public
     * @param integer $limit
     * @return array
     */
    public function missingEastings($limit = 1)
    {
        $findspots = $this->getAdapter();
        $select = $findspots->select()
            ->from($this->_name)
            ->joinLeft('finds', 'finds.secuid = findspots.findID', array('recordID' => 'id'))
            ->where('gridref IS NOT NULL')
            ->where('easting IS NULL')
            ->limit($limit);
        return $findspots->fetchAll($select);
    }

    /** A  function to find records with an incorrect source
     * @access public
     * @param integer $limit
     * @return array
     */
    public function incorrectSource($limit = 1)
    {
        $findspots = $this->getAdapter();
        $select = $findspots->select()
            ->from($this->_name)
            ->joinLeft('finds', 'finds.secuid = findspots.findID', array('recordID' => 'id'))
            ->where('gridrefsrc = ?', 4)
            ->where('created <= ?', '2003-04-01')
            ->limit($limit);
        return $findspots->fetchAll($select);
    }

    /** A function to find records with missing elevation
     * @access public
     * @param integer $limit
     * @return array
     */
    public function missingElevation($limit = 1)
    {
        $findspots = $this->getAdapter();
        $select = $findspots->select()
            ->from($this->_name)
            ->joinLeft('finds', 'finds.secuid = findspots.findID', array('recordID' => 'id'))
            ->where('declong IS NOT NULL')
            ->where('elevation IS NULL')
            ->limit($limit);
        return $findspots->fetchAll($select);
    }

    public function getNewData($institution)
    {
        $findspotdata = $this->getAdapter();
        $select = $findspotdata->select()
            ->from($this->_name)
            ->where('findspots.institution = ?', $institution)
            ->where('gridref IS NOT NULL')
            ->where('fourFigureLat IS NULL')
            ->limit('1000');
        return $findspotdata->fetchAll($select);
    }

    /** A function to findSpot record with findID
     * @access public
     * @param integer $limit
     * @return array
     */
    public function getFindspotByfindID($findID)
    {
        $select = $this->select()->where('findID = ?', (string)$findID);
        return $this->fetchRow($select);
    }
}
