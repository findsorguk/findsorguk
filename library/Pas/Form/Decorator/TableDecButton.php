<?php
class Pas_Form_Decorator_TableDecButton extends Zend_Form_Decorator_Abstract
{
    protected $_format = '<td><input id="%s" name="%s" type="submit" value="%s"/></td>';

    public function render($content)
    {
        $element = $this->getElement();
        $value    = htmlentities($element->getValue());
        $label   = htmlentities($element->getLabel());
        $id      = htmlentities($element->getId());
        $name   = htmlentities($element->getValue());

        $markup  = sprintf($this->_format, $id, $label, $id, $name, $value);
        return $markup;
    }
}
 
