<?php
/** Filter extension based on HTML purifioer for allowing Basic HTML on forms and 
 * displays
 * 
 * An example of code use:
 * 
 * <code>
 * <?php
 * $termdesc = new Pas_Form_Element_CKEditor('termdesc');
 * $termdesc->setLabel('Activity description: ')
 * 		->setRequired(true)
 * 		->addFilter('BasicHtml');
 * ?>
 * </code>
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category Pas
 * @package Filter
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @example /app/forms/ActivityForm.php
 */
class Pas_Filter_BasicHtml implements Zend_Filter_Interface {
   
    /** The HTML purifier class
     * @access protected
     * @var \HTMLPurifier
     */
    protected $_htmlPurifier;
   
    /** Set up the filter's options
     * @access public
     * @param array $options
     */
    public function __construct() {
        $config = HTMLPurifier_Config::createDefault();
        $this->_htmlPurifier = new HTMLPurifier($config);
        $config->set('Core.Encoding', 'UTF-8');
        $config->set('Cache.SerializerPath',  CACHE_PATH . '/htmlpurifier');
        $config->set('HTML.Doctype', 'XHTML 1.0 Strict');
        $config->set('HTML.Allowed', 'br,p,em,h1,h2,h3,h4,h5,strong,'
            . 'a[href|title|class|rel],ul,ol,li,code,pre,'
            . 'blockquote,img[src|alt|height|width|class],'
            . 'sub,sup,br,span[class|id],div[class|id],'
            . 'table[border|cellspacing|cellpadding|width|summary|style],'
            . 'tr,tbody,td[colspan|abbr|rowspan|valign],'
            . 'th[abbr|colspan|rowspan|valign]'
        );
        $config->set('AutoFormat.RemoveEmpty.RemoveNbsp',false);
        $config->set('AutoFormat.RemoveEmpty', true);
        $config->set('AutoFormat.Linkify', true);
        $config->set('HTML.TidyLevel', 'heavy');
    }

    /** Filter the input
    * @param string $value
    * @return object
    */
    public function filter($value) {
        $value = str_replace('&nbsp;',' ', $value);
        return $this->_htmlPurifier->purify($value);
    }
}