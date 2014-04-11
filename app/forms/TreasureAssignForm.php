<?php
/** Form for assignation by curator
* 
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class TreasureAssignForm extends Pas_Form
{

public function __construct($options = null)
{
	$curators = new Peoples();
	$assigned = $curators->getCurators();
	
	ZendX_JQuery::enableForm($this);
	
	parent::__construct($options);
	$this->setName('actionsForTreasure');
	
	$curatorID = new Zend_Form_Element_Select('curatorID');
	$curatorID->setLabel('Curator assigned: ')
	->setRequired(true)
	->setAttrib('class', 'span6 selectpicker show-menu-arrow')
	->addValidator('InArray', false, array(array_keys($assigned)))
	->addMultiOptions($assigned);
	
	$chaseDate = new ZendX_JQuery_Form_Element_DatePicker('chaseDate');
	$chaseDate->setLabel('Chase date assigned: ')
		->setRequired(true)
		->setJQueryParam('dateFormat', 'yy-mm-dd')
		->addFilters(array('StringTrim','StripTags'))
		->addErrorMessage('You must enter a chase date')
		->setAttrib('size', 20);
	
	$hash = new Zend_Form_Element_Hash('csrf');
	$hash->setValue($this->_salt)->setTimeout(4800);
		
	$submit = new Zend_Form_Element_Submit('submit');
	$this->addElements(array(
	$curatorID, $chaseDate, $submit, $hash
	));
	
	$this->addDisplayGroup(array('curatorID','chaseDate'), 'details');
	
	$this->addDisplayGroup(array('submit'), 'buttons');
	
	parent::init();
	}
}