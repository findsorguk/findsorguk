<?php
/** Form for setting up and editing preservation states
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class PreservationsForm extends Pas_Form {
	
public function __construct($options = null) {

	parent::__construct($options);
	

	$this->setName('preservations');

	$term = new Zend_Form_Element_Text('term');
	$term->setLabel('Title for preservation state: ')
		->setRequired(true)
		->addFilters(array('StripTags','StringTrim'))
		->addValidator('Alpha',false,array('allowWhiteSpace' => true))
		->addErrorMessage('Please enter a valid title for the state!');

	$termdesc = new Pas_Form_Element_RTE('termdesc');
	$termdesc->setLabel('Description of preservation state: ')
		->setAttrib('rows',10)
		->setAttrib('cols',40)
		->setAttrib('Height',400)
		->setAttrib('ToolbarSet','Finds')
		->addFilters(array('StringTrim', 'BasicHtml', 'EmptyParagraph', 'WordChars'));
	
	$valid = new Zend_Form_Element_Checkbox('valid');
	$valid->setLabel('Is this term valid?: ')
		->setRequired(true)
		->addFilters(array('StripTags','StringTrim'))
		->addValidator('Digits');
	
	$submit = new Zend_Form_Element_Submit('submit');
	$submit->setAttrib('id', 'submitbutton')
		->setAttrib('class', 'large')
		->removeDecorator('DtDdWrapper')
		->removeDecorator('HtmlTag')
		->removeDecorator('Label');
	
	$this->addElements(array(
	$term, $termdesc, $valid,
	$submit));
	
	$this->addDisplayGroup(array('term','termdesc','valid'), 'details');
	$this->details->setLegend('Preservation state details: ');
	
	$this->addDisplayGroup(array('submit'), 'buttons');
	parent::init();  
	}
}