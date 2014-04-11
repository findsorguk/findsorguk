<?php

/** Form for manipulating events details
*
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class CalendarForm extends Pas_Form {

public function __construct($options = null) {

	$eventtypes = new EventTypes();
	$event_options = $eventtypes->getTypesWords();
	$event_options['Leave'] = 'Leave';
	$event_options['Celebration'] = 'Celebration';
	$event_options['Demonstration'] = 'Demonstration';
	$event_options['Workshop'] = 'Workshop';
	$event_options['TVC'] = 'TVC';
	$event_options['Project meeting'] = 'Project meeting';
	$event_options['Hack day'] = 'Hack day';
	$event_options['Regional Meeting'] = 'Regional Meeting';
	$event_options['National Meeting'] = 'National Meeting';
	$event_options['Celebration'] = 'Celebration/Party';
	$event_options['Training course'] = 'Training course';
	
	ZendX_JQuery::enableForm($this);

	parent::__construct($options);


	$this->setName('event');

	$eventTitle = new Zend_Form_Element_Text('title');
	$eventTitle->setLabel('Event title: ')
	->setRequired(true)
	->addErrorMessage('You must enter an event title')
	->addFilters(array('StripTags', 'StringTrim', 'BasicHtml'))
	->setAttrib('size',70)
	->setAttrib('class', 'span8');

	$eventDescription = new Zend_Form_Element_Textarea('content');
	$eventDescription->setLabel('Event description: ')
	->setRequired(true)
	->addFilters(array('StringTrim','WordChars'))
	->setAttrib('rows',10)
	->setAttrib('cols',40)
	->setAttrib('Height',400)
	->setAttrib('class', 'span8')
	->addErrorMessage('You must enter a description');

	$address = new Zend_Form_Element_Text('location');
	$address->setLabel('Address: ')
	->setRequired(true)
	->addErrorMessage('You must enter an address/location')
	->setAttrib('class','span8')
	->addFilters(array('StripTags','StringTrim', 'BasicHtml'));

	$eventStartTime = new Zend_Form_Element_Text('startTime');
	$eventStartTime->setLabel('Event start time: ')
	->setRequired(true)
	->addFilters(array('StripTags','StringTrim'))
	->addValidator(new Zend_Validate_Date('H:i:s'))
	->addErrorMessage('You must enter a time for the start of the event')
	->setAttribs(array('placeholder' =>  'Enter in 24 hour clock format eg 11:00 not 1100 or 11.00', 'class' => 'span8'));

	$eventEndTime = new Zend_Form_Element_Text('endTime');
	$eventEndTime->setLabel('Event end time: ')
	->setRequired(true)
	->addFilters(array('StripTags','StringTrim'))
	->addValidator(new Zend_Validate_Date('H:i:s'))
	->addErrorMessage('You must enter a time for the end of the event')
	->setAttribs(array('placeholder' =>  'Enter in 24 hour clock format eg 11:00 not 1100 or 11.00', 'class' => 'span8'));

	$eventStartDate = new ZendX_JQuery_Form_Element_DatePicker('startDate');
	$eventStartDate->setLabel('Event start date: ')
	->setRequired(true)
	->setJQueryParam('dateFormat', 'yy-mm-dd')
	->addFilters(array('StripTags','StringTrim'))
	->addValidator('Datetime')
	->setAttrib('placeholder','Format of YYYY-mm-dd')
	->addErrorMessage('You must enter a date')
	->setAttrib('size', 20);

	$eventEndDate = new ZendX_JQuery_Form_Element_DatePicker('endDate');
	$eventEndDate->setLabel('Event end date: ')
	->setRequired(true)
	->setJQueryParam('dateFormat', 'yy-mm-dd')
	->addFilters(array('StripTags','StringTrim'))
	->addValidator('Datetime')
	->setAttrib('placeholder','Format of YYYY-mm-dd')
	->addErrorMessage('You must enter a date')
	->setAttrib('size', 20);


	$eventType = new Zend_Form_Element_Select('eventType');
	$eventType->setLabel('Type of event: ')
	->setRequired(true)
	->addFilters(array('StripTags','StringTrim'))
	->setAttrib('class', 'span6 selectpicker show-menu-arrow')
	->addValidator('stringLength', false, array(1,50))
	->addValidator('inArray', false, array(array_keys($event_options)))
	->addMultiOptions(array(null => 'Choose type of event', 'Available options' => $event_options));

	
	$submit = new Zend_Form_Element_Submit('submit');

	$this->addElements(array(
            $eventTitle, $eventDescription, $eventStartTime,
            $eventEndTime, $eventStartDate, $eventEndDate, 
            $address, $eventType, $submit
	));

	$this->addDisplayGroup(array(
            'title', 'content', 'location',
            'startTime', 'endTime', 'startDate',
            'endDate', 'eventType'), 'details');

	$this->addDisplayGroup(array('submit'), 'buttons');

	parent::init();
	}
}