<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/** An interface to the Edina UniqueNameSearch api call
 * @category Pas
 * @package Pas_Geo_Edina
 * @subpackage UniqueNameSearch
 * @license GNU Public
 * @since 3/2/12
 * @version 1
 * @copyright Daniel Pett, The British Museum
 * @author Daniel pett
 * @uses Pas_Geo_Edina_Exception
 * @see http://unlock.edina.ac.uk/places/queries/
 * Usage:
 *
 * $edina = new Pas_Geo_Edina_UniqueNameSearch();
 * $edina->setName('Cambridge');
 * $edina->setFormat('json'); - you can use georss, kml, xml, jaon
 * $edina->setGazetteer('geonames'); - you can use unlock, os, geonames
 * $edina->get();

 * If you want to get the url of the api call
 * $edina->getUrl();
 *
 * Then process the object returned as you want (up to you!)
 */
class Pas_Geo_Edina_UniqueNameSearch extends Pas_Geo_Edina{

    /** The method api call
     *
     */
    const METHOD = 'uniqueNameSearch?';

    /** The name of the place to query
     *
     * @var string
     */
    protected $_name;

    /** Call the api using the parent class
     *
     * @return type
     */
    public function get() {
    $params = array(
        'name' => $this->_name
    );
    return parent::get(self::METHOD, $params);
    }

    /** Set the name of the place to query
     *
     * @param string $name
     * @return string
     * @throws Pas_Geo_Edina_Exception
     */
    public function setName($name){
        if(!is_string($name)){
            throw new Pas_Geo_Edina_Exception('The unique name should be a string');
        } else {
            return $this->_name = $name;
        }
    }

    /** Get the name you called the function on
     *
     * @return type
     */
    public function getName(){
        return $this->_name;
    }
}

