<?php
/** Form for submitting an error
*
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
*/
class NotifyFloForm extends Pas_Form {

	public function __construct(array $options) {
	
	$f = new Contacts();
	$flos = $f->getFloEmailsForForm();	
		
	parent::__construct($options);

	
	$this->setName('notifyFlo');
	
	$flo = new Zend_Form_Element_Select('flo');
	$flo->setLabel('Which flo is yours?: ')
		->setAttrib('class', 'input-xxlarge selectpicker show-menu-arrow')
		->setRequired(true)
		->addMultiOptions(array(null => 'Choose your FLO','Available FLOs' => $flos));
	
	$type = new Zend_Form_Element_Select('type');
	$type->setLabel('Message type: ')
		->setAttrib('class', 'input-xxlarge selectpicker show-menu-arrow')
		->setRequired(true)
		->addMultiOptions(array(
		NULL => 'Choose reason',
		'Choose error type' => array(
		'Can you publish this please?' => 'Can you publish this please?',
		'More info' => 'I have further information',
		'Image problem' => 'Image problem',
		'Grid reference issues' => 'Grid reference issues',
		'Duplicated record' => 'Duplicated record',
		'Data problems apparent' => 'Data problems - what do I do?',
		'Other' => 'Other reason')))
		->addErrorMessage('You must enter an error report type');



	$content = new Pas_Form_Element_CKEditor('content');
	$content->setLabel('Enter your comment: ')
	->setRequired(true)
	->addFilter('StringTrim')
	->setAttrib('Height',400)
	->setAttrib('ToolbarSet','Basic')
	->addFilters(array('StringTrim','WordChars','HtmlBody','EmptyParagraph'))
	->addErrorMessage('Please enter something in the comments box!');

						
	$hash = new Zend_Form_Element_Hash('csrf');
	$hash->setValue($this->_salt)->setTimeout(60);
		
	$submit = new Zend_Form_Element_Submit('submit');

	$this->addElements(array($content, $flo, $type, $submit, $hash));

	$this->addDisplayGroup(array('flo','type', 'content', ), 'details');

	
	$this->addDisplayGroup(array('submit'), 'buttons');
	
	parent::init();
	}
}