<?php
/**
 * A basic view helper for displaying  user agent
 *
 * Example of use:
 * <code>
 * <?php
 * echo $this->userAgent()
 * ?>
 * </code>
 * 
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @category   Pas
 * @package    Pas_View_Helper
 * @subpackage Abstract
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @example /app/views/scripts/email/requestPublication.phtml
 */
class Pas_View_Helper_UserAgent  {

    protected $_userAgent;

    /** Get the user agent
     * @access public
     * @return string
     */
    public function getUserAgent() {
        $useragent = new Zend_Http_UserAgent();
        $this->_userAgent = $useragent->getUserAgent();
        return $this->_userAgent;
    }

    /** The function
     * @access public
     * @return \Pas_View_Helper_UserAgent
     */
    public function userAgent() {
        return $this;
    }

    /** Magic to string method
     * @access public
     * @return string
     */
    public function __toString() {
        return $this->getUserAgent();
    }
}