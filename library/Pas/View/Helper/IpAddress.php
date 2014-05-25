<?php
/**
 * A basic view helper for displaying ip address
 *
 * This helper is just used to get the user's Ip address from the front
 * controller. Very simple!
 *
 * @category   Pas
 * @package    Pas_View_Helper
 * @subpackage Abstract
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @see Zend_View_Helper_Abstract
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @uses Zend_Controller_Front
 * @version 1
 *
 */
class Pas_View_Helper_IpAddress extends Zend_View_Helper_Abstract
{

    /** The IP address
     * @access protected
     * @var string
     */
    protected $_ip;

    /** The front controller object
     * @access protected
     * @var object
     */
    protected $_front;

    /** Get the front controller object
     * @access public
     * @return object
     */
    public function getFront() {
        $this->_front = Zend_Controller_Front::getInstance()->getRequest();
        return $this->_front;
    }

    /** Get the IP address to return
     * @access public
     * @return string
     */
    public function getIp() {
        $this->_ip = $this->getFront()->getClientIp();
        return $this->_ip;
    }

    /** The function
     * @access public
     * @return \Pas_View_Helper_IpAddress
     */
    public function ipAddress() {
        return $this;
    }

    /** The to string method
     * @access public
     * @return type
     */
    public function __toString() {
        return $this->getIp();
    }
}
