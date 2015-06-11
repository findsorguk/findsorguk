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
 * @package    View
 * @subpackage Helper
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @example /app/views/scripts/email/requestPublication.phtml
 */
class Pas_View_Helper_UserAgent
{

    /** The user agent string
     * @access protected
     * @var NULL
     */
    protected $_userAgent = NULL;

    /** Get the user agent
     * @access public
     * @return string
     */
    public function getUserAgent()
    {
        $useragent = new Zend_Http_UserAgent();
        $this->_userAgent = $useragent->getUserAgent();
        return $this->_userAgent;
    }

    /** The function
     * @access public
     * @return \Pas_View_Helper_UserAgent
     */
    public function userAgent()
    {
        return $this;
    }

    /** Magic to string method
     * @access public
     * @return string
     */
    public function __toString()
    {
        return $this->getUserAgent();
    }
}