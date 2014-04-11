<?php

/** Form for adding and editing rally data
*
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class RallyForm extends Pas_Form {

public function __construct($options = null) {

	$counties = new OsCounties();
	$county_options = $counties->getCountiesID();

	parent::__construct($options);

	ZendX_JQuery::enableForm($this);


	$this->setName('rally');

	$rally_name = new Zend_Form_Element_Text('rally_name');
	$rally_name->setLabel('Rally name: ')
		->setRequired(true)
		->addFilters(array('StripTags','StringTrim', 'Purifier'))
		->setAttrib('class', 'span6')
		->addErrorMessage('You must enter a rally name');

	$organisername = new Zend_Form_Element_Text('organisername');
	$organisername->setLabel('Rally Organiser: ')
		->addFilters(array('StripTags','StringTrim'));

	$organiser = new Zend_Form_Element_Hidden('organiser');
	$organiser->addFilters(array('StripTags','StringTrim'));

	$county = new Zend_Form_Element_Select('countyID');
	$county->setLabel('County: ')
		->setAttrib('class', 'span6 selectpicker show-menu-arrow')
		->addMultiOptions(array(NULL => 'Choose a county' ,'Valid counties' => $county_options))
		->addValidator('InArray', false, array(array_keys($county_options)))
		->addFilters(array('StripTags','StringTrim'))
		->addValidator('Int');;

	$district = new Zend_Form_Element_Select('districtID');
	$district->setLabel('District: ')
		->setAttrib('class', 'span6 selectpicker show-menu-arrow')
		->setRegisterInArrayValidator(false)
		->addFilters(array('StripTags','StringTrim'))
		->addMultiOptions(array(NULL => 'Choose district after county'))
		->addValidator('Int');

	$parish = new Zend_Form_Element_Select('parishID');
	$parish->setLabel('Parish: ')
		->setAttrib('class', 'span6 selectpicker show-menu-arrow')
		->setRegisterInArrayValidator(false)
		->addFilters(array('StripTags','StringTrim'))
		->addMultiOptions(array(NULL => 'Choose parish after district'))
		->addValidator('Int');

	$gridref = new Zend_Form_Element_Text('gridref');
	$gridref->setLabel('Centred on field at NGR: ')
		->setRequired(false)
		->addFilters(array('StripTags','StringTrim'))
		->setAttrib('maxlength',16)
		->addValidators(array('NotEmpty','ValidGridRef'));

	$record_method = new Zend_Form_Element_Textarea('record_method');
	$record_method->setLabel('Recording methodology employed: ')
		->setAttribs(array('class' => 'span6'))
		->addFilters(array('StripTags', 'BasicHtml','EmptyParagraph','StringTrim'));

	$comments = new Zend_Form_Element_Textarea('comments');
	$comments->setLabel('Comments on rally: ')
		->setAttribs(array('class' => 'span6'))
		->addFilters(array('StripTags', 'BasicHtml','EmptyParagraph','StringTrim'));

	//Date found from
	$date_from = new ZendX_JQuery_Form_Element_DatePicker('date_from');
	$date_from->setLabel('Start date of rally: ')
		->setJQueryParam('dateFormat', 'yy-mm-dd')
		->addFilters(array('StripTags','StringTrim'))
		->addValidator('Datetime');

	//Date found to
	$date_to = new ZendX_JQuery_Form_Element_DatePicker('date_to');
	$date_to->setLabel('End date of rally: ')
		->setJQueryParam('dateFormat', 'yy-mm-dd')
		->addFilters(array('StripTags','StringTrim'))
		->addValidator('Datetime');

	$submit = new Zend_Form_Element_Submit('submit');



	$this->addElements(array(
	$rally_name, $date_from, $date_to,
	$organiser, $organisername, $county,
	$district, $parish, $gridref, $comments,
	$record_method,	$submit));

	$this->addDisplayGroup(array(
	'rally_name', 'comments','record_method',
	'date_from', 'date_to', 'organiser',
	'organisername', 'countyID', 'districtID',
	'parishID', 'gridref'), 'details');

	$this->details->setLegend('Rally details: ');

	$this->addDisplayGroup(array('submit'), 'buttons');

	parent::init();
	}
}
