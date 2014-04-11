<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


/** Retrieve a constituency boundary in KML
 *
 * @since 2/2/2012
 * @version 1
 * @category Pas
 * @package Pas_twfy
 * @subpackage Boundary
 * @author Daniel Pett
 * @copyright Daniel Pett
 * @license GNU
 * @uses Pas_Twfy
 */
class Pas_Twfy_Boundary extends Pas_Twfy {

    /** Method to use
     *
     */
     const METHOD = 'getBoundary';

     /** Get the constituency
      *
      * @param string $constituency
      * @return void
      */
     public function get($constituency){
     $params = array(
         'key' => $this->_apikey,
         'name' => $constituency
     );
     return parent::get(self::METHOD, $params);
     }

}
