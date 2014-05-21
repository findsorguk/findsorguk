<?php
/**
 * A view helper for displaying the current page URL
 * @category   Pas
 * @package    Pas_View_Helper
 * @subpackage Abstract
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @see Zend_View_Helper_Abstract
 */
class Pas_View_Helper_CurUrl extends Zend_View_Helper_Abstract
{

    protected $_ssl;

    protected $_port;

    protected $_front;

    protected $_https;

    protected $_portNumber;

    protected $_serverName;

    protected $_requestUri;

    public function __construct()
    {
        $this->_front = Zend_Front_Controller::getInstance();
    }

    public function getHttps()
    {
        $this->_https = $this->_front->getRequest('HTTPS');

        return $this->_https;
    }

    public function getPortNumber()
    {
        $this->_portNumber = $this->_front->getRequest('SERVER_PORT');

        return $this->_portNumber;
    }

    public function getUri()
    {
        $this->_requestUri = $this->_front->getRequest('REQUEST_URI');

        return $this->_requestUri;
    }

    public function getServerName()
    {
        $this->_serverName = $this->_front->getRequest('SERVER_NAME');

        return $this->_serverName;
    }

    /** Get whether ssl is enabled
     *
     * @return boolean
     */
    public function getSsl()
    {
        $this->_ssl = (isset($this->getHttps()) && $this->getHttps() == "on");

        return $this->_ssl;
    }

    /** Get the port
     *
     * @return int
     */
    public function getPort()
    {
        $port = (isset($this->getPortNumber()) && ((!$this->getSsl() && $this->getPortNumber() != "80")
        || ($this->getSsl() && $this->getPortNumber() != "443")));
    $this->_port = ($port) ? ':' . $this->getPortNumber() : '';

        return $this->_port;
    }

    /** The function
     *
     * @return \Pas_View_Helper_CurUrl
     */
    public function curUrl()
    {
        return $this;
    }

    /** The magic method
     *
     * @return string
     */
    public function __toString()
    {
        return $this->createUrl();
    }

    /** Create the url
     * @return string
     */
    public function createUrl()
    {
        $url = ($this->getSsl() ? 'https://' : 'http://')
                . $this->getServerName()
                . $this->getPort()
                . $this->getUri();

    return $url;
    }

}
