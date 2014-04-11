<?php
 /**
 * ACL integration
 *
 * Places_Controller_Action_Helper_Acl provides ACL support to a 
 * controller.
 *
 * @uses       Zend_Controller_Action_Helper_Abstract
 * @package    Controller
 * @subpackage Controller_Action
 * @copyright  Copyright (c) 2007,2008 Rob Allen
 * @license    http://framework.zend.com/license/new-bsd  New BSD License
 */
class Pas_Controller_Action_Helper_GeoCoder 
extends Zend_Controller_Action_Helper_Abstract {

    protected $_geocoder;

    public function direct(){
    $this->_geocoder = new Pas_Service_Geo_Coder(Zend_Registry::get('config')
            ->webservice->googlemaps->apikey);
    return $this->_geocoder;

    }
}