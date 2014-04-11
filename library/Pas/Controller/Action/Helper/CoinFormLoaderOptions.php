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
class Pas_Controller_Action_Helper_CoinFormLoaderOptions
    extends Zend_Controller_Action_Helper_Abstract {

    protected $_view;

    public function preDispatch()
    {

	$this->_view = $this->_actionController->view;
    }

    public function direct($broadperiod, $coinDataFlat){

    $broadperiod = $this->_filter->filter($broadperiod);
    return $this->optionsAddClone($broadperiod, $coinDataFlat);
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

    public function optionsAddClone($broadperiod, $coinDataFlat){
//   	Zend_Controller_Action_HelperBroker::getStaticHelper('FlashMessenger')
//   		->addMessage('Your last record data has been cloned');
	$coinDataFlat = $coinDataFlat['0'];
    switch ($broadperiod) {
    case 'IRON AGE':
        if(isset($coinDataFlat['denomination'])) {
        $geographies= new Geography();
        $geography_options = $geographies->getIronAgeGeographyMenu($coinDataFlat['denomination']);
        $this->_view->form->geographyID->addMultiOptions(array(NULL =>
            'Choose geographic region', 'Available regions' => $geography_options));
        $this->_view->form->geographyID->addValidator('InArray', false, array(array_keys($geography_options)));
        }
        break;
    case 'ROMAN':
        if(isset($coinDataFlat['ruler_id'])) {
        $reverses = new Revtypes();
        $reverse_options = $reverses->getRevTypesForm($coinDataFlat['ruler_id']);
        if($reverse_options) {
        $this->_view->form->revtypeID->addMultiOptions(array(NULL =>
            'Choose reverse type', 'Available reverses' => $reverse_options));
        $this->_view->form->revtypeID->setRegisterInArrayValidator(false);
        } else {
        $this->_view->form->revtypeID->addMultiOptions(array(NULL =>
            'No options available'));
         $this->_view->form->revtypeID->setRegisterInArrayValidator(false);
        }
        } else {
        $this->_view->form->revtypeID->addMultiOptions(array(NULL =>
            'No options available'));
         $this->_view->form->revtypeID->setRegisterInArrayValidator(false);
        }
        if(isset($coinDataFlat['ruler']) && ($coinDataFlat['ruler_id'] == 242)){
        $moneyers = new Moneyers();
        $moneyer_options = $moneyers->getRepublicMoneyers();
        $this->_view->form->moneyer->addMultiOptions(array(NULL => NULL,
            'Choose moneyer' => $moneyer_options));
        } else {
        $this->_view->form->moneyer->addMultiOptions(array(NULL =>
            'No options available'));
        //$this->_view->form->moneyer->disabled=true;
        }
        break;

        case 'EARLY MEDIEVAL':
        $types = new MedievalTypes();
        $type_options = $types->getMedievalTypeToRulerMenu($coinDataFlat['ruler_id']);
        $this->_view->form->typeID->addMultiOptions(array(NULL =>
            'Choose Early Medieval type',
            'Available types' => $type_options));
        break;
        case 'MEDIEVAL':
            $types = new MedievalTypes();
            $type_options = $types->getMedievalTypeToRulerMenu($coinDataFlat['ruler_id']);
            $this->_view->form->typeID->addMultiOptions(array(NULL => 'Choose Medieval type',
                    'Available types' => $type_options));
        break;
        case 'POST MEDIEVAL':
            $types = new MedievalTypes();
            $type_options = $types->getMedievalTypeToRulerMenu((int)$coinDataFlat['ruler_id']);
            
            $this->_view->form->typeID->addMultiOptions(array(NULL => 'Choose Post Medieval type',
                'Available types' => $type_options));
        break;
    }
    }

}


