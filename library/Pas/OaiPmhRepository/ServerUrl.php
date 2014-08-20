<?php
/** Code moved from the Zend View Helper ServerUrl for getting the server url
 * 
 * @category Pas
 * @package Pas_OaiPmhRepository
 * @version 1
 * @since 6/2/12
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @author danielpett
 * @copyright (c) 2014 Daniel Pett
 */
class Pas_OaiPmhRepository_ServerUrl {
    
    /** Url Scheme
     * @access protected
     * @var string
     */
    protected $_scheme;

    /** Host (including port)
     * @access protected
     * @var string
     */
    protected $_host;

    /** Constructor
     * @access public
     * @return void
     */
    public function __construct() {
        if (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] === true)) {
            $scheme = 'https';
        } else {
            $scheme = 'http';
        }
        $this->setScheme($scheme);

        if (isset($_SERVER['HTTP_HOST']) && !empty($_SERVER['HTTP_HOST'])) {
            $this->setHost($_SERVER['HTTP_HOST']);
        } else if (isset($_SERVER['SERVER_NAME'], $_SERVER['SERVER_PORT'])) {
            $name = $_SERVER['SERVER_NAME'];
            $port = $_SERVER['SERVER_PORT'];

            if (($scheme == 'http' && $port == 80) ||
                ($scheme == 'https' && $port == 443)) {
                $this->setHost($name);
            } else {
                $this->setHost($name . ':' . $port);
            }
        }
    }

    /**
     * View helper entry point:
     * Returns the current host's URL like http://site.com
     *
     * @param  string|boolean $requestUri  [optional] if true, the request URI
     *                                     found in $_SERVER will be appended
     *                                     as a path. If a string is given, it
     *                                     will be appended as a path. Default
     *                                     is to not append any path.
     * @return string                      server url
     */
    public function get($requestUri = null) {
        if ($requestUri === true) {
            $path = $_SERVER['REQUEST_URI'];
        } else if (is_string($requestUri)) {
            $path = $requestUri;
        } else {
            $path = '';
        }
        return $this->getScheme() . '://' . $this->getHost() . $path;
    }

    /**
     * Returns host
     *
     * @return string  host
     */
    public function getHost()
    {
        return $this->_host;
    }

    /** Sets host
     * @access public
     * @param  string $host new host
     * @return Zend_View_Helper_ServerUrl  fluent interface, returns self
     */
    public function setHost($host) {
        $this->_host = $host;
        return $this;
    }

    /** Returns scheme (typically http or https)
     * @access public
     * @return string  scheme (typically http or https)
     */
    public function getScheme() {
        return $this->_scheme;
    }

    /** Sets scheme (typically http or https)
     * @access public
     * @param  string $scheme new scheme (typically http or https)
     * @return Zend_View_Helper_ServerUrl  fluent interface, returns self
     */
    public function setScheme($scheme)
    {
        $this->_scheme = $scheme;
        return $this;
    }
}