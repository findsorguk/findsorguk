<?php
/**
* Form for adding and editing acronym data.
*
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License

*/

class AcronymForm extends Pas_Form {

public function __construct($options = null) {

	parent::__construct($options);


	$this->setName('acronym');


	$abbreviation = new Zend_Form_Element_Text('abbreviation');
	$abbreviation->setLabel('Abbreviated term: ')
		->setRequired(true)
		->addFilter('StringTrim')
		->addFilter('StripTags')
		->addErrorMessage('Enter a term.')
		->setAttrib('size',20);

	$expanded = new Zend_Form_Element_Text('expanded');
	$expanded->setLabel('Expanded: ')
		->setRequired(true)
		->addFilter('StringTrim')
		->addFilter('StripTags')
		->setAttrib('size',60);


	$valid = new Zend_Form_Element_Checkbox('valid');
	$valid->setLabel('Is this term valid?: ')
		->setRequired(false);

	//Submit button
	$submit = new Zend_Form_Element_Submit('submit');


	$this->addElements(array(
	$abbreviation, $expanded, $valid,
	$submit));

	$this->addDisplayGroup(array('abbreviation','expanded','valid'), 'details');
	$this->details->setLegend('Acronym details: ');
	$this->addDisplayGroup(array('submit'), 'buttons');
	parent::init();
	}

}