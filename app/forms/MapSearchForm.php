<?php
class MapSearchForm extends Pas_Form {
	
public function __construct($options = null) {
	
$counties = new Counties();
	$county_options = $counties->getCountyname2();

	parent::__construct($options);
	
	$this->setName('mapsearch');
	
	$latitude = new Zend_Form_Element_Text('declat');
	$latitude->setLabel('Latitude: ')
;

	$longitude = new Zend_Form_Element_Text('declong');
	$longitude->setLabel('Longitude: ');
	
	
	$county = new Zend_Form_Element_Select('county');
	$county->setLabel('County: ')
		->setAttrib('class', 'span6 selectpicker show-menu-arrow')
		->addValidators(array('NotEmpty'))
		->addMultiOptions(array(NULL => NULL,'Choose county' => $county_options));
	
	$district = new Zend_Form_Element_Select('district');
	$district->setLabel('District: ')
		->setAttrib('class', 'span6 selectpicker show-menu-arrow')
		->setRegisterInArrayValidator(false);
	
	$parish = new Zend_Form_Element_Select('parish');
	$parish->setLabel('Parish: ')
		->setAttrib('class', 'span6 selectpicker show-menu-arrow')
		->setRegisterInArrayValidator(false);
	
	$distance = new Zend_Form_Element_Select('distance');
	$distance->setLabel('Distance from point: ')
		->setAttrib('class', 'span6 selectpicker show-menu-arrow')
		->addMultiOptions(array(NULL => NULL, 'Choose distance' => array(
		'0.05' => '50 metres','0.1' => '100 metres', '0.25' => '250 metres',
		'0.5' => '500 metres','1' => '1 kilometre','2' => '2 kilometres',
		'3' => '3 kilometres', '4' => '4 kilometres', '5' => '5 kilometres', '10' => '10 kilometres')));
	
	$objecttype = new Zend_Form_Element_Text('objecttype');
	$objecttype->setLabel('Object type: ')
		->setRequired(false)
		->setAttrib('size',20)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addErrorMessage('You must enter an object type and it must be valid');
	
	$gridref = new Zend_Form_Element_Text('gridref');
	$gridref->setLabel('Nat. Grid Reference: ')
		->setRequired(false)
		->setAttrib('size',16)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->setAttrib('maxlength',16);



	$submit = new Zend_Form_Element_Submit('submit');

	$this->addElements(array(
	$objecttype,
	$distance,
	$county,
	$district,
	$parish,
	$gridref,
	$latitude,
	$longitude,
	$submit));
	
	$this->addDisplayGroup(array('objecttype','county','district','parish','gridref','declat','declong','distance'), 'details');
	
	
	$this->details->setLegend('Spatial data: ');
	$this->addDisplayGroup(array('submit'), 'buttons');
	  
	parent::init();
	}
}