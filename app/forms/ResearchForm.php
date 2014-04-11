<?php
/** Form for entering and editing research projects
*
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class ResearchForm extends Pas_Form {

public function __construct($options = null) {

	$projecttypes = new ProjectTypes();
	$projectype_list = $projecttypes->getTypes();

	ZendX_JQuery::enableForm($this);

	parent::__construct($options);

	$this->setName('research');


	$investigator = new Zend_Form_Element_Text('investigator');
	$investigator->setLabel('Principal work conducted by: ')
		->setRequired(true)
		->setAttrib('size',60)
		->addFilters(array('StripTags','StringTrim'))
		->addErrorMessage('You must enter a lead for this project.');

	$level = new Zend_Form_Element_Select('level');
	$level->setLabel('Level of research: ')
		->setRequired(true)
		->addFilters(array('StripTags','StringTrim'))
		->setAttrib('class', 'span6 selectpicker show-menu-arrow')
		->addMultiOptions(array(NULL => NULL,'Choose type of research' => $projectype_list))
		->addValidator('inArray', false, array(array_keys($projectype_list)));

	$title = new Zend_Form_Element_Text('title');
	$title->setLabel('Project title: ')
		->setRequired(true)
		->addFilters(array('StripTags','StringTrim', 'Purifier'))
		->setAttrib('size',60)
		->addErrorMessage('Choose title for the project.');

	$description = $this->addElement('RTE', 'description',array(
	'label' => 'Short description of project: '));
	$description = $this->getElement('description')->setRequired(false)
		->setAttribs(array('cols' => 80, 'rows' => 10))
		->addFilters(array('BasicHtml', 'StringTrim', 'EmptyParagraph'));

	$startDate = new ZendX_JQuery_Form_Element_DatePicker('startDate');
	$startDate->setLabel('Start date of project')
		->setAttrib('size',20)
		->setJQueryParam('dateFormat', 'yy-mm-dd')
		->addValidator('Datetime')
		->setRequired(false)
		->addErrorMessage('You must enter a start date for this project');

	$endDate = new ZendX_JQuery_Form_Element_DatePicker('endDate');
	$endDate->setLabel('End date of project')
		->setAttrib('size',20)
		->setJQueryParam('dateFormat', 'yy-mm-dd')
		->setRequired(false)
		->addValidator('Datetime')
		->addErrorMessage('You must enter an end date for this project');

	$valid = new Zend_Form_Element_Checkbox('valid');
	$valid->setLabel('Make public: ')
		->setRequired(true)
		->addFilters(array('StripTags','StringTrim'))
		->addValidator('Digits');

	$submit = new Zend_Form_Element_Submit('submit');

	$hash = new Zend_Form_Element_Hash('csrf');
	$hash->setValue($this->_salt)
		->setTimeout(4800);

	$this->addElements(array(
		$title, $description, $level,
		$startDate, $endDate, $valid,
		$investigator, $submit, $hash
		));

	$this->addDisplayGroup(array(
		'title','investigator','level',
		'description','startDate','endDate',
		'valid',), 'details');
	$this->addDisplayGroup(array('submit'), 'buttons');
	parent::init();
	}
}
