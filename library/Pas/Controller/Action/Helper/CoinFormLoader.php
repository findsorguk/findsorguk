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
class Pas_Controller_Action_Helper_CoinFormLoader
    extends Zend_Controller_Action_Helper_Abstract {
    
    protected $_view;

    public function preDispatch()
    {
	
	$this->_view = $this->_actionController->view;
    }
    
    public function direct($broadperiod){
     
    $broadperiod = $this->_filter->filter($broadperiod);
    return $this->loadForm($broadperiod);
    }
    
    protected $_filter;
    
    public function __construct() {
        $this->_filter = new Zend_Filter_StringToUpper();            
    }
    
    protected $_periods = array(
        'ROMAN','IRON AGE', 'EARLY MEDIEVAL',
        'POST MEDIEVAL', 'MEDIEVAL', 'BYZANTINE',
        'GREEK AND ROMAN PROVINCIAL'
        );
    
    public function loadForm($broadperiod){
        switch ($broadperiod) {
        case 'ROMAN':
            $form = new RomanCoinForm();
            $form->details->setLegend('Add Roman numismatic data');
            $form->submit->setLabel('Add Roman data');
            $this->_view->headTitle('Add a Roman coin\'s details');
            $this->_view->jQuery()->addJavascriptFile($this->_view->baseUrl()
            . '/js/JQuery/coinslinkedinit.js',$type='text/javascript');
            break;
        case 'IRON AGE':
            $form = new IronAgeCoinFormNew();
            $form->details->setLegend('Add Iron Age numismatic data');
            $form->submit->setLabel('Add Iron Age data');
            $this->_view->headTitle('Add an Iron Age coin\'s details');
            $this->_view->jQuery()->addJavascriptFile($this->_view->baseUrl() 
            . '/js/JQuery/iacoinslinkedinit.js',$type='text/javascript');
            break;
        case 'EARLY MEDIEVAL':
            $form = new EarlyMedievalCoinForm();
            $form->details->setLegend('Add Early Medieval numismatic data');
            $form->submit->setLabel('Add Early Medieval data');
            $this->_view->headTitle('Add an Early Medieval coin\'s details');
            $this->_view->jQuery()->addJavascriptFile($this->_view->baseUrl() 
            . '/js/JQuery/coinslinkedinitearlymededit.js',$type='text/javascript');
            break; 
        case 'MEDIEVAL':
            $form = new MedievalCoinForm();
            $form->details->setLegend('Add Medieval numismatic data');
            $form->submit->setLabel('Add Medieval data');
            $this->_view->headTitle('Add a Medieval coin\'s details');
            $this->_view->jQuery()->addJavascriptFile($this->_view->baseUrl() 
            . '/js/JQuery/coinslinkedinitmededit.js',$type='text/javascript');
            break; 
        case 'POST MEDIEVAL':
            $form = new PostMedievalCoinForm();
            $form->details->setLegend('Add Post Medieval numismatic data');
            $form->submit->setLabel('Add Post Medieval data');
            $this->_view->headTitle('Add a Post Medieval coin\'s details');
            $this->_view->jQuery()->addJavascriptFile($this->_view->baseUrl() 
            . '/js/JQuery/coinslinkedinitpostmededit.js',$type='text/javascript');
            break; 
        case 'BYZANTINE':
            $form = new ByzantineCoinForm();
            $form->details->setLegend('Add Byzantine numismatic data');
            $form->submit->setLabel('Add Byzantine data');
            break; 
        case 'GREEK AND ROMAN PROVINCIAL':
            $form = new GreekAndRomanCoinForm();
            $form->details->setLegend('Add Greek & Roman numismatic data');
            $form->submit->setLabel('Add Greek & Roman data');
            break; 

        default:
            throw new Exception('You cannot have a coin for that period.');
            break;
    }	
    return $form;
    }
    
    public function optionsAddClone($broadperiod, $coinDataFlat){
       
        switch ($broadperiod) {
        case 'IRON AGE':
        if(isset($coinDataFlat['denomination'])) {
        $geographies= new Geography();
        $geography_options = $geographies->getIronAgeGeographyMenu($coinDataFlat['denomination']);
        $form->geographyID->addMultiOptions(array(NULL => 'Choose geographic region', 
            'Available regions' => $geography_options));
        $form->geographyID->addValidator('InArray', false, array(array_keys($geography_options)));
        }
        break;

        case 'ROMAN':
        if(isset($coinDataFlat['ruler'])) {
        $reverses = new Revtypes();
        $reverse_options = $reverses->getRevTypesForm($coinDataFlat['ruler']);
        if($reverse_options)
        {
        $form->revtypeID->addMultiOptions(array(NULL => 'Choose reverse type', 
            'Available reverses' => $reverse_options));
        } else {
        $form->revtypeID->addMultiOptions(array(NULL => 'No options available'));
        }
        } else {
        $form->revtypeID->addMultiOptions(array(NULL => 'No options available'));
        }
        if(isset($coinDataFlat['ruler']) && ($coinDataFlat['ruler'] == 242)){
        $moneyers = new Moneyers();
        $moneyer_options = $moneyers->getRepublicMoneyers();
        $form->moneyer->addMultiOptions(array(NULL => NULL,'Choose reverse type' => $moneyer_options));
        } else {
        $form->moneyer->addMultiOptions(array(NULL => 'No options available'));
        //$form->moneyer->disabled=true;
        }	
        break;

        case 'EARLY MEDIEVAL':
        $types = new MedievalTypes();
        $type_options = $types->getMedievalTypeToRulerMenu($coinDataFlat['ruler']);
        $form->typeID->addMultiOptions(array(NULL => 'Choose Early Medieval type',
                'Available types' => $type_options));
        break;

        case 'MEDIEVAL':
            $types = new MedievalTypes();
            $type_options = $types->getMedievalTypeToRulerMenu($coinDataFlat['ruler']);
            $form->typeID->addMultiOptions(array(NULL => 'Choose Medieval type',
                    'Available types' => $type_options));
        break;

        case 'POST MEDIEVAL':
            $types = new MedievalTypes();
            $type_options = $types->getMedievalTypeToRulerMenu($coinDataFlat['ruler']);
            $form->typeID->addMultiOptions(array(NULL => 'Choose Post Medieval type',
                'Available types' => $type_options));
        break;	
    }
    }
    
    
    
}


