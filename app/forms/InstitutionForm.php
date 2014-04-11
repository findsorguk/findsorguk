<?php
/** Form for creating institutions
*
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class InstitutionForm extends Pas_Form {

public function __construct($options = null) {

	parent::__construct($options);

	$this->setName('institution');

	$institution = new Zend_Form_Element_Text('institution');
	$institution->setLabel('Recording institution title: ')
	->setRequired(true)
	->setAttrib('size',60)
	->addFilters(array('StripTags','StringTrim'))
	->addErrorMessage('Choose title for the role.');

	$description = new Pas_Form_Element_RTE('description');
	$description->setLabel('Role description: ')
	->setRequired(true)
	->setAttrib('rows',10)
	->setAttrib('cols',40)
	->setAttrib('Height',400)
	->setAttrib('ToolbarSet','Finds')
	->addFilters(array('StringTrim', 'BasicHtml', 'EmptyParagraph', 'WordChars'));

	//Submit button
	$submit = new Zend_Form_Element_Submit('submit');
	$this->addElements(array(
	$institution, $description, $submit));

	$this->addDisplayGroup(array('institution','description'), 'details');

	$this->addDisplayGroup(array('submit'), 'buttons');

parent::init();
	}
}