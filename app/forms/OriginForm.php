<?php
/** Form for setting up and editing map grid reference origins.
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/

class OriginForm extends Pas_Form {
	
public function __construct($options = null) {

	parent::__construct($options);
	
	$this->setName('origingridref');

	$term = new Zend_Form_Element_Text('term');
	$term->setLabel('Grid reference origin term: ')
		->setRequired(true)
		->addFilters(array('StripTags','StringTrim', 'Purifier'))
		->setAttrib('size',60)
		->addErrorMessage('Please enter a valid grid reference origin term!');

	$termdesc = new Zend_Form_Element_Textarea('termdesc');
	$termdesc->setLabel('Description of term: ')
		->setRequired(true)
		->addFilters(array('StripTags','StringTrim','WordChars','BasicHtml', 'EmptyParagraph'))
		->setAttrib('rows',10)
		->setAttrib('cols',80)
		->addErrorMessage('You must enter a descriptive term or David Williams will eat you.');

	$valid = new Zend_Form_Element_Checkbox('valid');
	$valid->setLabel('Is this term valid?: ')
		->setRequired(true)
		->addValidator('Int')
		->addErrorMessage('You must set the status of this term');

	$submit = new Zend_Form_Element_Submit('submit');
	
	$hash = new Zend_Form_Element_Hash('csrf');
	$hash->setValue($this->_salt)->setTimeout(4800);
	
	$this->addElements(array(
	$term, 	$termdesc,	$valid,
	$submit, $hash));
	
	$this->addDisplayGroup(array('term','termdesc','valid'), 'details');
	
	$this->details->setLegend('Ascribed culture');
	
	$this->addDisplayGroup(array('submit'), 'buttons');
	
	parent::init();
	}
}