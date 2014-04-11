<?php

/** Form for rejection of upgrades
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class RejectUpgradeForm extends Pas_Form {

public function __construct($options = null) {

	parent::__construct($options);

	$roles = new Roles();
	$role_options = $roles->getRoles();

	$inst = new Institutions();
	$inst_options = $inst->getInsts();       

	$projecttypes = new ProjectTypes();
	$projectype_list = $projecttypes->getTypes();
  
	$this->setName('acceptupgrades');
	
	ZendX_JQuery::enableForm($this);

	$researchOutline = new Zend_Form_Element_Textarea('researchOutline');
	$researchOutline->setLabel('Research outline: ')
		->setRequired(true)
		->addFilters(array('StringTrim', 'BasicHtml'))
		->setAttribs(array('rows' => 10))
		->addErrorMessage('Outline must be present.');
		
	$message = new Zend_Form_Element_Textarea('messageToUser');
	$message->setLabel('Message to user: ')
		->setRequired(true)
		->addFilters(array('StringTrim', 'BasicHtml', 'EmptyParagraph'))
		->setAttribs(array('rows' => 10))
		->addErrorMessage('You must enter a message for the user to know they have been approved.');

	$reference = new Zend_Form_Element_Text('reference');
	$reference->setLabel('Referee\'s name: ')
		->setAttrib('size',30)
		->addFilters(array('StringTrim', 'StripTags', 'Purifier'));

	$referenceEmail = new Zend_Form_Element_Text('referenceEmail');
	$referenceEmail->setLabel('Referee\'s email address: ')
	->setAttrib('size',30)
	->addFilters(array('StringTrim', 'StripTags','StringToLower'))
	->addValidator('EmailAddress',false,array('mx' => true));


	$fullname = new Zend_Form_Element_Text('fullname');
	$fullname->setLabel('Fullname: ')
		->setAttrib('size',30)
		->addFilters(array('StringTrim', 'StripTags', 'Purifier'));


	$email = $this->addElement('text', 'email',array('label' => 'Email Address', 'size' => '30'))->email;
	$email->setRequired(true)
		->addFilters(array('StringTrim', 'StripTags','StringToLower'))
		->addValidator('EmailAddress',false,array('mx' => true))
		->addErrorMessage('Please enter a valid address!');

	$already = new Zend_Form_Element_Radio('already');
	$already->setLabel('Is your topic already listed on our research register?: ')
		->addMultiOptions(array( 1 => 'Yes it is',0 => 'No it isn\'t' ))
		->setRequired(true);

	//Submit button 
	$submit = new Zend_Form_Element_Submit('submit');
	$submit->setLabel('Reject application');

	$hash = new Zend_Form_Element_Hash('csrf');
	$hash->setValue($this->_salt)->setTimeout(4800);
	
	$this->addElements(array(
	$researchOutline, $fullname, $reference,
	$referenceEmail, $submit, $message));

	$this->addDisplayGroup(array(
		'fullname','email','messageToUser',
		'reference','referenceEmail','researchOutline'), 'details');
	
	$this->details->setLegend('Details: ');

	$this->addDisplayGroup(array('submit'), 'buttons');
	
	parent::init();
	}
}