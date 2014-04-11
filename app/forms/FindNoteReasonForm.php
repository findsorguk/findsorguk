<?php
/** Form for manipulating find of note reasons
*
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/

class FindNoteReasonForm extends Pas_Form {

	public function __construct($options = null) {

	parent::__construct($options);

	$this->setName('FindNoteReason');

	$term = new Zend_Form_Element_Text('term');
	$term->setLabel('Title for reason: ')
	->setRequired(true)
	->setAttrib('size',60)
	->addFilters(array('BasicHtml','EmptyParagraph', 'StringTrim'))
	->addErrorMessage('Please enter a valid title for the term!');

	$termdesc = new Zend_Form_Element_Textarea('termdesc');
	$termdesc->setLabel('Description of reason: ')
	->addFilters(array('BasicHtml','EmptyParagraph', 'StringTrim'))
	->setAttrib('rows',10)
	->setAttrib('cols',80);

	$valid = new Zend_Form_Element_Checkbox('valid');
	$valid->setLabel('Is this term valid?: ')
	->setRequired(true)
	->addValidator('NotEmpty','Digits');

	$submit = new Zend_Form_Element_Submit('submit');

	$this->addElements(array(
	$term,	$termdesc,	$valid,
	$submit));

	$this->addDisplayGroup(array('term','termdesc','valid'), 'details');
	$this->details->setLegend('Find of note reasoning details: ');
	$this->addDisplayGroup(array('submit'), 'buttons');

	parent::init();
	}
}