<?php
/** Filter extension based on HTML purifier for allowing Basic HTML on forms 
 * and displays
 * 
 * An example of code use:
 * 
 * <code>
 * <?php
 * $this->_markdown = new Pas_Filter_EmailTextOnly();
 * $clean = $this->_markdown->filter($string);
 * ?>
 * </code>
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category   Pas
 * @package    Filter
 * @license    GNU General Public License
 * @version 1
 * @example /library/Pas/Controller/Action/Helper/Mailer.php
*/
class Pas_Filter_EmailTextOnly implements Zend_Filter_Interface {
   
    /** The purifier object
     * @access protected
     * @var \HTMLPurifier 
     */
    protected $_htmlPurifier;

    /** Set up the filter's options
     * @access public
     * @param array $options
     */
    public function __construct(array $options = null) {
        $config = HTMLPurifier_Config::createDefault();
        $this->_htmlPurifier = new HTMLPurifier($config);
        $config->set('Cache.SerializerPath',  CACHE_PATH . '/htmlpurifier');
        $config->set('HTML.Allowed', 'a[href]');
        $config->set('AutoFormat.RemoveEmpty.RemoveNbsp',TRUE);
        $config->set('AutoFormat.RemoveEmpty', TRUE);
        $config->set('AutoFormat.Linkify', true);
        $config->set('HTML.TidyLevel', 'heavy'); 
    }

    /** Filter the input
    * @param string $value
    * @return object
    */
    public function filter($value)  {
        return $this->_htmlPurifier->purify($value);
    }
}