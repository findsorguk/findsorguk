<?php
/** Form for manipulating Iron Age data
*
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class IronAgeCoinFormNew extends Pas_Form {

public function __construct($options = null) {

	// Construct the select menu data
	$denominations = new Denominations();
	$denomination_options = $denominations->getOptionsIronAge();

	$statuses = new Statuses();
	$status_options = $statuses->getCoinStatus();

	$dies = new Dieaxes;
	$die_options = $dies->getAxes();

	$wears = new Weartypes;
	$wear_options = $wears->getWears();

	$rulers = new Rulers();
	$ro = $rulers->getIronAgeRulers();

	$mints = new Mints;
	$mint_options = $mints->getIronAgeMints();

	$tribes = new Tribes();
	$to = $tribes->getTribes();

	$atypes = new AllenTypes();
	$atypelist = $atypes->getATypes();

	$vatypes = new VanArsdellTypes();
	$vatypelist = $vatypes->getVATypesDD();

	$macktypes = new MackTypes();
	$macktypelist = $macktypes->getMackTypesDD();

	$abc = new AbcNumbers();
	$abclist = $abc->getTerms();

	$regions = new Geography();
	$ia_geog = $regions->getIronAgeGeographyDD();

	parent::__construct($options);


	$this->setName('ironagecoin');

	$denomination = new Zend_Form_Element_Select('denomination');
	$denomination->setLabel('Denomination: ')
		->setAttrib('class', 'span6 selectpicker show-menu-arrow')
		->setRequired(true)
		->addFilters(array('StripTags', 'StringTrim'))
		->addMultiOptions(array(NULL => 'Choose denomination', 'Available options' => $denomination_options))
		->addValidator('InArray', false, array(array_keys($denomination_options)))
		->addValidator('Int');

	$denomination_qualifier = new Zend_Form_Element_Radio('denomination_qualifier');
	$denomination_qualifier->setLabel('Denomination qualifier: ')
		->addMultiOptions(array('1' => 'Certain','2' => 'Probably','3' => 'Possibly'))
		->setValue(1)
		->addFilters(array('StripTags', 'StringTrim'))
		->addValidator('Digits')
		->setOptions(array('separator' => ''));

	$geographyID = new Zend_Form_Element_Select('geographyID');
	$geographyID->setLabel('Geographic area: ')
		->setAttrib('class', 'span6 selectpicker show-menu-arrow')
		->addMultiOptions(array(NULL => 'Choose geography', 'Available options' => $ia_geog))
		->addValidator('InArray', false, array(array_keys($ia_geog)))
		->addFilters(array('StripTags', 'StringTrim'))
		->addValidator('Digits');

	$geography_qualifier = new Zend_Form_Element_Radio('geography_qualifier');
	$geography_qualifier->setLabel('Geographic qualifier: ')
		->addMultiOptions(array('1' => 'Certain','2' => 'Probably','3' => 'Possibly'))
		->addFilters(array('StripTags', 'StringTrim'))
		->addValidator('Digits')
		->setOptions(array('separator' => ''));

	$ruler_id= new Zend_Form_Element_Select('ruler_id');
	$ruler_id->setLabel('Ruler: ')
		->setAttrib('class', 'span6 selectpicker show-menu-arrow')
		->addFilters(array('StripTags', 'StringTrim'))
		->addValidator('Digits')
		->addMultiOptions(array(NULL => 'Choose primary ruler', 'Available rulers' => $ro));

	$ruler_qualifier = new Zend_Form_Element_Radio('ruler_qualifier');
	$ruler_qualifier->setLabel('Issuer qualifier: ')
		->addMultiOptions(array('1' => 'Certain','2' => 'Probably','3' => 'Possibly'))
		->addFilters(array('StripTags', 'StringTrim'))
		->addValidator('Int')
		->setOptions(array('separator' => ''));

	$ruler2_id= new Zend_Form_Element_Select('ruler2_id');
	$ruler2_id->setLabel('Secondary ruler: ')
		->setAttrib('class', 'span6 selectpicker show-menu-arrow')
		->addFilters(array('StripTags', 'StringTrim'))
		->addValidator('Int')
		->addMultiOptions(array(NULL => 'Choose issuing secondary ruler', 'Available rulers' => $ro))
		->addValidator('InArray', false, array(array_keys($ro)));

	$ruler2_qualifier = new Zend_Form_Element_Radio('ruler2_qualifier');
	$ruler2_qualifier->setLabel('Secondary issuer qualifier: ')
		->addMultiOptions(array('1' => 'Certain','2' => 'Probably','3' => 'Possibly'))
		->addFilters(array('StripTags', 'StringTrim'))
		->addValidator('Int')
		->setOptions(array('separator' => ''));

	$mint_id= new Zend_Form_Element_Select('mint_id');
	$mint_id->setLabel('Issuing mint: ')
		->setAttrib('class', 'span6 selectpicker show-menu-arrow')
		->setRegisterInArrayValidator(false)
		->addFilters(array('StripTags', 'StringTrim'))
		->addValidator('Int')
		->addMultiOptions(array(NULL => 'Choose issuing mint', 'Available options' => $mint_options))
		->addValidator('InArray', false, array(array_keys($mint_options)));

	$tribe= new Zend_Form_Element_Select('tribe');
	$tribe->setLabel('Tribe: ')
		->setAttrib('class', 'span6 selectpicker show-menu-arrow')
		->addFilters(array('StripTags', 'StringTrim'))
		->addValidator('Int')
		->setRegisterInArrayValidator(false)
		->addMultiOptions(array(NULL => 'Choose tribe', 'Available options' => $to))
		->addValidator('InArray', false, array(array_keys($to)));

	$tribe_qualifier = new Zend_Form_Element_Radio('tribe_qualifier');
	$tribe_qualifier->setLabel('Tribe qualifier: ')
		->addMultiOptions(array('1' => 'Certain','2' => 'Probably','3' => 'Possibly'))
		->addFilters(array('StripTags', 'StringTrim'))
		->addValidator('Int')
		->setOptions(array('separator' => ''));

	$status = new Zend_Form_Element_Select('status');
	$status->setLabel('Status: ')
		->addFilters(array('StripTags', 'StringTrim'))
		->setAttrib('class', 'span6 selectpicker show-menu-arrow')
		->addValidator('Int')
		->setValue(1)
		->addMultiOptions(array(NULL => 'Choose coin status', 'Available options' => $status_options))
		->addValidator('InArray', false, array(array_keys($status_options)));

	$status_qualifier = new Zend_Form_Element_Radio('status_qualifier');
	$status_qualifier->setLabel('Status qualifier: ')
		->addMultiOptions(array('1' => 'Certain','2' => 'Probably','3' => 'Possibly'))
		->setValue(1)
		->addFilters(array('StripTags', 'StringTrim'))
		->addValidator('Digits')
		->setOptions(array('separator' => ''));

	$degree_of_wear = new Zend_Form_Element_Select('degree_of_wear');
	$degree_of_wear->setLabel('Degree of wear: ')
		->setAttrib('class', 'span6 selectpicker show-menu-arrow')
		->addFilters(array('StripTags', 'StringTrim'))
		->addValidator('Digits')
		->addMultiOptions(array(NULL => 'Choose wear status', 'Available options' => $wear_options))
		->addValidator('InArray', false, array(array_keys($wear_options)));

	$obverse_inscription = new Zend_Form_Element_Text('obverse_inscription');
	$obverse_inscription->setLabel('Obverse inscription: ')
		->addFilters(array('StripTags', 'StringTrim'))
		->setAttribs(array('rows' => 8, 'cols' => 80, 'class' => 'span6'));

	$reverse_inscription = new Zend_Form_Element_Text('reverse_inscription');
	$reverse_inscription->setLabel('Reverse inscription: ')
		->addFilters(array('StripTags', 'StringTrim'))
		->setAttribs(array('rows' => 8, 'cols' => 80, 'class' => 'span6'));

	$obverse_description = new Zend_Form_Element_Textarea('obverse_description');
	$obverse_description->setLabel('Obverse description: ')
		->setAttribs(array('rows' => 8, 'cols' => 80, 'class' => 'span6'))
		->addFilters(array('StripTags', 'StringTrim','BasicHtml','EmptyParagraph'));

	$reverse_description = new Zend_Form_Element_Textarea('reverse_description');
	$reverse_description->setLabel('Reverse description: ')
		->setAttribs(array('rows' => 8, 'cols' => 80, 'class' => 'span6'))
		->addFilters(array('StripTags', 'StringTrim','BasicHtml','EmptyParagraph'));

	$die_axis_measurement = new Zend_Form_Element_Select('die_axis_measurement');
	$die_axis_measurement->setLabel('Die axis measurement: ')
		->setAttrib('class', 'span6 selectpicker show-menu-arrow')
		->addMultiOptions(array(NULL => 'Choose die axis', 'Available options' => $die_options))
		->addValidator('InArray', false, array(array_keys($die_options)));

	$die_axis_certainty = new Zend_Form_Element_Radio('die_axis_certainty');
	$die_axis_certainty->setLabel('Die axis certainty: ')
		->addMultiOptions(array('1' => 'Certain','2' => 'Probably','3' => 'Possibly'))
		->addFilters(array('StripTags', 'StringTrim'))
		->addValidator('Int')
		->setOptions(array('separator' => ''));

	$mack_type = new Zend_Form_Element_Select('mack_type');
	$mack_type->setLabel('Mack Type: ')
		->addFilters(array('StripTags', 'StringTrim'))
		->setAttrib('class', 'span6 selectpicker show-menu-arrow')
		->addMultiOptions(array(NULL => 'Choose a Mack type','Valid types' => $macktypelist))
		->addValidator('InArray', false, array(array_keys($macktypelist)));

	$bmc_type = new Zend_Form_Element_Text('bmc_type');
	$bmc_type->setLabel('British Museum catalogue number: ')
		->addFilters(array('StripTags', 'StringTrim', 'Purifier'));

	$allen_type = new Zend_Form_Element_Select('allen_type');
	$allen_type->setLabel('Allen Type: ')
		->addFilters(array('StripTags', 'StringTrim'))
		->setAttrib('class', 'span6 selectpicker show-menu-arrow')
		->addMultiOptions(array(NULL => 'Choose an Allen type','Valid types' => $atypelist))
		->addValidator('InArray', false, array(array_keys($atypelist)));

	$va_type = new Zend_Form_Element_Select('va_type');
	$va_type->setLabel('Van Arsdell Number: ')
		->addFilters(array('StripTags', 'StringTrim'))
		->setAttrib('class', 'span6 selectpicker show-menu-arrow')
		->addMultiOptions(array(NULL => 'Choose Van Arsdell type','Valid types' => $vatypelist))
		->addValidator('InArray', false, array(array_keys($vatypelist)));


	$cciNumber  = new Zend_Form_Element_Text('cciNumber');
	$cciNumber->setLabel('Celtic Coin Index Number: ')
		->setAttrib('size',12)
		->addFilters(array('StripTags', 'StringTrim'))
		->setDescription('This is the coin\'s unique CCI number, not a comparison field.  Numbers are issued by the CCI.');

	$rudd_type = new Zend_Form_Element_Select('rudd_type');
	$rudd_type->setLabel('Ancient British Coinage number: ')
		->setAttrib('class', 'span6 selectpicker show-menu-arrow')
		->addFilters(array('StripTags', 'StringTrim','Purifier'))
		->addMultiOptions(array(NULL => 'Choose ABC number','Valid types' => $abclist))
		->addValidator('InArray', false, array(array_keys($abclist)));

	$phase_date_1 = new Zend_Form_Element_Text('phase_date_1');
	$phase_date_1->setLabel('Phase date 1: ')
		->addFilters(array('StripTags', 'StringTrim', 'Purifier'));

	$phase_date_2 = new Zend_Form_Element_Text('phase_date_2');
	$phase_date_2->setLabel('Phase date 2: ')
		->addFilters(array('StripTags', 'StringTrim', 'Purifier'));

	$context = new Zend_Form_Element_Text('context');
	$context->setLabel('Context of coins: ')
		->addFilters(array('StripTags', 'StringTrim', 'Purifier'));

	$depositionDate = new Zend_Form_Element_Text('depositionDate');
	$depositionDate->setLabel('Date of deposition: ')
		->addFilters(array('StripTags', 'StringTrim','Purifier'))
		->addValidator('Datetime');

	$numChiab = new Zend_Form_Element_Text('numChiab');
	$numChiab->setLabel('Coin hoards of Iron Age Britain number: ')
		->addFilters(array('StripTags', 'StringTrim','Purifier'));

	$submit = new Zend_Form_Element_Submit('submit');

	$this->addElements(array(
	$ruler_id, $ruler_qualifier, $denomination,
	$denomination_qualifier, $mint_id, $ruler2_id,
	$ruler2_qualifier, $geographyID, $geography_qualifier,
	$status, $status_qualifier, $degree_of_wear,
	$obverse_description, $obverse_inscription, $reverse_description,
	$reverse_inscription, $die_axis_measurement, $die_axis_certainty,
	$tribe, $tribe_qualifier, $bmc_type,
	$mack_type, $allen_type, $va_type,
	$rudd_type, $cciNumber, $numChiab,
	$context, $depositionDate, $phase_date_1,
	$phase_date_2, $submit));

	$this->addDisplayGroup(array(
	'denomination','denomination_qualifier', 'geographyID',
	'geography_qualifier','tribe','tribe_qualifier',
	'ruler_id','ruler_qualifier','ruler2_id',
	'ruler2_qualifier','mint_id','status',
	'status_qualifier','degree_of_wear','obverse_description',
	'obverse_inscription', 'reverse_description','reverse_inscription',
	'die_axis_measurement','die_axis_certainty', 'bmc_type',
	'va_type','allen_type','rudd_type',
	'mack_type','cciNumber','numChiab',
	'context', 'phase_date_1','phase_date_2',
	'depositionDate'), 'details');

	$this->addDisplayGroup(array('submit'),'buttons');

	parent::init();
	}
}