<?php
/** Form for filtering Scheduled Monuments
* 
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class SAMFilterForm extends Pas_Form
{
public function __construct($options = null) {

	
	$counties = new OsCounties();
	$county_options = $counties->getCountiesID();
	
	parent::__construct($options);
	$this->setName('filtersams');
	
	$decorator =  array('TableDecInput');
	
	$monumentName = new Zend_Form_Element_Text('monumentName');
	$monumentName->setLabel('Filter by name:')
		->setRequired(true)
		->addFilters(array('StringTrim', 'StripTags', 'Purifier'))
		->addErrorMessage('You must enter a monument name')
		->setAttrib('size', 20);
	
	$parish = new Zend_Form_Element_Select('parish');
	$parish->setLabel('Filter by parish')
		->addFilters(array('StringTrim', 'StripTags'))
		->addValidator('StringLength', false, array(1, 200))
		->setAttrib('class', 'span6 selectpicker show-menu-arrow');
	
	$district = new Zend_Form_Element_Select('district');
	$district->setLabel('Filter by district: ')
		->addFilters(array('StringTrim', 'StripTags'))
		->addValidator('StringLength', false, array(1, 200))
		->setAttrib('class', 'span6 selectpicker show-menu-arrow');
	
	$county = new Zend_Form_Element_Select('county');
	$county->setLabel('Filter by county: ')
		->setRequired(false)
		->addFilters(array('StringTrim', 'StripTags'))
		->setAttrib('class', 'span6 selectpicker show-menu-arrow')
		->addValidator('StringLength', false, array(1, 200))
		->addMultiOptions(array(NULL => NULL,'Choose county' => $county_options)) 
		->addValidator('InArray', false, array(array_keys($county_options)));
	
	$hash = new Zend_Form_Element_Hash('csrf');
	$hash->setValue($this->_salt)->setTimeout(4800);
		
	//Submit button 
	$submit = new Zend_Form_Element_Submit('submit');
	$submit->setLabel('Filter:');
	
	$this->addElements(array(
	$monumentName, $county, $district,
	$parish, $submit, $hash));
	parent::init();  
	}
}