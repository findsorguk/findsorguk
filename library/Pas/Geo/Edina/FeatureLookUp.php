<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/** An interface to the Edina FeatureLookUp api call
 * @category Pas
 * @package Pas_Geo_Edina
 * @subpackage FeatureLookUp
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
 * $edina = new Pas_Geo_Edina_FeatureLookUp();
 * $edina->setId('9657'); - Lincolnshire county
 * $edina->setFormat('json'); - you can use georss, kml, xml, jaon
 * $edina->setGazetteer('os'); - you can use unlock, os, geonames
 * $edina->get();
 * You can also get the id queried back from:
 * $edina->getId();
 * If you want to get the url of the api call
 * $edina->getUrl();
 *
 * Then process the object returned as you want (up to you!)
 */
class Pas_Geo_Edina_FeatureLookUp extends Pas_Geo_Edina {

    /** Api method to call
     *
     */
    const METHOD = 'featureLookup?';

    /** The id to query
     *
     * @var string
     */
    protected $_id;

    /** Get the data via parent class
     * @access public
     * @return type
     */
    public function get() {

        $params = array('id' => $this->_id);

        return parent::get(self::METHOD, $params);
    }

    /** Set up the id to query
     * @access public
     * @param type $id
     * @return type
     * @throws Pas_Geo_Edina_Exception
     */
    public function setId($id){
        if(!is_int){
            throw new Pas_Geo_Edina_Exception('Entity ID must be an integer');
        } else {
            return $this->_id = $id;
        }
    }

    /** Get the ID called
     * @access public
     * @return type
     */
    public function getId(){
        return $this->_id;
    }

}
