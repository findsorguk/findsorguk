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
class Pas_Controller_Action_Helper_FindspotFormOptions
    extends Zend_Controller_Action_Helper_Abstract {

  	protected $_view;

    public function preDispatch()
    {

	$this->_view = $this->_actionController->view;
    }

    public function __construct() {
    }

    public function direct(){

    return $this->optionsAddClone();
    }


    protected function _getIdentity(){
    $user = new Pas_User_Details();
    $person = $user->getPerson();
    if($person){
    	return $person->id;
    } else {
    	throw new Pas_Exception_BadJuJu('No user credentials found', 500);
    }
    }



    public function optionsAddClone(){
    $findspots = new Findspots();
    $findspot = $findspots->getLastRecord($this->_getIdentity());
    $data = $findspot[0];
    $this->_view->form->populate($data);
    Zend_Controller_Action_HelperBroker::getStaticHelper('FlashMessenger')
    	->addMessage('Your last record data has been cloned');
//    Zend_Debug::dump($data);
    if(array_key_exists('countyID', $data) && !is_null($data['countyID'])) {
    $districts = new OsDistricts();
    $district = $districts->getDistrictsToCountyList($data['countyID']);
    if($district) {
    $this->_view->form->districtID->addMultiOptions(array(NULL => 'Choose district',
    	'Available districts' => $district));
    }
//    Zend_Debug::dump($district);
    if(array_key_exists('districtID', $data) && !is_null($data['districtID'])) {
    $parishes = new OsParishes();
    $parishes = $parishes->getParishesToDistrictList($data['districtID']);
    $this->_view->form->parishID->addMultiOptions(array(NULL => 'Choose parish',
    	'Available parishes' => $parishes));
    }
//    Zend_Debug::dump($parishes);
     if(array_key_exists('countyID' , $data) && !is_null($data['countyID'])) {
    $cnts = new OsCounties();
    $region_list = $cnts->getCountyToRegionList($data['countyID']);
    $this->_view->form->regionID->addMultiOptions(array(NULL => 'Choose region',
    	'Available regions' => $region_list));
    }
    }
//    Zend_Debug::dump($region_list);
     if(array_key_exists('landusevalue', $data)) {
    $landcodes = new Landuses();
    $landusecode_options = $landcodes->getLandusesChildList($data['landusevalue']);
    $this->_view->form->landusecode->addMultiOptions(array(NULL => 'Choose code',
    	'Available landuses' => $landusecode_options));
     }
    if(array_key_exists('landowner', $data)) {
    $finders = new Peoples();
    $finders = $finders->getName($findspot['landowner']);
    foreach($finders as $finder) {
    $form->landownername->setValue($finder['term']);
    }
    }
    }

}


