<?php
/**
* Form for adding a type of coin to a specific ruler
*
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class AddTypeToRulerForm extends Pas_Form
{
public function __construct($options = null)
{
parent::__construct($options);

	$this->setName('TypeToRuler');

	$type = new Zend_Form_Element_Select('type');
	$type->setLabel('Medieval coin type: ')
	->setRequired(true)
	->addFilters(array('StripTags','StringTrim'))
	->setAttribs(array('class' => 'span6 selectpicker show-menu-arrow'));

	$ruler_id = new Zend_Form_Element_Hidden('ruler_id');
	$ruler_id->setRequired(true)
	->addValidator('Int');


	//Submit button
	$submit = new Zend_Form_Element_Submit('submit');

	$this->addElements(array($type, $ruler_id, $submit))
	->setLegend('Add a new type')
	->setMethod('post');

	parent::init();
	}

}