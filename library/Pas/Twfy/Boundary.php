<?php
/** Retrieve a constituency boundary in KML
 *
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @since 2/2/2012
 * @version 1
 * @category Pas
 * @package Pas_twfy
 * @license GNU
 * @uses Pas_Twfy
 * @see http://www.theyworkforyou.com/api/docs/getBoundary
 * 
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
