<?php

class My_Decorator_SimpleInput extends Zend_Form_Decorator_Abstract
{
    protected $_format = '<label for="%s">%s</label><input id="%s" name="%s" type="text" value="%s"/>';

    public function render($content)
    {
        $element = $this->getElement();
        $name    = htmlentities($element->getFullyQualifiedName());
        $label   = htmlentities($element->getLabel());
        $id      = htmlentities($element->getId());
        $value   = htmlentities($element->getValue());
        $markup  = sprintf($this->_format, $id, $label, $id, $name, $value);
        return $markup;
    }
}
class DatePickerForm extends Pas_Form
{
public function __construct($options = null) {
	parent::__construct($options);
	       
	$this->setName('datepicker');


$datefrom = new Zend_Form_Element_Text('datefrom');
$datefrom->setLabel('Date from: ')
->setRequired(true)
->addFilters(array('StripTags', 'StringTrim'))
->addValidator('Datetime');

$dateto = new Zend_Form_Element_Text('dateto');
$dateto->setLabel('Date to: ')
->setRequired(true)
->addFilters(array('StripTags', 'StringTrim'))
->addValidator('Datetime');

	$hash = new Zend_Form_Element_Hash('csrf');
	$hash->setValue($this->_salt)->setTimeout(4800);
//Submit button 
$submit = new Zend_Form_Element_Submit('submit');
$this->addElements(array($datefrom,$dateto,$submit, $hash));

$this->setLegend('Choose your own dates: ');
$this->addDisplayGroup(array('submit'), 'buttons');

parent::init();
	}
}