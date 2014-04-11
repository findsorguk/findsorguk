<?php
/** Form for entering and editing medievalish tokens and jettons
*
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class TokenJettonForm extends Pas_Form {

	public function __construct($options = null) {

	$rulers = new Rulers();
	$ro = $rulers->getJettonRulers();
	$dies = new Dieaxes;
	$die_options = $dies->getAxes();
	$wears = new Weartypes;
	$wear_options = $wears->getWears();
	
	$categories = new JettonClasses();
	$cat_options = $categories->getClasses();
	
	$groups = new JettonGroups();
	$group_options = $groups->getGroups();

	$types = new JettonTypes();
	$type_options = $types->getTypes();
	
	parent::__construct($options);

	$this->setName('jettontoken');

	$denomination = new Zend_Form_Element_Select('denomination');
	$denomination->setLabel('Denomination: ')
		->setRequired(true)
		->addFilters(array('StripTags', 'StringTrim'))
		->addMultiOptions(array(NULL => 'Choose denomination',
		'Choose denomination' => array(
		'64' => 'Jetton',
		'65' => 'Farthing token',
		'66' => 'Token halfpenny',
		'67' => 'Token penny'
		)
		))
		->setAttrib('class', 'span6 selectpicker show-menu-arrow')
		->setRegisterInArrayValidator(true)
		->addErrorMessage('You must enter a denomination');

	$denomination_qualifier = new Zend_Form_Element_Radio('denomination_qualifier');
	$denomination_qualifier->setLabel('Denomination qualifier: ')
		->addMultiOptions(array('1' => 'Certain','2' => 'Probably','3' => 'Possibly'))
		->setValue(1)
		->addFilters(array('StripTags', 'StringTrim'))
		->setOptions(array('separator' => ''))
		->addValidator('Int');


	$ruler= new Zend_Form_Element_Select('ruler_id');
	$ruler->setLabel('Ruler: ')
		->setRegisterInArrayValidator(false)
		->setAttrib('class', 'span6 selectpicker show-menu-arrow')
		->addMultiOptions(array(NULL => 'Choose an issuer','Available rulers' => $ro))
		->addValidator('InArray', false, array(array_keys($ro)));

	$ruler_qualifier = new Zend_Form_Element_Radio('ruler_qualifier');
	$ruler_qualifier->setLabel('Ruler qualifier: ')
		->addMultiOptions(array('1' => 'Certain','2' => 'Probably','3' => 'Possibly'))
		->addFilters(array('StripTags', 'StringTrim'))
		->setOptions(array('separator' => ''));

	$mint_id= new Zend_Form_Element_Select('mint_id');
	$mint_id->setLabel('Issuing mint: ')
		->setAttrib('class', 'span6 selectpicker show-menu-arrow')
		->setRegisterInArrayValidator(true)
		->addFilters(array('StripTags', 'StringTrim'))
		->addMultiOptions(array(NULL => 'Choose a mint', 'Available mints' => array(
		286 => 'Nuremberg',
		1530 => 'Paris',
		291 => 'Tournai',
		1531 => 'Unknown'
		)));

	$mint_qualifier = new Zend_Form_Element_Radio('mint_qualifier');
	$mint_qualifier->setLabel('Mint qualifier: ')
		->addMultiOptions(array('1' => 'Certain','2' => 'Probably','3' => 'Possibly'))
		->addFilters(array('StripTags', 'StringTrim'))
		->setOptions(array('separator' => ''));

	$degree_of_wear = new Zend_Form_Element_Select('degree_of_wear');
	$degree_of_wear->setLabel('Degree of wear: ')
		->setAttrib('class', 'span6 selectpicker show-menu-arrow')
		->addMultiOptions(array(NULL => 'Choose degree of wear', 'Available options' => $wear_options))
		->addValidator('InArray', false, array(array_keys($wear_options)));

	$obverse_inscription = new Zend_Form_Element_Text('obverse_inscription');
	$obverse_inscription->setLabel('Obverse inscription: ')
		->setAttrib('size',50)
		->addFilters(array('StripTags', 'StringTrim'));

	$reverse_inscription = new Zend_Form_Element_Text('reverse_inscription');
	$reverse_inscription->setLabel('Reverse inscription: ')
		->setAttrib('size',50)
		->addFilters(array('StripTags', 'StringTrim'));

	$obverse_description = new Zend_Form_Element_Textarea('obverse_description');
	$obverse_description->setLabel('Obverse description: ')
		->setAttribs(array('rows' => 3, 'cols' => 80, 'class' => 'span6'))
		->addFilters(array('StripTags', 'StringTrim'));

	$reverse_description = new Zend_Form_Element_Textarea('reverse_description');
	$reverse_description->setLabel('Reverse description: ')
		->addFilters(array('StripTags', 'StringTrim'))
		->setAttribs(array('rows' => 3, 'cols' => 80, 'class' => 'span6'));

	$reverse_mintmark = new Zend_Form_Element_Textarea('reverse_mintmark');
	$reverse_mintmark->setLabel('Reverse mintmark: ')
		->addValidators(array('NotEmpty'))
		->setAttribs(array('rows' => 3, 'cols' => 80, 'class' => 'span6'))
		->addFilters(array('StripTags', 'StringTrim'));


	$die_axis_measurement = new Zend_Form_Element_Select('die_axis_measurement');
	$die_axis_measurement->setLabel('Die axis measurement: ')
		->setAttrib('class', 'span6 selectpicker show-menu-arrow')
		->addMultiOptions(array(NULL => 'Choose die axis', 'Available dies' => $die_options))
		->addFilters(array('StripTags', 'StringTrim'))
		->addValidator('InArray', false, array(array_keys($die_options)));

	$die_axis_certainty = new Zend_Form_Element_Radio('die_axis_certainty');
	$die_axis_certainty->setLabel('Die axis certainty: ')
		->addMultiOptions(array('1' => 'Certain','2' => 'Probably','3' => 'Possibly'))
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->setOptions(array('separator' => ''));
	
	$categoryID = new Zend_Form_Element_Select('jettonClass');
	$categoryID->setLabel('Class of token: ')
		->setAttrib('class', 'span6 selectpicker show-menu-arrow')
		->addValidators(array('NotEmpty','Digits'))
		->addFilters(array('StripTags', 'StringTrim'))
		->addMultiOptions(array(NULL => 'Choose class', 'Available classes' => $cat_options))
		->addValidator('InArray', false, array(array_keys($cat_options)));

	$jettonGroupID = new Zend_Form_Element_Select('jettonGroup');
	$jettonGroupID->setLabel('Group of token: ')
		->setAttrib('class', 'span6 selectpicker show-menu-arrow')
		->addValidators(array('NotEmpty','Digits'))
		->addFilters(array('StripTags', 'StringTrim'))
		->addMultiOptions(array(NULL => 'Choose group', 'Available groups' => $group_options))
		->addValidator('InArray', false, array(array_keys($group_options)));
		
	$jettonTypeID = new Zend_Form_Element_Select('jettonType');
	$jettonTypeID->setLabel('Type of token: ')
		->setAttrib('class', 'span6 selectpicker show-menu-arrow')
		->addValidators(array('NotEmpty','Digits'))
		->addFilters(array('StripTags', 'StringTrim'))
		->addMultiOptions(array(NULL => 'Choose type', 'Available types' => $type_options))
		->addValidator('InArray', false, array(array_keys($type_options)));
	//Submit button
	$submit = new Zend_Form_Element_Submit('submit');

	$this->addElements(array(
	$ruler, $denomination, $degree_of_wear,
	$obverse_description, $obverse_inscription,	$reverse_description,
	$reverse_inscription, $die_axis_measurement, $die_axis_certainty,
	$mint_id, $mint_qualifier, $ruler_qualifier,
	$denomination_qualifier, $categoryID,
	$jettonGroupID, $jettonTypeID, $submit));

	$this->addDisplayGroup(array( 
	'jettonClass', 'jettonGroup', 'jettonType',
	'denomination','denomination_qualifier', 'ruler_id',
	'ruler_qualifier', 'mint_id','mint_qualifier',
	'status', 'status_qualifier', 'degree_of_wear',
	'obverse_description', 'obverse_inscription','reverse_description',
	'reverse_inscription', 'die_axis_measurement','die_axis_certainty',
	), 'details');

	$this->addDisplayGroup(array('submit'),'buttons');

	parent::init();
	}
}