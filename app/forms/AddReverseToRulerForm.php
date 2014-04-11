<?php
/**
* Form for adding reverse types to rulers
*
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class AddReverseToRulerForm extends Pas_Form
{
public function __construct($options = null) {

	parent::__construct($options);

	$this->setName('MintToRuler');

	$reverseID = new Zend_Form_Element_Select('reverseID');
	$reverseID->setLabel('Reverse type: ')
	->setRequired(true)
	->addFilters(array('StripTags','StringTrim','StringToLower'))
	->setAttribs(array('class' => 'span8 selectpicker show-menu-arrow'));

	$rulerID = new Zend_Form_Element_Hidden('rulerID');
	$rulerID->addValidator('Int');


	//Submit button
	$submit = new Zend_Form_Element_Submit('submit');
	$submit->setLabel('Add a reverse type for this ruler');

	$this->addElements(array($reverseID, $rulerID, $submit));
	$this->addDisplayGroup(array('reverseID'), 'details');

	$this->details->setLegend('Add an active Mint');

	$this->addDisplayGroup(array('submit'), 'buttons');

	$this->details->setLegend('Add an active reverse type');

	parent::init();
	}

}