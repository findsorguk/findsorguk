<?php
/** Form for setting up and editing provisional valuations
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class ProvisionalValuationForm extends Pas_Form {

public function __construct($options = null) {
	
	$curators = new Peoples();
	$assigned = $curators->getValuers();

	ZendX_JQuery::enableForm($this);

	parent::__construct($options);


	$this->setName('provisionalvaluations');


	$valuerID = new Zend_Form_Element_Select('valuerID');
	$valuerID->setLabel('Valuation provided by: ')
		->setRequired(true)
		->addFilters(array('StripTags','StringTrim'))
		->setAttrib('class', 'span6 selectpicker show-menu-arrow')
		->addValidator('StringLength', false, array(1,25))
		->addValidator('InArray', false, array(array_keys($assigned)))
		->addMultiOptions($assigned);

	$value = new Zend_Form_Element_Text('value');
	$value->setLabel('Estimated market value: ')
		->setRequired(true)
		->addFilters(array('StripTags','StringTrim'))
		->addValidator('Float');
	
	$comments  = new Pas_Form_Element_RTE('comments');
	$comments->setLabel('Valuation comments: ')
		->setRequired(false)
		->setAttrib('rows',10)
		->setAttrib('cols',40)
		->setAttrib('Height',400)
		->setAttrib('ToolbarSet','Finds')
		->addFilters(array('StringTrim', 'BasicHtml', 'EmptyParagraph', 'WordChars'));
	
	$dateOfValuation = new ZendX_JQuery_Form_Element_DatePicker('dateOfValuation');
	$dateOfValuation->setLabel('Valuation provided on: ')
		->setRequired(true)
		->setJQueryParam('dateFormat', 'yy-mm-dd')
		->addFilters(array('StripTags','StringTrim'))
		->setAttrib('size', 20)
		->addValidator('Datetime');
	
	$submit = new Zend_Form_Element_Submit('submit');
	
	$this->addElements(array(
	$valuerID, $value, $dateOfValuation,
	$comments, $submit
	));
	
	$this->addDisplayGroup(array(
	'valuerID', 'value', 'dateOfValuation',
	'comments'), 'details');
	
	$this->addDisplayGroup(array('submit'), 'buttons');
	parent::init();
	}
}