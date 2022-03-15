<?php

/**
 * A view helper for displaying the current page URL
 *
 * To use this view helper
 *
 * <code>
 * <?php
 * echo $this->curUrl();
 * ?>
 * </code>
 *
 *
 * @category   Pas
 * @package View
 * @subpackage Helper
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @see  Zend_View_Helper_Abstract
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @version 1
 * @uses Zend_Controller_Front
 */
class Pas_View_Helper_CurUrl extends Zend_View_Helper_Abstract
{
    /** SSL string
     * @access protected
     * @var string
     */
    protected $_ssl;

    /** The port number
     * @access protected
     * @var int
     */
    protected $_port;

    /** The front controller object
     * @access protected
     * @var object
     */
    protected $_front;

    /** The https string
     * @access protected
     * @var string
     */
    protected $_https;

    /** The port number in use
     * @access protected
     * @var int
     */
    protected $_portNumber;

    /** The server base name
     * @access protected
     * @var string
     */
    protected $_serverName;

    /** The currently requested URI
     * @access protected
     * @var string
     */
    protected $_requestUri;

    /** Get the front controller object
     * @access public
     * @return object
     */
    public function getFront(): object
    {
        $this->_front = Zend_Controller_Front::getInstance();
        return $this->_front;
    }

    /** Get whether https
     * @access public
     * @return string
     */
    public function getHttps(): string
    {
        $this->_https = $this->getFront()->getRequest('HTTPS');
        return $this->_https;
    }

    /** Get the port number from the request
     * @access public
     * @return int
     */
    public function getPortNumber(): int
    {
        $this->_portNumber = $_SERVER['SERVER_PORT'] != 80 ? "{$_SERVER['SERVER_PORT']}" : '';
        return $this->_portNumber;
    }

    /** Get the requested uri
     * @access public
     * @return string
     */
    public function getUri(): string
    {
        $this->_requestUri = $this->getFront()->getRequest()->getRequestUri();
        return $this->_requestUri;
    }

    /** Get the server's name
     * @access public
     * @return string
     */
    public function getServerName(): string
    {
        $this->_serverName = $_SERVER['HTTP_HOST'];
        return $this->_serverName;
    }

    /** Get whether ssl is enabled
     * @access public
     * @return boolean
     */
    public function getSsl(): bool
    {
        $this->_ssl = (null !== ($this->getHttps()) && $this->getHttps() == "on");
        return $this->_ssl;
    }

    /** The function
     * @access public
     * @return \Pas_View_Helper_CurUrl
     */
    public function curUrl()
    {
        return $this;
    }

    /** The magic method
     * @access public
     * @return string
     */
    public function __toString()
    {
        return $this->createUrl();
    }

    /** Create the url
     * @access public
     * @return string
     */
    public function createUrl(): string
    {
        if ($this->getPortNumber() === 443) {
            $protocol = 'https://';
        } else {
            $protocol = 'http://';
        }
        return $protocol . $this->getServerName() . $this->getUri();
    }
}
