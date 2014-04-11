<?php
require_once 'Zend/Form/Element/Xhtml.php';

/**
 * Textarea form element
 * 
 * @category   Zend
 * @package    Zend_Form
 * @subpackage Element
 * @copyright  Copyright (c) 2005-2008 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: Textarea.php 8064 2008-02-16 10:58:39Z thomas $
 */
class Pas_Form_Element_TinyMce extends Zend_Form_Element_Xhtml
{
    /**
     * Use formTextarea view helper by default
     * @var string
     */
    public $helper = 'FormTinyMce';
}