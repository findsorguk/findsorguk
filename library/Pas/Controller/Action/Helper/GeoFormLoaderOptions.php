<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CoinFormLoader
 *
 * @author Daniel Pett <dpett@britishmuseum.org>
 */
class Pas_Controller_Action_Helper_GeoFormLoaderOptions
    extends Zend_Controller_Action_Helper_Abstract {

    protected $_view;

    protected $_places;

    public function preDispatch()
    {

	$this->_view = $this->_actionController->view;
    }

    public function direct($values){

    return $this->optionsGeoLoader($values);
    }


    public function __construct() {
        $this->_places = new Places();
    }

    public function optionsGeoLoader($values){
    if(array_key_exists('county', $values)){
        $districts = $this->_places->getDistrictList($values['county']);
        $parishes  = $this->_places->getParishCList($values['county']);
        $this->_view->form->district->addValidator('inArray', false, array(array_keys($districts)));
        $this->_view->form->district->addMultiOptions(array(NULL => 'Choose district',
                'Available districts' => $districts));
        $this->_view->form->parish->addValidator('inArray', false, array(array_keys($parishes)));
        $this->_view->form->parish->addMultiOptions(array(NULL => 'Choose parishes',
                'Available parishes' => $parishes));
    }
    }

}


