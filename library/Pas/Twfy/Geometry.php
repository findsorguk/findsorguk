<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/** Retrieve the geometry for a constituency from twfy
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
 */
class Pas_Twfy_Geometry extends Pas_Twfy {

        /** The method to use
         *
         */
        const METHOD = 'getGeometry';

        /** Get the geometry of a constituency
         * @access public
         * @param string $constituency
         * @return type
         * @throws Pas_Twfy_Exception
         */
	public function get($constituency) {
        if(!is_null($constituency) && is_string($constituency)){
        $params = array(
        'key'       => $this->_apikey,
        'name'      => $constituency,
        'format'    => $this->_format);
        return parent::get(self::METHOD, $params);
        } else {
            throw new Pas_Twfy_Exception('No constituency provided');
        }
        }

}