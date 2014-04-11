<?php
/** Form for manipulating numismatic mint data 
* 
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class MintForm extends Pas_Form {

	public function __construct($options = null) {
	
	$periods = new Periods();
	$period_actives = $periods->getMintsActive();

	parent::__construct($options);

	$this->setName('people');

	$mint_name = new Zend_Form_Element_Text('mint_name');
	$mint_name->setLabel('Mint name: ')
		->setRequired(true)
		->addValidator('Alnum',false, array('allowWhiteSpace' => true))
		->addFilters(array('StripTags','StringTrim'))
		->setAttrib('size',70);

	$valid = new Zend_Form_Element_Checkbox('valid');
	$valid->SetLabel('Is this ruler or issuer currently valid: ')
		->setRequired(true)
		->addFilters(array('StripTags','StringTrim'))
		->addValidator('Int');
	
	$period = new Zend_Form_Element_Select('period');
	$period->setLabel('Period: ')
		->setRequired(true)
		->addFilters(array('StripTags','StringTrim'))
		->setAttrib('class', 'span6 selectpicker show-menu-arrow')
		->addMultiOptions(array(
		NULL => 'Choose period',
		'Available periods:' => $period_actives))
		->addValidator('InArray', false, array(array_keys($period_actives)))
		->addErrorMessage('You must enter a period for this mint');

		//Submit button 
	$submit = new Zend_Form_Element_Submit('submit');

	$hash = new Zend_Form_Element_Hash('csrf');
	$hash->setValue($this->_salt)->setTimeout(4800);
	
	$this->addElements(array(
	$mint_name, $valid, $period, $hash,
	$submit));
	
	$this->addDisplayGroup(array('mint_name', 'period', 'valid', 
	'submit'), 'details');
	
	$this->details->setLegend('Mint details: ');
	
	parent::init();
	}
}