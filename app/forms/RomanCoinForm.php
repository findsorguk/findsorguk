<?php
/** Form for entering and editing Roman coin data
*
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class RomanCoinForm extends Pas_Form
{
public function __construct($options = null)
{
	// Construct the select menu data
	$denominations = new Denominations();
	$denomination_options = $denominations->getOptionsRoman();

	$statuses = new Statuses();
	$status_options = $statuses->getCoinStatus();

	$dies = new Dieaxes;
	$die_options = $dies->getAxes();

	$wears = new Weartypes;
	$wear_options = $wears->getWears();

	$rulers = new Rulers();
	$ro = $rulers->getRomanRulers();

	$mints = new Mints();
	$mo = $mints->getRomanMints();

	$reeces = new Reeces();
	$reece = $reeces->getOptions();

	$money = new Moneyers();
	$moneyers = $money->getRepublicMoneyers();

	$statuses = new Statuses();
	$statuses = $statuses->getCoinStatus();

	$reverses = new Revtypes();
	$reverses = $reverses->getRevTypes();


	parent::__construct($options);


	$this->setName('romancoin');

	$denomination = new Zend_Form_Element_Select('denomination');
	$denomination->setLabel('Denomination: ')
		->setRequired(true)
		->setAttrib('class', 'span6 selectpicker show-menu-arrow')
		->addMultiOptions(array(NULL => 'Choose denomination', 'Valid denominations' => $denomination_options))
		->addValidator('InArray', false, array(array_keys($denomination_options)))
		->addFilters(array('StripTags', 'StringTrim'))
		->addErrorMessage('You must enter a denomination');

	$denomination_qualifier = new Zend_Form_Element_Radio('denomination_qualifier');
	$denomination_qualifier->setLabel('Denomination qualifier: ')
		->addMultiOptions(array('1' => 'Certain','2' => 'Probably','3' => 'Possibly'))
		->setValue(1)
		->addFilters(array('StripTags', 'StringTrim'))
		->setOptions(array('separator' => ''));;


	$ruler= new Zend_Form_Element_Select('ruler_id');
	$ruler->setLabel('Ruler: ')
		->setAttrib('class', 'span6 selectpicker show-menu-arrow')
		->addMultiOptions(array(NULL => 'Choose a ruler', 'Valid rulers' => $ro))
		->addValidator('InArray', false, array(array_keys($ro)));

	$ruler_qualifier = new Zend_Form_Element_Radio('ruler_qualifier');
	$ruler_qualifier->setLabel('Ruler qualifier: ')
		->addMultiOptions(array('1' => 'Certain','2' => 'Probably','3' => 'Possibly'))
		->addFilters(array('StripTags', 'StringTrim'))
		->setOptions(array('separator' => ''));

	$mint_id= new Zend_Form_Element_Select('mint_id');
	$mint_id->setLabel('Issuing mint: ')
		->setAttrib('class', 'span6 selectpicker show-menu-arrow')
		->addMultiOptions(array(NULL => 'Choose a mint', 'Valid mints' => $mo))
		->addValidator('InArray', false, array(array_keys($mo)))
		->addFilters(array('StripTags', 'StringTrim'));

	$mint_qualifier = new Zend_Form_Element_Radio('mint_qualifier');
	$mint_qualifier->setLabel('Mint qualifier: ')
		->addMultiOptions(array('1' => 'Certain','2' => 'Probably','3' => 'Possibly'))
		->addFilters(array('StripTags', 'StringTrim'))
		->setOptions(array('separator' => ''));

	$reeceID = new Zend_Form_Element_Select('reeceID');
	$reeceID->setLabel('Reece period: ')
		->setAttrib('class', 'span6 selectpicker show-menu-arrow')
		->addMultiOptions(array(NULL => 'Choose a Reece period', 'Valid periods' => $reece))
		->addValidator('InArray', false, array(array_keys($reece)))
		->addFilters(array('StripTags', 'StringTrim'));

	$moneyer = new Zend_Form_Element_Select('moneyer');
	$moneyer->setLabel('Republican Moneyer: ')
		->setAttrib('class', 'span6 selectpicker show-menu-arrow')
		->addMultiOptions(array(NULL => 'Choose a moneyer', 'Valid moneyers' => $moneyers))
		->addValidator('InArray', false, array(array_keys($moneyers)))
		->addFilters(array('StripTags', 'StringTrim'));

	$moneyer_qualifier = new Zend_Form_Element_Radio('moneyer_qualifier');
	$moneyer_qualifier->setLabel('Republican Moneyer qualifier: ')
		->addMultiOptions(array('1' => 'Certain','2' => 'Probably','3' => 'Possibly'))
		->addFilters(array('StripTags', 'StringTrim'))
		->setOptions(array('separator' => ''));

	$revtypeID = new Zend_Form_Element_Select('revtypeID');
	$revtypeID->setLabel('Reverse type: ')
		->setAttrib('class', 'span6 selectpicker show-menu-arrow')
		->addMultiOptions(array(NULL => 'Choose a reverse type', 'Valid reverses' => $reverses))
		->addFilters(array('StripTags', 'StringTrim'))
		->addValidator('InArray', false, array(array_keys($reverses)));

	$revTypeID_qualifier = new Zend_Form_Element_Radio('revTypeID_qualifier');
	$revTypeID_qualifier->setLabel('Reverse type qualifier: ')
		->addMultiOptions(array('1' => 'Certain','2' => 'Probably','3' => 'Possibly'))
		->addFilters(array('StripTags', 'StringTrim'))
		->setOptions(array('separator' => ''));

	$status = new Zend_Form_Element_Select('status');
	$status->setLabel('Status: ')
		->setValue(1)
		->setAttrib('class', 'span6 selectpicker show-menu-arrow')
		->addFilters(array('StripTags', 'StringTrim'))
		->addMultiOptions(array(NULL => 'Choose coin status', 'Valid options' => $statuses))
		->addValidator('InArray', false, array(array_keys($statuses)))		;

	$status_qualifier = new Zend_Form_Element_Radio('status_qualifier');
	$status_qualifier->setLabel('Status qualifier: ')
		->addMultiOptions(array('1' => 'Certain','2' => 'Probably','3' => 'Possibly'))
		->setValue(1)
		->addFilters(array('StripTags', 'StringTrim'))
		->setOptions(array('separator' => ''));

	$degree_of_wear = new Zend_Form_Element_Select('degree_of_wear');
	$degree_of_wear->setLabel('Degree of wear: ')
		->setAttrib('class', 'span6 selectpicker show-menu-arrow')
		->addMultiOptions(array(NULL => 'Choose coin wear status', 'Valid options' => $wear_options))
		->addValidator('InArray', false, array(array_keys($wear_options)))
		->addFilters(array('StripTags', 'StringTrim'));

	$obverse_inscription = new Zend_Form_Element_Text('obverse_inscription');
	$obverse_inscription->setLabel('Obverse inscription: ')
		->setAttrib('class','span6')
		->addFilters(array('StripTags', 'StringTrim'));

	$reverse_inscription = new Zend_Form_Element_Text('reverse_inscription');
	$reverse_inscription->setLabel('Reverse inscription: ')
		->setAttrib('class','span6')
		->addFilters(array('StripTags', 'StringTrim'));

	$obverse_description = new Zend_Form_Element_Textarea('obverse_description');
	$obverse_description->setLabel('Obverse description: ')
		->setAttribs(array('rows' => 3, 'cols' => 80, 'class' => 'span6'))
		->addFilters(array('StripTags', 'StringTrim'));

	$reverse_description = new Zend_Form_Element_Textarea('reverse_description');
	$reverse_description->setLabel('Reverse description: ')
		->setAttribs(array('rows' => 3, 'cols' => 80, 'class' => 'span6'))
		->addFilters(array('StripTags', 'StringTrim'));

	$reverse_mintmark = new Zend_Form_Element_Textarea('reverse_mintmark');
	$reverse_mintmark->setLabel('Reverse mintmark: ')
		->setAttribs(array('rows' => 3, 'cols' => 80, 'class' => 'span6'))
		->addFilters(array('StripTags', 'StringTrim'));


	$die_axis_measurement = new Zend_Form_Element_Select('die_axis_measurement');
	$die_axis_measurement->setLabel('Die axis measurement: ')
		->setAttrib('class', 'span6 selectpicker show-menu-arrow')
		->addMultiOptions(array(NULL => NULL,'Choose die axis' => $die_options))
		->addValidator('InArray', false, array(array_keys($die_options)))
		->addFilters(array('StripTags', 'StringTrim'));

	$die_axis_certainty = new Zend_Form_Element_Radio('die_axis_certainty');
	$die_axis_certainty->setLabel('Die axis certainty: ')
		->addMultiOptions(array('1' => 'Certain','2' => 'Probably','3' => 'Possibly'))
		->addFilters(array('StripTags', 'StringTrim'))
		->setOptions(array('separator' => ''));


	//Submit button
	$submit = new Zend_Form_Element_Submit('submit');

//	$action = Zend_Controller_Front::getInstance()->getRequest()->getActionName();
//	if($action == 'edit') {
//		$rulers = new Rulers();
//		$ruler_options = $rulers->getRomanRulers();
//		$ruler->addMultiOptions(array(NULL => NULL,'Choose ruler' => $ruler_options))
//			->addValidator('InArray', false, array(array_keys($ruler_options)));
//		$mints = new Mints();
//		$mint_options = $mints->getRomanMints();
//		$mint_id->addMultiOptions(array(NULL => NULL,'Choose Roman mint' => $mint_options))
//			->addValidator('InArray', false, array(array_keys($mint_options)));
//		$reeces = new Reeces();
//		$reece_options = $reeces->getReeces();
//		$reeceID->addMultiOptions(array(NULL => NULL,'Choose Reece period' => $reece_options))
//			->addValidator('InArray', false, array(array_keys($reece_options)));
//	}

	$this->addElements(array(
	$ruler, $denomination, $moneyer,
	$mint_id, $reeceID, $status,
	$revtypeID, $degree_of_wear, $obverse_description,
	$obverse_inscription, $reverse_description, $reverse_inscription,
	$die_axis_measurement, $die_axis_certainty, $mint_qualifier,
	$ruler_qualifier, $denomination_qualifier, $status_qualifier,
	$revTypeID_qualifier, $reverse_mintmark,
	$submit));

	$this->addDisplayGroup(array(
	'denomination','denomination_qualifier','ruler_id',
	'ruler_qualifier','mint_id','mint_qualifier',
	'reeceID','revtypeID','revTypeID_qualifier',
	'moneyer','status',
	'status_qualifier','degree_of_wear','obverse_description',
	'obverse_inscription','reverse_description','reverse_inscription',
	'reverse_mintmark','die_axis_measurement','die_axis_certainty'
	), 'details');
	$this->addDisplayGroup(array('submit'),'buttons');
	parent::init();
	}
}