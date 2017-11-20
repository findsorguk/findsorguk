<?php
/** A view helper for displaying a Creative Commons licence
 *
 * An example of use:
 * <code>
 * <?php
 * echo $this->creativeCommonsLicense()->setLicense(2);
 * ?>
 * </code>
 *
 * @category   Pas
 * @package    View
 * @subpackage Helper
 * @copyright  Copyright (c) 2017 dpett @ britishmuseum.org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @author Daniel Pett <dpett@britishmuseum.org>
 *
 */
class Pas_View_Helper_CreativeCommonsLicense extends Zend_View_Helper_Abstract
{
    /**
     * The all rights reserved string
     *
     */
    const ALLRIGHTS = 'All Rights Reserved';

    /**
     * Base of the creative commons license url
     */
    const BASECREATIVE = 'http://creativecommons.org/licenses/';

    /**
     *  Text for view license
     */
    const VIEWLIC = 'View license restrictions';

    /** The license variable
     * @access protected
     * @var int
     */
    protected $_license;

    /** Get the license
     * @access public
     * @return int
     */
    public function getLicense() {
        return $this->_license;
    }

    /**
     * Set the license
     * @access public
     * @param int $license
     * @return \Pas_View_Helper_CreativeCommonsLicense
     */
    public function setLicense($license) {
        $this->_license = $license;
        return $this;
    }

    /** Get the cache object
     * @access public
     * @return \Zend_Cache
     */
    public function getCache() {
        $this->_cache = Zend_Registry::get('cache');
        return $this->_cache;
    }

    /** The cache object
     * @access public
     * @var \Zend_Cacge
     */
    protected $_cache;

    /** Function to return
     * @access public
     * @return \Pas_View_Helper_FlickrLicense
     */
    public function creativeCommonsLicense(){
        return $this;
    }

    /** Build the html to return to string
     * @access public
     * @param int $license
     * @return string
     */
    public function getHtmlLicense( $license ){
        if (!($this->getCache()->test('cclicense' . $license))) {
            switch ($license) {
                case 1:
                    $licensetype = self::ALLRIGHTS;
                    $licensetype .= '<img src="/assets/ccIcons/by-nc-sa.png" height="15" width="80" />';

                    break;
                case 2:
                    $licensetype = '<a href="';
                    $licensetype .= self::BASECREATIVE;
                    $licensetype .= 'by-nc-sa/4.0/" title="';
                    $licensetype .= self::VIEWLIC;
                    $licensetype .= '">';
                    $licensetype .= '<img src="/assets/ccIcons/by-nc-sa.png" height="15" width="80" /></a>';
                    break;
                case 3:
                    $licensetype = '<a href="';
                    $licensetype .= self::BASECREATIVE;
                    $licensetype .= 'by-nc/4.0/" title="';
                    $licensetype .= self::VIEWLIC;
                    $licensetype .= '">';
                    $licensetype .= '<img src="/assets/ccIcons/by-nc.png" height="15" width="80" /></a>';

                    break;
                case 6:
                    $licensetype = '<a href="';
                    $licensetype .= self::BASECREATIVE;
                    $licensetype .= 'by-nc-nd/4.0/" title="';
                    $licensetype .= self::VIEWLIC;
                    $licensetype .= '">';
                    $licensetype .= '<img src="/assets/ccIcons/by-nc-nd.png" height="15" width="80" /></a>';

                    break;
                case 4:
                    $licensetype = '<a href="';
                    $licensetype .= self::BASECREATIVE;
                    $licensetype .= 'by/2.0/" title="';
                    $licensetype .= self::VIEWLIC;
                    $licensetype .= '">';
                    $licensetype .= '<img src="/assets/ccIcons/by.png" height="15" width="80" /></a>';
                    break;
                case 5:
                    $licensetype = '<a href="';
                    $licensetype .= self::BASECREATIVE;
                    $licensetype .= 'by-sa/4.0/" title="';
                    $licensetype .= self::VIEWLIC;
                    $licensetype .= '">';
                    $licensetype .= '<img src="/assets/ccIcons/by-sa.png" height="15" width="80" /></a>';

                    break;
                default:
                    $licensetype = self::ALLRIGHTS;
                    break;
            }
            $this->getCache()->save($licensetype);
            } else {
                $licensetype = $this->getCache()->load('cclicense' . $license);
        }
        return $licensetype;
    }

    /** To string function
     * @access public
     * @return string
     */
    public function __toString() {
        return $this->getHtmlLicense($this->getLicense());
    }
}