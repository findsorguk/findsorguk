<?php
/** Form for editing and creating Iron Age tribal data
* 
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
*/
class IronAgeTribeForm extends Pas_Form {
	
public function __construct(array $options) {
	
	parent::__construct($options);

	$this->setName('ironagetribes');

	$tribe = new Zend_Form_Element_Text('tribe');
	$tribe->setLabel('Tribe name: ')
	->setRequired(true)
	->setAttrib('size',60)
	->addFilters(array('StripTags', 'StringTrim', 'Purifier'))
	->addErrorMessage('You must enter a name for the tribe.');

	$description = new Pas_Form_Element_CKEditor('description');
	$description->setLabel('Description of the tribe: ')
	->setRequired(false)
	->setAttrib('rows',10)
	->setAttrib('cols',40)
	->setAttrib('Height',400)
	->setAttrib('ToolbarSet','Finds')
	->addFilters(array('StringTrim', 'BasicHtml', 'EmptyParagraph', 'WordChars'));

	$submit = new Zend_Form_Element_Submit('submit');

	$this->addElements(array(
	$tribe,	$description, $submit)
	);

	$this->addDisplayGroup(array('tribe', 'description', 'submit'), 'details');
	
	parent::init();
	}
}