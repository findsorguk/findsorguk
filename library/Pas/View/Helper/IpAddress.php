<?php
/**
 * A basic view helper for displaying ip address
 * @category   Pas
 * @package    Pas_View_Helper
 * @subpackage Abstract
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @see Zend_View_Helper_Abstract
 * @author Daniel Pett <dpett@britishmuseum.org>
 */
class Pas_View_Helper_IpAddress extends Zend_View_Helper_Abstract
{

    protected $_ip;

    /** Get the IP address from the controller
     *
     * @return string
     */
    public function getIp() {
        $this->_ip = Zend_Controller_Front::getInstance()->getRequest()->getClientIp();
        return $this->_ip;
    }

    /** the function
     *
     * @return \Pas_View_Helper_IpAddress
     */
    public function ipAddress() {
        return $this;
    }

    /** The magic method
     *
     * @return string
     */
    public function __toString() {
        return $this->getIp();
    }
}