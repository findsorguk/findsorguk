<?php
/** Filter extension based on HTML purifier for string arming html removal.
 * 
 * An example of code use:
 * 
 * <code>
 * <?php
 * $firstName->setRequired(true)
 * ->addFilters(array('StripTags', 'StringTrim', 'Purifier'));
 * ?>
 * </code>
 * 
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @version 1
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @category   Pas
 * @package    Pas_Filter
 * @example /app/forms/EditAccountForm.php
 */
class Pas_Filter_Purifier implements Zend_Filter_Interface {
   
    /** The html purifier instance
     * @access protected
     * @var \HTMLPurifier
     */
    protected $_htmlPurifier;
    
    /** The constructor
     * @access public
     * @return void
     */
    public function __construct(){
	$config = HTMLPurifier_Config::createDefault();
	$this->_htmlPurifier = new HTMLPurifier($config);
	$config->set('Cache.SerializerPath',  CACHE_PATH . '/htmlpurifier');
	$config->set('HTML.Allowed', '');
	$config->set('AutoFormat.RemoveEmpty.RemoveNbsp',TRUE);
	$config->set('AutoFormat.RemoveEmpty', TRUE);
	$config->set('AutoFormat.Linkify', false);
	$config->set('AutoFormat.AutoParagraph', false);
	$config->set('HTML.TidyLevel', 'heavy');
    }

    /** The filter 
     * @access public
     * @param string $value
     * @return string
     */
    public function filter($value)  {
        return $this->_htmlPurifier->purify($value);
    }
}