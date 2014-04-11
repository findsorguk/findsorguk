<?php
/** Form for editing and creating manufacturing methodologies
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class ManufacturesForm extends Pas_Form {
	
public function __construct($options = null) {

	parent::__construct($options);
	
	$this->setName('Manufactures');

	$term = new Zend_Form_Element_Text('term');
	$term->setLabel('Method of manufacture term: ')
	->setRequired(true)
	->addFilters(array('StripTags', 'StringTrim'))
	->addErrorMessage('Please enter a valid title for this method!')
;

	$termdesc = new Pas_Form_Element_RTE('termdesc');
	$termdesc->setLabel('Description of manufacture method: ')
	->setAttrib('rows',10)
	->setAttrib('cols',40)
	->setAttrib('Height',400)
	->setAttrib('ToolbarSet','Finds')
	->addFilters(array('StringTrim', 'BasicHtml', 'EmptyParagraph', 'WordChars'));

	$valid = new Zend_Form_Element_Checkbox('valid');
	$valid->setLabel('Is this term valid?: ')
	->setRequired(true)
	->addFilters(array('StripTags', 'StringTrim'))
	->addValidator('Digits');

	$submit = new Zend_Form_Element_Submit('submit');

	$this->addElements(array(
	$term, 	$termdesc, 	$valid,
	$submit)
	);

	$this->addDisplayGroup(array('term','termdesc','valid'), 'details');

	$this->details->setLegend('Method of manufacture details: ');
	
	$this->addDisplayGroup(array('submit'), 'buttons');
	
	parent::init();
	}
}
