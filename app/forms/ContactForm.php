<?php
/** Form for adding and editing staff contacts
*
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class ContactForm extends Pas_Form
{
	public function __construct($options = null) {

	$roles = new StaffRoles();
	$role_options = $roles->getOptions();

	$institutions = new Institutions();
	$insts = $institutions->getInsts();

	$staffregions = new StaffRegions();
	$staffregions_options = $staffregions->getOptions();

	$countries = new Countries();
	$countries_options = $countries->getOptions();

	$users = new Users();
	$users_options = $users->getOptions();

	parent::__construct($options);


	$this->setName('contact');

	$firstname = new Zend_Form_Element_Text('firstname');
	$firstname->setLabel('First name: ')
	->setRequired(true)
	->addFilters(array('StripTags','StringTrim'))
	->addErrorMessage('You must enter a firstname')
	->addValidator('StringLength', false, array(1,200));

	$lastname = new Zend_Form_Element_Text('lastname');
	$lastname->setLabel('Last name: ')
		->setRequired(true)
		->addFilters(array('StripTags','StringTrim'))
		->addValidator('StringLength', false, array(1,200))
		->addErrorMessage('You must enter a lastname');

	$role = new Zend_Form_Element_Select('role');
	$role->setLabel('Role within the Scheme: ')
		->setRequired(true)
		->addFilters(array('StripTags','StringTrim'))
		->setAttrib('class', 'span6 selectpicker show-menu-arrow')
		->addMultiOptions(array(NULL => NULL,'Choose a role' => $role_options))
		->addErrorMessage('You must choose a role');

	$dbaseID = new Zend_Form_Element_Select('dbaseID');
	$dbaseID->setLabel('Database account: ')
		->setRequired(false)
		->addFilters(array('StripTags','StringTrim'))
		->addValidator('Int')
		->setAttrib('class', 'span6 selectpicker show-menu-arrow')
		->addMultiOptions(array(NULL => NULL, 'Choose account' => $users_options))
		->addErrorMessage('You must enter a database account.');


	$email_one = new Zend_Form_Element_Text('email_one');
	$email_one->SetLabel('Primary email address: ')
		->setRequired(true)
		->setAttrib('size',50)
		->addFilters(array('StripTags','StringTrim'))
		->addValidator('StringLength', false, array(1,200))
		->addValidator('EmailAddress', false)
		->addErrorMessage('You must enter an email address');

	$email_two = new Zend_Form_Element_Text('email_two');
	$email_two->SetLabel('Secondary email address: ')
		->setAttrib('size',50)
		->addFilters(array('StripTags','StringTrim'))
		->addValidator('StringLength', false, array(1,200))
		->addValidator('EmailAddress', false);

	$address_1 = new Zend_Form_Element_Text('address_1');
	$address_1->SetLabel('Address line one: ')
		->setRequired(true)
		->setAttrib('size',50)
		->addFilters(array('StripTags','StringTrim'))
		->addValidator('StringLength', false, array(1,200))
		->addErrorMessage('You must enter a first line for the address');

	$address_2 = new Zend_Form_Element_Text('address_2');
	$address_2->SetLabel('Address line two: ')
	->setAttrib('size',50)
	->addFilters(array('StripTags','StringTrim'))
	->addValidator('StringLength', false, array(1,200));

	$town = new Zend_Form_Element_Text('town');
	$town->SetLabel('Town: ')
	->setRequired(true)
	->addFilters(array('StripTags','StringTrim'))
	->addValidator('StringLength', false, array(1,200))
	->addErrorMessage('You must enter a town');

	$county = new Zend_Form_Element_Text('county');
	$county->SetLabel('County: ')
	->setRequired(true)
	->addFilters(array('StripTags','StringTrim'))
	->addValidator('StringLength', false, array(1,200))
	->addErrorMessage('You must enter a county or unitary authority');

	$euroregion = new Zend_Form_Element_Text('euroregion');
	$euroregion->SetLabel('Administrative region: ')
	->setRequired(false)
	->addFilters(array('StripTags','StringTrim'))
	->addValidator('StringLength', false, array(1,200));

	$postcode = new Zend_Form_Element_Text('postcode');
	$postcode->SetLabel('Postcode: ')
	->setRequired(true)
	->addFilters(array('StripTags','StringTrim'))
	->addValidator('ValidPostCode')
	->addValidator('StringLength', false, array(1,200))
	->addErrorMessage('You must enter a postal code');

	$country = new Zend_Form_Element_Select('country');
	$country->SetLabel('Country: ')
	->setRequired(true)
	->setAttrib('class', 'span6 selectpicker show-menu-arrow')
	->addFilters(array('StripTags','StringTrim'))
	->addValidator('StringLength', false, array(1,200))
	->addValidator('InArray', false, array(array_keys($countries_options)));


	$telephone = new Zend_Form_Element_Text('telephone');
	$telephone->SetLabel('Telephone number: ')
	->setRequired(true)
	->setAttrib('size',50)
	->addFilters(array('StripTags','StringTrim'))
	->addValidator('StringLength', false, array(1,200))
	->addErrorMessage('You must enter a telephone number');

	$fax = new Zend_Form_Element_Text('fax');
	$fax->SetLabel('Fax number: ')
	->setRequired(false)
	->setAttrib('size',50)
	->addFilters(array('StripTags','StringTrim'))
	->addValidator('StringLength', false, array(1,200));


	$identifier = new Zend_Form_Element_Select('identifier');
	$identifier->SetLabel('Database entry identifier: ')
	->setRequired(true)
	->addMultiOptions(array(NULL => NULL, 'Choose institution' => $insts))
	->addValidator('InArray', false, array(array_keys($insts)))
	->addFilters(array('StripTags','StringTrim'))
	->setAttrib('class', 'span6 selectpicker show-menu-arrow')
	->addValidator('StringLength', false, array(1,6));

	$region = new Zend_Form_Element_Select('region');
	$region->SetLabel('Recording region: ')
	->setRequired(true)
	->addFilters(array('StripTags','StringTrim'))
		->setAttrib('class', 'span6 selectpicker show-menu-arrow')
	->addValidator('StringLength', false, array(1,10))
	->addValidator('InArray', false, array(array_keys($staffregions_options)))
	->addMultiOptions(array(NULL => NULL, 'Choose staff region' => $staffregions_options));

	$profile= new Pas_Form_Element_RTE('profile');
	$profile->setLabel('Profile: ')
	->setRequired(false)
	->setAttrib('rows',10)
	->setAttrib('cols',40)
	->setAttrib('Height',400)
	->setAttrib('ToolbarSet','Finds')
	->addFilter('StringTrim')
	->addFilter('BasicHtml')
	->addFilter('EmptyParagraph')
	->addFilter('WordChars');

	$website = new Zend_Form_Element_Text('website');
	$website->SetLabel('Employer\'s website address: ')
	->setRequired(false)
	->setAttrib('size',50)
	->addFilters(array('StripTags','StringTrim'))
	->addValidator('StringLength', false, array(1,150));

	$alumni = new Zend_Form_Element_Checkbox('alumni');
	$alumni->SetLabel('Currently employed by the Scheme: ')
	->setRequired(true)
	->addFilters(array('StripTags','StringTrim'));

	$submit = new Zend_Form_Element_Submit('submit');

	$hash = new Zend_Form_Element_Hash('csrf');
	$hash->setValue($this->_salt)->setTimeout(4800);
	
	$this->addElements(array(
	$firstname, $lastname, $role,
	$dbaseID, $email_one, $email_two,
	$address_1, $address_2, $town,
	$postcode, $county,	$identifier,
	$telephone,	$fax, $region,
	$profile, $website, $alumni,
	$submit, $hash));



	$this->addDisplayGroup(array('firstname', 'lastname', 'role',
	'dbaseID', 'identifier', 'region',
	'profile', 'email_one', 'email_two',
	'address_1', 'address_2', 'town',
	'postcode', 'county', 'telephone',
	'fax', 'website', 'alumni'), 'details');

	$this->addDisplayGroup(array('submit'), 'buttons');

	$this->details->setLegend('Contact details');

	parent::init();
	}

}