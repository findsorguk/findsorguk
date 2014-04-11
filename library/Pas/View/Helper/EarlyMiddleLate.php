<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/** A view helper for displayin the qualifier for Early|Middle|Late period data
 *
 * @category Pas
 * @package Pas_View
 * @subpackage Helper
 * @since 30.1.12
 * @author Daniel Pett
 * @version 1
 * @copyright Daniel Pett
 * @license GNU
 */
class Pas_View_Helper_EarlyMiddleLate extends Zend_View_Helper_Abstract {

    /** Switch for displaying correct period qualifier
     * @access public
     * @param string $qualifier
     * @return string|boolean
     */
    public function earlyMiddleLate($qualifier) {
        if(is_numeric($qualifier)){
            switch ($qualifier){
                case 1:
                    $string = 'Early';
                    break;
                case 2:
                    $string = 'Middle';
                    break;
                case 3:
                    $string = 'Late';
                    break;
            }
            return $string;
        } else {
            return false;
        }
    }
}

