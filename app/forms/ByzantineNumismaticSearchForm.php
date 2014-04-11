<?php

class ByzantineNumismaticSearchForm extends Pas_Form
{
    protected $_higherlevel = array('admin', 'flos', 'fa', 'heros', 'treasure');

	protected $_restricted = array(null,'public', 'member', 'research');
	
	
	public function __construct($options = null)
	{
	$institutions = new Institutions();
	$inst_options = $institutions->getInsts();
	//Get data to form select menu for primary and secondary material

	//Get data to form select menu for periods
	//Get Rally data
	$rallies = new Rallies();
	$rally_options = $rallies->getRallies();

	//Get Hoard data
	$hoards = new Hoards();
	$hoard_options = $hoards->getHoards();

	$counties = new OsCounties();
	$county_options = $counties->getCountiesID();

	$rulers = new Rulers();
	$ruler_options = $rulers->getRulersByzantine();

	$denominations = new Denominations();
	$denomination_options = $denominations->getDenomsByzantine();

    $mints = new Mints();
	$mint_options = $mints->getMintsByzantine();

    $axis = new Dieaxes();
	$axis_options = $axis->getAxes();

	$regions = new OsRegions();
	$region_options = $regions->getRegionsID();

	parent::__construct($options);

	$this->setName('byzantine-search');

	$old_findID = new Zend_Form_Element_Text('old_findID');
	$old_findID->setLabel('Find number: ')
		->setRequired(false)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addErrorMessage('Please enter a valid number!')
		->setDisableTranslator(true);

	$description = new Zend_Form_Element_Text('description');
	$description->setLabel('Object description contains: ')
		->setRequired(false)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty')
		->addErrorMessage('Please enter a valid term')
		->setDisableTranslator(true);


	$workflow = new Zend_Form_Element_Select('workflow');
	$workflow->setLabel('Workflow stage: ')
		->setRequired(false)
		->addFilters(array('StripTags', 'StringTrim'))
		->setAttribs(array('class' => 'span4 selectpicker show-menu-arrow'));
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
		->addFilters(array('StripTags', 'StringTrim'))
		->setUncheckedValue(NULL);

	$rallyID =  new Zend_Form_Element_Select('rallyID');
	$rallyID->setLabel('Found at this rally: ')
		->addFilters(array('StripTags', 'StringTrim'))
		->setAttribs(array('class' => 'span4 selectpicker show-menu-arrow'))
		->addMultiOptions(array(NULL => 'Choose rally name', 
			'Available rallies' => $rally_options));

	$hoard = new Zend_Form_Element_Checkbox('hoard');
	$hoard->setLabel('Hoard find: ')
		->addFilters(array('StripTags', 'StringTrim'))
		->setUncheckedValue(NULL);

	$hoardID =  new Zend_Form_Element_Select('hID');
	$hoardID->setLabel('Part of this hoard: ')
		->addFilters(array('StripTags','StringTrim'))
		->setAttribs(array('class' => 'span6 selectpicker show-menu-arrow'))
		->addMultiOptions(array(NULL => 'Choose hoard name', 'Available hoards' => $hoard_options));

	$county = new Zend_Form_Element_Select('countyID');
	$county->setLabel('County: ')
		->addFilters(array('StripTags','StringTrim'))
		->addValidators(array('NotEmpty'))
		->setAttribs(array('class' => 'span6 selectpicker show-menu-arrow'))
		->addMultiOptions(array(NULL => 'Choose county', 
			'Available counties' => $county_options));

	$district = new Zend_Form_Element_Select('districtID');
	$district->setLabel('District: ')
		->addMultiOptions(array(NULL => 'Choose district after county'))
		->setRegisterInArrayValidator(false)
		->setAttribs(array('class' => 'span6 selectpicker show-menu-arrow'))
		->disabled = true;

	$parish = new Zend_Form_Element_Select('parishID');
	$parish->setLabel('Parish: ')
		->setRegisterInArrayValidator(false)
		->addFilters(array('StripTags','StringTrim'))
		->addMultiOptions(array(NULL => 'Choose parish after county'))
		->setAttribs(array('class' => 'span6 selectpicker show-menu-arrow'))
		->disabled = true;

	$regionID = new Zend_Form_Element_Select('regionID');
	$regionID->setLabel('European region: ')
		->setRegisterInArrayValidator(false)
		->addFilters(array('StripTags','StringTrim'))
		->addMultiOptions(array(NULL => 'Choose a region for a wide result',
		'Choose region' => $region_options))
		->setAttribs(array('class' => 'span6 selectpicker show-menu-arrow'));

	$gridref = new Zend_Form_Element_Text('gridref');
	$gridref->setLabel('Grid reference: ')
		->addFilters(array('StripTags','StringTrim'))
		->addValidators(array('NotEmpty','ValidGridRef'));

	$fourFigure = new Zend_Form_Element_Text('fourFigure');
	$fourFigure->setLabel('Four figure grid reference: ')
		->addFilters(array('StripTags','StringTrim'))
		->addValidators(array('NotEmpty','ValidGridRef'));
	###
	##Numismatic data
	###
	//Denomination
	$denomination = new Zend_Form_Element_Select('denomination');
	$denomination->setLabel('Denomination: ')
		->setRegisterInArrayValidator(false)
		->setRequired(false)
		->addFilters(array('StripTags','StringTrim'))
		->setAttribs(array('class' => 'span4 selectpicker show-menu-arrow'))
		->addMultiOptions(array(NULL => 'Choose denomination type', 
			'Available denominations' => $denomination_options));

	//Primary ruler
	$ruler = new Zend_Form_Element_Select('ruler');
	$ruler->setLabel('Ruler / issuer: ')
		->setRegisterInArrayValidator(false)
		->setAttribs(array('class' => 'span4 selectpicker show-menu-arrow'))
		->addFilters(array('StripTags','StringTrim'))
		->addMultiOptions(array(NULL => 'Choose primary ruler', 
			'Available rulers' => $ruler_options));

	//Mint
	$mint = new Zend_Form_Element_Select('mint');
	$mint->setLabel('Issuing mint: ')
		->setAttribs(array('class' => 'span4 selectpicker show-menu-arrow'))
		->setRegisterInArrayValidator(false)
		->addFilters(array('StripTags','StringTrim'))
		->addMultiOptions(array(NULL => 'Choose denomination type', 
			'Available mints' => $mint_options));

	//Obverse inscription
	$obverseinsc = new Zend_Form_Element_Text('obverseLegend');
	$obverseinsc->setLabel('Obverse inscription contains: ')
		->setAttrib('size',60)
		->addFilters(array('StripTags','StringTrim'))
		->addErrorMessage('Please enter a valid term');

	//Obverse description
	$obversedesc = new Zend_Form_Element_Text('obverseDescription');
	$obversedesc->setLabel('Obverse description contains: ')
		->addFilters(array('StripTags','StringTrim'))
		->setAttrib('size',60)
		->addErrorMessage('Please enter a valid term');

	//reverse inscription
	$reverseinsc = new Zend_Form_Element_Text('reverseLegend');
	$reverseinsc->setLabel('Reverse inscription contains: ')
		->addFilters(array('StripTags','StringTrim'))
		->setAttrib('size',60)
		->addErrorMessage('Please enter a valid term');

	//reverse description
	$reversedesc = new Zend_Form_Element_Text('reverseDescription');
	$reversedesc->setLabel('Reverse description contains: ')
		->addFilters(array('StripTags','StringTrim'))
		->setAttrib('size',60)
		->addErrorMessage('Please enter a valid term');

	//Die axis
	$axis = new Zend_Form_Element_Select('axis');
	$axis->setLabel('Die axis measurement: ')
		->setRegisterInArrayValidator(false)
		->addFilters(array('StripTags','StringTrim'))
		->setAttribs(array('class' => 'span4 selectpicker show-menu-arrow'))
		->addMultiOptions(array(NULL => 'Choose measurement', 'Available axes' => $axis_options));

	$institution = new Zend_Form_Element_Select('institution');
	$institution->setLabel('Recording institution: ')
	->setRequired(false)
	->setAttribs(array('class' => 'span4 selectpicker show-menu-arrow'))
	->addFilters(array('StringTrim','StripTags'))
	->addMultiOptions(array(NULL => 'Choose institution', 'Choose institution' => $inst_options));

	$objecttype = new Zend_Form_Element_Hidden('objecttype');
	$objecttype->setValue('coin');
	$objecttype->addFilters(array('StripTags','StringTrim'));

	$broadperiod = new Zend_Form_Element_Hidden('broadperiod');
	$broadperiod->setValue('Byzantine')
		->addFilters(array('StripTags','StringTrim','StringToUpper'));

	//	Submit button
	$submit = new Zend_Form_Element_Submit('submit');


	$this->addElements(array(
	$old_findID, $description, $workflow,
	$rally, $rallyID, $hoard,
	$hoardID, $county, $regionID,
	$district, $parish, $fourFigure,
	$gridref, $denomination, $ruler,
	$mint, $axis, $obverseinsc,
	$obversedesc, $reverseinsc, $reversedesc,
	$objecttype, $broadperiod, $institution,
	$submit));

	$this->addDisplayGroup(array(
	'denomination', 'ruler','mint',
	'moneyer', 'axis', 'obverseLegend',
	'obverseDescription','reverseLegend','reverseDescription'),
	'numismatics');

	$this->addDisplayGroup(array(
	'old_findID', 'description', 'rally',
	'rallyID', 'hoard', 'hID',
	'workflow'), 'details');
	$this->addDisplayGroup(array(
	'countyID','regionID','districtID',
	'parishID','gridref','fourFigure',
	'institution'), 'spatial');

	$this->numismatics->setLegend('Numismatic details');

	$this->details->setLegend('Artefact details');

	$this->spatial->setLegend('Spatial details');


	$this->addDisplayGroup(array('submit'), 'buttons');

	parent::init();
}
}