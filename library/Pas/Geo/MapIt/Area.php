<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


/** A wrapper for interfacing with the MapIt api, specifically the area call.
 * This extends the Mapit base class.
 *
 * @category Pas
 * @package Pas_Geo_Mapit
 * @subpackage Postcode
 * @version 1
 * @since 6/2/12
 * @copyright Daniel Pett, British Museum
 * @license GNU public
 * @see http://mapit.mysociety.org/
 * @author Daniel Pett
 * @uses Pas_Validate_ValidPostcode
 * @uses Pas_Geo_Mapit_Exception
 *
 * USAGE
 *
 * $m = new Pas_Geo_Mapit_Area();
 * Lots of different options available here
 *
 * SIMPLE - get data for a specific place id
 *
 * $m->setID(34805);
 * Optional set formats as wkt, geojson, kml, json
 * $m->setFormat('wkt');
 * $m->get();
 *
 * COMPLEX examples
 *
 * $m->setID(34805);
 * $m->setMethod('overlaps');
 * $m->setFilter('WMC'); (Which westminster constituencies overlap)
 * $m->get();
 */
class Pas_Geo_Mapit_Area extends Pas_Geo_Mapit {

    /** Set the api method to use
     *
     */
    const APIMETHOD = 'area';

    /** The types list
     * @access protected
     * @var string
     */
    protected $_types = null;

    /** The area ID to query
     * @access protected
     * @var integer
     */
    protected $_id;

    /** get the geometry
     *
     * @var type
     */
    protected $_geometry = null;

    /** Available methods
     *
     * @var type
     */

    protected $_methods = array(
        'geometry',
        'children',
        'touches',
        'overlaps',
        'covers',
        'covered',
        'coverlaps',
    	'example_postcode'
    );

    /** Allowed entity types
     * @access protected
     * @var array
     */
    protected $_allowedTypes = array(
        'CTY' =>  'County council',
        'CED' =>  'Ccounty ward',
        'COI' =>  'Isles of Scilly',
        'COP' =>  'Isles of Scilly parish',
        'CPC' =>  'Civil Parish',
        'DIS' =>  'District council',
        'DIW' =>  'District ward',
        'EUR' =>  'Euro region',
        'GLA' =>  'London Assembly',
        'LAC' =>  'London Assembly constituency',
        'LBO' =>  'London borough',
        'LBW' =>  'London ward',
        'LGD' =>  'NI council',
        'LGE' =>  'NI electoral area',
        'LGW' =>  'NI ward',
        'MTD' =>  'Metropolitan district',
        'MTW' =>  'Metropolitan ward',
        'NIE' =>  'NI Assembly constituency',
        'OLF' =>  'Lower Layer Super Output Area, Full',
        'OLG' =>  'Lower Layer Super Output Area, Generalised',
        'OMF' =>  'Middle Layer Super Output Area, Full',
        'OMG' =>  'Middle Layer Super Output Area, Generalised',
        'SPC' =>  'Scottish Parliament constituency',
        'SPE' =>  'Scottish Parliament region',
        'UTA' =>  'Unitary authority',
        'UTE' =>  'Unitary authority electoral division',
        'UTW' =>  'Unitary authority ward',
        'WAC' =>  'Welsh Assembly constituency',
        'WAE' =>  'Welsh Assembly region',
        'WMC' =>  'UK Parliamentary constituency'
    );

    /** The method to use
     * @acces protected
     * @var type
     */
    protected $_method;

    /** The formats available
     * @access protected
     * @var type
     */
    protected $_formats = array('json','kml','geojson', 'wkt');

    /** Get parent data
     * @access public
     * @return type
     */
    public function get() {
    return parent::get(self::APIMETHOD, $this->_createParams());
    }

    /** Set the format you want
     * @access public
     * @param type $format
     * @throws Pas_Geo_Mapit_Exception
     */
    public function setFormat($format){
        if(in_array($format, $this->_formats)){
            $this->_format = $format;
        } else {
            throw new Pas_Geo_Mapit_Exception('That format is not allowed');
        }
    }

    /** Get the format you want
     * @access public
     * @return type
     */
    public function getFormat(){
    	return $this->_format;
    }

    /** Set the ID up
     * @acces public
     * @param type $id
     * @return type
     * @throws Pas_Geo_Mapit_Exception
     */
    public function setId($id){
    $validator = new Zend_Validate_Digits();
    if($validator->isValid($id)){
    	return $this->_id = $id;
    } else {
        throw new Pas_Geo_Mapit_Exception('The id must be an integer');
    }
    }

    /** Get the ID queried
     * @access public
     * @return type
     */
    public function getId(){
    	return $this->_id;
    }

    /**
     * @param $_method the $_method to set
     */
    public function setMethod($method) {
        if(!in_array($method, $this->_methods)){
            throw new Pas_Geo_Mapit_Exception('That method does not exist');

        } else {
             return $this->_method = $method;
        }
    }

    /**
     * @return the $_method
     */
    public function getMethod() {
            return $this->_method;
    }

    /** Create the method
     * @access protected
     * @return type
     */
    protected function _createParams(){
       $params = array();
       $params[] =  $this->_id;
       $params[] =  $this->_method;
       return $params;
    }

    /** Set the filter up for method calls
     * @access public
     * @param type $type
     * @return type
     * @throws Pas_Geo_Mapit_Exception
     */
    public function setFilter($type){
      if(strlen($type) <> 3){
            throw new Pas_Geo_Mapit_Exception('The area must be a three letter string');
        }
        $validator = new Zend_Validate_Alpha();
        if(!$validator->isValid($type)){
            throw new Pas_Geo_Mapit_Exception('Invalid characters used', 500);
        }
        if(!in_array($type, array_flip($this->_allowedTypes))){
            throw new Pas_Geo_Mapit_Exception('The area type of ' . $type . ' must be in allowed list');
        }
        return $this->_filter = '?type=' . $type;

    }

}
