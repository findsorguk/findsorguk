<?php

/** Form for retrieval of passwords
 *
 * An example of code use:
 *
 * <code>
 * <?php
 * $form = new ResetPasswordKeyForm();
 * $this->view->form = $form;
 * ?>
 * </code>
 *
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category   Pas
 * @package    Pas_Form
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @example /app/modules/user/controllers/AccountController.php
 */
class ResetPasswordKeyForm extends Pas_Form
{

    /** The constructor
     * @access public
     * @param array $options
     * @return void
     */
    public function __construct($options = null)
    {
        parent::__construct($options);
        $this->init();
    }

    /** Init the form
     * @return void
     * @access public
     */
    public function init()
    {
        $this->setMethod('post')->setAttrib('id', 'resetpassword');

        $username = $this->addElement('Text', 'username',
            array('label' => 'Username: '));

        $username = $this->getElement('username')
            ->setRequired(true)
            ->addErrorMessage('You must enter a username')
            ->addFilters(array('StringTrim', 'StripTags', 'Purifier'))
            ->addValidator('Db_RecordExists', false,
                array('table' => 'users', 'field' => 'username'));

        $activationKey = $this->addElement('Text', 'activationKey',
            array('label' => 'Reset password key'));
        $activationKey = $this->getElement('activationKey')
            ->setDescription('The reset key can be found in the email you '
                . 'received when asking for a new password. '
                . 'Check your spam filter')
            ->setRequired(true)
            ->addErrorMessage('You must enter a reset key')
            ->addFilters(array('StringTrim', 'StripTags', 'Purifier'))
            ->addValidator('Db_RecordExists', false,
                array('table' => 'users', 'field' => 'activationKey'));

        $password = $this->addElement('Text', 'password',
            array('label' => 'New password'));
        $password = $this->getElement('password')
            ->setRequired(true)
            ->setDescription('Password must be longer than 6 characters
                    and must include letters and numbers i.e. p4ssw0rd')
            ->addFilters(array('StringTrim', 'StripTags', 'Purifier'))
            ->addValidator('StringLength', true, array(6))
            ->addValidator('Regex', true, array('/^(?=.*\d)(?=.*[a-zA-Z]).{6,}$/'))
            ->setRequired(true)
            ->addErrorMessage('Please enter a valid password!');
        $password->getValidator('StringLength')->setMessage('Password is too short');
        $password->getValidator('Regex')->setMessage('Password does not contain letters and numbers');

        $email = $this->addElement('Text', 'email',
            array('label' => 'Email Address: ', 'size' => '30'))->email;
        $email->addValidator('EmailAddress')
            ->setRequired(true)
            ->addFilters(array('StringTrim', 'StripTags'))
            ->addErrorMessage('Please enter a valid address!')
            ->addValidator('Db_RecordExists', false,
                array('table' => 'users', 'field' => 'email'));

        $hash = new Zend_Form_Element_Hash('csrf');

        $hash->setValue($this->_salt)
            ->setTimeout(4800);
        $this->addElement($hash);

        $captcha = new Pas_Form_Element_Recaptcha('captcha');
        $captcha->setLabel('Please complete the Captcha field to prove you exist');

        $this->addElement($captcha);
        $submit = $this->addElement('submit', 'submit');
        $submit = $this->getElement('submit')
            ->setLabel('Change my password');

        $this->addDisplayGroup(array(
            'username', 'email', 'password',
            'activationKey', 'captcha', 'submit'
        ), 'details');

        $this->setLegend('Reset my password: ');

        parent::init();
    }
}
