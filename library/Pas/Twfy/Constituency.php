<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/** Retrieve details for a constituency
 *
 * @since 2/2/2012
 * @version 1
 * @category Pas
 * @package Pas_twfy
 * @subpackage Constituency
 * @author Daniel Pett
 * @copyright Daniel Pett
 * @license GNU
 * @uses Pas_Twfy
 */
class Pas_Twfy_Constituency extends Pas_Twfy {

    /** Method to call
     *
     */
    const METHOD = 'getConstituency';

    /** Get the constituency data
     *
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

