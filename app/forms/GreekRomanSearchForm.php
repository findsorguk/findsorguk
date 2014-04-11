<?php
/** Form for retrieval of Greek and Roman coin data
*
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class GreekRomanSearchForm extends Pas_Form {

	protected $_higherlevel = array('admin','flos','fa','heros', 'treasure', 'research');

	protected $_restricted = array(null,'public','member');

	public function __construct($options = null) {

	//Get data to form select menu for primary and secondary material
	$primaries = new Materials();
	$primary_options = $primaries->getPrimaries();

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
	$denomination_options = $denominations->getDenomsGreek();

	$mints = new Mints();
	$mint_options = $mints->getMintsGreek();

	$axis = new Dieaxes();
	$axis_options = $axis->getAxes();

	$regions = new OsRegions();
	$region_options = $regions->getRegionsID();
	
	parent::__construct($options);


	$this->setName('greek-search');

	$old_findID = new Zend_Form_Element_Text('old_findID');
	$old_findID->setLabel('Find number: ')
	->setRequired(false)
	->addFilters(array('StringTrim','StripTags'))
	->addErrorMessage('Please enter a valid number!');

	$description = new Zend_Form_Element_Text('description');
	$description->setLabel('Object description contains: ')
	->setRequired(false)
	->addFilters(array('StringTrim','StripTags'))
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
	->addFilters(array('StringTrim','StripTags'))

	->setUncheckedValue(NULL);

	$rallyID =  new Zend_Form_Element_Select('rallyID');
	$rallyID->setLabel('Found at this rally: ')
	->setRequired(false)
	->setAttrib('class', 'span6 selectpicker show-menu-arrow')
	->addFilters(array('StringTrim','StripTags'))
	->addValidator('Int')
	->addValidator('InArray', false, array(array_keys($rally_options)));


	$hoard = new Zend_Form_Element_Checkbox('hoard');
	$hoard->setLabel('Hoard find: ')
	->setRequired(false)
	->addFilters(array('StringTrim','StripTags'))
	->setUncheckedValue(NULL);

	$hoardID =  new Zend_Form_Element_Select('hID');
	$hoardID->setLabel('Part of this hoard: ')
	->setRequired(false)
	->addFilters(array('StringTrim','StripTags'))
	->setAttrib('class', 'span6 selectpicker show-menu-arrow')
	->addMultiOptions(array(NULL => 'Choose hoard name', 'Available hoards' => $hoard_options))
	->addValidator('InArray', false, array(array_keys($hoard_options)));

	$county = new Zend_Form_Element_Select('countyID');
	$county->setLabel('County: ')
	->addFilters(array('StringTrim','StripTags'))
	->setAttrib('class', 'span6 selectpicker show-menu-arrow')
	->addMultiOptions(array(NULL => 'Choose county', 'Available counties' => $county_options))
	->addValidator('InArray', false, array(array_keys($county_options)));

	$district = new Zend_Form_Element_Select('districtID');
	$district->setLabel('District: ')
	->addFilters(array('StringTrim','StripTags'))
	->setAttrib('class', 'span6 selectpicker show-menu-arrow')
	->addMultiOptions(array(NULL => 'Choose district after county'));

	$parish = new Zend_Form_Element_Select('parishID');
	$parish->setLabel('Parish: ')
	->addFilters(array('StringTrim','StripTags'))
	->setAttrib('class', 'span6 selectpicker show-menu-arrow')
	->addMultiOptions(array(NULL => 'Choose parish after county'));

	$regionID = new Zend_Form_Element_Select('regionID');
	$regionID->setLabel('European region: ')
	->addMultiOptions(array(NULL => 'Choose a region for a wide result',
	'Choose region' => $region_options))
	->setAttrib('class', 'span6 selectpicker show-menu-arrow')
	->addValidator('Digits')
	->addFilters(array('StringTrim','StripTags'));

	$gridref = new Zend_Form_Element_Text('gridref');
	$gridref->setLabel('Grid reference: ')
	->addValidators(array('NotEmpty','ValidGridRef','Alnum'))
	->addFilters(array('StringTrim','StripTags'));

	$fourFigure = new Zend_Form_Element_Text('fourfigure');
	$fourFigure->setLabel('Four figure grid reference: ')
	->addValidators(array('NotEmpty','ValidGridRef','Alnum'))
	->addFilters(array('StringTrim','StripTags'));

	###
	##Numismatic data
	###
	//Denomination
	$denomination = new Zend_Form_Element_Select('denomination');
	$denomination->setLabel('Denomination: ')
	->setRequired(false)
	->addFilters(array('StringTrim','StripTags'))
	->setAttrib('class', 'span6 selectpicker show-menu-arrow')
	->addMultiOptions(array(NULL => 'Choose denomination type', 'Available denominations' => $denomination_options))
	->addValidator('InArray', false, array(array_keys($denomination_options)));

	//Primary ruler
	$ruler = new Zend_Form_Element_Select('ruler');
	$ruler->setLabel('Ruler / issuer: ')
	->setRequired(false)
	->setAttrib('class', 'span6 selectpicker show-menu-arrow')
	->addFilters(array('StringTrim','StripTags'))
	->addMultiOptions(array(NULL => 'Choose primary ruler', 'Available rulers' => $ruler_options))
	->addValidator('InArray', false, array(array_keys($ruler_options)));

	//Mint
	$mint = new Zend_Form_Element_Select('mint');
	$mint->setLabel('Issuing mint: ')
	->setRegisterInArrayValidator(false)
	->setRequired(false)
	->addFilters(array('StringTrim','StripTags'))
	->setAttrib('class', 'span6 selectpicker show-menu-arrow')
	->addMultiOptions(array(NULL => 'Choose issuing mint', 'Available mints' => $mint_options))
	->addValidator('InArray', false, array(array_keys($mint_options)));

	//Obverse inscription
	$obverseinsc = new Zend_Form_Element_Text('obverseLegend');
	$obverseinsc->setLabel('Obverse inscription contains: ')
	->setRequired(false)
	->setAttrib('size',50)
	->addFilters(array('StringTrim','StripTags'))
	->addErrorMessage('Please enter a valid term');

	//Obverse description
	$obversedesc = new Zend_Form_Element_Text('obverseDescription');
	$obversedesc->setLabel('Obverse description contains: ')
	->setRequired(false)
	->addFilters(array('StringTrim','StripTags'))
	->setAttrib('size',50)
	->addErrorMessage('Please enter a valid term');

	//reverse inscription
	$reverseinsc = new Zend_Form_Element_Text('reverseLegend');
	$reverseinsc->setLabel('Reverse inscription contains: ')
	->setRequired(false)
	->addFilters(array('StringTrim','StripTags'))
	->setAttrib('size',50)
	->addErrorMessage('Please enter a valid term');

	//reverse description
	$reversedesc = new Zend_Form_Element_Text('reverseDescription');
	$reversedesc->setLabel('Reverse description contains: ')
	->setRequired(false)
	->addFilters(array('StringTrim','StripTags'))
	->setAttrib('size',50)
	->addErrorMessage('Please enter a valid term');

	//Die axis
	$axis = new Zend_Form_Element_Select('axis');
	$axis->setLabel('Die axis measurement: ')
	->setRequired(false)
	->setAttrib('class', 'span6 selectpicker show-menu-arrow')
	->addFilters(array('StringTrim','StripTags'))
	->addMultiOptions(array(NULL => 'Choose measurement', 'Available axes' => $axis_options))
	->addValidator('InArray', false, array(array_keys($axis_options)));

	$objecttype = new Zend_Form_Element_Hidden('objecttype');
	$objecttype->setValue('coin');
	$objecttype->removeDecorator('HtmlTag')
	->addFilters(array('StringTrim','StripTags', 'StringToUpper'));

	$broadperiod = new Zend_Form_Element_Hidden('broadperiod');
	$broadperiod->setValue('Greek and Roman Provincial')
	->addFilters(array('StringTrim','StripTags','StringToUpper'))
	->removeDecorator('label');

	//Submit button
	$submit = new Zend_Form_Element_Submit('submit');
	$submit->setLabel('Search..');

	$this->addElements(array(
	$old_findID, $description, $workflow,
	$rally,$rallyID,$hoard,
	$hoardID,$county,$regionID,
	$district,$parish,$fourFigure,
	$gridref,$denomination,$ruler
	,$mint,$axis,$obverseinsc,
	$obversedesc,$reverseinsc,$reversedesc,
	$objecttype,$broadperiod, $submit));

	$this->addDisplayGroup(array(
	'denomination','ruler','mint',
	'moneyer','axis','obverseLegend',
	'obverseDescription','reverseLegend','reverseDescription'), 'numismatics');

	$this->addDisplayGroup(array(
	'old_findID','description','rally',
	'rallyID','hoard','hID',
	'workflow'), 'details');

	$this->addDisplayGroup(array(
	'countyID','regionID','districtID',
	'parishID','gridref','fourfigure'), 'spatial');

	$this->numismatics->setLegend('Numismatic details');



	$this->details->setLegend('Artefact details');

	$this->spatial->setLegend('Spatial details');

	$this->addDisplayGroup(array('submit'), 'buttons');
	parent::init();
	}
}