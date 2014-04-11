<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/** A wrapper for interfacing with the MapIt api, specifically the areas call.
 * This extends the Mapit base class.
 *
 * @category Pas
 * @package Pas_Geo_Mapit
 * @subpackage Areas
 * @version 1
 * @since 6/2/12
 * @copyright Daniel Pett, British Museum
 * @license GNU public
 * @see http://mapit.mysociety.org/
 * @author Daniel Pett
 * @uses Zend_Validate_Alpha
 * @uses Zend_Validate_Digits
 * @uses Zend_Valdiated_AlphaNum
 * @uses Pas_Geo_Mapit_Exception
 *
 * USAGE
 *
 * $m = new Pas_Geo_Mapit_Areas();
 * Now select one of the following
 * $m->setIds(array(2637,2378));
 * OR
 * $m->setName('Camb');
 * OR
 * $m->setTypes(array('CTY','CED'));
 * To execute call
 * $m->get();
 * To get the url called use the following after the get() method call has been
 * issued.
 * $m->getUrl();
 * If you want the expanded names for the type you queried, then call
 * $m->getQualifiedTypes();
 * After the get() method call.
 * If you would like a list of the available types call:
 * $m->getAllowedTypes();
 *
 */
class Pas_Geo_Mapit_Areas extends Pas_Geo_Mapit {

    /** The api method used
    *
    * @var string
    */
    const APIMETHOD = 'areas';

    /** The types to query
     *
     * @var array
     */
    protected $_types;

    /** The ids of the entities to call
     *
     * @var type
     */
    protected $_ids;

    /** Allowed entity types
     *
     * @var array
     */
    protected $_allowedTypes = array(
        'CTY' =>  'County council',
        'CED' =>  'Ccounty ward',
        'COI' =>  'Isles of Scilly',
        'COP' =>  '(Isles of Scilly parish',
        'CPC' =>  'Civil Parish',
        'DIS' =>  'district council',
        'DIW' =>  'district ward',
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

    /** The partial name string to search on
     *
     * @var string
     */
    protected $_name = null;


    /** Set the types of entity to retrieve data upon
     * @access public
     * @param type $types
     * @throws Pas_Geo_Mapit_Exception
     */
    public function setTypes($types) {
    if(is_array($types)){
        foreach($types as $type){
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
        }
        $this->_types = implode(',',$types);
    } else {
        throw new Pas_Geo_Mapit_Exception('Areas must be an array');
    }
    }

    /** Get the types list back
     * @access public
     * @return string
     */
    public function getTypes() {
        return $this->_types;
    }


    /** Get the data from the api
     * @access public
     * @return object
     */
    public function get() {
    $params = array(
        $this->_ids,
        $this->_types,
        $this->_name,
    );

    if(sizeof(array_filter($params)) > 1){
        throw new Pas_Geo_Mapit_Exception('You have too many method calls');
    }
    return parent::get(self::APIMETHOD, $params);
    }

    /** Set the name to query
     * @access Public
     * @param string $name
     * @return string
     * @throws Pas_Geo_Mapit_Exception
     */
    public function setName($name){
    if(is_string($name)){
    $validator = new Zend_Validate_Alnum($allowWhiteSpace = true);
    if(!$validator->isValid($name)){
      throw new Pas_Geo_Mapit_Exception('That string is not valid', 500);
    } else {
    return $this->_name = $name;
    }
    }  else {
      throw new Pas_Geo_Mapit_Exception('The names to search for must be a string',500);
    }
    }

    /** Get the name queried
     * @access public
     * @return string
     */
    public function getName() {
        return $this->_name;
    }


    /** Get the format back you queried
     * @access public
     * @return string
     */
    public function getFormat(){
        return $this->_format;
    }

    /** Set the ids to query
     * @access public
     * @param string $ids
     * @throws Pas_Geo_Mapit_Exception
     */
    public function setIds($ids){
    if(is_array($ids)){
        $validator = new Zend_Validate_Digits();
        foreach($ids as $id){
            if(!$validator->isValid($id)){
                throw new Pas_Geo_Mapit_Exception('The id supplied must be a number');
            }
        }
        $this->_ids = implode(',',$ids);
    } else {
        throw new Pas_Geo_Mapit_Exception('The ids must be an array');
    }
    }

    /** Get the ids queried
     * @access public
     * @return string
     */
    public function getIds(){
        return $this->_ids;
    }


    /** Get the types you queried as expanded names
     * @access public
     * @return type
     */
    public function getQualifiedTypes(){
        if(isset($this->_types)){
            $types = explode(',', $this->_types);

            $fullnames = array();
            foreach($types as $type){

                if(in_array($type, array_flip($this->_allowedTypes)))   {
                    foreach($this->_allowedTypes as $k => $v){
                        if($type === $k){
                        $fullnames[] = $v;
                        }
                    }
                }
            }

        return $fullnames;

        }
    }

    /** Get an array of the types
     * @access public
     * @return type
     */
    public function getAllowedTypes(){
        return $this->_allowedTypes;
    }
}
