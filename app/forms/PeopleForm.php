<?php
/** Form for setting up and editing personal details of everyone on the database
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
*/
class PeopleForm extends Pas_Form {

public function __construct(array $options) {
	
    $users = new Users();
    $users_options = $users->getOptions();
	
    $countries = new Countries();
    $countries_options = $countries->getOptions();
	
    $counties = new OsCounties();
	$county_options = $counties->getCountiesID();
    	
    $activities = new PrimaryActivities();
    $activities_options = $activities->getTerms();

    $organisations = new Organisations;
    $organisations_options = $organisations->getOrgs();

	parent::__construct($options);

    $this->setName('people');

	$title = new Zend_Form_Element_Select('title');
	
	$title->setLabel('Title: ')
		->addFilters(array('StripTags','StringTrim'))
		->setValue('Mr')
		->addErrorMessage('Choose title of person')
		->setAttrib('class', 'span2 selectpicker show-menu-arrow')
		->addMultiOptions(array(
		'Mr' => 'Mr', 'Mrs' => 'Mrs', 'Miss' => 'Miss', 
		'Ms' => 'Ms', 'Dr' => 'Dr.', 'Prof' => 'Prof.',
		'Sir' => 'Sir', 'Lord' => 'Lord', 'Lady' => 'Lady', 'Other' => 'Other',
		'Captain' => 'Captain', 'Master' => 'Master', 'Dame' => 'Dame',
		'Duke' => 'Duke', 'Admiral' => 'Admiral', 'Baron' => 'Baron',
		'Baroness' => 'Baroness', 'Brigadier' => 'Brigadier', 'Captain' => 'Captain',
		'Colonel' => 'Colonel', 'Colonel Sir' => 'Colonel Sir', 'Commander' => 'Commander',
		'Commodore' => 'Commodore', 'Countess' => 'Countess', 'Earl' => 'Earl',
		'Field Marshal Lord' => 'Field Marshal Lord', 'General' => 'General',
		'Group Captain' => 'Group Captain', 'Lieutenant' => 'Lieutenant', 
		'Lieutenant Commander' => 'Lieutenant Commander',
		'Lt-Col' => 'Lt-Col', 'Lt-Gen Sir' => 'Lt-Gen Sir', 'Maj-Gen' => 'Maj-Gen',
		'Major' => 'Major', 'Prince' => 'Prince', 'Princesss' => 'Princess',
		'Rear Admiral' => 'Rear Admiral', 'Squadron Leader' => 'Squadron Leader',
		'The Baroness' => 'The Baroness', 'The Countess' => 'The Countess',
		'The Countess of' => 'The Countess of', 'The Duchess of' => 'The Duchess of',
		'The Duke of' => 'The Duke of', 'The Earl of' => 'The Earl of',
		'The Hon' => 'The Hon', 'His Honour Judge' => 'His Honour Judge', 'The Hon Mrs' => 'The Hon Mrs',
		'The Marchioness of' => 'The Marchioness of', 'The Marquess of' => 'The Marquess of',
		'The Viscount' => 'The Viscount', 'The Viscountess' => 'The Viscountess',
		'Wing Commander' => 'Wing Commander', 'The Reverend' => 'The Reverend',
		'Archbishop' => 'Archbishop', 'Canon' => 'Canon', 'Bishop' => 'Bishop'));

	$forename = new Zend_Form_Element_Text('forename');
	$forename->setLabel('Forename: ')
            ->setRequired(true)
            ->addFilters(array('StripTags','StringTrim'))
            ->addErrorMessage('Please enter person\'s forename')
            ->addFilter(new Zend_Filter_Callback(array('callback' => 'ucfirst')));

	$surname = new Zend_Form_Element_Text('surname');
	$surname->setLabel('Surname: ')
		->setRequired(true)
		->addFilters(array('StripTags','StringTrim'))
        ->addFilter(new Zend_Filter_Callback(array('callback' => 'ucfirst')))
		->addErrorMessage('Please enter person\'s surname');

	$fullname = new Zend_Form_Element_Text('fullname');
	$fullname->setLabel('Fullname: ')
		->setRequired(true)
		->addFilters(array('StripTags','StringTrim'))
		->addErrorMessage('Please enter person\'s fullname');

	$email = new Zend_Form_Element_Text('email');
	$email->SetLabel('Email address: ')
		->addFilters(array('StringTrim', 'StringToLower', 'StripTags'))
		->addValidator('StringLength', false, array(1,200))
		->addValidator('EmailAddress', false, array('mx' => true) )
		->setAttrib('size','60');

	$dbaseID = new Zend_Form_Element_Select('dbaseID');
	$dbaseID->setLabel('User account: ')
		->addFilters(array('StripTags','StringTrim'))
		->setAttrib('class', 'input-xxlarge selectpicker show-menu-arrow')
		->addValidator('Int')
		->addValidator('InArray', false, array(array_keys($users_options),null))
		->addMultiOptions(array(NULL => 'Choose a user account',
		'Existing accounts' => $users_options))
		->addErrorMessage('You must enter a database account.');

	$canRecord = $this->addElement('checkbox', 'canRecord',array('label' => 'Allowed to record: '))->canRecord;
		
	$address = new Zend_Form_Element_Text('address');
	$address->SetLabel('Address: ')
		->addFilters(array('StripTags','StringTrim', 'Purifier'))
		->addValidator('StringLength', false, array(1,500));

	$town_city = new Zend_Form_Element_Text('town_city');
	$town_city->SetLabel('Town: ')
		->addFilters(array('StripTags','StringTrim', 'Purifier'))
		->addValidator('StringLength', false, array(1,200));
	
	$countyID = new Zend_Form_Element_Select('countyID');
	$countyID->setLabel('County: ')
		->addFilters(array('StripTags','StringTrim', 'Purifier'))
		->setAttrib('class', 'input-xxlarge selectpicker show-menu-arrow')
		->addMultiOptions(array(NULL => 'Please choose a county',
		'Valid counties' => $county_options));

	$postcode = new Zend_Form_Element_Text('postcode');
	$postcode->SetLabel('Postcode: ')
		->setRequired(false)
		->addFilters(array('StripTags','StringTrim','StringToUpper', 'Purifier'))
		->addValidator('StringLength', false, array(1,200))
		->addValidator('ValidPostCode');

	$country = new Zend_Form_Element_Select('country');
	$country->SetLabel('Country: ')
		->addFilters(array('StripTags','StringTrim'))
		->setAttrib('class', 'input-xxlarge selectpicker show-menu-arrow')
		->addValidator('StringLength', false, array(1,4))
		->addValidator('InArray', false, array(array_keys($countries_options)))
		->addMultiOptions(array(NULL => 'Please choose a country of residence',
		'Valid countries' => $countries_options))
		->setValue('GB');

	$hometel = new Zend_Form_Element_Text('hometel');
	$hometel->SetLabel('Home telephone number: ')
		->addFilters(array('StripTags','StringTrim', 'Purifier'))
		->addValidator('StringLength', false, array(1,30));

	$worktel = new Zend_Form_Element_Text('worktel');
	$worktel->SetLabel('Work telephone number: ')
		->addFilters(array('StripTags','StringTrim', 'Purifier'))
		->addValidator('StringLength', false, array(1,30));

	$fax = new Zend_Form_Element_Text('faxno');
	$fax->SetLabel('Fax number: ')
		->addFilters(array('StripTags','StringTrim', 'Purifier'))
		->addValidator('StringLength', false, array(1,30));

	$comments = new Pas_Form_Element_CKEditor('comments');
	$comments->SetLabel('Comments: ')
		->setAttrib('rows',10)
		->setAttrib('cols',40)
		->setAttrib('Height',400)
		->setAttrib('ToolbarSet','Finds')
		->addFilters(array('StringTrim', 'BasicHtml', 'EmptyParagraph', 
                    'WordChars'));

	$organisationID = new Zend_Form_Element_Select('organisationID');
	$organisationID->SetLabel('Organisation attached to: ')
		->addFilters(array('StripTags','StringTrim'))
		->setAttrib('class', 'input-xxlarge selectpicker show-menu-arrow')
		->addMultiOptions(array(NULL => 'Please choose an organisation',
		'Valid organisations' => $organisations_options))
		->addValidator('InArray', false, array(array_keys($organisations_options)));

	$primary_activity = new Zend_Form_Element_Select('primary_activity');
	$primary_activity->SetLabel('Person\'s primary activity: ')
		->setRequired(true)
		->addFilters(array('StripTags','StringTrim'))
		->setAttrib('class', 'input-xxlarge selectpicker show-menu-arrow')
		->addValidator('Int')
		->addValidator('InArray', false, array(array_keys($activities_options)))
		->addMultiOptions(array(NULL => 'Choose a primary activity',
                    'Valid activities' => $activities_options))
		->addErrorMessage('You must enter an activity for this person.');

	$submit = new Zend_Form_Element_Submit('submit');

	
	$this->addElements(array(
	$title, $forename, $surname,
	$fullname, $email, $address,
	$town_city, $countyID, $postcode,
	$country, $dbaseID, $canRecord, $hometel,
	$worktel, $fax, $comments,
	$organisationID, $primary_activity, $submit));

	$this->addDisplayGroup(array(
	'title','forename','surname',
	'fullname','email','address',
	'town_city','countyID','postcode',
	'country','dbaseID', 'canRecord',
	'hometel','worktel','faxno',
	'comments',	'organisationID','primary_activity'), 
	'details');
	$this->details->setLegend('Person details: ');
	
	$this->addDisplayGroup(array('submit'), 'buttons');

	parent::init();
	}
}
