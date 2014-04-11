<?php
/** Form for entering data about decorative methods
*
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class DecMethodsForm extends Pas_Form
{
public function __construct($options = null)
{

parent::__construct($options);

	$this->setName('Decmethods');


	$term = new Zend_Form_Element_Text('term');
	$term->setLabel('Decoration style term: ')
	->setRequired(true)
	->addFilters(array('StripTags','StringTrim'))
	->addErrorMessage('Please enter a valid title for this decorative method!');

	$termdesc = new Pas_Form_Element_RTE('termdesc');
	$termdesc->setLabel('Description of decoration style: ')
	->setRequired(false)
	->setAttrib('rows',10)
	->setAttrib('cols',40)
	->setAttrib('Height',400)
	->setAttrib('ToolbarSet','Finds')
	->addFilters(array('BasicHtml','EmptyParagraph'))
	->addFilter('WordChars');

	$valid = new Zend_Form_Element_Checkbox('valid');
	$valid->setLabel('Is this term valid?: ')
	->setRequired(true);

	$hash = new Zend_Form_Element_Hash('csrf');
	$hash->setValue($this->_salt)->setTimeout(4800);
	
	$submit = new Zend_Form_Element_Submit('submit');


	$this->addElements(array($term, $termdesc, $valid, $submit, $hash));

	$this->addDisplayGroup(array('term','termdesc','valid'), 'details');
	$this->addDisplayGroup(array('submit'), 'buttons');

	$this->details->setLegend('Decoration method details: ');
        parent::init();
	}
}