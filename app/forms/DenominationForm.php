<?php
/** Form for entering data about denominations
*
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class DenominationForm extends Pas_Form
{

public function __construct($options = null)
{
	//Get data to form select menu for periods
	$periods = new Periods();
	$period_options = $periods->getPeriodFrom();

	//Materials menu
	$materials = new Materials();
	$material_options = $materials->getMetals();

	parent::__construct($options);

	$this->setName('denomination');

	$denomination = new Zend_Form_Element_Text('denomination');
	$denomination->setLabel('Denomination name: ')
	->setRequired(true)
	->setAttrib('size',70)
	->addErrorMessage('Please enter a term.');

	//Period from: Assigned via dropdown
	$period = new Zend_Form_Element_Select('period');
	$period->setLabel('Period assigned to: ')
	->setRequired(true)
	->addFilters(array('StripTags','StringTrim'))
	->setAttrib('class', 'span6 selectpicker show-menu-arrow')
	->addValidator('InArray', false, array(array_keys($period_options)))
	->addMultiOptions(array(NULL => NULL, 'Choose period from' => $period_options))
	->addErrorMessage('You must enter a period for this denomination');

	//Primary material
	$material = new Zend_Form_Element_Select('material');
	$material->setLabel('Material: ')
	->setRequired(false)
	->setAttrib('class', 'span6 selectpicker show-menu-arrow')
	->addFilters(array('StripTags','StringTrim'))
	->addValidator('InArray', false, array(array_keys($material_options)))
	->addMultiOptions(array(NULL => NULL,'Choose material' => $material_options))
	->addErrorMessage('You must enter a material for this denomination.');

	$description = new Pas_Form_Element_RTE('description');
	$description->setLabel('Description: ')
	->setRequired(false)
	->setAttribs(array('rows' => 10, 'cols' => 40, 'Height' => 400))
	->setAttrib('ToolbarSet','Finds')
	->addFilters(array('BasicHtml', 'EmptyParagraph', 'WordChars'))
	->addErrorMessage('You must enter a description for this denomination.');

	$rarity = new Zend_Form_Element_Textarea('rarity');
	$rarity->setLabel('Rarity: ')
	->setRequired(false)
	->setAttrib('rows',10)
	->setAttrib('cols',70)
	->addFilters(array('BasicHtml', 'EmptyParagraph', 'WordChars'));

	$weight = new Zend_Form_Element_Text('weight');
	$weight->setLabel('Weight: ')
	->setRequired(false)
	->addFilters(array('StripTags','StringTrim'))
	->setAttrib('size',5);

	$diameter = new Zend_Form_Element_Text('diameter');
	$diameter->setLabel('Diameter: ')
	->setRequired(false)
	->setAttrib('size',5)
	->addFilters(array('StripTags','StringTrim'));

	$thickness = new Zend_Form_Element_Text('thickness');
	$thickness->setLabel('Thickness: ')
	->setRequired(false)
	->setAttrib('size',5)
	->addFilters(array('StripTags','StringTrim'));

	$valid = new Zend_Form_Element_Checkbox('valid');
	$valid->setLabel('Denomination in use: ')
	->setRequired(true)
	->addFilters(array('StripTags','StringTrim'))
	->addErrorMessage('You must set a status');

	$submit = new Zend_Form_Element_Submit('submit');

	$hash = new Zend_Form_Element_Hash('csrf');
	$hash->setValue($this->_salt)->setTimeout(4800);
	
	$this->addElements(array(
	$denomination, $period,	$material,
	$description, $weight, $rarity,
	$thickness, $diameter, $valid,
	$submit, $hash));

	$this->addDisplayGroup(array(
            'denomination','period','material',
            'description','rarity','thickness',
            'diameter','weight','valid'), 'details');

	$this->addDisplayGroup(array('submit'), 'buttons');

	$this->details->setLegend('Denomination details: ');
	parent::init();
	}
}