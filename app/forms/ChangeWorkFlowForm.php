<?php
/** Form for submitting an error
*
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class ChangeWorkFlowForm extends Pas_Form {

	public function __construct($options = null) {
	
	parent::__construct($options);

	$this->setName('workflowChange');
	$wfstage = new Zend_Form_Element_Radio('secwfstage');
	$wfstage->setRequired(true)
	->addMultiOptions(array(
		'1' => 'Quarantine',
		'2' => 'Review',
		'4' => 'Validation',
		'3' => 'Published'))
	->addFilters(array('StripTags', 'StringTrim'));
	
	$finder = new Zend_Form_Element_Checkbox('finder');
	$finder->setLabel('Inform finder of workflow change?: ');
	$finder->setUncheckedValue(NULL);
	

	$content = new Pas_Form_Element_RTE('content');
	$content->setLabel('Enter your comment: ')
	->addFilter('StringTrim')
	->setAttrib('Height',400)
	->setAttrib('ToolbarSet','Basic')
	->addFilters(array('StringTrim','WordChars','HtmlBody','EmptyParagraph'))
	->addErrorMessage('Please enter something in the comments box!');
	
	$submit = new Zend_Form_Element_Submit('submit');
	$submit->setLabel('Change status');

	$this->addElements(array($wfstage, $finder, $content, $submit));

	$this->addDisplayGroup(array('secwfstage','finder', 'content', ), 'details');
	
	$this->addDisplayGroup(array('submit'), 'buttons');
	
	parent::init();
	}
}