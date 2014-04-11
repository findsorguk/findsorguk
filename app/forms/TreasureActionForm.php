<?php
/** Form for assigning Treasure case actions
* 
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class TreasureActionForm extends Pas_Form
{

public function __construct($options = null) {
	
	$actionTypes = new TreasureActionTypes();
	$actionlist = $actionTypes->getList();

	ZendX_JQuery::enableForm($this);
	
	parent::__construct($options);

	

	$this->setName('actionsForTreasure');

	$actionDescription = new Pas_Form_Element_RTE('actionTaken');
	$actionDescription->setLabel('Action summary: ')
		->setRequired(true)
		->setAttribs(array('rows' => 10, 'cols' => 40, 'Height' => 400,
		'ToolbarSet' => 'Basic'))
		->addFilters(array('StringTrim', 'WordChars', 'BasicHtml', 'EmptyParagraph'));

	$action = new Zend_Form_Element_Select('actionID');
	$action->setLabel('Type of action taken: ')
		->setRequired(true)
		->addFilters(array('StringTrim', 'StripTags'))
		->setAttrib('class', 'span6 selectpicker show-menu-arrow')
		->addValidator('InArray', false, array(array_keys($actionlist)))
		->addMultiOptions($actionlist);

	$submit = new Zend_Form_Element_Submit('submit');
	$hash = new Zend_Form_Element_Hash('csrf');
	$hash->setValue($this->_salt)
		->setTimeout(4800);
	
	$this->addElements(array(
	$action, $actionDescription, $submit, $hash
	));
	
	$this->addDisplayGroup(array('actionID','actionTaken',), 'details');
	$this->addDisplayGroup(array('submit'), 'buttons');
	
	parent::init();
	}
}
