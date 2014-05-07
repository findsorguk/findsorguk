<?php
/**
 * A basic view helper for displaying  user agent
 * @category   Pas
 * @package    Pas_View_Helper
 * @subpackage Abstract
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @see Zend_View_Helper_Abstract
 */
class Pas_View_Helper_UserAgent extends Zend_View_Helper_Abstract
{

    protected $_userAgent;

    /** Get the user agent
     *
     * @return string
     */
    public function getUserAgent() {
        $useragent = new Zend_Http_UserAgent();
        $this->_userAgent = $useragent->getUserAgent();
        return $this->_userAgent;
    }

    /** The function
     *
     * @return \Pas_View_Helper_UserAgent
     */
    public function userAgent() {
        return $this;
    }

    /** Magic to string method
     *
     * @return string
     */
    public function __toString() {
        return $this->getUserAgent();
    }

}