<?php
class Pas_Form_Decorator_SimpleTextArea extends Zend_Form_Decorator_Abstract
{
    protected $_format = '<li><label for="termdesc" class="required">Activity description:</label><br>
<textarea name="%s" id="%s" value=%s rows=%s cols=%s></textarea></li>';

    public function render($content)
    {
        $element = $this->getElement();
        $name    = htmlentities($element->getFullyQualifiedName());
        $label   = htmlentities($element->getLabel());
        $id      = htmlentities($element->getId());
        $value   = htmlentities($element->getValue());
		$rows = htmlentities($element->getAttrib('rows'));
		$cols = htmlentities($element->getAttrib('cols'));
        $markup  = sprintf($this->_format, $id, $label, $id, $name, $value,$rows,$cols);
        return $markup;
    }
}
 
