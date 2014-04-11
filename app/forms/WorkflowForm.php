<?php
/** Form for editing workflow stages
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/

class WorkflowForm extends Pas_Form {

	public function __construct($options = null) {

	parent::__construct($options);

	$this->setName('workflow');

	$workflowstage = new Zend_Form_Element_Text('workflowstage');
	$workflowstage->setLabel('Work flow stage title: ')
		->setRequired(true)
		->setAttrib('size',60)
		->addFilters(array('StripTags', 'StringTrim', 'Purifier'));

	$valid = new Zend_Form_Element_Checkbox('valid');
	$valid->setLabel('Workflow stage is currently in use: ')
		->setRequired(true)
		->addFilters(array('StripTags', 'StringTrim'))
		->addValidator('Digits');
	
	$termdesc = new Pas_Form_Element_RTE('termdesc');
	$termdesc->setLabel('Description of workflow stage: ')
		->setRequired(true)
		->setAttrib('rows',10)
		->setAttrib('cols',40)
		->setAttrib('Height',400)
		->setAttrib('ToolbarSet','Basic')
		->addFilters(array('StringTrim', 'BasicHtml', 'EmptyParagraph', 'WordChars'));
	
	//Submit button 
	$submit = new Zend_Form_Element_Submit('submit');
	$submit->setAttrib('id', 'submitbutton')
		->removeDecorator('DtDdWrapper')
		->removeDecorator('HtmlTag');
	
	$hash = new Zend_Form_Element_Hash('csrf');
	$hash->setValue($this->_salt)->setTimeout(4800);		
	
	$this->addElements(array(
	$workflowstage, $valid, $termdesc,
	$submit, $hash));

	$this->addDisplayGroup(array('workflowstage','termdesc','valid'), 'details');
	
	$this->details->setLegend('HER details: ');

	$this->addDisplayGroup(array('submit'), 'buttons');
	
	parent::init();
	}
}