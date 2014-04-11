<?php
/** Form for filtering organisations.
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class OrganisationFilterForm extends Pas_Form {
	
public function __construct($options = null) {

	$periods = new Periods();
	$periodword_options = $periods->getPeriodFromWords();

	$activities = new PrimaryActivities();
	$activities_options = $activities->getTerms();
	
	$counties = new OsCounties();
	$county_options = $counties->getCountiesID();

	parent::__construct($options);

 	$this->setName('filterpeople');

	$name = new Zend_Form_Element_Text('organisation');
	$name->setLabel('Filter by name')
		->addFilters(array('StripTags','StringTrim', 'Purifier'))
		->setAttrib('class', 'span6 selectpicker show-menu-arrow')
		->setAttrib('size', 40);
	
	$contact = new Zend_Form_Element_Text('contact');
	$contact->setLabel('Filter by contact person: ')
		->addFilters(array('StripTags','StringTrim'))
		->addErrorMessage('Enter a valid organisation')
		->setAttrib('size', 20);
	
	$contactpersonID = new Zend_Form_Element_Hidden('contactpersonID');
	$contactpersonID->addFilters(array('StripTags','StringTrim'))
		->addValidator('Alnum');
					
	
	$county = new Zend_Form_Element_Select('county');
	$county->setLabel('Filter by county')
		->addFilters(array('StripTags','StringTrim'))
		->addValidator('StringLength', false, array(1,200))
		->addMultiOptions(array(NULL => NULL,'Choose county' => $county_options))
		->addValidator('InArray', false, array(array_keys($county_options)));
	
	//Submit button 
	$submit = new Zend_Form_Element_Submit('submit');
	$submit->setLabel('Filter');
	
	$hash = new Zend_Form_Element_Hash('csrf');
	$hash->setValue($this->_salt)->setTimeout(4800);
	
	$this->addElements(array(
	$name, $county, $contact,
	$contactpersonID, $submit, $hash));
	
	parent::init();
	}
}
