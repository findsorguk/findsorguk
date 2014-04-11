<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/** An interface to the Edina FootprintLookup api call
 * @category Pas
 * @package Pas_Geo_Edina
 * @subpackage FootprintLookup
 * @license GNU Public
 * @since 3/2/12
 * @version 1
 * @copyright Daniel Pett, The British Museum
 * @author Daniel Pett
 * @uses Pas_Geo_Edina_Exception
 * @see http://unlock.edina.ac.uk/places/queries/
 *
 * Usage:
 *
 * $edina = new Pas_Geo_Edina_FootprintLookup();
 * $edina->setFootprints(array(5823266,5823268)); - Lincolnshire county
 * $edina->setFormat('json'); - you can use georss, kml, xml, jaon
 * $edina->setGazetteer('os'); - you can use unlock, os, geonames
 * $edina->get();
 * You can also get the footprints queried back from:
 * $edina->getFootprints();
 * If you want to get the url of the api call
 * $edina->getUrl();
 *
 * Then process the object returned as you want (up to you!)
 */
class Pas_Geo_Edina_FootprintLookup extends Pas_Geo_Edina {

    /** The api method to call
     *
     */
    const METHOD = 'footprintLookup?';

    /** The footprints you want to query
     * @access protected
     * @var string
     */
    protected $_footprints;

    /** Set up footprints to query
     * @access protected
     * @param array $footprints
     * @return type
     * @throws Pas_Geo_Edina_Exception
     */
    public function setFootprints(array $footprints){
        if(!is_array($footprints)){
            throw new Pas_Geo_Edina_Exception('The footprint IDs must be an array');
        } else {
            return $this->_footprints = implode(',',$footprints);
        }
    }

    /** Get the list of footprints queried
     * @access public
     * @return type
     */
    public function getFootprints(){
        return $this->_footprints;
    }

    /** Get the data from the api using parent class
     * @access public
     * @return type
     */
    public function get() {
        $params = array(
            'identifier' => $this->_footprints
        );
    return parent::get(self::METHOD, $params);
    }

}