<?php
/** Form for editing and adding HER signups
* 
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/

class HerForm extends Pas_Form {
	
public function __construct($options = null) {

parent::__construct($options);
	$this->setName('Her');
	
	$name = new Zend_Form_Element_Text('name');
	$name->setLabel('HER name: ')
	->setRequired(true)
	->setAttrib('size',60)
	->addFilters(array('StripTags', 'StringTrim', 'Purifier'))
	->addErrorMessage('Please enter an HER name');

	$contact_name = new Zend_Form_Element_Text('contact_name');
	$contact_name->setLabel('Contact name: ')
	->setRequired(true)
	->addFilters(array('StripTags', 'StringTrim', 'Purifier'))
	->setAttrib('size',40)
	->addErrorMessage('Please enter a contact name');

	$submit = new Zend_Form_Element_Submit('submit');
	$this->addElements(array($name,$contact_name,$submit));

	$this->addDisplayGroup(array('name','contact_name'), 'details');
	$this->details->setLegend('HER details: ');
	$this->addDisplayGroup(array('submit'), 'buttons');
	parent::init();
	}
	
}