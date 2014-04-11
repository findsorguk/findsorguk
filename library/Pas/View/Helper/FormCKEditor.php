<?php
class Pas_View_Helper_FormCKEditor extends ZendX_JQuery_View_Helper_UiWidget
{
    static $set = false;

    public function formCKEditor($name, $value = null, $params = null, $attribs = null)
    {
        $hTextA = new Zend_View_Helper_FormTextarea();
        $hTextA -> setView($this -> view);
        $xhtml = $hTextA -> formTextarea($name, $value, $attribs);
        $this->view->jQuery()->addJavascript('$(document).ready(function(){$("#' . $this->_normalizeId($name) . '").ckeditor(' . (!is_null($params) ? 'function(){},' . Zend_Json_Encoder::encode($params) : '') . ')});');
        
        if (self::$set == false) {
            $this->view->jQuery()->addJavascriptFile($this->view->baseUrl() . '/js/ckeditor/ckeditor.js');
            $this->view->jQuery()->addJavascriptFile($this->view->baseUrl() . '/js/ckeditor/adapters/jquery.js');
            self::$set = true;
        }
        return $xhtml;
    }
}