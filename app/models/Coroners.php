<?php

/**
 * Model for displaying coroner details
 *
 * An example of use:
 * <code>
 * <?php
 * $model = new Coroners();
 * $data = $model->getCoronerDetails($id);
 * ?>
 * </code>
 *
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category Pas
 * @package Db_Table
 * @subpackage Abstract
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @since 22 September 2011
 * @example /app/modules/admin/controllers/CoronersController.php
 */
class Coroners extends Pas_Db_Table_Abstract
{

    /** The geoplanet service
     * @access protected
     * @var \Pas_Service_Geo_Geoplanet
     */
    protected $_geoPlanet;

    /** The geocoder
     * @access protected
     * @var \Pas_Service_Geo_Coder
     */
    protected $_geoCoder;

    /** The table name
     * @access public
     * @var string
     */
    protected $_name = 'coroners';

    /** The primary key
     * @access public
     * @var integer
     */
    protected $_primary = 'id';

    /** Get the geoplanet service
     * @access public
     * @return \Pas_Service_Geo_Geoplanet
     */
    public function getGeoPlanet()
    {
        $this->_geoPlanet = new Pas_Service_Geo_GeoPlanet($this->_config->webservice->ydnkeys->appid);
        return $this->_geoPlanet;
    }

    /** Get the geocoder class
     * @access public
     * @return \Pas_Service_Geo_Coder
     */
    public function getGeoCoder()
    {
        $this->_geoCoder = new Pas_Service_Geo_Coder();
        return $this->_geoCoder;
    }


    /** Retrieve all coroners on the system
     * @access public
     * @param array $params
     * @return object pagination for view
     */
    public function getAll(array $params)
    {
        $coroners = $this->getAdapter();
        $select = $coroners->select()->from($this->_name)->order('region_name ASC');;
        $paginator = Zend_Paginator::factory($select);
        $paginator->setItemCountPerPage(20)->setPageRange(10);
        if (isset($params['page']) && ($params['page'] != "")) {
            $paginator->setCurrentPageNumber($params['page']);
        }
        return $paginator;
    }

    /** Retrieve individual coroner details
     * @param integer $id
     * @return object
     * @access public
     */
    public function getCoronerDetails($id)
    {
        $key = md5('coroner' . $id);
        if (!$data = $this->_cache->load($key)) {
            $coroners = $this->getAdapter();
            $select = $coroners->select()->from($this->_name)->where('id = ?', (int)$id);
            $data = $coroners->fetchAll($select);
            $this->_cache->save($data, $key);
        }
        return $data;
    }

    /** Add a coroner to the system
     * @access public
     * @param array $data
     * @return array
     */
    public function addCoroner(array $data)
    {
        $insertData = $this->_geocodeAddress($data);
        return parent::insert($insertData);
    }

    /** Update a coroner
     * @access public
     * @param array $data
     * @param integer $id
     */
    public function updateCoroner(array $data, $id)
    {
        $updateData = $this->_geocodeAddress($data);
        $where = $this->getAdapter()->quoteInto($this->_primary . '= ?', (int)$id);
        $this->_cache->remove('coroner' . $id);
        return parent::update($updateData, $where);
    }

    /** Geocode the address
     * @access public
     * @param array $data
     * @return array
     * @throws Exception
     */
    protected function _geocodeAddress(array $data)
    {
        $address = $data['address_1'] . ',' . $data['address_2'] . ','
            . $data['town'] . ',' . $data['county'] . ',' . $data['postcode']
            . ',' . $data['country'];
        $coords = $this->getGeoCoder()->getCoordinates($address);
        if ($coords) {
            $data['latitude'] = $coords['lat'];
            $data['longitude'] = $coords['lon'];
            $place = $this->getGeoPlanet()->reverseGeoCode(
                $coords['lat'], $coords['lon']
            );
            $data['woeid'] = $place['woeid'];
        } else {
            $data['latitude'] = null;
            $data['longitude'] = null;
            $data['woeid'] = null;
        }
        return $data;
    }
}