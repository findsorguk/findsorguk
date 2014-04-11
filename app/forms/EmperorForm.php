<?php

/** Form for manipulating emperor data
*
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/

class EmperorForm extends Pas_Form {

public function __construct($options = null) {

$reeces = new Reeces();
$reeces_options = $reeces->getOptions();

$rulers = new Rulers();
$rulers_options = $rulers->getOptions();

$dynasties = new Dynasties();
$dynasties_options = $dynasties->getOptions();


parent::__construct($options);
	$this->setName('EmperorDetails');

	$name = new Zend_Form_Element_Text('name');
	$name->setLabel('Emperor\'s name: ')
	->setRequired(true)
	->addFilters(array('StripTags','StringTrim', 'Purifier'))
	->addErrorMessage('Enter an emperor name!');

	$reeceID = new Zend_Form_Element_Select('reeceID');
	$reeceID->setLabel('Reece period assigned: ')
	->setRequired(false)
	->addFilters(array('StripTags','StringTrim'))
	->setAttrib('class', 'span6 selectpicker show-menu-arrow')
	->addMultiOptions(array(NULL => NULL,'Choose a Reece period' => $reeces_options))
	->addValidator('InArray', false, array(array_keys($reeces_options)))
	->addValidator('Int');

	$pasID = new Zend_Form_Element_Select('pasID');
	$pasID->setLabel('Database ID: ')
	->setRequired(true)
	->addFilters(array('StripTags','StringTrim'))
	->setAttrib('class', 'span6 selectpicker show-menu-arrow')
	->addValidator('InArray', false, array(array_keys($rulers_options)))
	->addMultiOptions(array(NULL => NULL, 'Choose a database id' => $rulers_options))
	->addValidator('Int')
	->addErrorMessage('You must assign the bio to an existing entry');

	$date_from = new Zend_Form_Element_Text('date_from');
	$date_from->setLabel('Issued coins from: ')
	->setRequired(true)
	->addFilters(array('StripTags','StringTrim'))
	->addValidator('Int')
	->addErrorMessage('You must enter a date for the start of reign');

	$date_to = new Zend_Form_Element_Text('date_to');
	$date_to->setLabel('Issued coins until: ')
	->setRequired(true)
	->addFilters(array('StripTags','StringTrim'))
	->addValidator('Int')
	->addErrorMessage('You must enter a date for the end of reign');

	$biography = new Pas_Form_Element_RTE('biography');
	$biography->setLabel('Biography: ')
	->setRequired(true)
	->addFilters(array('StringTrim','WordChars','BasicHtml','EmptyParagraph'))
	->setAttribs(array('rows' => 10, 'cols' => 40, 'Height' => 400))
	->setAttrib('ToolbarSet','Finds')
	->addErrorMessage('You must enter a biography');

	$dynasty = new Zend_Form_Element_Select('dynasty');
	$dynasty->setLabel('Dynastic grouping: ')
	->setRequired(true)
	->setAttrib('class', 'span6 selectpicker show-menu-arrow')
	->addFilters(array('StripTags','StringTrim'))
	->addValidator('InArray', false, array(array_keys($dynasties_options)))
	->addMultiOptions(array(NULL => NULL, 'Choose a dynasty' => $dynasties_options))
	->addErrorMessage('You must select a dynastic grouping');
	
	$hash = new Zend_Form_Element_Hash('csrf');
	$hash->setValue($this->_salt)->setTimeout(4800);

	$submit = new Zend_Form_Element_Submit('submit');


	$this->addElements(array(
	$name,
	$reeceID,
	$pasID,
	$date_from,
	$date_to,
	$biography,
	$dynasty,
	$submit,
	$hash));

	$this->addDisplayGroup(array(
            'name','reeceID','pasID',
            'date_from','date_to','biography',
            'dynasty','submit'), 'details');


	parent::init();
	}
}