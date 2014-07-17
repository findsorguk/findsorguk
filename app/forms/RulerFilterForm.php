<?php
/** Form for filtering rulers
* 
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
*/
class RulerFilterForm extends Pas_Form
{
public function __construct(array $options)
{
	parent::__construct($options);
	$this->setName('filterruler');
	
	$ruler = new Zend_Form_Element_Text('ruler');
	$ruler->setLabel('Filter by name')
		->setRequired(false)
		->addFilters(array('StringTrim', 'StripTags'))
		->addErrorMessage('Come on it\'s not that hard, enter a title!')
		->setAttrib('size', 20);
	
	$hash = new Zend_Form_Element_Hash('csrf');
	$hash->setValue($this->_salt)->setTimeout(4800);
	
	//Submit button 
	$submit = new Zend_Form_Element_Submit('submit');
	$submit->setLabel('Filter');
	
	$this->addElements(array(
	$ruler,	$submit, $hash));
	parent::init();  
	}
}