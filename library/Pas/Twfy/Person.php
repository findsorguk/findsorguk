<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


/** Retrieve a person's details from twfy
 *
 * @uses Pas_Twfy_Exception
 * @since 2/2/2012
 * @version 1
 * @category Pas
 * @package Pas_twfy
 * @subpackage Geometry
 * @author Daniel Pett
 * @copyright Daniel Pett
 * @license GNU
 * @uses Pas_Twfy
 * @uses Pas_Twfy_Exception
 */
class Pas_Twfy_Person extends Pas_Twfy{

    /** The correct method to use
     *
     */
    const METHOD = 'getPerson';

    /** Get a person's data
     *
     * @param int $id
     * @return object
     * @throws Pas_Twfy_Exception
     */
    public function get($id){
    if(is_numeric($id) && !is_null($id)){
        $params = array(
        'key'       =>  $this->_apikey,
        'output'    =>  'js',
        'id'        =>  $id
        );
    return parent::get(self::METHOD, $params);
    } else {
        throw new Pas_Twfy_Exception('Person ID problems',500);
    }
    }
}

