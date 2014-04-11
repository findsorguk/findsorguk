<?php
/**
* Form for adding and editing primary activities for people
*
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class ActivityForm extends Pas_Form {

	public function __construct($options = null) {

	parent::__construct($options);

	$this->setName('activity');

	$term = new Zend_Form_Element_Text('term');
	$term->setLabel('Activity title: ')
		->setRequired(true)
		->addFilter('StringTrim')
		->addFilter('StripTags')
		->addErrorMessage('Choose title for the activity.')
		->setAttrib('size',70);

	$termdesc = new Pas_Form_Element_RTE('termdesc');
	$termdesc->setLabel('Activity description: ')
		->setRequired(true)
		->setAttrib('rows',10)
		->setAttrib('cols',40)
		->setAttrib('Height',400)
		->setAttrib('ToolbarSet','Finds')
		->addFilter('StringTrim')
		->addFilter('BasicHtml')
		->addFilter('EmptyParagraph')
		->addFilter('WordChars');


	$valid = new Zend_Form_Element_Checkbox('valid');
	$valid->setLabel('Is this term valid?: ')
		->setRequired(false)
		->addValidator('NotEmpty','boolean');

		//Submit button
	$submit = new Zend_Form_Element_Submit('submit');;

	$this->addElements( array($term, $termdesc, $valid, $submit) );

	$this->addDisplayGroup(array('term','termdesc','valid','submit'), 'details');
	$this->details->setLegend('Primary activity details: ');
    parent::init();
	}

}