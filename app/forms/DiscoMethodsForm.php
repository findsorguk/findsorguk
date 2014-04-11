<?php

/** Form for entering data about discovery methods
*
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/

class DiscoMethodsForm extends Pas_Form
{
public function __construct($options = null)
{
parent::__construct($options);
	$this->setName('discoverymethods');

	$method = new Zend_Form_Element_Text('method');
	$method->setLabel('Discovery method term: ')
	->setRequired(true)
	->addFilters(array('StripTags','StringTrim'))
	->addValidator('NotEmpty')
	->addErrorMessage('You must enter a valid term');

	$termdesc = new Pas_Form_Element_RTE('termdesc');
	$termdesc->setLabel('Description of method: ')
	->setRequired(true)
	->setAttrib('rows',10)
	->setAttrib('cols',40)
	->setAttrib('Height',400)
	->setAttrib('ToolbarSet','Finds')
	->addFilters(array('BasicHtml', 'EmptyParagraph', 'WordChars'))
	->addErrorMessage('You must enter a description for this term');

	$valid = new Zend_Form_Element_Checkbox('valid');
	$valid->setLabel('Is this term valid?: ')
	->setRequired(true)
	->addFilters(array('StripTags','StringTrim'))
	->addErrorMessage('You must set a status for this term');

	$hash = new Zend_Form_Element_Hash('csrf');
	$hash->setValue($this->_salt)->setTimeout(60);
	
	//Submit button 
	$submit = new Zend_Form_Element_Submit('submit');

	$this->addElements(array($method, $termdesc, $valid, $submit, $hash));

	$this->addDisplayGroup(array('method','termdesc','valid'), 'details');
	
	$this->details->setLegend('Discovery methods');
	
	$this->addDisplayGroup(array('submit'), 'buttons');
	
	parent::init();
	}

}