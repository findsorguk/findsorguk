<?php
/**
* Render a normal button output for forms
*
*
* @category   Pas
* @package    Form
* @subpackage Decorator
* @copyright  Copyright (c) 2005-2011 Zend Technologies USA Inc. (http://www.zend.com)
* @license    GNU General Public License

*/
class Pas_Form_Decorator_NormalDecButton extends Zend_Form_Decorator_Abstract
{
    protected $_format = '<div><input id="%s" name="%s" type="submit" value="%s" label=%s/></div>';

    /**
     * Render the normal button as displayed in html $_format
     * @param string $content
     * @return string
     */
    public function render($content) {
	$element = $this->getElement();
	$value = htmlentities($element->getValue());
	$label = htmlentities($element->getLabel());
	$id = htmlentities($element->getId());
	$name = htmlentities($element->getName());
	$markup  = sprintf($this->_format, $id, $label, $id, $name, $value);
	return $markup;
    }
}
 
