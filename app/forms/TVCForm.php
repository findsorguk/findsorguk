<?php
/** Form for adding and editing TVC dates and details
* 
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class TVCForm extends Pas_Form {

public function __construct($options = null) {
	
	ZendX_JQuery::enableForm($this);
	
	parent::__construct($options);

	$this->setName('tvcdates');

	$date = new ZendX_JQuery_Form_Element_DatePicker('date');
	$date->setLabel('Date of TVC: ')
		->setRequired(true)
		->setJQueryParam('dateFormat', 'yy-mm-dd')
		->addFilters(array('StripTags','StringTrim'))
		->addErrorMessage('You must enter a chase date')
		->addValidator('Datetime')
		->setAttrib('size', 20);

	$location = new Zend_Form_Element_Text('location');
	$location->setLabel('Location of meeting: ')
		->setRequired(true)
		->addFilters(array('StripTags','StringTrim'))
		->addErrorMessage('You must enter a location')
		->addValidator('Alnum',false,array('allowWhiteSpace' => true));

	$submit = new Zend_Form_Element_Submit('submit');

	$hash = new Zend_Form_Element_Hash('csrf');
	$hash->setValue($this->_salt)->setTimeout(4800);
	
	$this->addElements(array(
	$date, $location, $submit,
	$hash
	));


	$this->addDisplayGroup(array('date','location'), 'details');

	$this->addDisplayGroup(array('submit'), 'buttons');

	parent::init();
	}
}