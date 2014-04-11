<?php
/**
* Form for requesting an upgrade for a user's account
*
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License

*/
class AccountUpgradeForm extends Pas_Form {

public function __construct($options = null) {

parent::__construct($options);


	$this->setName('accountupgrades');


	$researchOutline = new Pas_Form_Element_RTE('researchOutline');
	$researchOutline->setLabel('Research outline: ')
		->setAttrib('rows',10)
		->setAttrib('cols',40)
		->setAttrib('Height',400)
		->setAttrib('ToolbarSet','Finds')
		->addFilter('StringTrim')
		->addFilter('BasicHtml')
		->addFilter('EmptyParagraph')
		->addFilter('WordChars')
		->addErrorMessage('Outline must be present.')
		->setDescription('Use this textarea to tell us whether you want to become a
		research level user and why. We would also like to know the probable length of time
		for this project so that we can inform our research board of progress.
		We need a good idea as we have to respect privacy of findspots and landowner/finder personal data');


	$reference = $this->addElement(
		'Text','reference',
		array('label' => 'Please provide a referee:', 'size' => '40',
		'description' => 'We ask you to provide a referee who can substantiate your request for higher level access.
		Ideally they will be an archaeologist of good standing.'))
		->reference;
	$reference->setRequired(false)
		->addFilter('StripTags')
		->addFilter('StringTrim');

	$referenceEmail = $this->addElement('Text','referenceEmail',
		array('label' => 'Please provide an email address for your referee:', 'size' => '40'))->referenceEmail;
	$referenceEmail->setRequired(false)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('EmailAddress');


	$already = new Zend_Form_Element_Radio('already');
	$already->setLabel('Is your topic already listed on our research register?: ')
		->addMultiOptions(array( 1 => 'Yes it is',0 => 'No it isn\'t' ))
		->setRequired(true)
		->setOptions(array('separator' => ''));


	//Submit button
	$submit = new Zend_Form_Element_Submit('submit');
	$submit->setLabel('Submit request');

	$this->addElements(
		array($researchOutline, $submit, $already,));

	$this->addDisplayGroup(array('researchOutline','reference','referenceEmail','already'), 'details');

	$this->details->setLegend('Details: ');
	$this->addDisplayGroup(array('submit'), 'buttons');
	parent::init();
	}

}