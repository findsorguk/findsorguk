<?php
/** Filter extension based on HTML purifioer for allowing Basic HTML on forms and displays
 * 
 * If you don't believe in this, read Padraig O'Braidy's blog!

 * 
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @category Pas
 * @package Filter
 * @see http://blog.astrumfutura.com/tag/htmlpurifier/
 * @see http://htmlpurifier.org/
 * 
*/
class Pas_Filter_HTMLPurifier implements Zend_Filter_Interface {
   
    /** The html purifier instance
     * @access protected
     * @var \HTMLPurifier
     */
    protected $_htmlPurifier;
    
    /** Constructor 
     * @access public
     * @param array $options
     */
    public function __construct(array $options = null){
	$config = HTMLPurifier_Config::createDefault();
	$this->_htmlPurifier = new HTMLPurifier($config);
	$config->set('Cache.SerializerPath',  CACHE_PATH . '/htmlpurifier');
	$config->set('HTML.Allowed', 'p,em,h1,h2,h3,h4,h5,strong,'
                . 'a[href|title|class|rel],ul,ol,li,code,pre,'
                . 'blockquote,img[src|alt|height|width|class],'
                . 'sub,sup,br,span[class|id],div[class|id],'
                . 'table[class|id|summary|width], caption, tbody,'
                . ' td, tfoot, th, thead, tr');
	$config->set('AutoFormat.RemoveEmpty.RemoveNbsp', true);
	$config->set('AutoFormat.RemoveEmpty', true);
	$config->set('AutoFormat.Linkify', true);
	$config->set('Attr.AllowedRel', 'nofollow,print,lightbox');
	$config->set('AutoFormat.AutoParagraph', true);
	$config->set('HTML.TidyLevel', 'medium');
    }
    
    /** Filter the value being sent
     * @access public
     * @param string $value
     * @return string
     */
    public function filter($value)  {
        return $this->_htmlPurifier->purify($value);
    }

}