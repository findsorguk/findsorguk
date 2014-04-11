<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/** Retrieve extended details for a single MP
 *
 * @since 2/2/2012
 * @version 1
 * @category Pas
 * @package Pas_twfy
 * @subpackage MpInfo
 * @author Daniel Pett
 * @copyright Daniel Pett
 * @license GNU
 * @uses Pas_Twfy
 */
class Pas_Twfy_MpInfo extends Pas_Twfy {

    /** Method to call
     *
     */
    const METHOD = 'getMPInfo';

    /** Get the extended data
     *
     * @param int $id
     * @param string $postcode
     * @return type
     */
    public function get($id = null, $fields = NULL){
     $params = array(
         'key' => $this->_apikey,
         'id' => $id,
         'fields' => $fields
     );
     return parent::get(self::METHOD, $params);
    }
}