<?php
/** Form for manipulating Iron Age data via search interface
*
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/

class IronAgeNumismaticSearchForm extends Pas_Form {

	protected $_higherlevel = array('admin','flos','fa','heros', 'treasure', 'research');

	protected $_restricted = array(null,'public','member');

	public function __construct($options = null) {

	//Get data to form select menu for periods
	//Get Rally data
	$rallies = new Rallies();
	$rally_options = $rallies->getRallies();
	//Get Hoard data
	$hoards = new Hoards();
	$hoard_options = $hoards->getHoards();

	$counties = new OsCounties();
	$county_options = $counties->getCountiesID();

	$denominations = new Denominations();
	$denom_options = $denominations->getOptionsIronAge();

	$rulers = new Rulers();
	$ruler_options = $rulers->getIronAgeRulers();

	$mints = new Mints();
	$mint_options = $mints->getIronAgeMints();

	$axis = new Dieaxes();
	$axis_options = $axis->getAxes();

	$geog = new Geography();
	$geog_options = $geog->getIronAgeGeographyDD();

	$regions = new OsRegions();
	$region_options = $regions->getRegionsID();
	
	$tribes = new Tribes();
	$tribe_options = $tribes->getTribes();


	$institutions = new Institutions();
	$inst_options = $institutions->getInsts();

	parent::__construct($options);

	$this->setName('IronAgeSearch');

	$old_findID = new Zend_Form_Element_Text('old_findID');
	$old_findID->setLabel('Find number: ')
		->setRequired(false)
		->addFilters(array('StripTags', 'StringTrim'))
		->addErrorMessage('Please enter a valid number!');

	$cci = new Zend_Form_Element_Text('cciNumber');
    $cci->setLabel('CCI number:')
        ->setDescription('This is a unique number')
        ->setFilters(array('StringTrim','StripTags'));

	$description = new Zend_Form_Element_Text('description');
	$description->setLabel('Object description contains: ')
		->setRequired(false)
		->addFilters(array('StripTags', 'StringTrim'))
		->addErrorMessage('Please enter a valid term');

	$workflow = new Zend_Form_Element_Select('workflow');
	$workflow->setLabel('Workflow stage: ')
		->setRequired(false)
		->addFilters(array('StripTags', 'StringTrim'))
		->setAttrib('class', 'span6 selectpicker show-menu-arrow');

	if(in_array($this->_role,$this->_higherlevel)) {
	$workflow->addMultiOptions(array(NULL => 'Choose a workflow stage',
	'Available workflow stages' => array('1'=> 'Quarantine','2' => 'On review',
	'4' => 'Awaiting validation', '3' => 'Published')));
	}
	if(in_array($this->_role,$this->_restricted)) {
	$workflow->addMultiOptions(array(NULL => 'Choose a workflow stage',
	'Available workflow stages' => array('4' => 'Awaiting validation', '3' => 'Published')));
	}

	//Rally details
	$rally = new Zend_Form_Element_Checkbox('rally');
	$rally->setLabel('Rally find: ')
		->setRequired(false)
		->addFilters(array('StripTags','StringTrim'))
		->setUncheckedValue(NULL);

	$geographyID = new Zend_Form_Element_Select('geographyID');
	$geographyID->setLabel('Geographic area: ')
		->setAttrib('class', 'span6 selectpicker show-menu-arrow')
		->addFilters(array('StripTags', 'StringTrim'))
		->addMultiOptions(array(NULL => 'Choose a geography',
		'Available geographies' => $geog_options))
		->addValidator('inArray', false, array(array_keys($geog_options)))
		->addValidator('Int');

	$rallyID =  new Zend_Form_Element_Select('rallyID');
	$rallyID->setLabel('Found at this rally: ')
		->setAttrib('class', 'span6 selectpicker show-menu-arrow')
		->setRequired(false)
		->addFilters(array('StripTags', 'StringTrim'))
		->addMultiOptions(array(NULL => 'Choose a rally',
		'Available rallies' => $rally_options))
		->addValidator('inArray', false, array(array_keys($rally_options)))
		->addValidator('Int');

	$hoard = new Zend_Form_Element_Checkbox('hoard');
	$hoard->setLabel('Hoard find: ')
		->setRequired(false)
		->addFilters(array('StripTags', 'StringTrim'))
		->setUncheckedValue(NULL);

	$hoardID =  new Zend_Form_Element_Select('hID');
	$hoardID->setLabel('Part of this hoard: ')
		->setAttrib('class', 'span6 selectpicker show-menu-arrow')
		->setRequired(false)
		->addFilters(array('StripTags', 'StringTrim'))
		->addMultiOptions(array(NULL => 'Choose a hoard',
		'Available hoards' => $hoard_options))
		->addValidator('inArray', false, array(array_keys($hoard_options)))
		->addValidator('Int');

	$county = new Zend_Form_Element_Select('countyID');
	$county->setLabel('County: ')
		->setAttrib('class', 'span6 selectpicker show-menu-arrow')
		->addFilters(array('StripTags', 'StringTrim'))
		->addMultiOptions(array(NULL => 'Choose a county',
		'Available counties' => $county_options))
		->addValidator('inArray', false, array(array_keys($county_options)));

	$district = new Zend_Form_Element_Select('districtID');
	$district->setLabel('District: ')
		->setAttrib('class', 'span6 selectpicker show-menu-arrow')
		->addFilters(array('StripTags', 'StringTrim'))
		->addMultiOptions(array(NULL => 'Choose district after county'));

	$parish = new Zend_Form_Element_Select('parishID');
	$parish->setLabel('Parish: ')
		->setAttrib('class', 'span6 selectpicker show-menu-arrow')
		->addFilters(array('StripTags', 'StringTrim'))
		->addMultiOptions(array(NULL => 'Choose parish after county', 'Available districts' => null));

	$regionID = new Zend_Form_Element_Select('regionID');
	$regionID->setLabel('European region: ')
		->setAttrib('class', 'span6 selectpicker show-menu-arrow')
		->addMultiOptions(array(NULL => 'Choose a region for a wide result',
		'Available regions' => $region_options))
		->addValidator('Int');


	$gridref = new Zend_Form_Element_Text('gridref');
	$gridref->setLabel('Grid reference: ')
		->addValidator('ValidGridRef')
		->addFilters(array('StripTags', 'StringTrim'))
		->addValidator('Alnum');

	$fourFigure = new Zend_Form_Element_Text('fourFigure');
	$fourFigure->setLabel('Four figure grid reference: ')
		->addFilters(array('StripTags', 'StringTrim'))
		->addValidator('ValidGridRef')
		->addValidator('Alnum');

	###
	##Numismatic data
	###
	//	Denomination
	$denomination = new Zend_Form_Element_Select('denomination');
	$denomination->setLabel('Denomination: ')
		->setAttrib('class', 'span6 selectpicker show-menu-arrow')
		->setRegisterInArrayValidator(false)
		->setRequired(false)
		->addFilters(array('StripTags', 'StringTrim'))
		->addMultiOptions(array(NULL => 'Choose denomination type',
		'Available denominations' => $denom_options))
		->addValidator('inArray', false, array(array_keys($denom_options)));

	//Primary ruler
	$ruler = new Zend_Form_Element_Select('ruler');
	$ruler->setLabel('Ruler / issuer: ')
		->setAttrib('class', 'span6 selectpicker show-menu-arrow')
		->setRequired(false)
		->addFilters(array('StripTags', 'StringTrim'))
		->addMultiOptions(array(NULL => 'Choose primary ruler' ,
		'Available rulers' => $ruler_options))
		->addValidator('inArray', false, array(array_keys($ruler_options)));

	//Mint
	$mint = new Zend_Form_Element_Select('mint');
	$mint->setLabel('Issuing mint: ')
		->setAttrib('class', 'span6 selectpicker show-menu-arrow')
		->setRequired(false)
		->addFilters(array('StripTags', 'StringTrim'))
		->addMultiOptions(array(NULL => 'Choose issuing mint',
		'Available mints' => $mint_options))
		->addValidator('inArray', false, array(array_keys($mint_options)));

	//Secondary ruler
	$ruler2 = new Zend_Form_Element_Select('ruler2');
	$ruler2->setLabel('Secondary ruler / issuer: ')
		->setAttrib('class', 'span6 selectpicker show-menu-arrow')
		->setRequired(false)
		->addFilters(array('StripTags', 'StringTrim'))
		->addMultiOptions(array(NULL => 'Choose secondary ruler',
		'Available rulers' => $ruler_options))
		->addValidator('inArray', false, array(array_keys($ruler_options)));


	//Obverse inscription
	$obverseinsc = new Zend_Form_Element_Text('obverseLegend');
	$obverseinsc->setLabel('Obverse inscription contains: ')
		->setRequired(false)
		->addFilters(array('StripTags', 'StringTrim'))
		->addErrorMessage('Please enter a valid term');

	//Obverse description
	$obversedesc = new Zend_Form_Element_Text('obverseDescription');
	$obversedesc->setLabel('Obverse description contains: ')
		->setRequired(false)
		->addFilters(array('StripTags', 'StringTrim'))
		->addErrorMessage('Please enter a valid term');

	//reverse inscription
	$reverseinsc = new Zend_Form_Element_Text('reverseLegend');
	$reverseinsc->setLabel('Reverse inscription contains: ')
		->setRequired(false)
		->addFilters(array('StripTags', 'StringTrim'))
		->addErrorMessage('Please enter a valid term');

	//reverse description
	$reversedesc = new Zend_Form_Element_Text('reverseDescription');
	$reversedesc->setLabel('Reverse description contains: ')
		->setRequired(false)
		->addFilters(array('StripTags', 'StringTrim'))
		->addErrorMessage('Please enter a valid term');

	//Die axis
	$axis = new Zend_Form_Element_Select('axis');
	$axis->setLabel('Die axis measurement: ')
		->setAttrib('class', 'span4 selectpicker show-menu-arrow')
		->setRequired(false)
		->addFilters(array('StripTags', 'StringTrim'))
		->addMultiOptions(array(NULL => 'Choose measurement',
		'Available die axes' => $axis_options))
		->addValidator('inArray', false, array(array_keys($axis_options)))
		->addErrorMessage('That option is not a valid choice')
		->addValidator('Int');

	//Tribe
	$tribe = new Zend_Form_Element_Select('tribe');
	$tribe->setLabel('Iron Age tribe: ')
		->setRequired(false)
		->setAttrib('class', 'span6 selectpicker show-menu-arrow')
		->addFilters(array('StripTags', 'StringTrim'))
		->addMultiOptions(array(NULL => 'Choose a tribe',
		'Available tribes' => $tribe_options))
		->addValidator('inArray', false, array(array_keys($tribe_options)))
		->addErrorMessage('That option is not a valid choice')
		->addValidator('Int');

	$objecttype = new Zend_Form_Element_Hidden('objecttype');
	$objecttype->setValue('COIN')
		->addFilters(array('StripTags', 'StringTrim', 'StringToUpper'))
		->addValidator('Alpha', false, array('allowWhiteSpace' => true));

	$broadperiod = new Zend_Form_Element_Hidden('broadperiod');
	$broadperiod->setValue('IRON AGE')
		->addValidator('Alnum',false, array('allowWhiteSpace' => true))
		->addFilters(array('StripTags', 'StringTrim'));

	$mack_type = new Zend_Form_Element_Text('mackType');
	$mack_type->setLabel('Mack Type: ')
		->addFilters(array('StripTags', 'StringTrim'))
		->addValidator('Alnum',false, array('allowWhiteSpace' => true));

	$bmc_type = new Zend_Form_Element_Text('bmc');
	$bmc_type->setLabel('British Museum catalogue number: ')
		->addFilters(array('StripTags', 'StringTrim'))
		->addValidator('Alnum',false, array('allowWhiteSpace' => true));

	$allen_type = new Zend_Form_Element_Text('allenType');
	$allen_type->setLabel('Allen Type: ')
		->addFilters(array('StripTags', 'StringTrim'))
		->addValidator('Alnum',false, array('allowWhiteSpace' => true));

	$va_type = new Zend_Form_Element_Text('vaType');
	$va_type->setLabel('Van Arsdell Number (VA): ')
		->addFilters(array('StripTags', 'StringTrim'))
		->addValidator('Alnum',false, array('allowWhiteSpace' => true));

	$rudd_type = new Zend_Form_Element_Text('ruddType');
	$rudd_type->setLabel('Ancient British Coins number (ABC): ')
		->addFilters(array('StripTags', 'StringTrim'))
		->addValidator('Alnum',false, array('allowWhiteSpace' => true));

	$phase_date_1 = new Zend_Form_Element_Text('phase_date_1');
	$phase_date_1->setLabel('Phase date 1: ')
		->addFilters(array('StripTags', 'StringTrim'))
		->addValidator('Alnum',false, array('allowWhiteSpace' => true));

	$phase_date_2 = new Zend_Form_Element_Text('phase_date_2');
	$phase_date_2->setLabel('Phase date 2: ')
		->addFilters(array('StripTags', 'StringTrim'))
		->addValidator('Alnum',false, array('allowWhiteSpace' => true));

	$context = new Zend_Form_Element_Text('context');
	$context->setLabel('Context of coins: ')
		->addFilters(array('StripTags', 'StringTrim'))
		->addValidator('Alnum',false, array('allowWhiteSpace' => true));

	$depositionDate = new Zend_Form_Element_Text('depositionDate');
	$depositionDate->setLabel('Date of deposition: ')
		->addFilters(array('StripTags', 'StringTrim'))
		->addValidator('Alnum',false, array('allowWhiteSpace' => true));

	$numChiab = new Zend_Form_Element_Text('numChiab');
	$numChiab->setLabel('Coin hoards of Iron Age Britain number: ')
		->addFilters(array('StripTags', 'StringTrim'))
		->addValidator('Alnum',false, array('allowWhiteSpace' => true));

	//Submit button
	$submit = new Zend_Form_Element_Submit('submit');
	$submit->setLabel('Submit your search');

	$institution = new Zend_Form_Element_Select('institution');
	$institution->setLabel('Recording institution: ')
		->setAttrib('class', 'span4 selectpicker show-menu-arrow')
		->setRequired(false)
		->addFilters(array('StringTrim','StripTags'))
		->addMultiOptions(array(NULL => 'Choose institution', 'Available institutions' => $inst_options));

	$hash = new Zend_Form_Element_Hash('csrf');
	$hash->setValue($this->_salt)->setTimeout(4800);

	$this->addElements(array(
	$old_findID, $description, $workflow,
	$rally, $rallyID, $hoard,
	$hoardID, $county, $regionID,
	$district, $parish, $fourFigure,
	$gridref, $denomination, $ruler,
	$mint, $axis, $obverseinsc,
	$obversedesc, $reverseinsc, $reversedesc,
	$ruler2, $tribe, $objecttype,
	$broadperiod, $geographyID, $bmc_type,
	$mack_type, $allen_type, $va_type,
	$rudd_type, $numChiab, $context,
	$depositionDate, $phase_date_1, $phase_date_2,
	$institution, $cci,$submit, $hash));

	$this->addDisplayGroup(array(
        'cciNumber', 'denomination', 'geographyID','ruler',
	'ruler2', 'tribe', 'mint',
	'axis', 'obverseLegend', 'obverseDescription',
	'reverseLegend', 'reverseDescription', 'bmc',
	'vaType', 'allenType', 'ruddType',
	'mackType', 'numChiab', 'context',
	'phase_date_1', 'phase_date_2','depositionDate'),
	'numismatics')
	->removeDecorator('HtmlTag');

	$this->numismatics->setLegend('Numismatic details: ');

	$this->addDisplayGroup(array(
	'old_findID','description','rally',
	'rallyID','hoard','hID',
	'workflow'), 'details')
	->removeDecorator('HtmlTag');
	$this->details->setLegend('Object details: ');

	$this->addDisplayGroup(array(
	'countyID', 'regionID', 'districtID',
	'parishID', 'gridref', 'fourFigure',
	'institution'),
	'spatial');

	$this->spatial->setLegend('Spatial details: ');

	$this->addDisplayGroup(array('submit'), 'buttons');

	parent::init();
	}
}