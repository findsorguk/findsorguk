<?php
/**
* Render a password element button and label
*
* @category   Pas
* @package    Form
* @subpackage Decorator
* @copyright  Copyright (c) 2005-2011 Zend Technologies USA Inc. (http://www.zend.com)
* @license    GNU General Public License

*/
class Pas_Form_Decorator_PasswordInput extends Zend_Form_Decorator_Abstract
{
    protected $_format = '<li><label for="%s">%s</label><input id="%s" name="%s" type="password" value="%s"/></li>';

	/**
	* Render the normal button as displayed in html $_format
	* @param string $content
	* @return string
	*/
    public function render($content) {
	$element = $this->getElement();
	$name    = htmlentities($element->getFullyQualifiedName());
	$label   = htmlentities($element->getLabel());
	$id      = htmlentities($element->getId());
	$value   = htmlentities($element->getValue());
	$markup  = sprintf($this->_format, $id, $label, $id, $name, $value);
	return $markup;
    }
}
 
