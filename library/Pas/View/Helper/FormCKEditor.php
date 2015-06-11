<?php

/** A view helper for rendering CKEditor within forms using ZendX_Jquery
 *
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @category Pas
 * @package View
 * @subpackage Helper
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 */
class Pas_View_Helper_FormCKEditor extends ZendX_JQuery_View_Helper_UiWidget
{
    /** The set variable
     *
     * @var bool
     */
    static $set = false;

    /** The function to return
     * @param string $name
     * @param string $value
     * @param array $params
     * @param array $attribs
     * @return string
     * @access public
     */
    public function formCKEditor($name, $value = null, $params = null, $attribs = null)
    {
        $hTextA = new Zend_View_Helper_FormTextarea();
        $hTextA->setView($this->view);
        $xhtml = $hTextA->formTextarea($name, $value, $attribs);
        $this->view->jQuery()->addJavascript('$(document).ready(function () {$("#' . $this->_normalizeId($name) . '").ckeditor(' . (!is_null($params) ? 'function () {},' . Zend_Json_Encoder::encode($params) : '') . ')});');

        if (self::$set == false) {
            $this->view->jQuery()->addJavascriptFile($this->view->baseUrl() . '/js/ckeditor/ckeditor.js');
            $this->view->jQuery()->addJavascriptFile($this->view->baseUrl() . '/js/ckeditor/adapters/jquery.js');
            self::$set = true;
        }

        return $xhtml;
    }
}
