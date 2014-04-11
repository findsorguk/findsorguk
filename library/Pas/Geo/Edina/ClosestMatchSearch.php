<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/** An interface to the Edina ClosestMatchSearch api call
 * @category Pas
 * @package Pas_Geo Edina
 * @subpackage ClosestMatchSearch
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
 * $edina = new Pas_Geo_Edina_ClosestMatchSearch();
 * $edina->setName('Astrope');
 * $edina->setFormat('json'); - you can use georss, kml, xml, jaon
 * $edina->get();
 * You can also get the place name queried back from:
 * $edina->getName();
 * If you want to get the url of the api call
 * $edina->getUrl();
 *
 * Then process the object returned as you want (up to you!)
 */
class Pas_Geo_Edina_ClosestMatchSearch extends Pas_Geo_Edina{

    /** The method to call
     *
     */
    const METHOD = 'closestMatchSearch?';

    /** The name that will be queried
     * @access protected
     * @var string
     */
    protected $_name;

    /** Set the name to query
     * @access protected
     * @param type $name
     * @return type
     * @throws Pas_Geo_Edina_Exception
     */
    public function setName($name){
        if(!is_string($name)){
            throw new Pas_Geo_Edina_Exception('The name must be a string');
        } else {
            return $this->_name = $name;
        }
    }

    /** Get the single name called via api
     * @access public
     * @return type
     */
    public function getName() {
        return $this->_name;
    }

    /** Query the api via parent
     * @access public
     * @return type
     */
    public function get() {
        $params = array(
            'name' => $this->_name
        );
    return parent::get(self::METHOD, $params);
    }
}
