<?php
/** Form for creating social media accounts for the foaf profiles
* 
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class SocialAccountsForm extends Pas_Form
{
public function __construct($options = null) {
	
	parent::__construct($options);
	
	$services = new WebServices();
	$servicesListed = $services->getValidServices();      
	
	$this->setName('socialweb');
	
	$username = new Zend_Form_Element_Text('account');
	$username->setLabel('Account username: ')
	->setRequired(true)
		->addFilters(array('StringTrim', 'StripTags'))
	->setAttrib('size',30)
	->addErrorMessage('Please enter a valid username!');
	
	$service = new Zend_Form_Element_Select('accountName');
	$service->setLabel('Social services: ')
		->addFilters(array('StringTrim', 'StripTags'))
		->setAttrib('class', 'span6 selectpicker show-menu-arrow')
		->addMultiOptions(array( NULL => 'Choose a service', 'Valid services' => $servicesListed))
		->addValidator('InArray', false, array(array_keys($servicesListed)));
	
	$public = new Zend_Form_Element_Checkbox('public');
	$public->setLabel('Show this to public users?: ')
		->addFilters(array('StringTrim', 'StripTags'))
		->setRequired(true)
		->addErrorMessage('You must set the status of this account');
	
	$submit = new Zend_Form_Element_Submit('submit');
	$hash = new Zend_Form_Element_Hash('csrf');
	$hash->setValue($this->_salt)
		->setTimeout(4800);
		
	$this->addElements(array( $service,$hash, $username, $public, $submit));
	
	$this->addDisplayGroup(array('accountName','account','public'), 'details');
	
	$this->addDisplayGroup(array('submit'), 'buttons');
	parent::init();
	}
}