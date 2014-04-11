<?php
/** Form for entering data about die axes.
*
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class DieAxisForm extends Pas_Form
{

public function __construct($options = null)
{

	parent::__construct($options);
      
	$this->setName('dieaxis');

	$die_axis_name = new Zend_Form_Element_Text('die_axis_name');
	$die_axis_name->setLabel('Die axis term: ')
	->setRequired(true)
	->setAttrib('size',70)
	->addErrorMessage('Please enter a term.')
	->addFilters(array('StripTags','StringTrim'));

	$valid = new Zend_Form_Element_Checkbox('valid');
	$valid->setLabel('Die axis term is in use: ')
	->setRequired(true)
	->addValidator('Int')
	->addFilters(array('StripTags','StringTrim'));

	//Submit button 
	$submit = new Zend_Form_Element_Submit('submit');

	$hash = new Zend_Form_Element_Hash('csrf');
	$hash->setValue($this->_salt)->setTimeout(60);
	
	$this->addElements(array($die_axis_name, $valid, $submit, $hash));

	$this->addDisplayGroup(array('die_axis_name','valid'), 'details');

	$this->details->setLegend('Die axis details: ');
	
	$this->addDisplayGroup(array('submit'), 'buttons');
	
	parent::init();
	}
}