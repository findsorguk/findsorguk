<?php
/** Form for submitting and editing coroner contact details
 * 
 * An example of code use:
 * 
 * <code>
 * <?php
 * $form = new CoronerForm();
 * ?>
 * </code>
 * 
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @version 1
 * @category   Pas
 * @package    Pas_Form
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @uses Countries
 * @uses OsCounties
*/

class CoronerForm extends Pas_Form {

    /** The constructor
     * @access public
     * @param array $options
     * @return void
     */
    public function __construct(array $options) {

	$countries = new Countries();
	$countries_options = $countries->getOptions();

	$counties = new OsCounties();
	$county_options = $counties->getCountyNames();

	parent::__construct($options);

	$this->setName('coroner');

	$firstname = new Zend_Form_Element_Text('firstname');
	$firstname->setLabel('First name: ')
                ->setRequired(true)
                ->addFilters(array('StripTags','StringTrim'))
                ->addErrorMessage('Enter a firstname!');

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
                ->setAttrib('class', 'input-xxlarge selectpicker show-menu-arrow')
                ->addValidators(array('NotEmpty'))
                ->addMultiOptions(array(
                    null => 'Choose county',
                    'Valid county' => $county_options
                ));

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
                ->setAttrib('class', 'input-xxlarge selectpicker show-menu-arrow')
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
            $address_1,	$address_2, $town,
            $postcode, $county,	$country,
            $telephone,	$fax, $region_name,
            $submit));

	$this->addDisplayGroup(array(
            'firstname', 'lastname', 'region_name',
            'email', 'address_1', 'address_2',
            'town', 'postcode', 'county',
            'country','telephone','fax',),
                'details');

	$this->addDisplayGroup(array('submit'), 'buttons');

	$this->details->setLegend('Submit Coroner\'s details ');
  	parent::init();
    }
}