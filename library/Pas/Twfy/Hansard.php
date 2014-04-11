<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/** Retrieve a list of parliamentary mentions for PAS
 *
 * @since 2/2/2012
 * @version 1
 * @category Pas
 * @package Pas_twfy
 * @subpackage Hansard
 * @author Daniel Pett
 * @copyright Daniel Pett
 * @license GNU
 * @uses Pas_Twfy
 */
class Pas_Twfy_Hansard extends Pas_Twfy {

    /** Basic method call
     *
     */
     const METHOD = 'getHansard';

     /** Retrieve data
      *
      * @param string $search
      * @param int $page
      * @param int $limit
      * @param string $order
      * @return array
      */
     public function get($search, $page, $limit, $order = 'd'){
         $params = array(
            'key' => $this->_apikey,
            'order' => $order,
            'search' => $search,
            'num' => $limit,
            'page' => $page
        );
     return parent::get(self::METHOD, $params);
     }
}

