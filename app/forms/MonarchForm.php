<?php
/** Form for creating monarch's data
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class MonarchForm extends Pas_Form {
	
public function __construct($options = null) {
	
	$rulers = new Rulers();
	$rulers_options = $rulers-> getAllMedRulers();
	
	$dynasties = new Dynasties();
	$dynasties_options = $dynasties->getOptions();

	parent::__construct($options);
       
	$this->setName('MonarchDetails');

	$name = new Zend_Form_Element_Text('name');
	$name->setLabel('Monarch\'s name: ')
		->setRequired(true)
		->addFilters(array('StripTags','StringTrim', 'Purifier'))
		->setAttrib('size','50')
		->addErrorMessage('You must enter a Monarch\'s name');

	$styled = new Zend_Form_Element_Text('styled');
	$styled->setLabel('Styled as: ')
		->setRequired(false)
		->addFilters(array('StripTags','StringTrim', 'Purifier'));

	$alias = new Zend_Form_Element_Text('alias');
	$alias->setLabel('Monarch\'s alias: ')
		->setRequired(false)
		->addFilters(array('StripTags','StringTrim', 'Purifier'));

	$dbaseID = new Zend_Form_Element_Select('dbaseID');
	$dbaseID->setLabel('Database ID: ')
		->setRequired(false)
		->addFilters(array('StripTags','StringTrim'))
		->setAttrib('class', 'span6 selectpicker show-menu-arrow')
		->addValidator('InArray', false, array(array_keys($rulers_options)))
		->addMultiOptions(array(NULL => NULL, 'Choose Database ID' => $rulers_options));

	$date_from = new Zend_Form_Element_Text('date_from');
	$date_from->setLabel('Issued coins from: ')
		->setRequired(false)
		->addFilters(array('StripTags','StringTrim'))
		->addValidator('Digits');

	$date_to = new Zend_Form_Element_Text('date_to');
	$date_to->setLabel('Issued coins until: ')
		->setRequired(false)
		->addFilters(array('StripTags','StringTrim'))
		->addValidator('Digits');

	$born = new Zend_Form_Element_Text('born');
	$born->setLabel('Born: ')
		->setRequired(false)
		->addFilters(array('StripTags','StringTrim'))
		->addValidator('Digits');

	$died = new Zend_Form_Element_Text('died');
	$died->setLabel('Died: ')
		->setRequired(false)
		->addFilters(array('StripTags','StringTrim'))
		->addValidator('Digits');

	$biography = new Pas_Form_Element_RTE('biography');
	$biography->setLabel('Biography: ')
		->setRequired(false)
		->setAttrib('rows',10)
		->setAttrib('cols',40)
		->setAttrib('Height',400)
		->setAttrib('ToolbarSet','Finds')
		->addFilters(array('StringTrim', 'BasicHtml', 'EmptyParagraph', 'WordChars'));

	$dynasty = new Zend_Form_Element_Select('dynasty');
	$dynasty->setLabel('Dynastic grouping: ')
		->setAttrib('class', 'span6 selectpicker show-menu-arrow')
		->setRequired(false)
		->addFilters(array('StripTags','StringTrim'))
		->addValidator('Digits');

	$publishState = new Zend_Form_Element_Select('publishState');
	$publishState->setLabel('Publication status: ')
		->setRequired(false)
		->setAttrib('class', 'span6 selectpicker show-menu-arrow')
		->addFilters(array('StripTags','StringTrim'))
		->addValidator('Digits')
		->addMultiOptions(array(NULL => NULL, 'Set status' => array('1' => 'Draft','2' => 'Published')))
		->setValue(1);

	$submit = new Zend_Form_Element_Submit('submit');

	$hash = new Zend_Form_Element_Hash('csrf');
	$hash->setValue($this->_salt)->setTimeout(4800);
	
	$this->addElements(array(
	$name, $styled, $alias, 
	$dbaseID, $date_from, $date_to,
	$born, $died, $biography, $dynasty, 
	$publishState, $submit, $hash));
	
	$this->addDisplayGroup(array('name','styled','alias'), 'names');
	$this->names->setLegend('Nomenclature');
	
	$this->addDisplayGroup(array('dbaseID','date_from','date_to','born','died'),'periods');
	$this->periods->setLegend('Dates');
	
	$this->addDisplayGroup(array('biography','dynasty','publishState'),'details');
	$this->details->setLegend('Biographical details');
	
	$this->addDisplayGroup(array('submit'), 'buttons');
	parent::init();  
	}
}