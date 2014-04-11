<?php
/** Form for adding and editing coin references. Never understood why we need this.
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class ReferenceCoinForm extends Pas_Form {

public function __construct($options = null) {

	$refs = new Coinclassifications();
	$ref_list = $refs->getClass();

	parent::__construct($options);
	$this->setName('addcoinreference');

	$classID = new Zend_Form_Element_Select('classID');
	$classID->setLabel('Publication title: ')
		->setRequired(true)
		->setAttrib('class', 'span6 selectpicker show-menu-arrow')
		->addMultiOptions(array(NULL => 'Choose reference','Valid choices' => $ref_list))
		->addValidator('InArray', false, array(array_keys($ref_list)))
		->addFilters(array('StripTags', 'StringTrim'))
		->addErrorMessage('You must enter a title');


	$volume = new Zend_Form_Element_Text('vol_no');
	$volume->setLabel('Volume number: ')
		->addFilters(array('StripTags', 'StringTrim', 'Purifier'))
		->setAttrib('size',9);

	$reference = new Zend_Form_Element_Text('reference');
	$reference->setLabel('Reference number: ')
		->addFilters(array('StripTags', 'StringTrim', 'Purifier'))
		->setAttrib('size', 15);

	//Submit button
	$submit = new Zend_Form_Element_Submit('submit');

	$hash = new Zend_Form_Element_Hash('csrf');
	$hash->setValue($this->_salt)->setTimeout(4800);

	$this->addElements(array(
	$classID, $volume, $reference,
	$submit, $hash));


	$this->addDisplayGroup(array('classID','vol_no','reference'), 'details');

	$this->details->setLegend('Add a new reference');

	$this->addDisplayGroup(array('submit'),'buttons');

	parent::init();
	}
}
