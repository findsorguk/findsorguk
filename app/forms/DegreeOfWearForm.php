<?php
/** Form for entering data about degrees of wear
*
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/

class DegreeOfWearForm extends Pas_Form {
	
	public function __construct($options = null){

	parent::__construct($options);
	$this->setName('degreeofwear');

	$term = new Zend_Form_Element_Text('term');
	$term->setLabel('Decoration style term: ')
	->setRequired(true)
	->setAttrib('size',70)
	->addFilters(array('StripTags','StringTrim'))
	->addErrorMessage('Please enter a valid title for this surface treatment');

	$termdesc = new Pas_Form_Element_RTE('termdesc');
	$termdesc->setLabel('Description of decoration style: ')
	->setRequired(true)
	->setAttrib('rows',10)
	->setAttrib('cols',40)
	->setAttrib('Height',400)
	->setAttrib('ToolbarSet','Finds')
	->addFilters(array('BasicHtml', 'EmptyParagraph', 'WordChars'))
	->addErrorMessage('You must enter a description for this surface treatment');

	$valid = new Zend_Form_Element_Checkbox('valid');
	$valid->setLabel('Period is currently in use: ')
	->setRequired(true)
	->addFilters(array('StripTags','StringTrim'))
	->addErrorMessage('You must set a status for this treatment term');

	$submit = new Zend_Form_Element_Submit('submit');
	
	$hash = new Zend_Form_Element_Hash('csrf');
	$hash->setValue($this->_salt)->setTimeout(60);

	$this->addElements(array(
	$term, $termdesc, $valid,
	$submit, $hash));

	$this->addDisplayGroup(array('term','termdesc','valid'), 'details');
	
	$this->details->setLegend('Surface treatment details: ');
	
	$this->addDisplayGroup(array('submit'), 'buttons');
	
	parent::init();
	}
}