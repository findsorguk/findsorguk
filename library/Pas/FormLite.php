<?php

/**
 * Extension of Zend form for PAS project
 *
 * Example of use:
 * <code>
 * <?php
 * class AccountForm extends Pas_Form {
 * }
 * ?>
 * </code>
 *
 * @category   Pas
 * @package    Form
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014, Daniel Pett
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @uses Zend_Registry
 * @uses EasyBib_Form
 * @uses EasyBib_Form_Decorator
 * @uses Zend_Config
 * @uses Zend_Layout
 * @uses Zend_View_Helper_HeadLink
 * @uses ZendX_Jquery
 * @uses Pas_User_Details
 * @example /app/forms/AcceptUpgradeForm.php
 */
class Pas_FormLite extends EasyBib_Form
{

    /** The form salt value
     * @access protected
     * @var string
     */
    public $_salt;

    /** The public key for recaptcha
     * @access protected
     * @var string
     */
    public $_pubKey;

    /** The private key for recaptcha
     * @access protected
     * @var string
     */
    public $_privateKey;

    /** The user role
     * @access protected
     * @var string
     */
    public $_role;

    /** Initialise values
     * @access public
     */
    public function init()
    {
        $this->_salt = Zend_Registry::get('config')->form->salt;
        EasyBib_Form_Decorator::setFormDecorator($this,
            EasyBib_Form_Decorator::BOOTSTRAP, 'submit', 'cancel');
    }

    /** The form constructor
     * @access public
     */
    public function __construct()
    {
        $this->addPrefixPath('Pas_Form_Element', 'Pas/Form/Element', 'element');
        $this->addElementPrefixPath('Pas_Filter', 'Pas/Filter/', 'filter');
        $this->addElementPrefixPath('Pas_Validate', 'Pas/Validate/', 'validate');
        $this->setAttrib('class', 'form-horizontal');
        $this->setAttrib('accept-charset', 'UTF-8');
        $this->clearDecorators();
        $person = new Pas_User_Details();
        $details = $person->getPerson();
        if ($details) {
            $this->_role = $details->role;
        } else {
            $this->_role = 'public';
        }
        parent::__construct();
    }
}