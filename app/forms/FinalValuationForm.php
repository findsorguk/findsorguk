<?php
/** Form for manipulating treasure valuation data
* 
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
* @todo		  Sort out the currency validator for £
*/
class FinalValuationForm extends Pas_Form {

	public function __construct($options = null) {


	ZendX_JQuery::enableForm($this);
	
	parent::__construct($options);
	
	$this->setName('finalvaluation');


	$value = new Zend_Form_Element_Text('value');
	$value->setLabel('Estimated market value: ')
	->setRequired(true)
	->addFilters(array('StripTags','StringTrim'))
	->addValidator('Currency');

	$comments  = new Pas_Form_Element_RTE('comments');
	$comments->setLabel('Valuation comments: ')
	->setRequired(false)
	->setAttrib('rows',10)
	->setAttrib('cols',40)
	->setAttrib('Height',400)
	->setAttrib('ToolbarSet','Finds')
	->addFilters(array('StripTags','StringTrim', 'BasicHtml','EmptyParagraph'));

	$dateOfValuation = new ZendX_JQuery_Form_Element_DatePicker('dateOfValuation');
	$dateOfValuation->setLabel('Valuation provided on: ')
	->setRequired(true)
	->setJQueryParam('dateFormat', 'yy-mm-dd')
	->addFilters(array('StripTags','StringTrim'))
	->addValidator('Datetime')
	->addErrorMessage('You must enter a chase date')
	->setAttrib('size', 20);

	$submit = new Zend_Form_Element_Submit('submit');

	$hash = new Zend_Form_Element_Hash('csrf');
	$hash->setValue($this->_salt)
	->setTimeout(60);

	$this->addElements(array(
	$value,	$dateOfValuation, $comments, $submit, $hash
	));
	
	$this->addDisplayGroup(array(
	'value',
	'dateOfValuation',
	'comments'), 'details');
	
	$this->addDisplayGroup(array('submit'), 'buttons');
	
	parent::init();
	}
}