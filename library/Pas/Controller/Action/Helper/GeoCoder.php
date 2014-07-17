<?php
/** An action helper for using the geocoder from Google
 * 
 * An example of code use:
 * 
 * <code>
 * <?php
 * 
 * ?>
 * </code>
 * 
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category Pas
 * @package Controller_Action
 * @subpackage Helper
 * @version 1
 * @uses Zend_Controller_Action_Helper_Abstract 
 * @uses Pas_Service_Geo_Coder
 * @uses Zend_Registry
 * @example 
 * 
*/
class Pas_Controller_Action_Helper_GeoCoder extends Zend_Controller_Action_Helper_Abstract {

    protected $_geocoder;

    public function direct(){
        $this->_geocoder = new Pas_Service_Geo_Coder(Zend_Registry::get('config')
                ->webservice->googlemaps->apikey);
        return $this->_geocoder;
    }
}