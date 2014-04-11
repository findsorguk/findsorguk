<?php
/** Form for manipulating Greek and Roman coin data
*
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class GreekAndRomanCoinForm extends Pas_Form {

public function __construct($options = null) {

	// Construct the select menu data
	$rulers = new Rulers();
	$ruler_options = $rulers->getRulersGreek();

	$denominations = new Denominations();
	$denomination_options = $denominations->getDenomsGreek();

	$mints = new Mints();
	$mint_options = $mints->getMintsGreek();

	$statuses = new Statuses();
	$status_options = $statuses->getCoinStatus();

	$dies = new Dieaxes;
	$die_options = $dies->getAxes();

	$wears = new Weartypes;
	$wear_options = $wears->getWears();

	parent::__construct($options);

	$this->setName('greekcoin');

	$denomination = new Zend_Form_Element_Select('denomination');
	$denomination->setLabel('Denomination: ')
	->addMultiOptions(array(NULL => 'Choose a denomination',
	'Valid denominations' => $denomination_options))
	->addFilters(array('StringTrim','StripTags'))
	->setAttrib('class', 'span6 selectpicker show-menu-arrow')
	->addValidator('InArray', false, array(array_keys($denomination_options)));

	$denomination_qualifier = new Zend_Form_Element_Radio('denomination_qualifier');
	$denomination_qualifier->setLabel('Denomination qualifier: ')
	->addMultiOptions(array('1' => 'Certain','2' => 'Probably','3' => 'Possibly'))
	->setValue(1)
	->addFilters(array('StringTrim','StripTags'))
	->setOptions(array('separator' => ''));

	$ruler= new Zend_Form_Element_Select('ruler_id');
	$ruler->setLabel('Ruler: ')
	->addValidators(array('NotEmpty','Int'))
	->addFilters(array('StringTrim','StripTags'))
	->setAttrib('class', 'span6 selectpicker show-menu-arrow')
	->addMultiOptions(array(NULL => 'Choose a ruler','Valid coin issuers' => $ruler_options))
	->addValidator('InArray', false, array(array_keys($ruler_options)));

	$ruler_qualifier = new Zend_Form_Element_Radio('ruler_qualifier');
	$ruler_qualifier->setLabel('Issuer qualifier: ')
	->addMultiOptions(array('1' => 'Certain','2' => 'Probably','3' => 'Possibly'))
	->addFilters(array('StringTrim','StripTags'))
	->setOptions(array('separator' => ''));

	$mint_ID= new Zend_Form_Element_Select('mint_id');
	$mint_ID->setLabel('Issuing mint: ')
	->addValidators(array('NotEmpty','Int'))
	->setAttrib('class', 'span6 selectpicker show-menu-arrow')
	->addFilters(array('StringTrim','StripTags'))
	->addMultiOptions(array(NULL => 'Choose denomination', 'Available options' => $mint_options))
	->addValidator('InArray', false, array(array_keys($mint_options)));

	$mint_qualifier = new Zend_Form_Element_Radio('mint_qualifier');
	$mint_qualifier->setLabel('Mint qualifier: ')
	->addMultiOptions(array('1' => 'Certain','2' => 'Probably','3' => 'Possibly'))
	->addFilters(array('StringTrim','StripTags'))
	->setOptions(array('separator' => ''));

	$status = new Zend_Form_Element_Select('status');
	$status->setLabel('Status: ')
	->setValue(1)
	->setAttrib('class', 'span6 selectpicker show-menu-arrow')
	->addFilters(array('StringTrim','StripTags'))
	->addMultiOptions(array(NULL => 'Choose coin status', 'Available options' => $status_options))
	->addValidator('InArray', false, array(array_keys($status_options)));

	$status_qualifier = new Zend_Form_Element_Radio('status_qualifier');
	$status_qualifier->setLabel('Status qualifier: ')
	->addMultiOptions(array('1' => 'Certain','2' => 'Probably','3' => 'Possibly'))
	->setValue(1)
	->addFilters(array('StringTrim','StripTags'))
	->setOptions(array('separator' => ''));

	$degree_of_wear = new Zend_Form_Element_Select('degree_of_wear');
	$degree_of_wear->setLabel('Degree of wear: ')
	->setAttrib('class', 'span6 selectpicker show-menu-arrow')
	->addMultiOptions(array(NULL => 'Choose degree of wear', 'Available options' => $wear_options))
	->addValidator('InArray', false, array(array_keys($wear_options)))
	->addFilters(array('StringTrim','StripTags'));

	$obverse_inscription = new Zend_Form_Element_Text('obverse_inscription');
	$obverse_inscription->setLabel('Obverse inscription: ')
	->setAttrib('class', 'span6')
	->addFilters(array('StringTrim','StripTags','BasicHtml','EmptyParagraph'));

	$reverse_inscription = new Zend_Form_Element_Text('reverse_inscription');
	$reverse_inscription->setLabel('Reverse inscription: ')
	->setAttrib('class','span6')
	->addFilters(array('StringTrim','StripTags','BasicHtml','EmptyParagraph'));

	$obverse_description = new Zend_Form_Element_Textarea('obverse_description');
	$obverse_description->setLabel('Obverse description: ')
	->addValidators(array('NotEmpty'))
	->setAttrib('rows',8)
	->setAttrib('cols',60)
	->setAttrib('class', 'span6')
	->addFilters(array('StringTrim','StripTags','BasicHtml','EmptyParagraph'));

	$reverse_description = new Zend_Form_Element_Textarea('reverse_description');
	$reverse_description->setLabel('Reverse description: ')
	->addValidators(array('NotEmpty'))
	->setAttrib('rows',8)
	->setAttrib('cols',60)
	->setAttrib('class', 'span6')
	->addFilters(array('StringTrim','StripTags','BasicHtml','EmptyParagraph'));

	$die_axis_measurement = new Zend_Form_Element_Select('die_axis_measurement');
	$die_axis_measurement->setLabel('Die axis measurement: ')
	->setAttrib('class', 'span6 selectpicker show-menu-arrow')
	->addMultiOptions(array(NULL => 'Choose die axis', 'Available options' => $die_options))
	->addValidator('InArray', false, array(array_keys($die_options)))
	->addFilters(array('StringTrim','StripTags'));

	$die_axis_certainty = new Zend_Form_Element_Radio('die_axis_certainty');
	$die_axis_certainty->setLabel('Die axis certainty: ')
	->addMultiOptions(array('1' => 'Certain','2' => 'Probably','3' => 'Possibly'))
	->addFilters(array('StringTrim','StripTags'))
	->setOptions(array('separator' => ''));

	$submit = new Zend_Form_Element_Submit('submit');

	$this->addElements(array(
	$ruler,	$denomination, $mint_ID,
	$status, $degree_of_wear, $obverse_description,
	$obverse_inscription, $reverse_description,	$reverse_inscription,
	$die_axis_measurement, $die_axis_certainty, $mint_qualifier,
	$ruler_qualifier, $denomination_qualifier, $status_qualifier,
	$submit));

	$this->addDisplayGroup(array(
	'denomination','denomination_qualifier','ruler_id',
	'ruler_qualifier', 'mint_id', 'mint_qualifier',
	'status', 'status_qualifier', 'degree_of_wear','obverse_description',
	'obverse_inscription', 'reverse_description', 'reverse_inscription',
	'die_axis_measurement', 'die_axis_certainty'), 'details');
	$this->addDisplayGroup(array('submit'),'buttons');
	parent::init();
	}
}