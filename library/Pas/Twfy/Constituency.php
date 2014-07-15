<?php
/** Retrieve details for a constituency
 * 
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @since 2/2/2012
 * @version 1
 * @category Pas
 * @package Pas_twfy
 * @subpackage Constituency
 * @license GNU
 * @uses Pas_Twfy
 * @see http://www.theyworkforyou.com/api/docs/getConstituency
 */
class Pas_Twfy_Constituency extends Pas_Twfy {

    /** Method to call
     *
     */
    const METHOD = 'getConstituency';

    /** Get the constituency data
     * @access public
     * @param string $name
     * @param string $postcode
     * @return type
     */
    public function get($name = null, $postcode = NULL){
     $params = array(
         'key' => $this->_apikey,
         'name' => $name,
         'postcode' => $postcode
     );
     return parent::get(self::METHOD, $params);
    }

}

