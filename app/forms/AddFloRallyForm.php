<?php
/**
* Form for cross referencing finds liaison officers to rallies
*
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
* @author     Daniel Pett
* @version    1.1
* @since	  7 October 2011
*/
class AddFloRallyForm extends Pas_Form{


	public function __construct($options = null) {

	$staff = new Contacts();
	$flos = $staff->getAttending();

	parent::__construct($options);

	ZendX_JQuery::enableForm($this);
	$this->setName('addFlo');


	$flo = new Zend_Form_Element_Select('staffID');
	$flo->setLabel('Finds officer present: ')
	->setRequired(true)
	->addFilters(array('StringTrim','StripTags'))
	->addValidator('Int')
	->addMultiOptions(array(NULL => 'Choose attending officer', 'Our staff members' => $flos))
	->setAttribs(array('class' => 'span6 selectpicker show-menu-arrow'));

	$dateFrom = new ZendX_JQuery_Form_Element_DatePicker('dateFrom');
	$dateFrom->setLabel('Attended from: ')
	->setRequired(true)
	->setJQueryParam('dateFormat', 'yy-mm-dd')
	->addValidator('Datetime')
	->addFilters(array('StripTags', 'StringTrim'))
	->addValidator('NotEmpty')
	->setAttrib('size', 20);


	$dateTo = new ZendX_JQuery_Form_Element_DatePicker('dateTo');
	$dateTo->setLabel('Attended to: ')
	->setRequired(false)
	->setJQueryParam('dateFormat', 'yy-mm-dd')
	->addValidator('Datetime')
	->addFilters(array('StripTags', 'StringTrim'))
	->setAttrib('size', 20);

	$submit = new Zend_Form_Element_Submit('submit');

	$this->addElements(array($flo, $dateFrom, $dateTo, $submit));

	$this->addDisplayGroup(array('staffID', 'dateFrom', 'dateTo'), 'details');

	$this->details->setLegend('Attending Finds Officers');

	$this->addDisplayGroup(array('submit'), 'buttons');

	parent::init();
	}
}