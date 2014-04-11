<?php
/** Form for searching for early medieval coin data
*
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class EarlyMedNumismaticSearchForm extends Pas_Form {

   

    protected $_higherlevel = array('admin', 'flos', 'fa', 'heros', 'treasure');

	protected $_restricted = array(null,'public', 'member', 'research');

	public function __construct($options = null) {


	$institutions = new Institutions();
	$inst_options = $institutions->getInsts();

	$rallies = new Rallies();
	$rally_options = $rallies->getRallies();

	$hoards = new Hoards();
	$hoard_options = $hoards->getHoards();

	$counties = new OsCounties();
	$county_options = $counties->getCountiesID();

	$rulers = new Rulers();
	$ruler_options = $rulers->getEarlyMedRulers();
	
	$denominations = new Denominations();
	$denomination_options = $denominations->getOptionsEarlyMedieval();

	$mints = new Mints();
	$mint_options = $mints->getEarlyMedievalMints();

	$axis = new Dieaxes();
	$axis_options = $axis->getAxes();

	$types = new MedievalTypes();
	$type_options = $types->getMedievalTypesForm(47);
	
	$cats = new CategoriesCoins();
	$cat_options = $cats->getPeriodEarlyMed();

	$regions = new OsRegions();
	$region_options = $regions->getRegionsID();

	parent::__construct($options);

	$this->setName('earlymedsearch');

	$old_findID = new Zend_Form_Element_Text('old_findID');
	$old_findID->setLabel('Find number: ')
		->setRequired(false)
		->addFilters(array('StripTags','StringTrim'))
		->addErrorMessage('Please enter a valid number!');


	$description = new Zend_Form_Element_Text('description');
	$description->setLabel('Object description contains: ')
		->setRequired(false)
		->addFilters(array('StripTags','StringTrim'))
		->addErrorMessage('Please enter a valid term');

    $workflow = new Zend_Form_Element_Select('workflow');
	$workflow->setLabel('Workflow stage: ')
		->setRequired(false)
		->setAttrib('class', 'span6 selectpicker show-menu-arrow')
		->addFilters(array('StringTrim','StripTags'))
		->addValidator('Int');

	if(in_array($this->_role,$this->_higherlevel)) {
	$workflow->addMultiOptions(array(NULL => 'Available Workflow stages',
            'Choose Worklow stage' => array(
                '1' => 'Quarantine',
                '2' => 'On review',
                '4' => 'Awaiting validation',
                '3' => 'Published')));
	}
	if(in_array($this->_role,$this->_restricted)) {
	$workflow->addMultiOptions(array(NULL => 'Available Workflow stages',
            'Choose Worklow stage' => array(
                '4' => 'Awaiting validation',
                '3' => 'Published')));
	}


	//Rally details
	$rally = new Zend_Form_Element_Checkbox('rally');
	$rally->setLabel('Rally find: ')
		->setRequired(false)
		->addValidator('Int')
		->addFilters(array('StringTrim','StripTags'))
		->setUncheckedValue(NULL);

	$rallyID =  new Zend_Form_Element_Select('rallyID');
	$rallyID->setLabel('Found at this rally: ')
		->setRequired(false)
		->setAttrib('class', 'span6 selectpicker show-menu-arrow')
		->addFilters(array('StringTrim','StripTags'))
		->addMultiOptions(array(NULL => 'Choose a rally','Available rallies' => $rally_options));

	$hoard = new Zend_Form_Element_Checkbox('hoard');
	$hoard->setLabel('Hoard find: ')
		->setRequired(false)
		->addFilters(array('StringTrim','StripTags'))
		->setUncheckedValue(NULL);

	$hoardID =  new Zend_Form_Element_Select('hID');
	$hoardID->setLabel('Part of this hoard: ')
		->setRequired(false)
		->setAttrib('class', 'span6 selectpicker show-menu-arrow')
		->addFilters(array('StringTrim','StripTags'))
		->addMultiOptions(array(
	             NULL => 'Available hoards',
	            'Choose a hoard' => $hoard_options));


	$county = new Zend_Form_Element_Select('countyID');
	$county->setLabel('County: ')
		->addValidators(array('NotEmpty'))
		->setAttrib('class', 'span6 selectpicker show-menu-arrow')
		->addFilters(array('StringTrim','StripTags'))
		->addMultiOptions(array(
	            NULL => 'Choose county',
	            'Available counties' => $county_options));

	$district = new Zend_Form_Element_Select('districtID');
	$district->setLabel('District: ')
		->addMultiOptions(array(NULL => 'Choose district after county'))
		->setAttrib('class', 'span6 selectpicker show-menu-arrow')
		->setRegisterInArrayValidator(false)
		->addFilters(array('StringTrim','StripTags'))
		->setDisableTranslator(true);

	$parish = new Zend_Form_Element_Select('parishID');
	$parish->setLabel('Parish: ')
		->setRegisterInArrayValidator(false)
		->setAttrib('class', 'span6 selectpicker show-menu-arrow')
		->addFilters(array('StringTrim','StripTags'))
		->addMultiOptions(array(NULL => 'Choose parish after county'))
		->setDisableTranslator(true);

	$regionID = new Zend_Form_Element_Select('regionID');
	$regionID->setLabel('European region: ')
		->setRegisterInArrayValidator(false)
		->setAttrib('class', 'span6 selectpicker show-menu-arrow')
		->addValidator('Int')
		->addMultiOptions(array(NULL => 'Choose a region for a wide result',
	            'Choose region' => $region_options));

	$gridref = new Zend_Form_Element_Text('gridref');
	$gridref->setLabel('Grid reference: ')
		->addValidators(array('NotEmpty','ValidGridRef'))
		->addFilters(array('StringTrim','StripTags'));

	$fourFigure = new Zend_Form_Element_Text('fourFigure');
	$fourFigure->setLabel('Four figure grid reference: ')
		->addValidators(array('NotEmpty'))
		->addFilters(array('StringTrim','StripTags'));


	$denomination = new Zend_Form_Element_Select('denomination');
	$denomination->setLabel('Denomination: ')
        ->setRequired(false)
        ->addFilters(array('StripTags','StringTrim'))
		->setAttrib('class', 'span6 selectpicker show-menu-arrow')
        ->addValidator('Int')
        ->addMultiOptions(array(
            NULL => 'Choose denomination type',
        'Available denominations' => $denomination_options));

	$cat = new Zend_Form_Element_Select('category');
	$cat->setLabel('Category: ')
        ->setRequired(false)
        ->addValidator(('Int'))
        ->addFilters(array('StripTags','StringTrim'))
		->setAttrib('class', 'span6 selectpicker show-menu-arrow')
        ->addMultiOptions(array(NULL => 'Choose an Early Medieval category',
        'Available categories' => $cat_options));


	$type = new Zend_Form_Element_Select('type');
	$type->setLabel('Coin type: ')
        ->setRequired(false)
        ->addValidator('Int')
		->setAttrib('class', 'span6 selectpicker show-menu-arrow')
		->addFilters(array('StripTags','StringTrim'))
		->addMultiOptions(array(NULL => 'Choose type after choosing ruler', 'Available types' => $type_options))
		->addValidator('InArray', false, array(array_keys($type_options)));


	//Primary ruler
	$ruler = new Zend_Form_Element_Select('ruler');
	$ruler->setLabel('Ruler / issuer: ')
        ->setRequired(false)
        ->addValidator('Int')
        ->addFilters(array('StripTags','StringTrim'))
		->setAttrib('class', 'span6 selectpicker show-menu-arrow')
        ->addMultiOptions(array(NULL => 'Choose primary ruler',
        'Available options' => $ruler_options))
        ->addValidator('InArray', false, array(array_keys($ruler_options)));


	//Mint
	$mint = new Zend_Form_Element_Select('mint');
	$mint->setLabel('Issuing mint: ')
        ->setRequired(false)
        ->addValidator('Int')
        ->addFilters(array('StripTags','StringTrim'))
		->setAttrib('class', 'span6 selectpicker show-menu-arrow')
        ->addMultiOptions(array(NULL => 'Choose issuing mint',
        'Available mints' => $mint_options));


	//Obverse inscription
	$obverseinsc = new Zend_Form_Element_Text('obverseLegend');
	$obverseinsc->setLabel('Obverse inscription contains: ')
		->setRequired(false)
		->addFilters(array('StripTags','StringTrim'))
		->addErrorMessage('Please enter a valid term');


	//Obverse description
	$obversedesc = new Zend_Form_Element_Text('obverseDescription');
	$obversedesc->setLabel('Obverse description contains: ')
		->setRequired(false)
		->addFilters(array('StripTags','StringTrim'))
		->addErrorMessage('Please enter a valid term');


	//reverse inscription
	$reverseinsc = new Zend_Form_Element_Text('reverseLegend');
	$reverseinsc->setLabel('Reverse inscription contains: ')
		->setRequired(false)
		->addFilters(array('StripTags','StringTrim'))
		->addErrorMessage('Please enter a valid term');


	//reverse description
	$reversedesc = new Zend_Form_Element_Text('reverseDescription');
	$reversedesc->setLabel('Reverse description contains: ')
		->setRequired(false)
		->addFilters(array('StripTags','StringTrim'))
		->addErrorMessage('Please enter a valid term');


	//Die axis
	$axis = new Zend_Form_Element_Select('axis');
	$axis->setLabel('Die axis measurement: ')
		->setRegisterInArrayValidator(false)
		->setRequired(false)
		->setAttrib('class', 'span6 selectpicker show-menu-arrow')
		->addFilters(array('StripTags','StringTrim'))
                ->addValidator('Int')
		->addMultiOptions(array(NULL => 'Choose a die axis measurement',
		'Available options' => $axis_options));


	$objecttype = new Zend_Form_Element_Hidden('objecttype');
	$objecttype->setValue('coin')
		->addFilter('StringToUpper');

	$broadperiod = new Zend_Form_Element_Hidden('broadperiod');
	$broadperiod->setValue('Early Medieval')->addFilter('StringToUpper');
	
	//Submit button
	$submit = new Zend_Form_Element_Submit('submit');

	$institution = new Zend_Form_Element_Select('institution');
	$institution->setLabel('Recording institution: ')
	->setRequired(false)
	->addFilters(array('StringTrim','StripTags'))
	->setAttrib('class', 'span6 selectpicker show-menu-arrow')
        ->addValidator('Alpha',true)
	->addMultiOptions(array(
            null => 'Choose an institution',
            'Available institutions' => $inst_options));

    $hash = new Zend_Form_Element_Hash('csrf');
	$hash->setValue($this->_salt)->setTimeout(4800);

	$this->addElements(array(
	$old_findID, $type, $description,
	$workflow, $rally, $rallyID,
	$hoard, $hoardID, $county,
	$regionID, $district, $parish,
	$fourFigure, $gridref, $denomination,
	$ruler, $mint, $axis,
	$obverseinsc, $obversedesc, $reverseinsc,
	$reversedesc, $objecttype, $broadperiod,
	$cat, $submit, $institution,
	$hash));


	$this->addDisplayGroup(array(
            'category', 'ruler','type',
            'denomination', 'mint','moneyer',
            'axis', 'obverseLegend', 'obverseDescription',
            'reverseLegend','reverseDescription'),
                'numismatics');
	$this->numismatics->setLegend('Numismatic details: ');
	$this->addDisplayGroup(array(
            'old_findID','description','rally',
            'rallyID','hoard','hID','workflow'),
                'details');

	$this->details->setLegend('Object details: ');
	$this->addDisplayGroup(array(
            'countyID','regionID','districtID',
            'parishID','gridref','fourFigure',
            'institution'), 'spatial');
	$this->spatial->setLegend('Spatial details: ');


	$this->addDisplayGroup(array('submit'), 'buttons');
	parent::init();
	}
}