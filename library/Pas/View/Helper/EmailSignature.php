<?php
/**
 * Email Signature helper for the email templates
 *
 * An example of use:
 * 
 * <code>
 * <?php
 * echo $this->emailSignature();
 * ?>
 * </code>
 * 
 * @author  Daniel Pett <dpett@britishmuseum.org>
 * @category Pas
 * @package View
 * @subpackage Helper
 * @copyright (c) 2014, Daniel Pett
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @uses viewHelper Pas_View_Helper
 * @uses viewHelper Zend_View_Helper_Escape
 * @uses date Zend_Date
 */
class Pas_View_Helper_EmailSignature extends Zend_View_Helper_Abstract
{
    /** The user to get signature from
     *@access protected
     * @var String
     */
    protected $_user;

    /** The time stamp
     * @access protected
     * @var string
     */
    protected $_timeStamp;

    /** Get the user's details
     * @access public
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

    /** To string method
     * @access public
     * @return string
     */
    public function __toString() {
        return $this->getSignature();
    }

    /** Get the email signature string
     * @access public
     * @return string
     */
    public function getSignature() {
        $html = '';
        $html .= '<p>Sent by: ' . $this->view->escape( $this->getUser() ) . ' at ';
        $html .= $this->getTimeStamp() . '</p>';
        return $html;
    }

    /** The function
     * @access public
     * @return \Pas_View_Helper_EmailSignature
     */
    public function emailSignature(){
        return $this;
    }
}
