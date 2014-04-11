<?php
/**
* Form for cross referencing rulers to denominations
*
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class AddDenomToRulerForm extends Pas_Form {

public function __construct($options = null){


	parent::__construct($options);

	$this->setName('MintToRuler');

	$denomination_id = new Zend_Form_Element_Select('denomination_id');
	$denomination_id->setLabel('Denomination: ')
	->setRequired(true)
	->addFilters(array('StripTags','StringTrim','StringToLower'))
	->addValidator('Int')
	->setAttribs(array('class'=> 'textInput'))
	->setAttribs(array('class' => 'span4 selectpicker show-menu-arrow'));

	$ruler_id = new Zend_Form_Element_Hidden('ruler_id');
	$ruler_id->addValidator('Int');

	$period_id = new Zend_Form_Element_Hidden('period_id');



	//Submit button
	$submit = new Zend_Form_Element_Submit('submit');
	$submit->setLabel('Add denomination');

	$this->addElements(array($denomination_id,$ruler_id,$period_id,$submit))
	->setLegend('Add an active denomination');
	$this->addDisplayGroup(array('denomination_id'), 'details');


	$this->addDisplayGroup(array('submit'), 'buttons');

	parent::init();
	}

}