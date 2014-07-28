<?php
/** Filter extension based on HTML purifier for allowing Basic HTML on forms and displays
 * 
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category   Pas
 * @package    Pas_Filter
 * @subpackage Interface
 * @copyright  Copyright (c) 2005-2011 Zend Technologies USA Inc. (http://www.zend.com)
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 */
class Pas_Filter_HtmlBody extends Pas_Filter_HTMLPurifier {
   
    /** The constructor
     * @access public
     * @return void
     */
    public function __construct() {
        $config = HTMLPurifier_Config::createDefault();
        $this->_htmlPurifier = new HTMLPurifier($config);
        $config->set('Cache.SerializerPath',  CACHE_PATH . '/htmlpurifier');
        $config->set('HTML.Allowed', 'br,p,em,h1,h2,h3,h4,h5,strong,'
                . 'a[name|href|title|class|rel],ul,ol,li,code,pre,'
                . 'blockquote,img[src|alt|height|width|class],sub,'
                . 'sup,br,span[class|id],div[class|id],table');
        $config->set('AutoFormat.RemoveEmpty.RemoveNbsp', true);
        $config->set('AutoFormat.RemoveEmpty', true);
        $config->set('AutoFormat.Linkify', true);
        $config->set('AutoFormat.AutoParagraph', true);
        $config->set('HTML.TidyLevel', 'medium'); 
        $config->set('Attr.AllowedRel', 'nofollow,print,lightbox');
        parent::__construct();
    }

}