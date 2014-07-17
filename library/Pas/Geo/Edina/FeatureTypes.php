<?php
/** An interface to the Edina FeatureTypes api call
 * The XML returns as badly formed so json use is best here
 * @category Pas
 * @package Pas_Geo_Edina
 * @subpackage FeatureTypes
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @since 3/2/12
 * @version 1
 * @uses Pas_Geo_Edina_Exception
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @see http://unlock.edina.ac.uk/places/queries/
 */
class Pas_Geo_Edina_FeatureTypes extends Pas_Geo_Edina {

    /** The method to call
     *
     */
    const TYPES_EDINA = 'supportedFeatureTypes?';

    /** Get the types list for querying, cached in parent class
     * @access public
     * @return array
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
     * @return array
     */
    protected function _getDataFromEdina(){
        $params = array(); // No params to call in this, leave empty
        return parent::get(self::TYPES_EDINA, $params);
    }
}



