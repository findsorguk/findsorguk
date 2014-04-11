<?php
/** Form for editing and creating materials
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class MaterialForm extends Pas_Form {
	
public function __construct($options = null) {

	parent::__construct($options);

	$this->setName('Material');

	$term = new Zend_Form_Element_Text('term');
	$term->setLabel('Material type name: ')
	->setRequired(true)
	->setAttrib('size',60)
	->addFilter(array('StripTags', 'StringTrim'))
	->addErrorMessage('Please enter a title for this material type');

	$termdesc = new Pas_Form_Element_RTE('termdesc');
	$termdesc->setLabel('Description of material type: ')
	->setRequired(true)
	->setAttrib('rows',10)
	->setAttrib('cols',40)
	->setAttrib('Height',400)
	->setAttrib('ToolbarSet','Finds')
	->addFilters(array('StringTrim', 'BasicHtml', 'EmptyParagraph', 'WordChars'));

	$valid = new Zend_Form_Element_Checkbox('valid');
	$valid->setLabel('Is this term valid?: ')
	->setRequired(true)
	->addValidator('Digits');

	$submit = new Zend_Form_Element_Submit('submit');
;
	
	$this->addElements(array(
	$term, $termdesc, $valid,
	$submit));

	$this->addDisplayGroup(array('term','termdesc','valid'), 'details');
	
	$this->details->setLegend('Material details: ');
	
	$this->addDisplayGroup(array('submit'), 'buttons');
	
	parent::init();
	}
}