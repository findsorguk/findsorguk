<?php
/** A view helper for rendering Rich text editor
 * @category Pas
 * @package View
 * @subpackage Helper
 * @todo remove?
 */
class Pas_View_Helper_RTE extends Zend_View_Helper_FormElement
{
    /**
     * Generates a richtext element using FCKeditor.
     *
     * @access public
     *
     * @param string|array $name If a string, the element name.  If an
     *                           array, all other parameters are ignored, and the array elements
     *                           are extracted in place of added parameters.
     *
     * @param mixed $value The element value.
     *
     * @param array $attribs Attributes for the element tag.
     *
     * @return string The element XHTML.
     */
    public function RTE($name = null, $value = null, $attribs = null)
    {
        if (is_null($name) && is_null($value) && is_null($attribs)) {
            return $this;
        }

        $info = $this->_getInfo($name, $value, $attribs);
        extract($info); // name, value, attribs, options, listsep, disable

        $editor = new fckeditor($name);

        // set variables
        $editor->BasePath = $this->view->baseUrl() . '/js/ckeditor/';
        $editor->ToolbarSet = empty($attribs['ToolbarSet']) ? 'Default' : $attribs['ToolbarSet'];
        $editor->Width = empty($attribs['Width']) ? '100%' : $attribs['Width'];
        $editor->Height = empty($attribs['Height']) ? 200 : $attribs['Height'];
        $editor->Value = $value;

        // set Config
        $editor->Config['BaseHref'] = $editor->BasePath;
        //$editor->Config['CustomConfigurationsPath'] = $editor->BasePath.'editor/fckconfig.js';
        $editor->Config['CustomConfigurationsPath'] = $editor->BasePath . 'ckconfig.js';
//        $editor->Config['SkinPath'] = $editor->BasePath.'editor/skins/silver/';
        return $editor->createHtml();

    }

}
