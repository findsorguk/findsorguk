<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


/** An interface to the Edina NameAndFeatureSearch api call
 * @category Pas
 * @package Pas_Geo_Edina
 * @subpackage NameAndFeatureSearch
 * @license GNU Public
 * @since 3/2/12
 * @version 1
 * @copyright Daniel Pett, The British Museum
 * @author Daniel Pett
 * @uses Pas_Geo_Edina_Exception
 * @see http://unlock.edina.ac.uk/places/queries/
 * Usage:
 *
 * $edina = new Pas_Geo_Edina_NameAndFeatureSearch();
 * $edina->setFeatureType('Cities');
 * $edina->setNames(array('cambridge'));
 * $edina->setFormat('json'); - you can use georss, kml, xml, jaon
 * $edina->setGazetteer('geonames'); - you can use unlock, os, geonames
 * $edina->get();
 * You can also get the names and features queried back from:
 * $edina->getFeatureTypes();
 * $edina->getNames();
 * If you want to get the url of the api call
 * $edina->getUrl();
 *
 * Then process the object returned as you want (up to you!)
 */
class Pas_Geo_Edina_NameAndFeatureSearch extends Pas_Geo_Edina {

    /** The method to call
     *
     */
    const METHOD = 'nameAndFeatureSearch?';

    /** The name(s) you want to query
     * @access protected
     * @var string
     */
    protected $_names;

    /** The type of feature to query
     * @access protected
     * @var string
     */
    protected $_types;

    /** The required keys to unlock the unlock api call
     * @access protected
     * @var array
     */
    protected $_required = array('name','featureType');

    /** Get the data from the api using parent
     * @access public
     * @return type
     */
    public function get() {
    $params = array(
            'name' => $this->_names,
            'featureType' => $this->_types
     );


    return parent::get(self::METHOD, $params);
    }

    /** Check that the required keys are there
     * @access protected
     * @todo move to a checking class of its own?
     * @param array $array
     * @return array $array
     * @throws Pas_Geo_Edina_Exception
     */
    protected function _requiredKeys($array){
        foreach($array as $k => $v){

            if(!array_key_exists($k, $this->_requiredKeys)){
                throw new Pas_Geo_Edina_Exception('You are missing a required term');
            }
        }
    }

    /** Set the names to query
     * @access public
     * @param array $names
     * @throws Pas_Geo_Edina_Exception
     */
    public function setNames(array $names){
    if(is_array($names)){
        $this->_names = implode(',',$names);
    }    else {
        throw new Pas_Geo_Edina_Exception('The search names must be an array');
    }
    }

    public function setFeatureType($type){
        $featureTypes = new Pas_Geo_Edina_FeatureTypes();
        $types = $featureTypes->getTypesList();

        if(!in_array($type, $types)){
            throw new Pas_Geo_Edina_Exception('That type is not supported');
        } else {
        return $this->_types = $type;
        }
    }

    public function getNames() {
        return $this->_names;
    }

    public function getTypes() {
        return $this->_types;
    }


}