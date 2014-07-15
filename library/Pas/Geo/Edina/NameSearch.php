<?php
/** An interface to the name search api method from edina unlock
 * 
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category Pas
 * @package Pas_Geo_Edina
 * @subpackage NameSearch
 * @version 1
 * @since 3/2/12
 * @uses Pas_Geo_Edina_Exception
 *
 * Usage:
 *
 * $edina = new Pas_Geo_Edina_NameSearch();
 * $edina->setNames(array('cambridge'));
 * $edina->setFormat('json'); - you can use georss, kml, xml, jaon
 * $edina->setGazetteer('geonames'); - you can use unlock, os, geonames
 * $edina->get();
 * You can get names queried back
 * $edina->getNames();
 * If you want to get the url of the api call
 * $edina->getUrl();
 *
 * Then process the object returned as you want (up to you!)
 *
 */

class Pas_Geo_Edina_NameSearch extends Pas_Geo_Edina{

    /** The method to use
     *
     */
    const METHOD = 'nameSearch?';

    /** the name parameter to send
     *
     * @var string
     */
    protected $_names;

    /** Get the data using parent class
     *
     * @return object
     */
    public function get() {
        $params = array(
            'name' => $this->_names
        );
        return parent::get(self::METHOD, $params);
    }

    /** Set the names to call
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

    /** Get the names back
     * @access public
     * @return array
     */
    public function getNames() {
        return $this->_names;
    }
}