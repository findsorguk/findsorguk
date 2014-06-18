<?php
/**
 * EmailSignature helper for the email templates
 *
 * @author  Daniel Pett <dpett@britishmuseum.org>
 * @category Pas
 * @package Pas_View_Helper
 * @copyright (c) 2014, Daniel Pett
 * @license http://URL GNU
 * @uses viewHelper Pas_View_Helper
 * @uses viewHelper Zend_View_Helper_Escape
 * @uses date Zend_Date
 */
class Pas_View_Helper_EmailSignature extends Zend_View_Helper_Abstract {

    protected $_user;

    protected $_timeStamp;

    /** Get the user's details
     *
     * @return string
     */
    public function getUser() {
        $user = new Pas_User_Details();
        return $user->getPerson()->fullname;
    }

    /** Get the timestamp for sending in W3C format
     *
     * @return string
     */
    public function getTimeStamp() {
        $date = new Zend_Date();
        return $this->_timeStamp = $date->get(Zend_Date::W3C);
    }

    /** Magic method
     *
     * @return string
     */
    public function __toString() {
        return $this->getSignature();
    }

    /** Get the email signature string
     *
     * @return string
     */
    public function getSignature() {
        $html = '';
        $html .= '<p>Sent by: ' . $this->view->escape( $this->getUser() ) . ' at ';
	$html .= $this->getTimeStamp() . '</p>';
	return $html;
    }

    /** The function
     *
     * @return \Pas_View_Helper_EmailSignature
     */
    public function emailSignature() {
        return $this;
    }
}