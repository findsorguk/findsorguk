<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/** An interface to the Edina FeatureTypes api call
 * The XML returns as badly formed so json use is best here
 * @category Pas
 * @package Pas_Geo_Edina
 * @subpackage FeatureTypes
 * @license GNU Public
 * @since 3/2/12
 * @version 1
 * @copyright Daniel Pett, The British Museum
 * @author Daniel Pett
 * @uses Pas_Geo_Edina_Exception
 * @see http://unlock.edina.ac.uk/places/queries/
 */
class Pas_Geo_Edina_FeatureTypes extends Pas_Geo_Edina {

    /** The method to call
     *
     */
    const TYPES_EDINA = 'supportedFeatureTypes?';

    /** Get the types list for querying, cached in parent class
     * @access public
     * @return type
     */
    public function getTypesList(){
    $types =  $this->_getDataFromEdina();
    $list = array();
    foreach($types->featureTypes as $type){
        $list[] = $type->name;
    }

    return $list;
    }

    /** Get the data from edina
     * @access protected
     * @return type
     */
    protected function _getDataFromEdina(){
    $params = array(); // No params to call in this, leave empty
    return parent::get(self::TYPES_EDINA, $params);
    }
}



