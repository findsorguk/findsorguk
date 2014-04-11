<?php
/** Form for submitting and editing coroner contact details
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/

class CoronerForm extends Pas_Form
{
public function __construct($options = null)
{

	$countries = new Countries();
	$countries_options = $countries->getOptions();

	$counties = new Counties();
	$county_options = $counties->getCountyname2();

	parent::__construct($options);

	$this->setName('coroner');

	$firstname = new Zend_Form_Element_Text('firstname');
	$firstname->setLabel('First name: ')
	->setRequired(true)
	->addFilters(array('StripTags','StringTrim'))
	->addErrorMessage('Come on it\'s not that hard, enter a firstname!');

	$lastname = new Zend_Form_Element_Text('lastname');
	$lastname->setLabel('Last name: ')
	->setRequired(true)
	->addFilters(array('StripTags','StringTrim'))
	->addValidator('StringLength', false, array(1,200));

	$email = new Zend_Form_Element_Text('email');
	$email->SetLabel('Email address: ')
	->setRequired(false)
	->addFilters(array('StripTags','StringTrim'))
	->addValidator('StringLength', false, array(1,200))
	->addValidator('EmailAddress', false);

	$address_1 = new Zend_Form_Element_Text('address_1');
	$address_1->SetLabel('Address line one: ')
	->setRequired(false)
	->addFilters(array('StripTags','StringTrim'))
	->addValidator('StringLength', false, array(1,200));

	$address_2 = new Zend_Form_Element_Text('address_2');
	$address_2->SetLabel('Address line two: ')
	->setRequired(false)
	->addFilters(array('StripTags','StringTrim'))
	->addValidator('StringLength', false, array(1,200));

	$town = new Zend_Form_Element_Text('town');
	$town->SetLabel('Town: ')
	->setRequired(false)
	->addFilters(array('StripTags','StringTrim'))
	->addValidator('StringLength', false, array(1,200));

	$county = new Zend_Form_Element_Select('county');
	$county->setLabel('County: ')
	->addFilters(array('StripTags','StringTrim'))
	->setAttrib('class', 'span6 selectpicker show-menu-arrow')
	->addValidators(array('NotEmpty'))
	->addMultiOptions(array(NULL => 'Choose county','Valid county' => $county_options));

	$region_name = new Zend_Form_Element_Text('region_name');
	$region_name->SetLabel('Administrative region: ')
	->setRequired(false)
	->addFilters(array('StripTags','StringTrim'))
	->addValidator('StringLength', false, array(1,200));

	$postcode = new Zend_Form_Element_Text('postcode');
	$postcode->SetLabel('Postcode: ')
	->setRequired(false)
	->addFilters(array('StripTags','StringTrim'))
	->addValidator('StringLength', false, array(1,200))
	->addValidator('ValidPostCode');

	$country = new Zend_Form_Element_Select('country');
	$country->SetLabel('Country: ')
	->setRequired(false)
	->addFilters(array('StripTags','StringTrim'))
	->setAttrib('class', 'span6 selectpicker show-menu-arrow')
	->addValidator('StringLength', false, array(1,4))
	->addValidator('InArray', false, array(array_keys($countries_options)))
	->addMultiOptions($countries_options)
	->setValue('GB');

	$telephone = new Zend_Form_Element_Text('telephone');
	$telephone->SetLabel('Telephone number: ')
	->setRequired(false)
	->addFilters(array('StripTags','StringTrim'))
	->addValidator('StringLength', false, array(1,200));

	$fax = new Zend_Form_Element_Text('fax');
	$fax->SetLabel('Fax number: ')
	->setRequired(false)
	->addFilters(array('StripTags','StringTrim'))
	->addValidator('StringLength', false, array(1,200));

	//Submit button
	$submit = new Zend_Form_Element_Submit('submit');
	
	$this->addElements(array(
	$firstname, $lastname, $email,
	$address_1,	$address_2,	$town,
	$postcode, $county,	$country,
	$telephone,	$fax,$region_name,
	$submit));

	$this->addDisplayGroup(array(
	'firstname', 'lastname', 'region_name',
	'email', 'address_1', 'address_2',
	'town', 'postcode', 'county',
	'country','telephone','fax',), 'details');

	$this->addDisplayGroup(array('submit'), 'buttons');

	$this->details->setLegend('Submit Coroner\'s details ');
  	parent::init();
	}

}