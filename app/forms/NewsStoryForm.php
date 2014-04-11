<?php
/** Form for creating and editing news stories for the Scheme website
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class NewsStoryForm extends Pas_Form {

	public function __construct($options = null) {
	
	parent::__construct($options);
	
	ZendX_JQuery::enableForm($this);
	
	$this->setName('newsstory');
	
	
	$date = Zend_Date::now()->toString('yyyy-MM-dd');
	
	$title = new Zend_Form_Element_Text('title');
	$title->setLabel('News story title: ')
		  ->setRequired(false)
		  ->setAttrib('size',60)
		  ->addErrorMessage('Please enter a title for this story.');


	$summary = new Zend_Form_Element_Textarea('summary');
	$summary->setLabel('Short summary: ')
		->setRequired(true)
		->setAttrib('rows',5)
		->setAttrib('cols',70)
		->addFilters(array('StripTags','StringTrim','Purifier'));
	
	$contents = new Pas_Form_Element_RTE('contents');
	$contents->setLabel('News story content: ')
		->setRequired(true)
		->setAttrib('rows',10)
		->setAttrib('cols',40)
		->setAttrib('Height',400)
		->addFilters(array('StringTrim', 'HtmlBody', 'EmptyParagraph', 'WordChars'));
	
	$address = new Zend_Form_Element_Text('primaryNewsLocation');
	$address->setLabel('News address (puts it on map): ')
		->setRequired(true)
		->setAttrib('size',50)
		->addFilters(array('StripTags','StringTrim','Purifier'));
	
	$author = new Zend_Form_Element_Text('author');
	$author->setLabel('Principal author: ')
		->setRequired(true)
		->setAttrib('size',60)
		->addErrorMessage('Please enter a title for this story.')
		->addFilters(array('StripTags','StringTrim', 'Purifier'));

	$contactEmail = new Zend_Form_Element_Text('contactEmail');
	$contactEmail->setLabel('Contact email address: ')
		->setRequired(false)
		->setAttrib('size',50)
		->addErrorMessage('Please enter a valid email.')
		->addFilters(array('StripTags','StringTrim', 'StringToLower'))
		->addValidator('EmailAddress');
	
	$contactName = new Zend_Form_Element_Text('contactName');
	$contactName->setLabel('Contact name: ')
		->setRequired(true)
		->setAttrib('size',50)
		->addErrorMessage('Please enter a contact for this story.')
		->addFilters(array('StripTags','StringTrim','Purifier'));
	
	$contactTel = new Zend_Form_Element_Text('contactTel');
	$contactTel->setLabel('Contact telephone number: ')
		->addFilters(array('StripTags','StringTrim'))
		->setRequired(false)
		->addErrorMessage('Please enter a valid telephone number.');
	
	$keywords = new Zend_Form_Element_Text('keywords');
	$keywords->setLabel('Keywords for the story: ')
		->setRequired(false)
		->addFilters(array('StripTags','StringTrim'))
		->setAttrib('size',50)
		->addErrorMessage('Please enter a valid tags.');
			
	$golive = new ZendX_JQuery_Form_Element_DatePicker('golive');
	$golive->setLabel('News story to go live: ')
		->setRequired(true)
		->setJQueryParam('dateFormat', 'yy-mm-dd')
		->addFilters(array('StripTags','StringTrim'))
		->setAttrib('size', 20);
	
	$publishstate = new Zend_Form_Element_Radio('publish_state');
	$publishstate->setLabel('Publication state: ')
		->addMultiOptions(array('0' => 'Draft','1' => 'Publish',))
		->setValue(1)
		->addFilters(array('StripTags','StringTrim'));
	
	$submit = new Zend_Form_Element_Submit('submit');

	
	$hash = new Zend_Form_Element_Hash('csrf');
	$hash->setValue($this->_salt)->setTimeout(4800);

	$this->addElements(array( 
	$title, $summary, $contents,
	$author, $contactEmail, $contactTel,
	$contactName, $keywords, $address,
	$golive, $publishstate, $submit, $hash));


	
	$this->addDisplayGroup(array(
	'title', 'summary', 'contents', 
	'author', 'contactName', 'contactTel', 
	'contactEmail', 'primaryNewsLocation', 'keywords',
	'golive', 'publish_state'), 
	'details');
	
	$this->details->setLegend('Story details: ');
	
	$this->addDisplayGroup(array('submit'), 'buttons');

	parent::init();
	}
}