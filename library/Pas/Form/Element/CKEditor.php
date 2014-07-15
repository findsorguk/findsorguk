<?php
/** A form element for rendering the rich text editor interface
 * 
 * An example code use:
 * 
 * <code>
 * <?php
 * $researchOutline = new Pas_Form_Element_CKEditor('researchOutline');
 * ?>
 * </code>
 * 
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @version 1
 * @category Pas
 * @package Pas_Form
 * @subpackage Element
 * @license http://URL name
 * @example /app/forms/AcceptUpgradeForm.php
 */
class Pas_Form_Element_CKEditor extends ZendX_JQuery_Form_Element_UiWidget
{
    /** Use formCKeditor view helper by default
     * @access public
     * @var string
     */
    public $helper = 'formCKEditor';

    /** Default ckeditor options
     * @access public
     * @var array
     */
    public $jQueryParams = array(
//        'toolbar' => 'Basic'
    );
}