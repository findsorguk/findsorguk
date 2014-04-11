<?php
/** Form for searching for medieval numismatic material
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/

class MedNumismaticSearchForm extends Pas_Form {

    protected $_higherlevel = array('admin', 'flos', 'fa', 'heros', 'treasure');

	protected $_restricted = array(null,'public', 'member', 'research');

	public function __construct($options = null) {


	$rallies = new Rallies();
	$rally_options = $rallies->getRallies();

	//Get Hoard data
	$hoards = new Hoards();
	$hoard_options = $hoards->getHoards();

	$counties = new OsCounties();
	$county_options = $counties->getCountiesID();

	$rulers = new Rulers();
	$ruler_options = $rulers->getMedievalRulers();

	$denominations = new Denominations();
	$denomination_options = $denominations->getOptionsMedieval();

	$mints = new Mints();
	$mint_options = $mints->getMedievalMints();

	$axis = new Dieaxes();
	$axis_options = $axis->getAxes();

	$cats = new CategoriesCoins();
	$cat_options = $cats->getPeriodMed();
	
	$types = new MedievalTypes();
	$type_options = $types->getMedievalTypesForm(29);
	
	$regions = new OsRegions();
	$region_options = $regions->getRegionsID();

	$institutions = new Institutions();
	$inst_options = $institutions->getInsts();

	$axis = new Dieaxes();
	$axis_options = $axis->getAxes();

	parent::__construct($options);



	$this->setName('medNumismaticsSearch');

	$old_findID = new Zend_Form_Element_Text('old_findID');
	$old_findID->setLabel('Find number: ')
		->addFilters(array('StripTags', 'StringTrim'))
		->addErrorMessage('Please enter a valid number!')
		->setAttrib('class', 'span6');

	$description = new Zend_Form_Element_Text('description');
	$description->setLabel('Object description contains: ')
		->addFilters(array('StripTags', 'StringTrim'))
		->addErrorMessage('Please enter a valid term');

	$workflow = new Zend_Form_Element_Select('workflow');
	$workflow->setLabel('Workflow stage: ')
		->addFilters(array('StripTags', 'StringTrim'))->addValidator('Int')
		->setAttrib('class', 'span6 selectpicker show-menu-arrow');

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
		->addFilters(array('StripTags', 'StringTrim'))
		->setUncheckedValue(NULL);

	$rallyID =  new Zend_Form_Element_Select('rallyID');
	$rallyID->setLabel('Found at this rally: ')
		->addFilters(array('StripTags', 'StringTrim'))
		->setAttrib('class', 'span6 selectpicker show-menu-arrow')
		->addMultiOptions(array(NULL => 'Choose rally name', 
			'Available rallies' => $rally_options))
		->addValidator('InArray', false, array(array_keys($rally_options)));

	$hoard = new Zend_Form_Element_Checkbox('hoard');
	$hoard->setLabel('Hoard find: ')
		->addFilters(array('StripTags', 'StringTrim'))
		->addValidator('Int')
		->setUncheckedValue(NULL);

	$hoardID =  new Zend_Form_Element_Select('hID');
	$hoardID->setLabel('Part of this hoard: ')
		->addFilters(array('StripTags', 'StringTrim'))
		->setAttrib('class', 'span6 selectpicker show-menu-arrow')
		->addMultiOptions(array(NULL => 'Choose hoard name', 'Available hoards' => $hoard_options))
		->addValidator('InArray', false, array(array_keys($hoard_options)));

	$county = new Zend_Form_Element_Select('countyID');
	$county->setLabel('County: ')
		->addFilters(array('StripTags', 'StringTrim'))
		->setAttrib('class', 'span6 selectpicker show-menu-arrow')
		->addMultiOptions(array(NULL => 'Choose county', 'Available counties' => $county_options))
		->addValidator('InArray', false, array(array_keys($county_options)));

	$district = new Zend_Form_Element_Select('districtID');
	$district->setLabel('District: ')
		->addMultiOptions(array(NULL => 'Choose district after county'))
		->setAttrib('class', 'span6 selectpicker show-menu-arrow')
		->setRegisterInArrayValidator(false);

	$parish = new Zend_Form_Element_Select('parishID');
	$parish->setLabel('Parish: ')
		->setRegisterInArrayValidator(false)
		->setAttrib('class', 'span6 selectpicker show-menu-arrow')
		->addMultiOptions(array(NULL => 'Choose parish after county'));

	$regionID = new Zend_Form_Element_Select('regionID');
	$regionID->setLabel('European region: ')
		->setRegisterInArrayValidator(false)
		->setAttrib('class', 'span6 selectpicker show-menu-arrow')
		->addMultiOptions(array(NULL => 'Choose a region for a wide result',
		'Choose region' => $region_options));

	$gridref = new Zend_Form_Element_Text('gridref');
	$gridref->setLabel('Grid reference: ')
		->addValidators(array('NotEmpty','ValidGridRef'));

	$fourFigure = new Zend_Form_Element_Text('fourFigure');
	$fourFigure->setLabel('Four figure grid reference: ')
		->addValidators(array('NotEmpty','ValidGridRef'));

	###
	##Numismatic data
	###
	//	Denomination
	$denomination = new Zend_Form_Element_Select('denomination');
	$denomination->setLabel('Denomination: ')
		->addFilters(array('StripTags', 'StringTrim'))
		->setAttrib('class', 'span6 selectpicker show-menu-arrow')
		->addMultiOptions(array(NULL => 'Choose a denomination',
		'Available denominations' => $denomination_options))
		->addValidator('InArray', false, array(array_keys($denomination_options)));

	$cat = new Zend_Form_Element_Select('category');
	$cat->setLabel('Category: ')
		->addFilters(array('StripTags', 'StringTrim'))
		->setAttrib('class', 'span6 selectpicker show-menu-arrow')
		->addMultiOptions(array(
		NULL => 'Choose a category',
		'Available categories' => $cat_options))
		->addValidator('InArray', false, array(array_keys($cat_options)));

	$type = new Zend_Form_Element_Select('type');
	$type->setLabel('Coin type: ')
		->addFilters(array('StripTags', 'StringTrim'))
		->setAttrib('class', 'span6 selectpicker show-menu-arrow')
		->setRegisterInArrayValidator(false)
        ->addValidator('Int')
		->addFilters(array('StripTags','StringTrim'))
		->addMultiOptions(array(NULL => 'Choose type after choosing ruler', 'Available types' => $type_options))
		->addValidator('InArray', false, array(array_keys($type_options)));

	//Primary ruler
	$ruler = new Zend_Form_Element_Select('ruler');
	$ruler->setLabel('Ruler / issuer: ')
		->setAttrib('class', 'span6 selectpicker show-menu-arrow')
		->addFilters(array('StripTags', 'StringTrim'))
		->addMultiOptions(array(
		NULL => 'Choose a ruler', 
		'Available issuers' => $ruler_options))
		->addValidator('InArray', false, array(array_keys($ruler_options)));

	//Mint
	$mint = new Zend_Form_Element_Select('mint');
	$mint->setLabel('Issuing mint: ')
		->setAttrib('class', 'span6 selectpicker show-menu-arrow')
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addMultiOptions(array(
		NULL => 'Choose a mint', 
		'Available mints' => $mint_options))
		->addValidator('InArray', false, array(array_keys($mint_options)));

	//Obverse inscription
	$obverseinsc = new Zend_Form_Element_Text('obverseLegend');
	$obverseinsc->setLabel('Obverse inscription contains: ')
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
		->addFilters(array('StripTags', 'StringTrim'))
		->addErrorMessage('Please enter a valid term');

	//reverse description
	$reversedesc = new Zend_Form_Element_Text('reverseDescription');
	$reversedesc->setLabel('Reverse description contains: ')
		->addFilters(array('StripTags', 'StringTrim'))
		->addErrorMessage('Please enter a valid term');

	//Die axis
	$axis = new Zend_Form_Element_Select('axis');
	$axis->setLabel('Die axis measurement: ')
		->setAttrib('class', 'span6 selectpicker show-menu-arrow')
		->addFilters(array('StripTags', 'StringTrim'))
		->addMultiOptions(array(
			NULL => 'Choose an axis', 
			'Available measurements' => $axis_options))
		->addValidator('InArray', false, array(array_keys($axis_options)));

	$objecttype = new Zend_Form_Element_Hidden('objecttype');
	$objecttype->setValue('COIN')
		->addFilters(array('StripTags', 'StringTrim'))
		->setAttrib('class', 'none')
		->addValidator('Alpha');

	$broadperiod = new Zend_Form_Element_Hidden('broadperiod');
	$broadperiod->setValue('MEDIEVAL')
		->setAttrib('class', 'none')
		->addFilters(array('StripTags', 'StringTrim'))
		->addValidator('Alpha');

	//Submit button
	$submit = new Zend_Form_Element_Submit('submit');
	$submit->setLabel('Submit');

	$hash = new Zend_Form_Element_Hash('csrf');
	$hash->setValue($this->_salt)->setTimeout(4800);

	$institution = new Zend_Form_Element_Select('institution');
	$institution->setLabel('Recording institution: ')
		->setAttrib('class', 'span4 selectpicker show-menu-arrow')
		->setRequired(false)
		->addFilters(array('StringTrim','StripTags'))
		->addMultiOptions(array(NULL => 'Choose an institution',
		'Available institutions' => $inst_options));

	$this->addElements(array(
	$old_findID, $type, $description,
	$workflow, $rally, $rallyID,
	$hoard, $hoardID, $county,
	$regionID, $district, $parish,
	$fourFigure, $gridref, $denomination,
	$ruler,$mint,$axis,
	$obverseinsc, $obversedesc,$reverseinsc,
	$reversedesc, $objecttype, $broadperiod,
	$cat, $submit,$hash, $institution));



	$this->addDisplayGroup(array(
	'category', 'ruler','type',
	'denomination', 'mint','moneyer',
	'axis',  'obverseLegend','obverseDescription',
	'reverseLegend','reverseDescription'), 'numismatics')
	->removeDecorator('HtmlTag');

	$this->numismatics->setLegend('Numismatic details: ');
	
	$this->addDisplayGroup(array('old_findID','description','rally','rallyID','hoard','hID','workflow'), 'details');
	
	$this->details->setLegend('Object details:');

	$this->addDisplayGroup(array('countyID','regionID','districtID','parishID','gridref','fourFigure', 'institution'), 'spatial');
	
	$this->spatial->setLegend('Spatial details: ');

	$this->setLegend('Perform an advanced search on our database: ');

	$this->addDisplayGroup(array('submit'), 'buttons');

	parent::init();
	}
}
