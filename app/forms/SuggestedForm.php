<?php
/** Form for suggesting research topics
*
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class SuggestedForm extends Pas_Form {

public function __construct($options = null) {

	$projecttypes = new ProjectTypes();
	$projectype_list = $projecttypes->getTypes();
	$periods = new Periods();
	$period_options = $periods->getPeriodFrom();

parent::__construct($options);

	$this->setName('suggested');


	$level = new Zend_Form_Element_Select('level');
	$level->setLabel('Level of research: ')
		->setRequired(true)
		->addMultiOptions(array('Please choose a level' => NULL,
		'Research levels' => $projectype_list))
		->setAttrib('class', 'span6 selectpicker show-menu-arrow')
		->addValidator('InArray', false, array(array_keys($projectype_list)))
		->addFilters(array('StringTrim', 'StripTags'));

	$period = new Zend_Form_Element_Select('period');
	$period->setLabel('Broad research period: ')
		->setRequired(true)
		->addMultiOptions(array('Please choose a period' => NULL,
		'Periods available' => $period_options))
		->setAttrib('class', 'span6 selectpicker show-menu-arrow')
		->addValidator('InArray', false, array(array_keys($period_options)))
		->addFilters(array('StringTrim', 'StripTags'));

	$title = new Zend_Form_Element_Text('title');
	$title->setLabel('Project title: ')
		->setRequired(true)
		->setAttrib('size',60)
		->addFilters(array('StringTrim', 'StripTags'))
		->addErrorMessage('Choose title for the project.');

	$description = $this->addElement('RTE', 'description',array(
	'label' => 'Short description of project: '));
	$description = $this->getElement('description')
		->setRequired(true)
		->addFilters(array('StringTrim', 'BasicHtml', 'EmptyParagraph', 'WordChars'))
		->setAttribs(array('cols' => 80, 'rows' => 10));

	$valid = new Zend_Form_Element_Checkbox('taken');
	$valid->setLabel('Is the topic taken: ')
		->setRequired(true)
		->addValidator('Int');

	$hash = new Zend_Form_Element_Hash('csrf');
	$hash->setValue($this->_salt)
		->setTimeout(4800);

	$submit = new Zend_Form_Element_Submit('submit');

	$this->addElements(array(
	$title, $level, $period,
	$description, $valid, $submit,
	$hash));

	$this->addDisplayGroup(array('title','level','period','description','taken'), 'details');
	$this->addDisplayGroup(array('submit'),'buttons');
	parent::init();
	}
}