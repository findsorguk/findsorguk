<?php
/**
* Form for adding mints to rulers
*
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class AddMintToRulerForm extends Pas_Form
{
public function __construct($options = null) {

	parent::__construct($options);
	$this->setName('MintToRuler');

	$mint = new Zend_Form_Element_Select('mint_id');
	$mint->setLabel('Active mint: ')
	->setRequired(true)
	->addValidator('Int')
	->addFilters(array('StripTags','StringTrim','StringToLower'))
	->setAttribs(array('class' => 'span6 selectpicker show-menu-arrow'));

	$ruler_id = new Zend_Form_Element_Hidden('ruler_id');
	$ruler_id ->removeDecorator('label')
	->addValidator('Int')
	->addFilter('StringTrim');



	$submit = new Zend_Form_Element_Submit('submit');

	$this->addElements(array($mint, $ruler_id, $submit));

	$this->addDisplayGroup(array('mint_id'), 'details');

	$this->details->setLegend('Add an active Mint');

	$this->addDisplayGroup(array('submit'), 'buttons');

	parent::init();
	}

}