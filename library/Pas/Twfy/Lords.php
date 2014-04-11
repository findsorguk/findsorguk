<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Lords
 *
 * @author danielpett
 */
class Pas_Twfy_Lords extends Pas_Twfy {

     const METHOD = 'getLords';

     public function get(){
     $params = array(
         'key' => $this->_apikey,
     );
     return parent::get(self::METHOD, $params);
     }
}

